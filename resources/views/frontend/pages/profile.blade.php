@extends('frontend.layouts.master')

@section('front-content')
    <!-- // -->
    <main class="container-lg px-3 py-4 py-md-5">
        <div class="row g-4 g-lg-5">
            <!-- Left Sidebar (Navigation) -->
            <div class="col-12 col-lg-3">
                <div class="bg-white rounded-4 shadow-sm border border-light p-3 p-md-4 sticky-lg-top" style="top: 130px;">
                    <!-- User Info Summary -->
                    <div class="d-flex align-items-center gap-3 mb-4 pb-4 border-bottom border-light">
                        <div class="position-relative flex-shrink-0">
                            <div class="rounded-circle bg-orange-50 d-flex align-items-center justify-content-center text-primary border border-2 border-white shadow-sm"
                                style="width: 60px; height: 60px; font-size: 1.5rem;">
                                {{ mb_strtoupper(mb_substr($firstName, 0, 1) . mb_substr($lastName, 0, 1)) }}
                            </div>
                            <span
                                class="position-absolute bottom-0 end-0 bg-success border border-2 border-white rounded-circle"
                                style="width: 15px; height: 15px;" title="Active"></span>
                        </div>
                        <div class="overflow-hidden">
                            <h2 class="h6 fw-black text-dark mb-0 text-truncate">{{ $profileUser->name }}</h2>
                            <p class="small text-muted mb-1 text-truncate" style="font-size: 0.75rem;">
                                {{ $profileUser->email ?: $profileUser->mobile }}</p>
                            <span class="badge bg-primary bg-opacity-10 text-primary fw-bold"
                                style="font-size: 0.65rem;">Customer</span>
                        </div>
                    </div>

                    <!-- Navigation Links (Pills) -->
                    <div class="nav flex-row flex-lg-column gap-2 overflow-x-auto no-scrollbar flex-nowrap"
                        id="profile-tabs" role="tablist">
                        <button
                            class="nav-link profile-nav-link active text-start d-flex align-items-center gap-3 w-100 border-0"
                            id="nav-dashboard-tab" data-bs-toggle="pill" data-bs-target="#nav-dashboard" type="button"
                            role="tab" aria-controls="nav-dashboard" aria-selected="true">
                            <i class="fa-solid fa-border-all flex-shrink-0" style="width: 20px;"></i> Dashboard
                        </button>
                        <button class="nav-link profile-nav-link text-start d-flex align-items-center gap-3 w-100 border-0"
                            id="nav-orders-tab" data-bs-toggle="pill" data-bs-target="#nav-orders" type="button"
                            role="tab" aria-controls="nav-orders" aria-selected="false">
                            <i class="fa-solid fa-box-open flex-shrink-0" style="width: 20px;"></i> My Orders
                        </button>
                        <button class="nav-link profile-nav-link text-start d-flex align-items-center gap-3 w-100 border-0"
                            id="nav-wishlist-tab" data-bs-toggle="pill" data-bs-target="#nav-wishlist" type="button"
                            role="tab" aria-controls="nav-wishlist" aria-selected="false">
                            <i class="fa-regular fa-heart flex-shrink-0" style="width: 20px;"></i> Wishlist
                        </button>
                        <button class="nav-link profile-nav-link text-start d-flex align-items-center gap-3 w-100 border-0"
                            id="nav-settings-tab" data-bs-toggle="pill" data-bs-target="#nav-settings" type="button"
                            role="tab" aria-controls="nav-settings" aria-selected="false">
                            <i class="fa-regular fa-user flex-shrink-0" style="width: 20px;"></i> Account Details
                        </button>

                        <div class="d-none d-lg-block border-top border-light my-2"></div>

                        <a href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                            class="nav-link profile-nav-link text-start d-flex align-items-center gap-3 w-100 text-danger hover-bg-danger hover-text-white transition-all">
                            <i class="fa-solid fa-arrow-right-from-bracket flex-shrink-0" style="width: 20px;"></i> Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right Content Area (Tab Content) -->
            <div class="col-12 col-lg-9">
                <div class="tab-content" id="nav-tabContent">

                    <!-- TAB: Dashboard Overview -->
                    <div class="tab-pane fade show active" id="nav-dashboard" role="tabpanel"
                        aria-labelledby="nav-dashboard-tab">
                        <div class="mb-4">
                            <h3 class="h4 fw-black text-dark mb-1">Hello, {{ $firstName }}! 👋</h3>
                            <p class="text-muted">From your account dashboard, you can view your recent orders, manage your
                                shipping addresses, and edit your password and account details.</p>
                        </div>

                        <!-- Quick Stats -->
                        <div class="row g-3 mb-5">
                            <div class="col-12 col-sm-4">
                                <div class="bg-white rounded-4 shadow-sm border border-light p-4 d-flex align-items-center gap-3 transition-all hover-text-primary cursor-pointer"
                                    onclick="document.getElementById('nav-orders-tab').click()">
                                    <div class="rounded-circle bg-orange-50 text-primary d-flex align-items-center justify-content-center fs-4 flex-shrink-0"
                                        style="width: 50px; height: 50px;">
                                        <i class="fa-solid fa-box"></i>
                                    </div>
                                    <div>
                                        <h4 class="fs-3 fw-black text-dark m-0 lh-1">{{ $totalOrders }}</h4>
                                        <span class="text-muted small fw-bold">Total Orders</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-4">
                                <div
                                    class="bg-white rounded-4 shadow-sm border border-light p-4 d-flex align-items-center gap-3 transition-all hover-text-primary cursor-pointer">
                                    <div class="rounded-circle bg-orange-50 text-primary d-flex align-items-center justify-content-center fs-4 flex-shrink-0"
                                        style="width: 50px; height: 50px;">
                                        <i class="fa-solid fa-hourglass-half"></i>
                                    </div>
                                    <div>
                                        <h4 class="fs-3 fw-black text-dark m-0 lh-1">{{ $pendingOrders }}</h4>
                                        <span class="text-muted small fw-bold">Pending Orders</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-4">
                                <div
                                    class="bg-white rounded-4 shadow-sm border border-light p-4 d-flex align-items-center gap-3 transition-all hover-text-primary cursor-pointer">
                                    <div class="rounded-circle bg-orange-50 text-primary d-flex align-items-center justify-content-center fs-4 flex-shrink-0"
                                        style="width: 50px; height: 50px;">
                                        <i class="fa-solid fa-circle-check"></i>
                                    </div>
                                    <div>
                                        <h4 class="fs-3 fw-black text-dark m-0 lh-1">{{ $completeOrders }}</h4>
                                        <span class="text-muted small fw-bold">Complete Orders</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Orders Summary -->
                        <div class="bg-white rounded-4 shadow-sm border border-light p-4 overflow-hidden">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4 class="h5 fw-bold text-dark m-0">Recent Orders Summary</h4>
                                <button onclick="document.getElementById('nav-orders-tab').click()"
                                    class="btn btn-link text-primary fw-bold text-decoration-none p-0 small">View
                                    All</button>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0" style="min-width: 600px;">
                                    <thead class="table-light text-secondary small text-uppercase">
                                        <tr>
                                            <th class="py-3 px-3 rounded-start">Order ID</th>
                                            <th class="py-3">Date</th>
                                            <th class="py-3">Total</th>
                                            <th class="py-3">Status</th>
                                            <th class="py-3 text-end rounded-end px-3">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="border-top-0">
                                        @include('frontend.partials.customer-recent-orders')
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- TAB: Orders List -->
                    <div class="tab-pane fade" id="nav-orders" role="tabpanel" aria-labelledby="nav-orders-tab">
                        @include('frontend.partials.customer-orders')
                    </div>
                    <!-- TAB: Wishlist (Placeholder for UI) -->
                    <div class="tab-pane fade" id="nav-wishlist" role="tabpanel" aria-labelledby="nav-wishlist-tab">
                        <div class="mb-4">
                            <h3 class="h4 fw-black text-dark mb-1">My Wishlist</h3>
                            <p class="text-muted">Items you've saved for later.</p>
                        </div>
                        <div class="bg-white rounded-4 shadow-sm border border-light p-5 text-center">
                            <div class="rounded-circle bg-orange-50 text-primary d-flex align-items-center justify-content-center fs-1 mx-auto mb-3"
                                style="width: 80px; height: 80px;">
                                <i class="fa-regular fa-heart"></i>
                            </div>
                            <h5 class="fw-bold text-dark">Your wishlist is empty</h5>
                            <p class="text-muted small mb-4">Start adding your favorite premium accessories!</p>
                            <a href="#" class="btn btn-primary rounded-pill fw-bold px-5 py-2">Start
                                Shopping</a>
                        </div>
                    </div>

                    <!-- TAB: Account Settings -->
                    <div class="tab-pane fade" id="nav-settings" role="tabpanel" aria-labelledby="nav-settings-tab">
                        <div class="mb-4">
                            <h3 class="h4 fw-black text-dark mb-1">Account Details</h3>
                            <p class="text-muted">Update your personal information and secure your account.</p>
                        </div>

                        <div class="bg-white rounded-4 shadow-sm border border-light p-4 p-md-5">
                            @include('frontend.partials.account-settings')
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </main>
@if (in_array(request('tab'), ['settings', 'orders']) || request()->has('orders_page') || $errors->any() || session()->has('profile_error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const hasAccountErrors = @json($errors->any() || session()->has('profile_error'));
            const ordersTab = !hasAccountErrors && (new URLSearchParams(window.location.search).get('tab') === 'orders' || new URLSearchParams(window.location.search).has('orders_page'));
            const button = document.getElementById(ordersTab ? 'nav-orders-tab' : 'nav-settings-tab');
            bootstrap.Tab.getOrCreateInstance(button).show();
        });
    </script>
@endif
@endsection
