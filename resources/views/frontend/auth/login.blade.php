@extends('frontend.layouts.master')

@section('front-content')
    <!-- // -->
    <!-- Main Login Section -->
    <main class="container-lg px-3 py-5">
        <div class="row justify-content-center align-items-center min-vh-75">
            <div class="col-12 col-xl-10">

                <div class="bg-white rounded-4 shadow border border-light overflow-hidden">
                    <div class="row g-0 align-items-stretch">

                        <!-- Image Side (Hidden on Mobile, Visible on md and up) -->
                        <div class="col-md-6 d-none d-md-block position-relative">
                            <img src="https://images.unsplash.com/photo-1603584173870-7f23fdae1b7a?auto=format&fit=crop&q=80&w=800"
                                class="position-absolute w-100 h-100 object-fit-cover" alt="Login Banner">
                            <div class="position-absolute top-0 start-0 w-100 h-100 login-banner-overlay z-1"></div>

                            <!-- Pattern Overlay -->
                            <div class="position-absolute top-0 start-0 w-100 h-100 z-2 opacity-10"
                                style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 20px 20px;">
                            </div>

                            <div class="position-absolute top-50 start-50 translate-middle text-center w-100 px-4 z-3">
                                <span class="badge bg-white text-primary rounded-pill px-3 py-2 mb-3 fw-bold shadow-sm"
                                    style="letter-spacing: 1px;">WELCOME BACK</span>
                                <h2 class="fw-black text-white mb-3 display-6 lh-sm">Unlock Premium <br>Car Accessories</h2>
                                <p class="text-light mx-auto" style="font-size: 0.95rem; max-width: 350px;">
                                    Sign in to access your orders, track deliveries, and discover exclusive members-only
                                    deals.
                                </p>
                            </div>
                        </div>

                        <!-- Form Side -->
                        <div class="col-12 col-md-6 p-4 p-sm-5 d-flex flex-column justify-content-center">

                            <div class="text-center text-md-start mb-4">
                                <h1 class="h3 fw-black text-dark mb-1">Sign In</h1>
                                <p class="text-muted small">Please enter your details to access your account.</p>
                            </div>

                            <!-- Login Form -->
                            <form method="POST" action="{{ route('login.submit') }}">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-secondary">Email or Phone Number <span
                                            class="text-danger">*</span></label>
                                    <div
                                        class="input-group bg-light rounded-3 overflow-hidden border {{ $errors->has('email_or_phone') ? 'border-danger' : '' }}">
                                        <span class="input-group-text bg-transparent border-light text-muted ps-3 pe-2">
                                            <i class="fa-regular fa-envelope"></i>
                                        </span>
                                        <input type="text"
                                            class="form-control bg-transparent border-light border-start-0 ps-0 focus-ring-0"
                                            placeholder="e.g. example@gmail.com" name="email_or_phone"
                                            value="{{ old('email_or_phone') }}" required>
                                    </div>
                                    @error('email_or_phone')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3 position-relative">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <label class="form-label small fw-bold text-secondary mb-0">Password <span
                                                class="text-danger">*</span></label>
                                        @if (Route::has('password.request'))
                                            <a href="#"
                                                class="text-primary text-decoration-none small fw-bold hover-text-dark transition-all"
                                                style="font-size: 0.75rem;">Forgot Password?</a>
                                        @endif
                                    </div>
                                    <div class="input-group bg-light rounded-3 overflow-hidden border">
                                        <span class="input-group-text bg-transparent border-light text-muted ps-3 pe-2">
                                            <i class="fa-solid fa-lock"></i>
                                        </span>
                                        <input type="password" id="login-password"
                                            class="form-control bg-transparent border-light border-start-0 border-end-0 ps-0 focus-ring-0"
                                            placeholder="••••••••" name="password" required>
                                        <button class="btn bg-transparent border-light border-start-0 text-muted pe-3"
                                            type="button" onclick="togglePasswordVisibility()">
                                            <i class="fa-regular fa-eye-slash" id="toggle-pwd-icon"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="mb-4 form-check d-flex align-items-center">
                                    <input type="checkbox" class="form-check-input cursor-pointer" id="rememberMe" checked>
                                    <label class="form-check-label small text-secondary fw-bold cursor-pointer ms-2 mt-1"
                                        for="rememberMe" style="font-size: 0.8rem;">
                                        Remember me for 30 days
                                    </label>
                                </div>

                                <button type="submit"
                                    class="btn btn-primary w-100 rounded-pill fw-bolder py-3 shadow-sm d-flex align-items-center justify-content-center gap-2 transition-all hover:scale-105 text-uppercase"
                                    style="letter-spacing: 1px;">
                                    Sign In securely <i class="fa-solid fa-arrow-right"></i>
                                </button>

                            </form>

                            <div class="text-center mt-4 pt-4 border-top border-light">
                                <p class="small text-muted fw-bold mb-0">New to CarBlissBD? <a
                                        href="{{ route('register') }}"
                                        class="text-primary fw-bolder text-decoration-none ms-1 hover-text-dark transition-all">Create
                                        an Account</a></p>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>
@endsection
