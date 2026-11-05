<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Status;
use App\Services\CustomerService;
use App\Support\BangladeshPhone;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CustomerAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('frontend.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email_or_phone' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string'],
        ]);
        $identity = trim($credentials['email_or_phone']);
        $authenticated = Auth::attempt([
            'email' => $identity, 'password' => $credentials['password'], 'role' => 'customer',
        ]);
        if (!$authenticated) {
            foreach (\App\Support\BangladeshPhone::variants($identity) as $mobile) {
                $authenticated = Auth::attempt([
                    'mobile' => $mobile, 'password' => $credentials['password'], 'role' => 'customer',
                ]);
                if ($authenticated) break;
            }
        }
        if ($authenticated) {
            $request->session()->regenerate();
            return redirect()->intended(route('home'));
        }
        return back()->withErrors([
            'email_or_phone' => 'The provided credentials do not match our records.',
        ])->onlyInput('email_or_phone');
    }
    public function showRegisterForm()
    {
        return view('frontend.auth.register');
    }

    public function profile(Request $request)
    {
        if ($request->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        abort_unless($request->user()->role === 'customer', 403);
        $profileUser = $request->user();
        $profileCustomer = Customer::where('user_id', $profileUser->id)->first();
        $nameParts = preg_split('/\s+/u', trim($profileUser->name), 2);
        $firstName = $nameParts[0] ?? '';
        $lastName = $nameParts[1] ?? '';
        $filters = $request->validate([
            'orders_q' => ['nullable', 'string', 'max:255'],
            'order_status' => ['nullable', 'integer', 'exists:statuses,id'],
        ]);
        $ownedOrders = Order::whereHas('customer', fn ($query) => $query->where('user_id', $profileUser->id));
        $orderStatuses = Status::orderBy('id')->get(['id', 'name']);
        $statusCounts = (clone $ownedOrders)->select('current_status')->selectRaw('COUNT(*) as order_count')
            ->groupBy('current_status')->pluck('order_count', 'current_status');
        $totalOrders = (int) $statusCounts->sum();
        $pendingOrders = $orderStatuses->filter(fn ($status) => strtolower(trim($status->name)) === 'pending')
            ->sum(fn ($status) => (int) $statusCounts->get($status->id, 0));
        $completeOrders = $orderStatuses->filter(fn ($status) => in_array(strtolower(trim($status->name)), ['delivered', 'completed', 'complete'], true))
            ->sum(fn ($status) => (int) $statusCounts->get($status->id, 0));
        $recentOrders = (clone $ownedOrders)->with('currentStatus')->latest('id')->limit(5)->get();
        $orderQuery = (clone $ownedOrders)->with(['currentStatus', 'details'])->latest('id');
        if (!empty($filters['orders_q'])) $orderQuery->where('order_no', 'like', '%' . trim($filters['orders_q']) . '%');
        if (!empty($filters['order_status'])) $orderQuery->where('current_status', $filters['order_status']);
        $customerOrders = $orderQuery->paginate(5, ['*'], 'orders_page')
            ->appends(array_merge($request->only(['orders_q', 'order_status']), ['tab' => 'orders']));
        return view('frontend.pages.profile', compact('profileUser', 'profileCustomer', 'firstName', 'lastName',
            'customerOrders', 'orderStatuses', 'totalOrders', 'pendingOrders', 'completeOrders', 'recentOrders'));
    }

    public function orderDetail(Request $request, string $order)
    {
        if ($request->user()->role === 'admin') return redirect()->route('admin.orders.show', ['order' => $order]);
        abort_unless($request->user()->role === 'customer', 403);
        $order = Order::whereKey($order)->whereHas('customer', fn ($query) => $query->where('user_id', $request->user()->id))
            ->with(['customerInfo', 'details', 'currentStatus'])->firstOrFail();
        return response()->view('frontend.pages.order-success', ['order' => $order, 'clearCart' => false, 'orderHistory' => true])
            ->header('Cache-Control', 'no-store, private')->header('Referrer-Policy', 'no-referrer');
    }

    public function updateProfile(Request $request, CustomerService $service)
    {
        $user = $request->user();
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        abort_unless($user->role === 'customer', 403);
        if (is_string($request->input('mobile'))) $request->merge(['mobile' => BangladeshPhone::normalize($request->input('mobile'))]);
        if (is_string($request->input('email'))) $request->merge(['email' => strtolower(trim($request->input('email'))) ?: null]);
        $identityChanged = $request->input('mobile') !== BangladeshPhone::normalize($user->mobile)
            || $request->input('email') !== ($user->email ? strtolower($user->email) : null);
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:120'],
            'last_name' => ['nullable', 'string', 'max:120'],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'mobile' => ['required', 'string', 'regex:/^\+8801[0-9]{9}$/', Rule::unique('users', 'mobile')->ignore($user->id)],
            'address' => ['nullable', 'string', 'max:255'],
            'current_password' => ['nullable', Rule::requiredIf($identityChanged || $request->filled('password')), 'string', 'current_password:web'],
            'password' => ['nullable', 'string', 'min:8', 'max:72', 'confirmed'],
            'password_confirmation' => ['nullable', 'required_with:password', 'string'],
        ]);
        try {
            $service->updateAccount($user, $data);
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (\Throwable $exception) {
            report($exception);
            return back()->withInput($request->except(['current_password', 'password', 'password_confirmation']))
                ->with('profile_error', 'Unable to update your account. Please try again.');
        }
        $user->refresh();
        if (!empty($data['password'])) {
            $request->session()->regenerate();
            $request->session()->put('password_hash_web', $user->password);
        }
        return redirect()->route('profile', ['tab' => 'settings'])->with('profile_success', 'Your account settings have been updated.');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
