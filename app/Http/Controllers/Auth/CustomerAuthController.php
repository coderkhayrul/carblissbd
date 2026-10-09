<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('frontend.auth.login');
    }

    public function login(Request $request)
    {
        // return $request->all();
        $credentials = $request->only('email_or_phone', 'password');

        // Attempt to log in using email or phone number
        if (
            Auth::attempt([
                'email' => $credentials['email_or_phone'],
                'password' => $credentials['password'],
                'role' => 'customer' // কাস্টমার রোল চেক
            ]) ||
            Auth::attempt([
                'mobile' => $credentials['email_or_phone'],
                'password' => $credentials['password'],
                'role' => 'customer' // কাস্টমার রোল চেক
            ])
        ) {
            // লগইন সফল হলে
            return redirect()->intended(route('home'));
        }

        // Authentication failed...
        return back()->withErrors([
            'email_or_phone' => 'The provided credentials do not match our records.',
        ]);
    }

    public function showRegisterForm()
    {
        return view('frontend.auth.register');
    }

    public function profile()
    {
        return view('frontend.pages.profile');
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
