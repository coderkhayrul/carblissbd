<!doctype html>

<html lang="en" class="layout-wide customizer-hide" dir="ltr" data-skin="default"
    data-assets-path="{{ asset('backend') }}/assets/" data-template="vertical-menu-template-no-customizer"
    data-bs-theme="light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Admin Login | Carblissbd</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('backend') }}/assets/extra/favicon.png" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('backend') }}/assets/vendor/fonts/iconify-icons.css" />

    <!-- Core CSS -->
    <!-- build:css assets/vendor/css/theme.css  -->

    <link rel="stylesheet" href="{{ asset('backend') }}/assets/vendor/css/core.css" />


    <!-- Vendor -->
    <link rel="stylesheet" href="{{ asset('backend') }}/assets/vendor/libs/@form-validation/form-validation.css" />

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="{{ asset('backend') }}/assets/vendor/css/pages/page-auth.css" />

    <!-- Helpers -->
    <script src="{{ asset('backend') }}/assets/vendor/js/helpers.js"></script>

    <script src="{{ asset('backend') }}/assets/js/config.js"></script>
</head>

<body>
    <!-- Content -->

    <div class="authentication-wrapper authentication-cover">
        <!-- Logo -->
        <a href="{{ route('admin.index') }}" class="app-brand auth-cover-brand gap-2">
            <span class="app-brand-logo demo">
                <span class="text-primary">
                    <img src="{{ asset('backend') }}/assets/extra/favicon.png" alt="">
                </span>
            </span>
            <span class="app-brand-text demo text-heading fw-bold">Car Bliss BD</span>
        </a>
        <!-- /Logo -->
        <div class="authentication-inner row m-0">
            <!-- /Left Text -->
            <div class="d-none d-lg-flex col-lg-7 col-xl-8 align-items-center p-5">
                <div class="w-100 d-flex justify-content-center">
                    <img src="{{ asset('backend') }}/assets/extra/login-cover.png" class="img-fluid" alt="Login image"
                        width="700" />
                </div>
            </div>
            <!-- /Left Text -->

            <!-- Login -->
            <div class="d-flex col-12 col-lg-5 col-xl-4 align-items-center authentication-bg p-sm-12 p-6">
                <div class="w-px-400 mx-auto mt-sm-12 mt-8">
                    <h4 class="mb-1">Welcome to Car Bliss BD! 🚗</h4>
                    <p class="mb-6">Please sign-in to your account and start the Managing</p>

                    <form id="formAuthentication" class="mb-6" action="{{ route('admin.login.submit') }}"
                        method="POST">
                        @csrf
                        <div class="mb-6 form-control-validation">
                            <label for="email" class="form-label">Email</label>
                            <input type="text" class="form-control @error('password') is-invalid @enderror"
                                id="email" name="email" placeholder="Enter your email" autofocus
                                value="{{ old('email') }}" />
                            @error('email')
                                <div style="color: red; font-size: 14px; margin-top: 5px;">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-password-toggle form-control-validation">
                            <label class="form-label" for="password">Password</label>
                            <div class="input-group input-group-merge">
                                <input type="password" id="password"
                                    class="form-control  @error('password') is-invalid @enderror" name="password"
                                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                    aria-describedby="password" />
                                <span class="input-group-text cursor-pointer"><i
                                        class="icon-base bx bx-hide"></i></span>
                            </div>
                            @error('password')
                                <div style="color: red; font-size: 14px; margin-top: 5px;">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="my-7">
                            <div class="d-flex justify-content-between">
                                <div class="form-check mb-0">
                                    <input class="form-check-input" type="checkbox" id="remember-me" />
                                    <label class="form-check-label" for="remember-me">Remember Me</label>
                                </div>
                                {{-- <a href="auth-forgot-password-cover.html">
                                    <p class="mb-0">Forgot Password?</p>
                                </a> --}}
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary d-grid w-100">Sign in</button>
                    </form>
                </div>
            </div>
            <!-- /Login -->
        </div>
    </div>
    <!-- / Content -->

    <script src="{{ asset('backend') }}/assets/vendor/libs/jquery/jquery.js"></script>

    <script src="{{ asset('backend') }}/assets/vendor/libs/popper/popper.js"></script>
    <script src="{{ asset('backend') }}/assets/vendor/js/bootstrap.js"></script>


    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="{{ asset('backend') }}/assets/vendor/libs/@form-validation/popular.js"></script>
    <script src="{{ asset('backend') }}/assets/vendor/libs/@form-validation/bootstrap5.js"></script>
    <script src="{{ asset('backend') }}/assets/vendor/libs/@form-validation/auto-focus.js"></script>

    <!-- Main JS -->

    <script src="{{ asset('backend') }}/assets/js/main.js"></script>
</body>

</html>
