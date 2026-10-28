@include('frontend.partials.header')

<body>
    {{-- @include('frontend.partials.topbar') --}}

    <header class="site-header sticky-top bg-white shadow-sm z-index-highest">
        @include('frontend.partials.navigation')

        @include('frontend.partials.navbar')
    </header>

    @yield('front-content')

    <footer class="bg-dark text-secondary pt-5 pb-5 border-top border-primary border-4" style="margin-top: 2rem;">
        <div class="container-lg px-3">
            <div class="row g-5">
                <div class="col-12 col-md-3">
                    <a href="{{ route('home') }}" class="d-inline-block text-decoration-none mb-3" aria-label="{{ $footerCompany?->name ?: 'Home' }}">
                        @if ($footerCompany?->logo)
                            <img src="{{ asset($footerCompany->logo) }}" alt="{{ $footerCompany->name ?: 'Company logo' }}" loading="lazy" class="img-fluid" style="max-width: 200px; max-height: 80px; object-fit: contain;">
                        @else
                            <span class="fs-4 fw-bolder text-white">{{ $footerCompany?->name ?: 'CarBlissBD' }}</span>
                        @endif
                    </a>
                    @if ($footerCompany?->footer_description)
                        <p class="small mb-4 lh-base" style="font-size: .8rem; white-space: pre-line; overflow-wrap: anywhere;">{{ $footerCompany->footer_description }}</p>
                    @endif
                    <div class="d-flex flex-wrap gap-2">
                        @foreach ($footerSocialLinks as $socialLink)
                            @php
                                $socialUrl = trim($socialLink->url ?? '');
                                $background = preg_match('/^#(?:[a-fA-F0-9]{3}|[a-fA-F0-9]{6}|[a-fA-F0-9]{8})$/', $socialLink->background_color ?? '') ? $socialLink->background_color : '#ffffff1a';
                                $foreground = preg_match('/^#(?:[a-fA-F0-9]{3}|[a-fA-F0-9]{6}|[a-fA-F0-9]{8})$/', $socialLink->foreground_color ?? '') ? $socialLink->foreground_color : '#FFFFFF';
                            @endphp
                            @if (filter_var($socialUrl, FILTER_VALIDATE_URL) && preg_match('~^https?://~i', $socialUrl))
                                <a href="{{ $socialUrl }}" target="_blank" rel="noopener noreferrer"
                                    aria-label="{{ $socialLink->name }}" title="{{ $socialLink->name }}"
                                    class="btn rounded-circle d-flex align-items-center justify-content-center border-0 hover-text-primary transition-all flex-shrink-0"
                                    style="width: 35px; height: 35px; background-color: {{ $background }}; color: {{ $foreground }};">
                                    <i class="{{ $socialLink->icon ?: 'fa-solid fa-link' }}" aria-hidden="true"></i>
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>

                <div class="col-12 col-md-3">
                    <h5 class="text-white fw-bold text-uppercase mb-4" style="font-size: 0.9rem; letter-spacing: 1px;">
                        Customer Care</h5>
                    <ul class="list-unstyled small d-flex flex-column gap-2" style="font-size: 0.8rem;">
                        @foreach ($customerCarePages as $carePage)
                            <li><a href="{{ route('pages.show', $carePage) }}"
                                class="text-secondary text-decoration-none hover-text-primary transition-all">{{ $carePage->name }}</a></li>
                        @endforeach
                    </ul>
                </div>

                <div class="col-12 col-md-3">
                    <h5 class="text-white fw-bold text-uppercase mb-4" style="font-size: 0.9rem; letter-spacing: 1px;">
                        Information</h5>
                    <ul class="list-unstyled small d-flex flex-column gap-2" style="font-size: 0.8rem;">
                        @foreach ($informationPages as $informationPage)
                            <li><a href="{{ route('pages.show', $informationPage) }}"
                                class="text-secondary text-decoration-none hover-text-primary transition-all">{{ $informationPage->name }}</a></li>
                        @endforeach
                    </ul>
                </div>

                <div class="col-12 col-md-3">
                    <h5 class="text-white fw-bold text-uppercase mb-4" style="font-size: 0.9rem; letter-spacing: 1px;">
                        Our Showroom Location</h5>
                    @if ($footerCompany?->address)
                    <div class="d-flex align-items-start gap-2 mb-3 small" style="font-size: 0.8rem;">
                        <i class="fa-solid fa-location-dot text-primary mt-1"></i>
                        <span style="white-space: pre-line; overflow-wrap: anywhere;">{{ $footerCompany->address }}</span>
                    </div>
                    @endif
                    @if ($footerCompany?->hotline || $footerCompany?->phone)
                    <div class="d-flex align-items-center gap-2 small" style="font-size: 0.8rem;">
                        <i class="fa-solid fa-phone text-primary"></i>
                        <span style="overflow-wrap: anywhere;">{{ collect([$footerCompany->hotline, $footerCompany->phone])->filter()->unique()->implode(', ') }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <div class="border-top border-secondary mt-5 pt-4 d-flex flex-column flex-md-row justify-content-between align-items-center small"
                style="font-size: 0.75rem;">
                <p class="mb-0 text-center text-md-start">&copy; 2026 CarBlissBD. All Rights Reserved. Crafted for
                    Ultimate Car Enthusiasts.</p>
                <div class="d-flex gap-3 mt-3 mt-md-0 fs-4">
                    <i class="fa-brands fa-cc-visa text-secondary hover-text-primary cursor-pointer transition-all"></i>
                    <i
                        class="fa-brands fa-cc-mastercard text-secondary hover-text-primary cursor-pointer transition-all"></i>
                    <i
                        class="fa-solid fa-money-bill-transfer text-secondary hover-text-primary cursor-pointer transition-all"></i>
                </div>
            </div>
        </div>
    </footer>

    <div
        class="fixed-bottom bg-white border-top border-light py-2 d-flex justify-content-between align-items-center d-md-none z-index-highest shadow-lg px-2">
        <a href="#" class="text-decoration-none text-primary d-flex flex-column align-items-center text-center"
            style="width: 20%;">
            <i class="fa-solid fa-house fs-5 mb-1"></i>
            <span class="fw-bold" style="font-size: 0.65rem;">Home</span>
        </a>
        <button onclick="toggleCategoryModal()"
            class="btn btn-link text-decoration-none text-muted hover-text-primary p-0 d-flex flex-column align-items-center text-center transition-all"
            style="width: 20%;">
            <i class="fa-solid fa-border-all fs-5 mb-1"></i>
            <span class="fw-bold" style="font-size: 0.65rem;">Category</span>
        </button>
        <a href="#offers"
            class="text-decoration-none text-muted hover-text-primary d-flex flex-column align-items-center text-center transition-all"
            style="width: 20%;">
            <i class="fa-solid fa-tags fs-5 mb-1"></i>
            <span class="fw-bold" style="font-size: 0.65rem;">Offer</span>
        </a>
        <button onclick="toggleCartDrawer()"
            class="btn btn-link text-decoration-none text-muted hover-text-primary p-0 position-relative d-flex flex-column align-items-center text-center transition-all"
            style="width: 20%;">
            <i class="fa-solid fa-cart-shopping fs-5 mb-1"></i>
            <span id="mobile-cart-badge"
                class="position-absolute top-0 start-50 translate-middle-x badge rounded-pill bg-primary text-white"
                style="font-size: 0.5rem; margin-top: -5px; margin-left: 10px;">0</span>
            <span class="fw-bold" style="font-size: 0.65rem;">Cart</span>
        </button>
        <button onclick="toggleModal('user-modal')"
            class="btn btn-link text-decoration-none text-muted hover-text-primary p-0 d-flex flex-column align-items-center text-center transition-all"
            style="width: 20%;">
            <i class="fa-regular fa-user fs-5 mb-1"></i>
            <span class="fw-bold" style="font-size: 0.65rem;">Profile</span>
        </button>
    </div>

    <!-- Drawer Overlay -->
    <div id="drawer-overlay" onclick="closeAllDrawers()"
        class="position-fixed top-0 start-0 w-100 h-100 z-index-highest d-none transition-all glass-overlay"></div>

    <!-- Shopping Cart Drawer -->
    <div id="cart-drawer" class="side-drawer d-flex flex-column border-start border-light shadow-lg">
        <div
            class="p-4 border-bottom border-secondary d-flex justify-content-between align-items-center bg-dark text-white">
            <h3 class="h5 fw-bolder mb-0 d-flex align-items-center gap-2"><i
                    class="fa-solid fa-cart-shopping text-primary"></i> Your Cart</h3>
            <button onclick="toggleCartDrawer()"
                class="btn btn-dark bg-secondary bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center p-0 border-0 hover-bg-primary transition-all"
                style="width: 30px; height: 30px;">
                <i class="fa-solid fa-xmark text-white"></i>
            </button>
        </div>
        <div id="cart-items-container" class="p-4 flex-grow-1 overflow-auto d-flex flex-column gap-3">
            <div id="empty-cart-state"
                class="h-100 d-flex flex-column align-items-center justify-content-center text-center">
                <div class="rounded-circle bg-orange-50 d-flex align-items-center justify-content-center text-primary fs-1 mb-3"
                    style="width: 80px; height: 80px;">
                    <i class="fa-solid fa-cart-flatbed-suitcases animate-pulse-custom"></i>
                </div>
                <h4 class="fw-bold text-dark h6">Your Cart is Empty</h4>
                <p class="small text-muted mb-4" style="max-width: 200px;">Fill it with high-grade upgrades for your
                    premium vehicle today!</p>
                <button onclick="toggleCartDrawer()" class="btn btn-primary rounded-pill btn-sm fw-bold px-4">Start
                    Shopping</button>
            </div>
        </div>
        <div class="p-4 border-top border-light bg-light mt-auto">
            <div class="d-flex justify-content-between align-items-center fw-bold mb-2" style="font-size: 0.85rem;">
                <span class="text-muted">Subtotal:</span>
                <span id="cart-subtotal" class="text-dark fw-bolder">৳0</span>
            </div>
            <div class="d-flex justify-content-between align-items-center fw-bold mb-3 pb-3 border-bottom border-secondary border-opacity-10"
                style="font-size: 0.85rem;">
                <span class="text-muted">Shipping (Estimated):</span>
                <span id="cart-shipping" class="text-dark fw-bolder">৳0</span>
            </div>
            <div class="d-flex justify-content-between align-items-center fw-bold mb-3">
                <span class="text-dark">Total:</span>
                <span id="cart-total" class="fs-5 text-primary fw-bolder">৳0</span>
            </div>
            <button onclick="proceedToCheckout()"
                class="btn btn-primary w-100 rounded-3 fw-bolder py-2 shadow-sm d-flex align-items-center justify-content-center gap-2">
                Checkout securely <i class="fa-solid fa-circle-arrow-right"></i>
            </button>
        </div>
    </div>

    <!-- Mobile Category Bottom Drawer -->
    <div id="category-drawer" class="bottom-drawer d-flex flex-column shadow-lg">
        <div class="p-3 border-bottom border-light d-flex justify-content-between align-items-center bg-white"
            style="border-top-left-radius: 1.5rem; border-top-right-radius: 1.5rem;">
            <button id="cat-back-btn" onclick="showMainCategories()"
                class="btn btn-link text-secondary p-0 d-none align-items-center justify-content-center text-decoration-none"
                style="width: 30px; height: 30px;">
                <i class="fa-solid fa-arrow-left"></i>
            </button>
            <h3 id="cat-modal-title" class="h6 fw-bolder text-dark mb-0 flex-grow-1 text-center">All Categories</h3>
            <button onclick="toggleCategoryModal()"
                class="btn btn-light rounded-circle p-0 d-flex align-items-center justify-content-center"
                style="width: 30px; height: 30px;">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div
            class="flex-grow-1 overflow-auto bg-light pb-5 position-relative border-top border-secondary border-opacity-10 category-drawer-content">
            <div id="main-cat-grid" class="p-3 row row-cols-3 g-2 m-0"></div>
            <div id="sub-cat-list" class="d-none p-3 pb-5 bg-white">
                <ul id="sub-cat-items" class="list-unstyled d-flex flex-column gap-2 m-0"></ul>
            </div>
        </div>
    </div>

    <!-- User LogIn Modal (Center Screen) -->
    <div id="user-modal"
        class="position-fixed top-0 start-0 w-100 h-100 bg-dark bg-opacity-50 z-index-highest d-none align-items-center justify-content-center p-3 transition-all">
        <div class="bg-white rounded-4 shadow-lg position-relative p-4 p-md-5 w-100" style="max-width: 400px;">
            <button onclick="closeModal('user-modal')"
                class="btn btn-light position-absolute top-0 end-0 m-3 rounded-circle d-flex align-items-center justify-content-center p-0 z-3 border"
                style="width: 35px; height: 35px;">
                <i class="fa-solid fa-xmark"></i>
            </button>
            <div class="text-center mb-4 mt-2">
                <span class="fs-3 fw-bolder text-dark">CAR<span class="text-primary">BLISS</span>BD</span>
                <p class="small text-muted mt-1">Enter details to sign in & track your premium orders</p>
            </div>
            <form onsubmit="handleDemoLogin(event)">
                <div class="mb-3">
                    <label class="form-label small fw-bold text-secondary">Email or Phone Number</label>
                    <input type="text" placeholder="example@gmail.com" required
                        class="form-control rounded-3 bg-light px-3 py-2 border-1 focus-ring-0">
                </div>
                <div class="mb-4">
                    <label class="form-label small fw-bold text-secondary">Password</label>
                    <input type="password" placeholder="••••••••" required
                        class="form-control rounded-3 bg-light px-3 py-2 border-1 focus-ring-0">
                </div>
                <button type="submit" class="btn btn-primary w-100 rounded-3 fw-bolder py-2 shadow-sm">Sign In
                    securely</button>
            </form>
        </div>
    </div>

    <!-- User LogIn Modal -->
    <div id="user-modal"
        class="position-fixed top-0 start-0 w-100 h-100 bg-dark bg-opacity-50 z-index-highest d-none align-items-center justify-content-center p-3 transition-all">
        <div class="bg-white rounded-4 shadow-lg position-relative p-4 p-md-5 w-100" style="max-width: 400px;">
            <button onclick="closeModal('user-modal')"
                class="btn btn-light position-absolute top-0 end-0 m-3 rounded-circle d-flex align-items-center justify-content-center p-0 z-3 border"
                style="width: 35px; height: 35px;">
                <i class="fa-solid fa-xmark"></i>
            </button>
            <div class="text-center mb-4 mt-2">
                <span class="fs-3 fw-bolder text-dark">CAR<span class="text-primary">BLISS</span>BD</span>
                <p class="small text-muted mt-1">Enter details to sign in & track your premium orders</p>
            </div>
            <form
                onsubmit="event.preventDefault(); closeModal('user-modal'); showToast('Login Successful', 'Welcome back!');">
                <div class="mb-3">
                    <label class="form-label small fw-bold text-secondary">Email or Phone Number</label>
                    <input type="text" placeholder="example@gmail.com" required
                        class="form-control rounded-3 bg-light px-3 py-2 border-1 focus-ring-0">
                </div>
                <div class="mb-4">
                    <label class="form-label small fw-bold text-secondary">Password</label>
                    <input type="password" placeholder="••••••••" required
                        class="form-control rounded-3 bg-light px-3 py-2 border-1 focus-ring-0">
                </div>
                <button type="submit" class="btn btn-primary w-100 rounded-3 fw-bolder py-2 shadow-sm">Sign In
                    securely</button>
            </form>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast-notif"
        class="position-fixed z-index-highest bg-dark text-white rounded-3 shadow-lg d-flex align-items-center gap-3 p-3 transition-all"
        style="top: 20px; right: 20px; border-left: 4px solid var(--bs-primary); transform: translateY(-150%); opacity: 0; min-width: 250px;">
        <div class="rounded-circle d-flex align-items-center justify-content-center text-primary"
            style="width: 30px; height: 30px; background: rgba(241, 90, 41, 0.2);">
            <i class="fa-solid fa-check small"></i>
        </div>
        <div>
            <h5 class="fw-bold text-white mb-0" style="font-size: 0.85rem;">Action Successful</h5>
            <p class="text-secondary mb-0" id="toast-subtitle" style="font-size: 0.75rem;">Item updated</p>
        </div>
    </div>

    @include('frontend.partials.popup-ad')
    @include('frontend.partials.footer')
