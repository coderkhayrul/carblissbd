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
                                HM
                            </div>
                            <span
                                class="position-absolute bottom-0 end-0 bg-success border border-2 border-white rounded-circle"
                                style="width: 15px; height: 15px;" title="Active"></span>
                        </div>
                        <div class="overflow-hidden">
                            <h2 class="h6 fw-black text-dark mb-0 text-truncate">Hasan Mahmud</h2>
                            <p class="small text-muted mb-1 text-truncate" style="font-size: 0.75rem;">
                                hasan.mahmud@gmail.com</p>
                            <span class="badge bg-primary bg-opacity-10 text-primary fw-bold"
                                style="font-size: 0.65rem;">Premium Member</span>
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
                            <h3 class="h4 fw-black text-dark mb-1">Hello, Hasan! 👋</h3>
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
                                        <h4 class="fs-3 fw-black text-dark m-0 lh-1">12</h4>
                                        <span class="text-muted small fw-bold">Total Orders</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-4">
                                <div
                                    class="bg-white rounded-4 shadow-sm border border-light p-4 d-flex align-items-center gap-3 transition-all hover-text-primary cursor-pointer">
                                    <div class="rounded-circle bg-orange-50 text-primary d-flex align-items-center justify-content-center fs-4 flex-shrink-0"
                                        style="width: 50px; height: 50px;">
                                        <i class="fa-solid fa-coins"></i>
                                    </div>
                                    <div>
                                        <h4 class="fs-3 fw-black text-dark m-0 lh-1">450</h4>
                                        <span class="text-muted small fw-bold">Reward Points</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-4">
                                <div
                                    class="bg-white rounded-4 shadow-sm border border-light p-4 d-flex align-items-center gap-3 transition-all hover-text-primary cursor-pointer">
                                    <div class="rounded-circle bg-orange-50 text-primary d-flex align-items-center justify-content-center fs-4 flex-shrink-0"
                                        style="width: 50px; height: 50px;">
                                        <i class="fa-solid fa-location-dot"></i>
                                    </div>
                                    <div>
                                        <h4 class="fs-3 fw-black text-dark m-0 lh-1">2</h4>
                                        <span class="text-muted small fw-bold">Saved Addresses</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Orders Summary -->
                        <div class="bg-white rounded-4 shadow-sm border border-light p-4 overflow-hidden">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4 class="h5 fw-bold text-dark m-0">Recent Orders</h4>
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
                                        <tr>
                                            <td class="fw-bold text-dark px-3 py-3">#CB89202</td>
                                            <td class="text-muted small">Oct 12, 2026</td>
                                            <td class="fw-bold text-dark">৳2,100</td>
                                            <td><span
                                                    class="badge bg-warning bg-opacity-10 text-warning border border-warning order-status-badge">Processing</span>
                                            </td>
                                            <td class="text-end px-3"><a href="#"
                                                    class="btn btn-outline-secondary btn-sm rounded-pill fw-bold">View</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold text-dark px-3 py-3">#CB88145</td>
                                            <td class="text-muted small">Sep 28, 2026</td>
                                            <td class="fw-bold text-dark">৳12,990</td>
                                            <td><span
                                                    class="badge bg-success bg-opacity-10 text-success border border-success order-status-badge">Delivered</span>
                                            </td>
                                            <td class="text-end px-3"><a href="#"
                                                    class="btn btn-outline-secondary btn-sm rounded-pill fw-bold">View</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold text-dark px-3 py-3">#CB87090</td>
                                            <td class="text-muted small">Sep 15, 2026</td>
                                            <td class="fw-bold text-dark">৳4,500</td>
                                            <td><span
                                                    class="badge bg-success bg-opacity-10 text-success border border-success order-status-badge">Delivered</span>
                                            </td>
                                            <td class="text-end px-3"><a href="#"
                                                    class="btn btn-outline-secondary btn-sm rounded-pill fw-bold">View</a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- TAB: Orders List -->
                    <div class="tab-pane fade" id="nav-orders" role="tabpanel" aria-labelledby="nav-orders-tab">
                        <div class="mb-4">
                            <h3 class="h4 fw-black text-dark mb-1">My Orders</h3>
                            <p class="text-muted">Track, return, or buy items again.</p>
                        </div>

                        <div class="bg-white rounded-4 shadow-sm border border-light overflow-hidden">
                            <!-- Filter Orders -->
                            <div
                                class="p-3 p-md-4 border-bottom border-light bg-light d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
                                <div class="d-flex align-items-center gap-2 overflow-x-auto no-scrollbar pb-1 pb-sm-0">
                                    <button class="btn btn-dark rounded-pill btn-sm fw-bold px-4 flex-shrink-0">All
                                        Orders</button>
                                    <button
                                        class="btn btn-outline-secondary bg-white text-dark border-light shadow-sm rounded-pill btn-sm fw-bold px-4 flex-shrink-0">Processing</button>
                                    <button
                                        class="btn btn-outline-secondary bg-white text-dark border-light shadow-sm rounded-pill btn-sm fw-bold px-4 flex-shrink-0">Delivered</button>
                                </div>
                                <div class="input-group input-group-sm" style="max-width: 250px;">
                                    <input type="text" class="form-control bg-white border-light focus-ring-0"
                                        placeholder="Search by Order ID">
                                    <button class="btn btn-primary" type="button"><i
                                            class="fa-solid fa-search"></i></button>
                                </div>
                            </div>

                            <!-- Single Order Card (Processing) -->
                            <div class="p-3 p-md-4 border-bottom border-light">
                                <div
                                    class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3">
                                    <div>
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <h5 class="fw-bold text-dark m-0">Order #CB89202</h5>
                                            <span
                                                class="badge bg-warning bg-opacity-10 text-warning border border-warning order-status-badge">Processing</span>
                                        </div>
                                        <span class="text-muted small">Placed on Oct 12, 2026</span>
                                    </div>
                                    <div class="text-md-end">
                                        <span class="fw-black text-primary d-block fs-5">৳2,100</span>
                                        <span class="text-muted small">2 Items • Cash on Delivery</span>
                                    </div>
                                </div>

                                <div
                                    class="bg-light rounded-3 p-3 d-flex align-items-center gap-3 overflow-x-auto no-scrollbar mb-3">
                                    <img src="https://images.unsplash.com/photo-1549399542-7e3f8b79c341?auto=format&fit=crop&q=80&w=100"
                                        class="rounded-2 object-fit-cover flex-shrink-0" width="60" height="60"
                                        alt="Product">
                                    <img src="https://images.unsplash.com/photo-1594535182308-8ffef26626b9?auto=format&fit=crop&q=80&w=100"
                                        class="rounded-2 object-fit-cover flex-shrink-0" width="60" height="60"
                                        alt="Product">
                                </div>

                                <div class="d-flex gap-2">
                                    <button class="btn btn-outline-primary btn-sm rounded-pill fw-bold px-4">Track
                                        Package</button>
                                    <button
                                        class="btn btn-light border-light shadow-sm btn-sm rounded-pill fw-bold px-4">View
                                        Invoice</button>
                                </div>
                            </div>

                            <!-- Single Order Card (Delivered) -->
                            <div class="p-3 p-md-4">
                                <div
                                    class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3">
                                    <div>
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <h5 class="fw-bold text-dark m-0">Order #CB88145</h5>
                                            <span
                                                class="badge bg-success bg-opacity-10 text-success border border-success order-status-badge">Delivered</span>
                                        </div>
                                        <span class="text-muted small">Delivered on Oct 01, 2026</span>
                                    </div>
                                    <div class="text-md-end">
                                        <span class="fw-black text-dark d-block fs-5">৳12,990</span>
                                        <span class="text-muted small">1 Item • Paid via bKash</span>
                                    </div>
                                </div>

                                <div
                                    class="bg-light rounded-3 p-3 d-flex align-items-center gap-3 overflow-x-auto no-scrollbar mb-3">
                                    <img src="https://images.unsplash.com/photo-1583267746897-ea9cf3c46d9a?auto=format&fit=crop&q=80&w=100"
                                        class="rounded-2 object-fit-cover flex-shrink-0" width="60" height="60"
                                        alt="Product">
                                    <div class="flex-grow-1">
                                        <h6 class="fw-bold text-dark m-0 line-clamp-1" style="font-size: 0.8rem;">Smart AI
                                            Android Multimedia Box</h6>
                                        <span class="text-muted small">Qty: 1</span>
                                    </div>
                                </div>

                                <div class="d-flex gap-2">
                                    <button class="btn btn-primary btn-sm rounded-pill fw-bold px-4">Buy Again</button>
                                    <button
                                        class="btn btn-light border-light shadow-sm btn-sm rounded-pill fw-bold px-4">Write
                                        Review</button>
                                </div>
                            </div>
                        </div>
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
                            <form onsubmit="handleProfileUpdate(event)">
                                <h5 class="fw-bold text-dark mb-3 border-bottom border-light pb-2">Personal Information
                                </h5>

                                <div class="row g-4 mb-4">
                                    <div class="col-12 col-md-6">
                                        <label class="form-label small fw-bold text-secondary">First Name</label>
                                        <input type="text" class="form-control bg-light" value="Hasan" required>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label small fw-bold text-secondary">Last Name</label>
                                        <input type="text" class="form-control bg-light" value="Mahmud" required>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label small fw-bold text-secondary">Email Address</label>
                                        <input type="email" class="form-control bg-light"
                                            value="hasan.mahmud@gmail.com" required>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label small fw-bold text-secondary">Phone Number</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">+880</span>
                                            <input type="tel" class="form-control bg-light border-start-0 ps-0"
                                                value="1999906676" required>
                                        </div>
                                    </div>
                                </div>

                                <h5 class="fw-bold text-dark mb-3 border-bottom border-light pb-2 mt-5">Password Change
                                    (Optional)</h5>

                                <div class="row g-4 mb-4">
                                    <div class="col-12">
                                        <label class="form-label small fw-bold text-secondary">Current Password</label>
                                        <input type="password" class="form-control bg-light"
                                            placeholder="Leave blank to remain unchanged">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label small fw-bold text-secondary">New Password</label>
                                        <input type="password" class="form-control bg-light">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label small fw-bold text-secondary">Confirm New Password</label>
                                        <input type="password" class="form-control bg-light">
                                    </div>
                                </div>

                                <div class="pt-3">
                                    <button type="submit"
                                        class="btn btn-primary rounded-pill fw-bolder px-5 py-2 shadow-sm text-uppercase">
                                        Save Changes
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </main>
@endsection
