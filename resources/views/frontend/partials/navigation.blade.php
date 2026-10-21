<!-- Top Main Bar -->
<div class="site-header-main container-lg py-3 d-flex align-items-center justify-content-between gap-3">

    <!-- Brand Logo -->
    <a href="{{ route('home') }}" class="site-header-logo text-decoration-none d-flex align-items-center flex-shrink-0">
        <span class="fs-4 fs-md-3 fw-bolder text-dark d-flex align-items-center">
            CAR<span class="text-primary d-flex align-items-center gap-1">BLISS<i
                    class="fa-solid fa-gauge-high"></i></span>BD
        </span>
    </a>

    <!-- Search Bar (Desktop) -->
    <div class="d-none d-md-block flex-grow-1" style="max-width: 650px; min-width: 0;">
        @include('frontend.partials.product-search', ['searchId' => 'main-search'])
    </div>
    <!-- Header Action Utilities -->
    <div class="site-header-actions d-flex align-items-center gap-3 gap-md-4">

        <!-- Search Button (Mobile Only) -->
        <button
            type="button" id="mobile-search-toggle" aria-label="Open product search" aria-expanded="false" aria-controls="mobile-search-container"
            class="btn btn-link p-0 text-dark hover-text-primary transition-all d-md-none text-decoration-none">
            <div class="bg-orange-50 rounded-circle d-flex align-items-center justify-content-center transition-all"
                style="width: 40px; height: 40px;">
                <i class="fa-solid fa-magnifying-glass fs-5"></i>
            </div>
        </button>

        <!-- Search Action Button (Replacing Wishlist) -->
        <button onclick="document.getElementById('main-search').focus();"
            class="d-none d-md-flex btn btn-link p-0 text-dark hover-text-primary transition-all text-decoration-none">
            <div class="bg-orange-50 rounded-circle d-flex align-items-center justify-content-center transition-all"
                style="width: 40px; height: 40px;">
                <i class="fa-solid fa-magnifying-glass fs-5"></i>
            </div>
        </button>

        <!-- User Profile -->
        <a href="{{ Auth::check() ? route('profile') : route('login') }}"
            class="text-decoration-none text-dark hover-text-primary d-flex align-items-center gap-2 transition-all">
            <div class="bg-orange-50 rounded-circle d-flex align-items-center justify-content-center transition-all"
                style="width: 40px; height: 40px;">
                <i class="fa-regular fa-user fs-5"></i>
            </div>
            <div class="d-none d-lg-block text-start lh-1">
                <span class="text-muted d-block mb-1"
                    style="font-size: 0.7rem;">{{ Auth::check() ? Auth::user()->name : 'Sign In' }}</span>
                <span class="fw-bold d-block" style="font-size: 0.85rem;">My Account</span>
            </div>
        </a>

        <!-- Cart Button -->
        <button onclick="toggleCartDrawer()"
            class="btn btn-link p-0 text-dark hover-text-primary transition-all text-decoration-none position-relative">
            <div class="bg-orange-50 rounded-circle d-flex align-items-center justify-content-center transition-all"
                style="width: 40px; height: 40px;">
                <i class="fa-solid fa-cart-shopping fs-5"></i>
            </div>
            <span id="cart-count-badge"
                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-dark text-white fw-bolder shadow-sm"
                style="font-size: 0.6rem;">0</span>
        </button>
    </div>
</div>

<!-- Mobile Search Container -->
<div id="mobile-search-container" class="d-none d-md-none px-3 pb-3 pt-2 bg-white w-100 border-top border-light">
    @include('frontend.partials.product-search', ['searchId' => 'mobile-product-search'])
</div>