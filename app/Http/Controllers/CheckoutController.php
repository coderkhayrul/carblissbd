<?php

namespace App\Http\Controllers;

use App\Services\CheckoutService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
    public function index(Request $request, CheckoutService $service)
    {
        try {
            $summary = $service->summary($request->session()->get('checkout.items', []), $request->session()->get('checkout.shipping_id'));
        } catch (ValidationException $exception) {
            $summary = $exception->validator->errors()->has('shipping_id')
                ? $service->summary($request->session()->get('checkout.items', []))
                : $service->summary([]);
            $request->session()->flash('error', $exception->validator->errors()->first());
        }
        return view('frontend.pages.checkout', [
            'summary' => $summary,
            'details' => $request->session()->get('checkout.details', []),
            'cartDisplay' => $summary['items'] ?: $request->session()->get('checkout_cart_display', []),
        ]);
    }

    public function update(Request $request, CheckoutService $service)
    {
        $validator = Validator::make($request->all(), [
            'items' => ['required', 'array', 'min:1', 'max:100'],
            'items.*.id' => ['required', 'integer', 'not_in:0', 'distinct'],
            'items.*.qty' => ['required', 'integer', 'min:1', 'max:99'],
            'shipping_id' => ['nullable', 'integer', 'exists:shipping_charge_settings,id'],
            'name' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
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
            array_intersect_key($data, array_flip(['name', 'phone', 'address', 'notes']))));

        if ($request->expectsJson()) return response()->json(['url' => route('checkout')]);
        return redirect()->route('checkout')->with('success', 'Checkout updated.');
    }
}
