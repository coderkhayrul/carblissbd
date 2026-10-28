<?php

namespace App\Http\Controllers;

use App\Services\CheckoutService;
use App\Services\OrderService;
use App\Models\Order;
use App\Models\Customer;
use App\Support\BangladeshPhone;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
    public function index(Request $request, CheckoutService $service)
    {
        if (!$request->session()->has('checkout.token')) {
            $request->session()->put('checkout.token', (string) Str::uuid());
        }
        try {
            $summary = $service->summary($request->session()->get('checkout.items', []), $request->session()->get('checkout.shipping_id'));
        } catch (ValidationException $exception) {
            $summary = $exception->validator->errors()->has('shipping_id')
                ? $service->summary($request->session()->get('checkout.items', []))
                : $service->summary([]);
            $request->session()->flash('error', $exception->validator->errors()->first());
        }
        $accountDetails = [];
        if ($request->user()?->role === 'customer') {
            $user = $request->user();
            $customer = Customer::where('user_id', $user->id)->first();
            $accountDetails = [
                'name' => $customer?->name ?? $user->name,
                'phone' => BangladeshPhone::normalize($user->mobile),
                'email' => $user->email,
                'address' => $customer?->shipping_address ?: ($customer?->address ?: $user->address),
            ];
        }
        return view('frontend.pages.checkout', [
            'summary' => $summary,
            'details' => array_replace($accountDetails, $request->session()->get('checkout.details', [])),
            'cartDisplay' => $summary['items'] ?: $request->session()->get('checkout_cart_display', []),
            'checkoutToken' => $request->session()->get('checkout.token'),
        ]);
    }

    public function update(Request $request, CheckoutService $service)
    {
        if (is_string($request->input('phone'))) {
            $request->merge(['phone' => BangladeshPhone::normalize($request->input('phone'))]);
        }
        $validator = Validator::make($request->all(), [
            'items' => ['required', 'array', 'min:1', 'max:100'],
            'items.*.id' => ['required', 'integer', 'not_in:0', 'distinct'],
            'items.*.qty' => ['required', 'integer', 'min:1', 'max:99'],
            'shipping_id' => ['nullable', 'integer', 'exists:shipping_charge_settings,id'],
            'name' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'regex:/^\+8801[0-9]{9}$/'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:2000'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);
        if ($validator->fails() && $request->expectsJson()) {
            return response()->json(['message' => 'Please check your cart.', 'errors' => $validator->errors()], 422);
        }
        $data = $validator->validate();
        $shippingId = $data['shipping_id'] ?? $request->session()->get('checkout.shipping_id');
        try {
            $summary = $service->summary($data['items'], $shippingId ? (int) $shippingId : null);
        } catch (ValidationException $exception) {
            if ($request->filled('shipping_id') || !$exception->validator->errors()->has('shipping_id')) {
                if ($request->expectsJson()) {
                    return response()->json(['message' => 'Please check your cart.', 'errors' => $exception->validator->errors()], 422);
                }
                throw $exception;
            }
            $summary = $service->summary($data['items']);
        }
        $request->session()->put('checkout.items', array_map(fn ($item) => ['id' => $item['id'], 'qty' => $item['qty']], $summary['items']));
        $request->session()->put('checkout.shipping_id', $summary['shipping']?->id);
        $request->session()->put('checkout_cart_display', $summary['items']);
        $request->session()->put('checkout.details', array_merge($request->session()->get('checkout.details', []),
            array_intersect_key($data, array_flip(['name', 'phone', 'email', 'address', 'notes']))));

        if ($request->expectsJson()) return response()->json(['url' => route('checkout')]);
        return redirect()->route('checkout')->with('success', 'Checkout updated.');
    }

    public function placeOrder(Request $request, OrderService $service)
    {
        if (is_string($request->input('phone'))) {
            $request->merge(['phone' => BangladeshPhone::normalize($request->input('phone'))]);
        }
        if (is_string($request->input('email'))) $request->merge(['email' => strtolower(trim($request->input('email')))]);
        $data = $request->validate([
            'checkout_token' => ['required', 'uuid'],
            'password' => ['nullable', 'string', 'min:8', 'max:72', 'confirmed'],
            'items' => ['required', 'array', 'min:1', 'max:100'],
            'items.*.id' => ['required', 'integer', 'not_in:0', 'distinct'],
            'items.*.qty' => ['required', 'integer', 'min:1', 'max:99'],
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'regex:/^\+8801[0-9]{9}$/'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['required', 'string', 'max:2000'],
            'shipping_id' => ['required', 'integer', 'exists:shipping_charge_settings,id'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);
        $allowedToken = $request->session()->get('checkout.token');
        $lastToken = $request->session()->get('last_order.token');
        if ($data['checkout_token'] !== $allowedToken && $data['checkout_token'] !== $lastToken) {
            return redirect()->route('checkout')->with('error', 'Your checkout has expired. Please submit the latest checkout form.');
        }
        try {
            $order = $service->place($data, $request->user());
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (\Throwable $exception) {
            report($exception);
            return back()->withInput()->with('error', 'Unable to place your order. Please try again. No payment has been collected.');
        }
        $completed = $request->session()->get('completed_orders', []);
        $completed[] = $order->id;
        $request->session()->put('completed_orders', array_slice(array_unique($completed), -30));
        if ($data['checkout_token'] === $allowedToken) {
            $request->session()->put('last_order', ['token' => $data['checkout_token'], 'id' => $order->id]);
            $request->session()->put('clear_cart_for_order', $order->id);
            $request->session()->forget(['checkout', 'checkout_cart_display']);
        }
        return redirect()->route('order.success', ['token' => $order->public_token]);
    }

    public function success(Request $request, string $token)
    {
        $order = Order::where('public_token', $token)->with(['customer', 'customerInfo', 'details', 'currentStatus'])->firstOrFail();
        $sessionAccess = in_array($order->id, $request->session()->get('completed_orders', []));
        $owner = $request->user() && $order->customer?->user_id === $request->user()->id;
        abort_unless($sessionAccess || $owner, 403);
        $clearCart = $request->session()->get('clear_cart_for_order') === $order->id;
        if ($clearCart) $request->session()->forget('clear_cart_for_order');
        return response()->view('frontend.pages.order-success', compact('order', 'clearCart'))
            ->header('Cache-Control', 'no-store, private')->header('Referrer-Policy', 'no-referrer');
    }
}
