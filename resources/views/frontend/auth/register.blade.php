@extends('frontend.layouts.master')

@section('front-content')
    <!-- // -->
    <main class="container-lg px-3 py-5">
        <div class="row justify-content-center align-items-center min-vh-75">
            <div class="col-12 col-xl-10">

                <div class="bg-white rounded-4 shadow border border-light overflow-hidden">
                    <div class="row g-0 align-items-stretch">

                        <!-- Image Side (Hidden on Mobile, Visible on md and up) -->
                        <div class="col-md-5 d-none d-md-block position-relative">
                            <img src="https://images.unsplash.com/photo-1542282088-fe8426682b8f?auto=format&fit=crop&q=80&w=800"
                                class="position-absolute w-100 h-100 object-fit-cover" alt="Register Banner">

                            <!-- Premium Gradient Overlay -->
                            <div class="position-absolute top-0 start-0 w-100 h-100 z-1"
                                style="background: linear-gradient(135deg, rgba(17,24,39,0.9) 0%, rgba(241,90,41,0.7) 100%);">
                            </div>

                            <!-- Pattern Overlay -->
                            <div class="position-absolute top-0 start-0 w-100 h-100 z-2 opacity-10"
                                style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 20px 20px;">
                            </div>

                            <div class="position-absolute top-50 start-50 translate-middle text-center w-100 px-4 z-3">
                                <div class="bg-white text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-sm"
                                    style="width: 60px; height: 60px;">
                                    <i class="fa-solid fa-user-plus fs-3"></i>
                                </div>
                                <h2 class="fw-black text-white mb-3 display-6 lh-sm">Join the <br>CarBliss Family</h2>
                                <p class="text-light mx-auto" style="font-size: 0.95rem; max-width: 350px;">
                                    Sign up to gain access to members-only deals, faster checkout, and real-time tracking
                                    for your premium orders.
                                </p>

                                <div class="mt-5 d-flex flex-column gap-3 text-start px-3">
                                    <div class="d-flex align-items-center gap-3 text-white">
                                        <i class="fa-solid fa-circle-check text-warning"></i>
                                        <span class="small fw-bold">Received Parcel To Cash On Delivery</span>
                                    </div>
                                    <div class="d-flex align-items-center gap-3 text-white">
                                        <i class="fa-solid fa-circle-check text-warning"></i>
                                        <span class="small fw-bold">Easy Order Payment</span>
                                    </div>
                                    <div class="d-flex align-items-center gap-3 text-white">
                                        <i class="fa-solid fa-circle-check text-warning"></i>
                                        <span class="small fw-bold">Priority customer support</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Side -->
                        <div class="col-12 col-md-7 p-4 p-sm-5 d-flex flex-column justify-content-center">

                            <div class="text-center text-md-start mb-4">
                                <h1 class="h3 fw-black text-dark mb-1">Create an Account</h1>
                                <p class="text-muted small">Fill in the details below to get started.</p>
                            </div>

                            <!-- Registration Form -->
                            <form onsubmit="handleRegisterSubmit(event)">

                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-secondary">Full Name <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group bg-light rounded-3 overflow-hidden">
                                        <span class="input-group-text bg-transparent border-light text-muted ps-3 pe-2">
                                            <i class="fa-regular fa-user"></i>
                                        </span>
                                        <input type="text"
                                            class="form-control bg-transparent border-light border-start-0 ps-0 focus-ring-0"
                                            placeholder="Enter your full name" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-secondary">Email Address <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group bg-light rounded-3 overflow-hidden">
                                        <span class="input-group-text bg-transparent border-light text-muted ps-3 pe-2">
                                            <i class="fa-regular fa-envelope"></i>
                                        </span>
                                        <input type="email"
                                            class="form-control bg-transparent border-light border-start-0 ps-0 focus-ring-0"
                                            placeholder="e.g. example@gmail.com" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-secondary">Phone Number <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group bg-light rounded-3 overflow-hidden">
                                        <span class="input-group-text bg-transparent border-light text-muted ps-3 pe-2">
                                            <i class="fa-solid fa-phone"></i>
                                        </span>
                                        <span
                                            class="input-group-text bg-transparent border-light text-dark fw-bold px-1 border-start-0 border-end-0">
                                            +880
                                        </span>
                                        <input type="tel"
                                            class="form-control bg-transparent border-light border-start-0 ps-1 focus-ring-0"
                                            placeholder="1XXXXXXXXX" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-secondary">Create Password <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group bg-light rounded-3 overflow-hidden">
                                        <span class="input-group-text bg-transparent border-light text-muted ps-3 pe-2">
                                            <i class="fa-solid fa-lock"></i>
                                        </span>
                                        <input type="password" id="reg-password"
                                            class="form-control bg-transparent border-light border-start-0 border-end-0 ps-0 focus-ring-0"
                                            placeholder="Minimum 6 characters" required minlength="6">
                                        <button class="btn bg-transparent border-light border-start-0 text-muted pe-3"
                                            type="button"
                                            onclick="togglePasswordVisibility('reg-password', 'toggle-pwd-icon')">
                                            <i class="fa-regular fa-eye-slash" id="toggle-pwd-icon"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="mb-4 form-check d-flex align-items-start">
                                    <input type="checkbox" class="form-check-input cursor-pointer mt-1" id="agreeTerms"
                                        required>
                                    <label class="form-check-label small text-secondary fw-bold cursor-pointer ms-2"
                                        for="agreeTerms" style="font-size: 0.8rem; line-height: 1.5;">
                                        By creating an account, I agree to CarBlissBD's <a href="#"
                                            class="text-primary text-decoration-none">Terms of Service</a> & <a
                                            href="#" class="text-primary text-decoration-none">Privacy Policy</a>.
                                    </label>
                                </div>

                                <button type="submit"
                                    class="btn btn-primary w-100 rounded-pill fw-bolder py-3 shadow-sm d-flex align-items-center justify-content-center gap-2 transition-all hover:scale-105 text-uppercase"
                                    style="letter-spacing: 1px;">
                                    Sign Up Securely <i class="fa-solid fa-user-check"></i>
                                </button>
                            </form>

                            <div class="text-center pt-3 border-top border-light">
                                <p class="small text-muted fw-bold mb-0">Already have an account? <a
                                        href="{{ route('login') }}"
                                        class="text-primary fw-bolder text-decoration-none ms-1 hover-text-dark transition-all">Sign
                                        In Here</a></p>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>
@endsection
