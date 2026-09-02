<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    // ১. অ্যাডমিন লগইন পেজ শো করবে
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    // ২. লগইন প্রসেস
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt(array_merge($credentials, ['role' => 'admin']))) {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors([
            'email' => 'সঠিক ইমেইল বা পাসওয়ার্ড দিন, অথবা আপনার অ্যাডমিন অ্যাক্সেস নেই।',
        ])->onlyInput('email');
    }

    // ৩. লগআউট প্রসেস
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
