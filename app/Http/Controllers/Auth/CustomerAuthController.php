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
