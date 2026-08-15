<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <!-- Viewport tag for 100% responsiveness -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CarBlissBD | Premier Car Accessories & Spare Parts Store</title>

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <!-- Google Font (Inter) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom Styles (Overriding Bootstrap defaults to match the brand) -->

    <style>
        :root {
            --bs-primary: #F15A29;
            --bs-primary-hover: #d4491c;
            --bs-primary-rgb: 241, 90, 41;
            --bs-dark: #111827;
            --bs-body-font-family: 'Inter', sans-serif;
            --bs-body-bg: #f8f9fa;
        }

        body {
            font-family: var(--bs-body-font-family);
            background-color: var(--bs-body-bg);
            /* Add padding bottom on mobile to accommodate bottom nav bar */
            padding-bottom: 70px;
        }

        @media (min-width: 768px) {
            body {
                padding-bottom: 0;
            }
        }

        /* Override Bootstrap Utilities */
        .text-primary {
            color: var(--bs-primary) !important;
        }

        .bg-primary {
            background-color: var(--bs-primary) !important;
        }

        .bg-dark {
            background-color: var(--bs-dark) !important;
        }

        .btn-primary {
            background-color: var(--bs-primary);
            border-color: var(--bs-primary);
            color: #fff;
        }

        .btn-primary:hover,
        .btn-primary:focus {
            background-color: var(--bs-primary-hover);
            border-color: var(--bs-primary-hover);
            color: #fff;
        }

        .btn-outline-primary {
            color: var(--bs-primary);
            border-color: var(--bs-primary);
        }

        .btn-outline-primary:hover {
            background-color: var(--bs-primary);
            color: white;
        }

        /* Helper Classes */
        .cursor-pointer {
            cursor: pointer;
        }

        .transition-all {
            transition: all 0.3s ease;
        }

        .hover-text-primary:hover {
            color: var(--bs-primary) !important;
        }

        .hover-bg-primary:hover {
            background-color: var(--bs-primary) !important;
            color: white !important;
        }

        .bg-orange-50 {
            background-color: #fff7f5 !important;
        }

        /* New Category Design Styles */
        .category-card {
            transition: all 0.3s ease;
            border-color: rgba(241, 90, 41, 0.15) !important;
            /* Light tint of primary color */
        }

        .category-card:hover {
            border-color: var(--bs-primary) !important;
            box-shadow: 0 8px 16px rgba(241, 90, 41, 0.15) !important;
            transform: translateY(-3px);
        }

        .category-card:hover .category-img-zoom {
            transform: scale(1.08);
        }

        .category-img-zoom {
            transition: transform 0.4s ease;
        }

        /* Custom Pagination (Matching the Image) */
        .category-pagination .swiper-pagination-bullet {
            background: #ffbba6;
            /* Light primary color */
            opacity: 0.8;
            width: 8px;
            height: 8px;
            margin: 0 4px !important;
            transition: all 0.3s ease;
        }

        .category-pagination .swiper-pagination-bullet-active {
            background: var(--bs-primary);
            opacity: 1;
            width: 24px;
            /* Pill shape for active dot */
            border-radius: 4px;
        }

        /* Typography Clamp */
        .line-clamp-1 {
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Smooth scrolling (Disabled on mobile to prevent sticky header jitter) */
        @media (min-width: 768px) {
            html {
                scroll-behavior: smooth;
            }
        }

        /* Anti-jitter fix for mobile sticky header */
        @media (max-width: 767px) {
            .sticky-top {
                position: -webkit-sticky !important;
                position: sticky !important;
                top: 0;
                -webkit-transform: translate3d(0, 0, 0);
                transform: translate3d(0, 0, 0);
                -webkit-backface-visibility: hidden;
                backface-visibility: hidden;
                -webkit-perspective: 1000;
                perspective: 1000;
                will-change: transform;
            }
        }

        /* Premium custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--bs-primary);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--bs-primary-hover);
        }

        /* Hide scrollbar for horizontal scroll containers */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Specific Component Styles */
        .z-index-highest {
            z-index: 1050;
        }

        /* Search Box Dropdown - Centered & Wide */
        .search-suggestion-box {
            width: 800px;
            max-width: 95vw;
            left: 50%;
            transform: translateX(-50%);
            display: none;
            /* Hidden by default */
        }

        /* Hero Slider Resizing */
        .hero-slider-container {
            height: 250px;
        }

        @media (min-width: 576px) {
            .hero-slider-container {
                height: 400px;
            }
        }

        @media (min-width: 768px) {
            .hero-slider-container {
                height: 500px;
            }
        }

        /* Swiper Customizations */
        .swiper-pagination-bullet {
            background: #fff;
            opacity: 0.5;
            width: 10px;
            height: 10px;
            transition: all 0.3s ease;
        }

        .swiper-pagination-bullet-active {
            background: var(--bs-primary);
            opacity: 1;
            width: 25px;
            border-radius: 5px;
        }

        /* Hide default Swiper navigation icons */
        .swiper-button-next::after,
        .swiper-button-prev::after {
            display: none;
        }

        .swiper-button-next,
        .swiper-button-prev {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            color: white;
            transition: background 0.3s;
        }

        .swiper-button-next:hover,
        .swiper-button-prev:hover {
            background: rgba(255, 255, 255, 0.4);
        }

        /* Promo Card Overlay */
        .promo-overlay {
            background: linear-gradient(to right, rgba(234, 88, 12, 0.7), rgba(0, 0, 0, 0.5));
        }

        /* Product Card Interactions */
        .product-card {
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
        }

        .product-card:hover .product-img {
            transform: scale(1.1);
        }

        /* Horizontal Snap Scrolling */
        .snap-x {
            scroll-snap-type: x mandatory;
        }

        .snap-start {
            scroll-snap-align: start;
        }

        /* Drawers (Cart & Category) */
        .side-drawer {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            width: 100%;
            max-width: 400px;
            background: #fff;
            z-index: 1060;
            transform: translateX(100%);
            transition: transform 0.3s ease-in-out;
        }

        .side-drawer.show {
            transform: translateX(0);
        }

        .bottom-drawer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 85vh;
            background: #fff;
            z-index: 1060;
            transform: translateY(100%);
            transition: transform 0.3s ease-in-out;
            border-top-left-radius: 1.5rem;
            border-top-right-radius: 1.5rem;
        }

        .bottom-drawer.show {
            transform: translateY(0);
        }

        /* Animation */
        @keyframes pulse-custom {
            0% {
                transform: scale(1);
                opacity: 1;
            }

            50% {
                transform: scale(1.05);
                opacity: 0.8;
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .animate-pulse-custom {
            animation: pulse-custom 2s infinite;
        }



        /* Drawer Overlay Glass Effect */
        .glass-overlay {
            background-color: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }

        /* Glass effect for desktop modal */
        @media (min-width: 768px) {
            #user-modal {
                backdrop-filter: blur(8px);
                -webkit-backdrop-filter: blur(8px);
                background-color: rgba(0, 0, 0, 0.5) !important;
            }
        }
    </style>
</head>

<body>

    <div class="bg-dark text-white py-2 px-3 border-bottom border-secondary d-flex justify-content-between align-items-center"
        style="font-size: 0.75rem;">
        <div class="d-flex align-items-center gap-3">
            <span class="d-flex align-items-center gap-1"><i class="fa-solid fa-phone text-primary"></i> Hotline: +880
                1999 906676</span>
            <span class="d-none d-md-flex align-items-center gap-1"><i class="fa-solid fa-envelope text-primary"></i>
                support@carblissbd.com</span>
        </div>
        <div class="d-flex align-items-center gap-3">
            <span class="text-primary fw-bold d-none d-sm-inline animate-pulse-custom">⚡ Super Delivery
                Nationwide!</span>
            <a href="#offers" class="text-white text-decoration-none hover-text-primary transition-all">Offers</a>
            <span class="text-secondary">|</span>
            <a href="#track" class="text-white text-decoration-none hover-text-primary transition-all">Track Order</a>
        </div>
    </div>

    <header class="sticky-top bg-white shadow-sm z-index-highest">
        <!-- Top Main Bar -->
        <div class="container-lg py-3 d-flex align-items-center justify-content-between gap-3">

            <!-- Brand Logo -->
            <a href="#" class="text-decoration-none d-flex align-items-center flex-shrink-0">
                <span class="fs-4 fs-md-3 fw-bolder text-dark d-flex align-items-center">
                    CAR<span class="text-primary d-flex align-items-center gap-1">BLISS<i
                            class="fa-solid fa-gauge-high"></i></span>BD
                </span>
            </a>

            <!-- Search Bar (Desktop) -->
            <div class="d-none d-md-block flex-grow-1 position-relative" style="max-width: 650px;">
                <form onsubmit="event.preventDefault();" class="d-flex w-100">
                    <input type="search" id="main-search"
                        placeholder="Search genuine accessories, engine oils, brake pads..."
                        class="form-control rounded-start-pill py-2 px-4 bg-light border-0" style="box-shadow: none;"
                        oninput="handleSearchSuggestions(this.value)"
                        onblur="setTimeout(() => document.getElementById('search-suggestions').style.display = 'none', 200)"
                        onfocus="if(this.value.length > 1) document.getElementById('search-suggestions').style.display = 'block'">
                    <button type="submit"
                        class="btn btn-primary rounded-end-pill px-4 d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </form>

                <!-- Live Search Suggestions Dropdown -->
                <div id="search-suggestions"
                    class="position-absolute top-100 mt-2 bg-white rounded-4 shadow-lg border border-light search-suggestion-box overflow-hidden z-3">
                    <div
                        class="p-3 border-bottom border-light d-flex justify-content-between align-items-center bg-light">
                        <span class="fw-bold text-secondary text-uppercase"
                            style="font-size: 0.7rem; letter-spacing: 0.05em;">Top Results for your search</span>
                        <a href="#" class="fw-bold text-primary text-decoration-none"
                            style="font-size: 0.7rem;">View All Results</a>
                    </div>
                    <!-- Product Grid Layout in Search -->
                    <div class="p-3 row row-cols-5 g-3 m-0">
                        <a href="#"
                            class="col text-decoration-none text-dark d-flex flex-column align-items-center gap-2 p-2 rounded-3 hover-text-primary hover-bg-primary bg-opacity-10 transition-all">
                            <div class="w-100 ratio ratio-1x1 bg-light rounded-3 overflow-hidden border border-light">
                                <img src="https://images.unsplash.com/photo-1486006920555-c77dce18193b?auto=format&fit=crop&q=80&w=200"
                                    alt="Brake Pads" class="object-fit-cover w-100 h-100">
                            </div>
                            <h4 class="fw-bold text-center line-clamp-2 mt-1 mb-0" style="font-size: 0.65rem;">Premium
                                Ceramic Brake Pads Set</h4>
                            <span class="fw-bolder text-primary mt-auto" style="font-size: 0.75rem;">৳10,200</span>
                        </a>
                        <!-- Search Item 2 -->
                        <a href="#"
                            class="col text-decoration-none text-dark d-flex flex-column align-items-center gap-2 p-2 rounded-3 hover-text-primary hover-bg-primary bg-opacity-10 transition-all">
                            <div class="w-100 ratio ratio-1x1 bg-light rounded-3 overflow-hidden border border-light">
                                <img src="https://images.unsplash.com/photo-1619642751034-765dfdf7c58e?auto=format&fit=crop&q=80&w=200"
                                    alt="Engine Oil" class="object-fit-cover w-100 h-100">
                            </div>
                            <h4 class="fw-bold text-center line-clamp-2 mt-1 mb-0" style="font-size: 0.65rem;">Advanced
                                Synthetic Engine Oil 5W-30</h4>
                            <span class="fw-bolder text-primary mt-auto" style="font-size: 0.75rem;">৳4,500</span>
                        </a>
                        <!-- Search Item 3 -->
                        <a href="#"
                            class="col text-decoration-none text-dark d-flex flex-column align-items-center gap-2 p-2 rounded-3 hover-text-primary hover-bg-primary bg-opacity-10 transition-all">
                            <div class="w-100 ratio ratio-1x1 bg-light rounded-3 overflow-hidden border border-light">
                                <img src="https://images.unsplash.com/photo-1549399542-7e3f8b79c341?auto=format&fit=crop&q=80&w=200"
                                    alt="Steering Cover" class="object-fit-cover w-100 h-100">
                            </div>
                            <h4 class="fw-bold text-center line-clamp-2 mt-1 mb-0" style="font-size: 0.65rem;">Carbon
                                Fiber Style Steering Cover</h4>
                            <span class="fw-bolder text-primary mt-auto" style="font-size: 0.75rem;">৳1,190</span>
                        </a>
                        <!-- Search Item 4 -->
                        <a href="#"
                            class="col text-decoration-none text-dark d-flex flex-column align-items-center gap-2 p-2 rounded-3 hover-text-primary hover-bg-primary bg-opacity-10 transition-all">
                            <div class="w-100 ratio ratio-1x1 bg-light rounded-3 overflow-hidden border border-light">
                                <img src="https://images.unsplash.com/photo-1563720223185-11003d516935?auto=format&fit=crop&q=80&w=200"
                                    alt="Air Bed" class="object-fit-cover w-100 h-100">
                            </div>
                            <h4 class="fw-bold text-center line-clamp-2 mt-1 mb-0" style="font-size: 0.65rem;">
                                Inflatable Back Seat Air Mattress</h4>
                            <span class="fw-bolder text-primary mt-auto" style="font-size: 0.75rem;">৳9,500</span>
                        </a>
                        <!-- Search Item 5 -->
                        <a href="#"
                            class="col text-decoration-none text-dark d-flex flex-column align-items-center gap-2 p-2 rounded-3 hover-text-primary hover-bg-primary bg-opacity-10 transition-all">
                            <div class="w-100 ratio ratio-1x1 bg-light rounded-3 overflow-hidden border border-light">
                                <img src="https://images.unsplash.com/photo-1600706432502-75a0e2b34440?auto=format&fit=crop&q=80&w=200"
                                    alt="Washer" class="object-fit-cover w-100 h-100">
                            </div>
                            <h4 class="fw-bold text-center line-clamp-2 mt-1 mb-0" style="font-size: 0.65rem;">
                                Cordless High Pressure Car Washer</h4>
                            <span class="fw-bolder text-primary mt-auto" style="font-size: 0.75rem;">৳10,000</span>
                        </a>
                        <!-- Add 5 more items for the second row -->
                        <a href="#"
                            class="col text-decoration-none text-dark d-flex flex-column align-items-center gap-2 p-2 rounded-3 hover-text-primary hover-bg-primary bg-opacity-10 transition-all">
                            <div class="w-100 ratio ratio-1x1 bg-light rounded-3 overflow-hidden border border-light">
                                <img src="https://images.unsplash.com/photo-1554223090-7e482851df45?auto=format&fit=crop&q=80&w=200"
                                    alt="Light" class="object-fit-cover w-100 h-100">
                            </div>
                            <h4 class="fw-bold text-center line-clamp-2 mt-1 mb-0" style="font-size: 0.65rem;">Super
                                Bright H4 LED Headlight Bulbs</h4>
                            <span class="fw-bolder text-primary mt-auto" style="font-size: 0.75rem;">৳3,500</span>
                        </a>
                        <a href="#"
                            class="col text-decoration-none text-dark d-flex flex-column align-items-center gap-2 p-2 rounded-3 hover-text-primary hover-bg-primary bg-opacity-10 transition-all">
                            <div class="w-100 ratio ratio-1x1 bg-light rounded-3 overflow-hidden border border-light">
                                <img src="https://images.unsplash.com/photo-1594535182308-8ffef26626b9?auto=format&fit=crop&q=80&w=200"
                                    alt="Perfume" class="object-fit-cover w-100 h-100">
                            </div>
                            <h4 class="fw-bold text-center line-clamp-2 mt-1 mb-0" style="font-size: 0.65rem;">Luxury
                                Dashboard Liquid Perfume</h4>
                            <span class="fw-bolder text-primary mt-auto" style="font-size: 0.75rem;">৳850</span>
                        </a>
                        <a href="#"
                            class="col text-decoration-none text-dark d-flex flex-column align-items-center gap-2 p-2 rounded-3 hover-text-primary hover-bg-primary bg-opacity-10 transition-all">
                            <div class="w-100 ratio ratio-1x1 bg-light rounded-3 overflow-hidden border border-light">
                                <img src="https://images.unsplash.com/photo-1583267746897-ea9cf3c46d9a?auto=format&fit=crop&q=80&w=200"
                                    alt="Android" class="object-fit-cover w-100 h-100">
                            </div>
                            <h4 class="fw-bold text-center line-clamp-2 mt-1 mb-0" style="font-size: 0.65rem;">Smart
                                AI Android Multimedia Box</h4>
                            <span class="fw-bolder text-primary mt-auto" style="font-size: 0.75rem;">৳12,990</span>
                        </a>
                        <a href="#"
                            class="col text-decoration-none text-dark d-flex flex-column align-items-center gap-2 p-2 rounded-3 hover-text-primary hover-bg-primary bg-opacity-10 transition-all">
                            <div class="w-100 ratio ratio-1x1 bg-light rounded-3 overflow-hidden border border-light">
                                <img src="https://images.unsplash.com/photo-1518182170546-07661fd94144?auto=format&fit=crop&q=80&w=200"
                                    alt="Wiper" class="object-fit-cover w-100 h-100">
                            </div>
                            <h4 class="fw-bold text-center line-clamp-2 mt-1 mb-0" style="font-size: 0.65rem;">
                                Silicone Frameless Wiper Blades</h4>
                            <span class="fw-bolder text-primary mt-auto" style="font-size: 0.75rem;">৳650</span>
                        </a>
                        <a href="#"
                            class="col text-decoration-none text-dark d-flex flex-column align-items-center gap-2 p-2 rounded-3 hover-text-primary hover-bg-primary bg-opacity-10 transition-all">
                            <div class="w-100 ratio ratio-1x1 bg-light rounded-3 overflow-hidden border border-light">
                                <img src="https://images.unsplash.com/photo-1530124566582-a618bc2615dc?auto=format&fit=crop&q=80&w=200"
                                    alt="Tools" class="object-fit-cover w-100 h-100">
                            </div>
                            <h4 class="fw-bold text-center line-clamp-2 mt-1 mb-0" style="font-size: 0.65rem;">
                                40-Piece Car Repair Tool Kit</h4>
                            <span class="fw-bolder text-primary mt-auto" style="font-size: 0.75rem;">৳2,200</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Header Action Utilities -->
            <div class="d-flex align-items-center gap-3 gap-md-4">

                <!-- Search Button (Mobile Only) -->
                <button
                    onclick="document.getElementById('main-search').focus(); document.getElementById('mobile-search-container').classList.toggle('d-none');"
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
                <a href="javascript:void(0)" onclick="toggleModal('user-modal')"
                    class="text-decoration-none text-dark hover-text-primary d-flex align-items-center gap-2 transition-all">
                    <div class="bg-orange-50 rounded-circle d-flex align-items-center justify-content-center transition-all"
                        style="width: 40px; height: 40px;">
                        <i class="fa-regular fa-user fs-5"></i>
                    </div>
                    <div class="d-none d-lg-block text-start lh-1">
                        <span class="text-muted d-block mb-1" style="font-size: 0.7rem;">Sign In</span>
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

        <!-- Mobile Search Container (Hidden by default) -->
        <div id="mobile-search-container" class="d-none px-3 pb-3 bg-white w-100 border-top border-light">
            <form onsubmit="event.preventDefault();"
                class="d-flex w-100 shadow-sm rounded-pill mt-2 bg-light border border-secondary border-opacity-10">
                <input type="search" placeholder="Search genuine accessories..."
                    class="form-control rounded-start-pill py-2 bg-transparent border-0 px-4 focus-ring-0"
                    style="box-shadow: none;">
                <button type="submit" class="btn btn-primary rounded-pill px-4 m-1">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </form>
        </div>

        <nav class="bg-white border-top border-bottom border-light w-100 shadow-sm">
            <div class="container-lg px-3">
                <div class="d-flex align-items-center gap-2 overflow-x-auto no-scrollbar py-2">
                    <a href="#interior"
                        class="btn bg-light text-dark fw-bold border border-secondary border-opacity-10 rounded-pill px-3 py-1 text-nowrap d-flex align-items-center gap-2 hover-bg-primary transition-all"
                        style="font-size: 0.75rem;">
                        <i class="fa-solid fa-couch text-muted"></i> Interior
                    </a>
                    <a href="#exterior"
                        class="btn bg-light text-dark fw-bold border border-secondary border-opacity-10 rounded-pill px-3 py-1 text-nowrap d-flex align-items-center gap-2 hover-bg-primary transition-all"
                        style="font-size: 0.75rem;">
                        <i class="fa-solid fa-car text-muted"></i> Exterior
                    </a>
                    <a href="#electronics"
                        class="btn bg-light text-dark fw-bold border border-secondary border-opacity-10 rounded-pill px-3 py-1 text-nowrap d-flex align-items-center gap-2 hover-bg-primary transition-all"
                        style="font-size: 0.75rem;">
                        <i class="fa-solid fa-microchip text-muted"></i> Electronics
                    </a>
                    <a href="#care"
                        class="btn bg-light text-dark fw-bold border border-secondary border-opacity-10 rounded-pill px-3 py-1 text-nowrap d-flex align-items-center gap-2 hover-bg-primary transition-all"
                        style="font-size: 0.75rem;">
                        <i class="fa-solid fa-spray-can text-muted"></i> Car Care
                    </a>
                    <a href="#spareparts"
                        class="btn bg-light text-dark fw-bold border border-secondary border-opacity-10 rounded-pill px-3 py-1 text-nowrap d-flex align-items-center gap-2 hover-bg-primary transition-all"
                        style="font-size: 0.75rem;">
                        <i class="fa-solid fa-gears text-muted"></i> Spare Parts
                    </a>
                    <a href="#lighting"
                        class="btn bg-light text-dark fw-bold border border-secondary border-opacity-10 rounded-pill px-3 py-1 text-nowrap d-flex align-items-center gap-2 hover-bg-primary transition-all"
                        style="font-size: 0.75rem;">
                        <i class="fa-solid fa-lightbulb text-muted"></i> Lighting
                    </a>
                    <a href="#perfumes"
                        class="btn bg-light text-dark fw-bold border border-secondary border-opacity-10 rounded-pill px-3 py-1 text-nowrap d-flex align-items-center gap-2 hover-bg-primary transition-all"
                        style="font-size: 0.75rem;">
                        <i class="fa-solid fa-wind text-muted"></i> Perfumes
                    </a>
                    <a href="#tools"
                        class="btn bg-light text-dark fw-bold border border-secondary border-opacity-10 rounded-pill px-3 py-1 text-nowrap d-flex align-items-center gap-2 hover-bg-primary transition-all"
                        style="font-size: 0.75rem;">
                        <i class="fa-solid fa-screwdriver-wrench text-muted"></i> Tools
                    </a>
                </div>
            </div>
        </nav>
    </header>

    <!-- Swiper Hero Section Updates Here -->
    <section class="container-lg px-0 px-md-3">
        <div
            class="swiper hero-swiper hero-slider-container position-relative bg-white overflow-hidden my-md-4 rounded-0 rounded-md-4 shadow-sm">

            <div class="swiper-wrapper">
                <!-- Slide 1 -->
                <div class="swiper-slide position-relative w-100 h-100">
                    <div class="position-absolute top-0 start-0 w-100 h-100 z-1"
                        style="background: linear-gradient(to right, rgba(0,0,0,0.85), rgba(0,0,0,0.5), transparent);">
                    </div>
                    <img src="https://images.unsplash.com/photo-1619642751034-765dfdf7c58e?auto=format&fit=crop&q=80&w=1200"
                        alt="Premium Accessories"
                        class="position-absolute top-0 start-0 w-100 h-100 object-fit-cover">
                    <div
                        class="position-relative z-2 h-100 container d-flex flex-column justify-content-center align-items-start text-white p-4 p-md-5">
                        <span class="badge bg-primary rounded-pill text-uppercase mb-3 px-3 py-2 shadow-sm"
                            style="font-size: 0.7rem; letter-spacing: 1px;">UP TO 35% DISCOUNT</span>
                        <h1 class="display-6 display-md-4 fw-bolder lh-sm mb-3">Elevate Your Premium <br
                                class="d-none d-sm-inline">Driving Experience</h1>
                        <p class="d-none d-sm-block text-light mb-4" style="max-width: 500px; font-size: 0.9rem;">
                            Explore
                            high-quality, genuine carbon fiber steering covers, suspension kits, and customized audio
                            systems with hassle-free fast home delivery.</p>
                        <a href="#new-arrivals"
                            class="btn btn-primary rounded-pill fw-bold px-4 py-2 px-md-5 py-md-3 shadow-lg transition-all"
                            style="font-size: 0.9rem;">Shop Accessories Now</a>
                    </div>
                </div>

                <!-- Slide 2 -->
                <div class="swiper-slide position-relative w-100 h-100">
                    <div class="position-absolute top-0 start-0 w-100 h-100 z-1"
                        style="background: linear-gradient(to right, rgba(0,0,0,0.85), rgba(0,0,0,0.5), transparent);">
                    </div>
                    <img src="https://images.unsplash.com/photo-1619642751034-765dfdf7c58e?auto=format&fit=crop&q=80&w=1200"
                        alt="Car Engine Oil Care"
                        class="position-absolute top-0 start-0 w-100 h-100 object-fit-cover">
                    <div
                        class="position-relative z-2 h-100 container d-flex flex-column justify-content-center align-items-start text-white p-4 p-md-5">
                        <span class="badge bg-primary rounded-pill text-uppercase mb-3 px-3 py-2 shadow-sm"
                            style="font-size: 0.7rem; letter-spacing: 1px;">GENUINE FLUIDS</span>
                        <h2 class="display-6 display-md-4 fw-bolder lh-sm mb-3">Elite Grade Spare Parts <br
                                class="d-none d-sm-inline">& Premium Engine Oils</h2>
                        <p class="d-none d-sm-block text-light mb-4" style="max-width: 500px; font-size: 0.9rem;">Keep
                            your engine young with premium engine oils, high-performance filters, and reliable brake pad
                            assemblies curated for all car makes.</p>
                        <a href="#parts"
                            class="btn btn-primary rounded-pill fw-bold px-4 py-2 px-md-5 py-md-3 shadow-lg transition-all"
                            style="font-size: 0.9rem;">View Parts Catalog</a>
                    </div>
                </div>
            </div>

            <!-- Bullet Pagination -->
            <div class="swiper-pagination mb-2"></div>
        </div>
    </section>

    <section class="container-lg px-3 py-5">
        <div class="row g-4">
            <!-- Promo 1 -->
            <div class="col-12 col-md-4">
                <div class="position-relative overflow-hidden rounded-4 shadow-sm group" style="height: 200px;">
                    <div class="position-absolute top-0 start-0 w-100 h-100 z-1 promo-overlay"></div>
                    <img src="https://images.unsplash.com/photo-1518182170546-07661fd94144?auto=format&fit=crop&q=80&w=600"
                        class="position-absolute top-0 start-0 w-100 h-100 object-fit-cover transition-all"
                        style="transition-duration: 500ms;" alt="Rainy">
                    <div class="position-relative z-2 h-100 p-4 d-flex flex-column justify-content-end text-white">
                        <h3 class="h5 fw-bold mb-1">Rainy Day Essentials</h3>
                        <p class="small text-light mb-3" style="font-size: 0.75rem;">High-performance silicone wiper
                            blades & mudguards.</p>
                        <a href="javascript:void(0)"
                            class="btn btn-light rounded-pill btn-sm text-primary fw-bold d-inline-flex align-items-center gap-2 align-self-start">Shop
                            Now <i class="fa-solid fa-circle-arrow-right"></i></a>
                    </div>
                </div>
            </div>
            <!-- Promo 2 -->
            <div class="col-12 col-md-4">
                <div class="position-relative overflow-hidden rounded-4 shadow-sm group" style="height: 200px;">
                    <div class="position-absolute top-0 start-0 w-100 h-100 z-1 promo-overlay"></div>
                    <img src="https://images.unsplash.com/photo-1506015391300-4802dc74de2e?auto=format&fit=crop&q=80&w=600"
                        class="position-absolute top-0 start-0 w-100 h-100 object-fit-cover transition-all"
                        style="transition-duration: 500ms;" alt="Summer">
                    <div class="position-relative z-2 h-100 p-4 d-flex flex-column justify-content-end text-white">
                        <h3 class="h5 fw-bold mb-1">Summer Essentials</h3>
                        <p class="small text-light mb-3" style="font-size: 0.75rem;">Premium dashboard shades & seat
                            cooling fans.</p>
                        <a href="javascript:void(0)"
                            class="btn btn-light rounded-pill btn-sm text-primary fw-bold d-inline-flex align-items-center gap-2 align-self-start">Shop
                            Now <i class="fa-solid fa-circle-arrow-right"></i></a>
                    </div>
                </div>
            </div>
            <!-- Promo 3 -->
            <div class="col-12 col-md-4">
                <div class="position-relative overflow-hidden rounded-4 shadow-sm group" style="height: 200px;">
                    <div class="position-absolute top-0 start-0 w-100 h-100 z-1 promo-overlay"></div>
                    <img src="https://images.unsplash.com/photo-1544263010-0934c99596c1?auto=format&fit=crop&q=80&w=600"
                        class="position-absolute top-0 start-0 w-100 h-100 object-fit-cover transition-all"
                        style="transition-duration: 500ms;" alt="Winter">
                    <div class="position-relative z-2 h-100 p-4 d-flex flex-column justify-content-end text-white">
                        <h3 class="h5 fw-bold mb-1">Winter Protection</h3>
                        <p class="small text-light mb-3" style="font-size: 0.75rem;">High strength fog lamps & engine
                            coolants.</p>
                        <a href="javascript:void(0)"
                            class="btn btn-light rounded-pill btn-sm text-primary fw-bold d-inline-flex align-items-center gap-2 align-self-start">Shop
                            Now <i class="fa-solid fa-circle-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-white py-5 border-top border-bottom border-light overflow-hidden">
        <div class="container-lg px-3">

            <!-- Centered Header -->
            <div class="text-center mb-4 pb-2">
                <h2 class="h3 fw-bolder text-dark text-uppercase mb-1" style="letter-spacing: 0.5px;">Shop By Category
                </h2>
                <p class="text-muted small mb-0">Choose your favorite products from our popular categories</p>
            </div>

            <!-- Swiper Category Container -->
            <div class="position-relative">
                <div class="swiper new-category-swiper pb-5"> <!-- pb-5 added to make room for pagination dots -->
                    <div class="swiper-wrapper">

                        <!-- Category Item 1 -->
                        <div class="swiper-slide" style="width: 210px;">
                            <a href="#interior"
                                class="text-decoration-none d-flex flex-column bg-white rounded-3 border category-card overflow-hidden h-100">
                                <div class="p-3 d-flex align-items-center justify-content-center bg-white"
                                    style="height: 170px;">
                                    <img src="https://images.unsplash.com/photo-1554223090-7e482851df45?auto=format&fit=crop&q=80&w=300"
                                        alt="Car Interior" class="w-100 h-100 object-fit-contain category-img-zoom">
                                </div>
                                <div class="border-top p-3 bg-white mt-auto"
                                    style="border-color: rgba(241, 90, 41, 0.1) !important;">
                                    <span class="fw-bolder text-dark text-center d-block"
                                        style="font-size: 0.85rem;">Car Interior</span>
                                </div>
                            </a>
                        </div>

                        <!-- Category Item 2 -->
                        <div class="swiper-slide" style="width: 210px;">
                            <a href="#exterior"
                                class="text-decoration-none d-flex flex-column bg-white rounded-3 border category-card overflow-hidden h-100">
                                <div class="p-3 d-flex align-items-center justify-content-center bg-white"
                                    style="height: 170px;">
                                    <img src="https://images.unsplash.com/photo-1554223090-7e482851df45?auto=format&fit=crop&q=80&w=300"
                                        alt="Car Exterior" class="w-100 h-100 object-fit-contain category-img-zoom">
                                </div>
                                <div class="border-top p-3 bg-white mt-auto"
                                    style="border-color: rgba(241, 90, 41, 0.1) !important;">
                                    <span class="fw-bolder text-dark text-center d-block"
                                        style="font-size: 0.85rem;">Car Exterior</span>
                                </div>
                            </a>
                        </div>

                        <!-- Category Item 3 -->
                        <div class="swiper-slide" style="width: 210px;">
                            <a href="#electronics"
                                class="text-decoration-none d-flex flex-column bg-white rounded-3 border category-card overflow-hidden h-100">
                                <div class="p-3 d-flex align-items-center justify-content-center bg-white"
                                    style="height: 170px;">
                                    <img src="https://images.unsplash.com/photo-1554223090-7e482851df45?auto=format&fit=crop&q=80&w=300"
                                        alt="Electronics & Gadgets"
                                        class="w-100 h-100 object-fit-contain category-img-zoom">
                                </div>
                                <div class="border-top p-3 bg-white mt-auto"
                                    style="border-color: rgba(241, 90, 41, 0.1) !important;">
                                    <span class="fw-bolder text-dark text-center d-block"
                                        style="font-size: 0.85rem;">Electronics & Gadgets</span>
                                </div>
                            </a>
                        </div>

                        <!-- Category Item 4 -->
                        <div class="swiper-slide" style="width: 210px;">
                            <a href="#care"
                                class="text-decoration-none d-flex flex-column bg-white rounded-3 border category-card overflow-hidden h-100">
                                <div class="p-3 d-flex align-items-center justify-content-center bg-white"
                                    style="height: 170px;">
                                    <img src="https://images.unsplash.com/photo-1554223090-7e482851df45?auto=format&fit=crop&q=80&w=300"
                                        alt="Car Care & Wash"
                                        class="w-100 h-100 object-fit-contain category-img-zoom">
                                </div>
                                <div class="border-top p-3 bg-white mt-auto"
                                    style="border-color: rgba(241, 90, 41, 0.1) !important;">
                                    <span class="fw-bolder text-dark text-center d-block"
                                        style="font-size: 0.85rem;">Car Care & Wash</span>
                                </div>
                            </a>
                        </div>

                        <!-- Category Item 5 -->
                        <div class="swiper-slide" style="width: 210px;">
                            <a href="#perfumes"
                                class="text-decoration-none d-flex flex-column bg-white rounded-3 border category-card overflow-hidden h-100">
                                <div class="p-3 d-flex align-items-center justify-content-center bg-white"
                                    style="height: 170px;">
                                    <img src="https://images.unsplash.com/photo-1554223090-7e482851df45?auto=format&fit=crop&q=80&w=300"
                                        alt="Perfumes & Air Fresheners"
                                        class="w-100 h-100 object-fit-contain category-img-zoom">
                                </div>
                                <div class="border-top p-3 bg-white mt-auto"
                                    style="border-color: rgba(241, 90, 41, 0.1) !important;">
                                    <span class="fw-bolder text-dark text-center d-block"
                                        style="font-size: 0.85rem;">Perfumes & Fresheners</span>
                                </div>
                            </a>
                        </div>

                        <!-- Category Item 6 -->
                        <div class="swiper-slide" style="width: 210px;">
                            <a href="#spareparts"
                                class="text-decoration-none d-flex flex-column bg-white rounded-3 border category-card overflow-hidden h-100">
                                <div class="p-3 d-flex align-items-center justify-content-center bg-white"
                                    style="height: 170px;">
                                    <img src="https://images.unsplash.com/photo-1554223090-7e482851df45?auto=format&fit=crop&q=80&w=300"
                                        alt="Spare Parts" class="w-100 h-100 object-fit-contain category-img-zoom">
                                </div>
                                <div class="border-top p-3 bg-white mt-auto"
                                    style="border-color: rgba(241, 90, 41, 0.1) !important;">
                                    <span class="fw-bolder text-dark text-center d-block"
                                        style="font-size: 0.85rem;">Genuine Spare Parts</span>
                                </div>
                            </a>
                        </div>

                        <!-- Category Item 7 -->
                        <div class="swiper-slide" style="width: 210px;">
                            <a href="#lighting"
                                class="text-decoration-none d-flex flex-column bg-white rounded-3 border category-card overflow-hidden h-100">
                                <div class="p-3 d-flex align-items-center justify-content-center bg-white"
                                    style="height: 170px;">
                                    <img src="https://images.unsplash.com/photo-1554223090-7e482851df45?auto=format&fit=crop&q=80&w=300"
                                        alt="LED Lighting" class="w-100 h-100 object-fit-contain category-img-zoom">
                                </div>
                                <div class="border-top p-3 bg-white mt-auto"
                                    style="border-color: rgba(241, 90, 41, 0.1) !important;">
                                    <span class="fw-bolder text-dark text-center d-block"
                                        style="font-size: 0.85rem;">LED & Lighting</span>
                                </div>
                            </a>
                        </div>

                    </div>

                    <!-- Dynamic Swiper Pagination Dots -->
                    <div class="swiper-pagination category-pagination w-100 d-flex justify-content-center mt-3"></div>
                </div>
            </div>
        </div>
    </section>

    <section id="new-arrivals" class="container-lg px-3 py-5 overflow-hidden">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4">
            <div>
                <h2 class="h4 fw-bolder text-dark text-uppercase d-flex align-items-center gap-2 mb-1">
                    <i class="fa-solid fa-fire text-primary animate-pulse-custom"></i> New Arrivals
                </h2>
                <p class="text-muted small mb-0">Freshest stock, direct from global manufacturers</p>
            </div>
            <!-- Interactive Filter Chips -->
            <div class="d-flex align-items-center gap-2 overflow-x-auto py-2 py-md-0 mt-3 mt-md-0">
                <button class="btn btn-primary rounded-pill btn-sm fw-bold px-3">All</button>
            </div>
        </div>

        <!-- Product Swiper Container -->
        <div class="position-relative px-md-4">
            <div class="swiper new-arrivals-swiper py-3">
                <div class="swiper-wrapper">

                    <!-- Product Card 1 -->
                    <div class="swiper-slide">
                        <div class="card border-light shadow-sm rounded-4 h-100 product-card">
                            <div class="position-absolute top-0 start-0 p-2 z-2 d-flex flex-column gap-1">
                                <span class="badge bg-primary rounded-pill text-uppercase shadow-sm"
                                    style="font-size: 0.6rem;">Free Delivery</span>
                            </div>
                            <div class="position-relative bg-light rounded-top-4 overflow-hidden d-flex align-items-center justify-content-center"
                                style="height: 180px;">
                                <a href="#"><img
                                        src="https://images.unsplash.com/photo-1549399542-7e3f8b79c341?auto=format&fit=crop&q=80&w=400"
                                        alt="Universal Steering Cover"
                                        class="w-100 h-100 object-fit-cover transition-all product-img"></a>
                            </div>
                            <div class="card-body d-flex flex-column p-3">
                                <div class="mb-auto">
                                    <span class="text-muted fw-bold text-uppercase d-block mb-1"
                                        style="font-size: 0.6rem; letter-spacing: 0.05em;">Car Interior</span>
                                    <a href="#" class="text-decoration-none text-dark">
                                        <h3 class="h6 fw-bold line-clamp-2 mb-2 hover-text-primary transition-all"
                                            style="font-size: 0.85rem;">Universal Carbon Fiber Style Custom Premium
                                            Steering Cover</h3>
                                    </a>
                                    <div class="d-flex text-warning mb-2" style="font-size: 0.7rem;">
                                        <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                            class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                            class="fa-solid fa-star"></i>
                                    </div>
                                    <span class="fw-bolder text-primary fs-5 mb-3 d-block">৳1,190</span>
                                </div>
                                <div class="d-flex gap-2 mt-auto">
                                    <button
                                        onclick="addToCart(1, 'Universal Steering Cover', 1190, 'https://images.unsplash.com/photo-1549399542-7e3f8b79c341?auto=format&fit=crop&q=80&w=400')"
                                        class="btn btn-outline-primary rounded-3 flex-shrink-0 d-flex align-items-center justify-content-center p-0"
                                        style="width: 40px; height: 40px;" title="Add to Cart">
                                        <i class="fa-solid fa-cart-plus"></i>
                                    </button>
                                    <button onclick="buyNow(1)" class="btn btn-primary rounded-3 flex-grow-1 fw-bold"
                                        style="font-size: 0.85rem;">Buy Now</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Product Card 2 -->
                    <div class="swiper-slide">
                        <div class="card border-light shadow-sm rounded-4 h-100 product-card">
                            <div class="position-absolute top-0 start-0 p-2 z-2 d-flex flex-column gap-1">
                                <span class="badge bg-dark rounded-pill text-uppercase shadow-sm"
                                    style="font-size: 0.6rem;">-15% OFF</span>
                            </div>
                            <div class="position-relative bg-light rounded-top-4 overflow-hidden d-flex align-items-center justify-content-center"
                                style="height: 180px;">
                                <a href="#"><img
                                        src="https://images.unsplash.com/photo-1563720223185-11003d516935?auto=format&fit=crop&q=80&w=400"
                                        alt="Ceramic Brake Pads"
                                        class="w-100 h-100 object-fit-cover transition-all product-img"></a>
                            </div>
                            <div class="card-body d-flex flex-column p-3">
                                <div class="mb-auto">
                                    <span class="text-muted fw-bold text-uppercase d-block mb-1"
                                        style="font-size: 0.6rem; letter-spacing: 0.05em;">Spare Parts</span>
                                    <a href="#" class="text-decoration-none text-dark">
                                        <h3 class="h6 fw-bold line-clamp-2 mb-2 hover-text-primary transition-all"
                                            style="font-size: 0.85rem;">Powerstop Extreme Sport Front Ceramic Carbon
                                            Brake Pads</h3>
                                    </a>
                                    <div class="d-flex text-warning mb-2" style="font-size: 0.7rem;">
                                        <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                            class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                            class="fa-regular fa-star"></i>
                                    </div>
                                    <div class="d-flex align-items-end gap-2 mb-3">
                                        <span class="fw-bolder text-primary fs-5 lh-1">৳10,200</span>
                                        <span class="text-muted text-decoration-line-through lh-1"
                                            style="font-size: 0.75rem;">৳12,000</span>
                                    </div>
                                </div>
                                <div class="d-flex gap-2 mt-auto">
                                    <button
                                        onclick="addToCart(2, 'Powerstop Ceramic Brake Pads', 10200, 'https://images.unsplash.com/photo-1486006920555-c77dce18193b?auto=format&fit=crop&q=80&w=400')"
                                        class="btn btn-outline-primary rounded-3 flex-shrink-0 d-flex align-items-center justify-content-center p-0"
                                        style="width: 40px; height: 40px;" title="Add to Cart">
                                        <i class="fa-solid fa-cart-plus"></i>
                                    </button>
                                    <button onclick="buyNow(2)" class="btn btn-primary rounded-3 flex-grow-1 fw-bold"
                                        style="font-size: 0.85rem;">Buy Now</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Product Card 3 -->
                    <div class="swiper-slide">
                        <div class="card border-light shadow-sm rounded-4 h-100 product-card">
                            <div class="position-relative bg-light rounded-top-4 overflow-hidden d-flex align-items-center justify-content-center"
                                style="height: 180px;">
                                <a href="#"><img
                                        src="https://images.unsplash.com/photo-1563720223185-11003d516935?auto=format&fit=crop&q=80&w=400"
                                        alt="Universal Seat Bed"
                                        class="w-100 h-100 object-fit-cover transition-all product-img"></a>
                            </div>
                            <div class="card-body d-flex flex-column p-3">
                                <div class="mb-auto">
                                    <span class="text-muted fw-bold text-uppercase d-block mb-1"
                                        style="font-size: 0.6rem; letter-spacing: 0.05em;">Car Interior</span>
                                    <a href="#" class="text-decoration-none text-dark">
                                        <h3 class="h6 fw-bold line-clamp-2 mb-2 hover-text-primary transition-all"
                                            style="font-size: 0.85rem;">Rechargeable Inflatable Back Seat Air Bed
                                            Mattress for Road Trips</h3>
                                    </a>
                                    <div class="d-flex text-warning mb-2" style="font-size: 0.7rem;">
                                        <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                            class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                            class="fa-solid fa-star"></i>
                                    </div>
                                    <span class="fw-bolder text-primary fs-5 mb-3 d-block">৳9,500</span>
                                </div>
                                <div class="d-flex gap-2 mt-auto">
                                    <button
                                        onclick="addToCart(3, 'Rechargeable Inflatable Air Bed', 9500, 'https://images.unsplash.com/photo-1563720223185-11003d516935?auto=format&fit=crop&q=80&w=400')"
                                        class="btn btn-outline-primary rounded-3 flex-shrink-0 d-flex align-items-center justify-content-center p-0"
                                        style="width: 40px; height: 40px;" title="Add to Cart">
                                        <i class="fa-solid fa-cart-plus"></i>
                                    </button>
                                    <button onclick="buyNow(3)" class="btn btn-primary rounded-3 flex-grow-1 fw-bold"
                                        style="font-size: 0.85rem;">Buy Now</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Product Card 4 -->
                    <div class="swiper-slide">
                        <div class="card border-light shadow-sm rounded-4 h-100 product-card">
                            <div class="position-absolute top-0 start-0 p-2 z-2 d-flex flex-column gap-1">
                                <span class="badge bg-dark rounded-pill text-uppercase shadow-sm"
                                    style="font-size: 0.6rem;">Hot Pick</span>
                            </div>
                            <div class="position-relative bg-light rounded-top-4 overflow-hidden d-flex align-items-center justify-content-center"
                                style="height: 180px;">
                                <a href="#"><img
                                        src="https://images.unsplash.com/photo-1563720223185-11003d516935?auto=format&fit=crop&q=80&w=400"
                                        alt="Pressure Washer"
                                        class="w-100 h-100 object-fit-cover transition-all product-img"></a>
                            </div>
                            <div class="card-body d-flex flex-column p-3">
                                <div class="mb-auto">
                                    <span class="text-muted fw-bold text-uppercase d-block mb-1"
                                        style="font-size: 0.6rem; letter-spacing: 0.05em;">Car Care</span>
                                    <a href="#" class="text-decoration-none text-dark">
                                        <h3 class="h6 fw-bold line-clamp-2 mb-2 hover-text-primary transition-all"
                                            style="font-size: 0.85rem;">Cordless High Pressure Car Washer & Gun -
                                            Premium Quality</h3>
                                    </a>
                                    <div class="d-flex text-warning mb-2" style="font-size: 0.7rem;">
                                        <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                            class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                            class="fa-regular fa-star"></i>
                                    </div>
                                    <span class="fw-bolder text-primary fs-5 mb-3 d-block">৳10,000</span>
                                </div>
                                <div class="d-flex gap-2 mt-auto">
                                    <button
                                        onclick="addToCart(4, 'Cordless Washer', 10000, 'https://images.unsplash.com/photo-1600706432502-75a0e2b34440?auto=format&fit=crop&q=80&w=400')"
                                        class="btn btn-outline-primary rounded-3 flex-shrink-0 d-flex align-items-center justify-content-center p-0"
                                        style="width: 40px; height: 40px;" title="Add to Cart">
                                        <i class="fa-solid fa-cart-plus"></i>
                                    </button>
                                    <button onclick="buyNow(4)" class="btn btn-primary rounded-3 flex-grow-1 fw-bold"
                                        style="font-size: 0.85rem;">Buy Now</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Product Card 5 -->
                    <div class="swiper-slide">
                        <div class="card border-light shadow-sm rounded-4 h-100 product-card">
                            <div class="position-absolute top-0 start-0 p-2 z-2 d-flex flex-column gap-1">
                                <span class="badge bg-primary rounded-pill text-uppercase shadow-sm"
                                    style="font-size: 0.6rem;">New</span>
                            </div>
                            <div class="position-relative bg-light rounded-top-4 overflow-hidden d-flex align-items-center justify-content-center"
                                style="height: 180px;">
                                <a href="#"><img
                                        src="https://images.unsplash.com/photo-1563720223185-11003d516935?auto=format&fit=crop&q=80&w=400"
                                        alt="Car Audio Player"
                                        class="w-100 h-100 object-fit-cover transition-all product-img"></a>
                            </div>
                            <div class="card-body d-flex flex-column p-3">
                                <div class="mb-auto">
                                    <span class="text-muted fw-bold text-uppercase d-block mb-1"
                                        style="font-size: 0.6rem; letter-spacing: 0.05em;">Electronics</span>
                                    <a href="#" class="text-decoration-none text-dark">
                                        <h3 class="h6 fw-bold line-clamp-2 mb-2 hover-text-primary transition-all"
                                            style="font-size: 0.85rem;">Premium Car Smart AI Multimedia Box Android
                                            Player</h3>
                                    </a>
                                    <div class="d-flex text-warning mb-2" style="font-size: 0.7rem;">
                                        <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                            class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                            class="fa-solid fa-star"></i>
                                    </div>
                                    <span class="fw-bolder text-primary fs-5 mb-3 d-block">৳12,990</span>
                                </div>
                                <div class="d-flex gap-2 mt-auto">
                                    <button
                                        onclick="addToCart(5, 'Smart AI Multimedia Box', 12990, 'https://images.unsplash.com/photo-1583267746897-ea9cf3c46d9a?auto=format&fit=crop&q=80&w=400')"
                                        class="btn btn-outline-primary rounded-3 flex-shrink-0 d-flex align-items-center justify-content-center p-0"
                                        style="width: 40px; height: 40px;" title="Add to Cart">
                                        <i class="fa-solid fa-cart-plus"></i>
                                    </button>
                                    <button onclick="buyNow(5)" class="btn btn-primary rounded-3 flex-grow-1 fw-bold"
                                        style="font-size: 0.85rem;">Buy Now</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <section id="popular-brands" class="bg-white py-5 border-top border-bottom border-light overflow-hidden">
        <div class="container-lg px-3">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h4 fw-bolder text-dark text-uppercase mb-1">Shop By Verified Brands</h2>
                    <p class="text-muted small mb-0">Authentic manufacturers with direct replacement warranties</p>
                </div>
                <div class="d-none d-sm-block bg-primary rounded" style="width: 60px; height: 4px;"></div>
            </div>

            <div class="position-relative px-md-4">
                <!-- Swiper Container -->
                <div class="swiper brands-swiper py-2">
                    <div class="swiper-wrapper">
                        <!-- Brand 1 -->
                        <div class="swiper-slide">
                            <div class="bg-light p-4 rounded-4 text-center border border-light transition-all hover-text-primary cursor-pointer d-flex align-items-center justify-content-center h-100"
                                onmouseover="this.classList.add('shadow-sm')"
                                onmouseout="this.classList.remove('shadow-sm')">
                                <span class="fw-bolder fs-6 text-secondary text-uppercase"
                                    style="letter-spacing: 1px;">CASTROL</span>
                            </div>
                        </div>
                        <!-- Brand 2 -->
                        <div class="swiper-slide">
                            <div class="bg-light p-4 rounded-4 text-center border border-light transition-all hover-text-primary cursor-pointer d-flex align-items-center justify-content-center h-100"
                                onmouseover="this.classList.add('shadow-sm')"
                                onmouseout="this.classList.remove('shadow-sm')">
                                <span class="fw-bolder fs-6 text-secondary text-uppercase"
                                    style="letter-spacing: 1px;">POWERSTOP</span>
                            </div>
                        </div>
                        <!-- Brand 3 -->
                        <div class="swiper-slide">
                            <div class="bg-light p-4 rounded-4 text-center border border-light transition-all hover-text-primary cursor-pointer d-flex align-items-center justify-content-center h-100"
                                onmouseover="this.classList.add('shadow-sm')"
                                onmouseout="this.classList.remove('shadow-sm')">
                                <span class="fw-bolder fs-6 text-secondary text-uppercase"
                                    style="letter-spacing: 1px;">YESIDO</span>
                            </div>
                        </div>
                        <!-- Brand 4 -->
                        <div class="swiper-slide">
                            <div class="bg-light p-4 rounded-4 text-center border border-light transition-all hover-text-primary cursor-pointer d-flex align-items-center justify-content-center h-100"
                                onmouseover="this.classList.add('shadow-sm')"
                                onmouseout="this.classList.remove('shadow-sm')">
                                <span class="fw-bolder fs-6 text-secondary text-uppercase"
                                    style="letter-spacing: 1px;">HYUNDAI</span>
                            </div>
                        </div>
                        <!-- Brand 5 -->
                        <div class="swiper-slide">
                            <div class="bg-light p-4 rounded-4 text-center border border-light transition-all hover-text-primary cursor-pointer d-flex align-items-center justify-content-center h-100"
                                onmouseover="this.classList.add('shadow-sm')"
                                onmouseout="this.classList.remove('shadow-sm')">
                                <span class="fw-bolder fs-6 text-secondary text-uppercase"
                                    style="letter-spacing: 1px;">DENSO</span>
                            </div>
                        </div>
                        <!-- Brand 6 -->
                        <div class="swiper-slide">
                            <div class="bg-light p-4 rounded-4 text-center border border-light transition-all hover-text-primary cursor-pointer d-flex align-items-center justify-content-center h-100"
                                onmouseover="this.classList.add('shadow-sm')"
                                onmouseout="this.classList.remove('shadow-sm')">
                                <span class="fw-bolder fs-6 text-secondary text-uppercase"
                                    style="letter-spacing: 1px;">LIQUI
                                    MOLY</span>
                            </div>
                        </div>
                        <!-- Brand 6 -->
                        <div class="swiper-slide">
                            <div class="bg-light p-4 rounded-4 text-center border border-light transition-all hover-text-primary cursor-pointer d-flex align-items-center justify-content-center h-100"
                                onmouseover="this.classList.add('shadow-sm')"
                                onmouseout="this.classList.remove('shadow-sm')">
                                <span class="fw-bolder fs-6 text-secondary text-uppercase"
                                    style="letter-spacing: 1px;">LIQUI
                                    MOLY</span>
                            </div>
                        </div>
                        <!-- Brand 6 -->
                        <div class="swiper-slide">
                            <div class="bg-light p-4 rounded-4 text-center border border-light transition-all hover-text-primary cursor-pointer d-flex align-items-center justify-content-center h-100"
                                onmouseover="this.classList.add('shadow-sm')"
                                onmouseout="this.classList.remove('shadow-sm')">
                                <span class="fw-bolder fs-6 text-secondary text-uppercase"
                                    style="letter-spacing: 1px;">LIQUI
                                    MOLY</span>
                            </div>
                        </div>
                        <!-- Brand 6 -->
                        <div class="swiper-slide">
                            <div class="bg-light p-4 rounded-4 text-center border border-light transition-all hover-text-primary cursor-pointer d-flex align-items-center justify-content-center h-100"
                                onmouseover="this.classList.add('shadow-sm')"
                                onmouseout="this.classList.remove('shadow-sm')">
                                <span class="fw-bolder fs-6 text-secondary text-uppercase"
                                    style="letter-spacing: 1px;">LIQUI
                                    MOLY</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Swiper Navigation Arrows -->
                {{-- <div class="swiper-button-prev brand-prev btn btn-light position-absolute top-50 start-0 translate-middle-y shadow-sm z-3 d-none d-md-flex align-items-center justify-content-center"
                    style="margin-left: 0px;">
                    <i class="fa-solid fa-chevron-left"></i>
                </div>
                <div class="swiper-button-next brand-next btn btn-light position-absolute top-50 end-0 translate-middle-y shadow-sm z-3 d-none d-md-flex align-items-center justify-content-center"
                    style="margin-right: 0px;">
                    <i class="fa-solid fa-chevron-right"></i>
                </div> --}}
            </div>
        </div>
    </section>

    <section id="you-may-like" class="container-lg px-3 py-5">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4">
            <div>
                <h2 class="h4 fw-bolder text-dark text-uppercase d-flex align-items-center gap-2 mb-1">
                    <i class="fa-solid fa-thumbs-up text-primary"></i> You May Like
                </h2>
                <p class="text-muted small mb-0">Handpicked premium accessories recommended just for you</p>
            </div>
        </div>

        <!-- JS will dynamically populate 10 products here in this row -->
        <div id="you-may-like-grid" class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-2 g-md-4">
            <!-- Dynamic Injection from JS -->
            <div class="col">
                <div class="card border-light shadow-sm rounded-4 h-100 product-card">
                    <div class="position-absolute top-0 start-0 p-2 z-2 d-flex flex-column gap-1">
                        <span class="badge bg-dark rounded-pill text-uppercase shadow-sm"
                            style="font-size: 0.55rem;">Recommended</span>
                    </div>
                    <div class="position-relative bg-light rounded-top-4 overflow-hidden d-flex align-items-center justify-content-center"
                        style="height: 150px;">
                        <a href="#"><img
                                src="https://images.unsplash.com/photo-1549399542-7e3f8b79c341?auto=format&fit=crop&q=80&w=400"
                                alt="Recommended Item"
                                class="w-100 h-100 object-fit-cover transition-all product-img"></a>
                    </div>
                    <div class="card-body d-flex flex-column p-2 p-sm-3">
                        <div class="mb-auto">
                            <span class="text-muted fw-bold text-uppercase d-block mb-1"
                                style="font-size: 0.55rem; letter-spacing: 0.05em;">Exclusive</span>
                            <a href="#" class="text-decoration-none text-dark">
                                <h3 class="h6 fw-bold line-clamp-2 mb-1 hover-text-primary transition-all"
                                    style="font-size: 0.75rem;">Premium Auto Part Model 5</h3>
                            </a>
                            <div class="d-flex text-warning mb-2" style="font-size: 0.6rem;">
                                <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                    class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                    class="fa-solid fa-star-half-stroke"></i>
                            </div>
                            <span class="fw-bolder text-primary mb-2 d-block" style="font-size: 0.9rem;">৳500</span>
                        </div>
                        <div class="d-flex gap-1 gap-sm-2 mt-auto">
                            <button
                                class="btn btn-outline-primary rounded-3 flex-shrink-0 d-flex align-items-center justify-content-center p-0"
                                style="width: 32px; height: 32px;" title="Add to Cart">
                                <i class="fa-solid fa-cart-plus" style="font-size: 0.75rem;"></i>
                            </button>
                            <button class="btn btn-primary rounded-3 flex-grow-1 fw-bold p-0"
                                style="font-size: 0.75rem; height: 32px;">
                                Buy Now
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card border-light shadow-sm rounded-4 h-100 product-card">
                    <div class="position-absolute top-0 start-0 p-2 z-2 d-flex flex-column gap-1">
                        <span class="badge bg-dark rounded-pill text-uppercase shadow-sm"
                            style="font-size: 0.55rem;">Recommended</span>
                    </div>
                    <div class="position-relative bg-light rounded-top-4 overflow-hidden d-flex align-items-center justify-content-center"
                        style="height: 150px;">
                        <a href="#"><img
                                src="https://images.unsplash.com/photo-1549399542-7e3f8b79c341?auto=format&fit=crop&q=80&w=400"
                                alt="Recommended Item"
                                class="w-100 h-100 object-fit-cover transition-all product-img"></a>
                    </div>
                    <div class="card-body d-flex flex-column p-2 p-sm-3">
                        <div class="mb-auto">
                            <span class="text-muted fw-bold text-uppercase d-block mb-1"
                                style="font-size: 0.55rem; letter-spacing: 0.05em;">Exclusive</span>
                            <a href="#" class="text-decoration-none text-dark">
                                <h3 class="h6 fw-bold line-clamp-2 mb-1 hover-text-primary transition-all"
                                    style="font-size: 0.75rem;">Premium Auto Part Model 5</h3>
                            </a>
                            <div class="d-flex text-warning mb-2" style="font-size: 0.6rem;">
                                <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                    class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                    class="fa-solid fa-star-half-stroke"></i>
                            </div>
                            <span class="fw-bolder text-primary mb-2 d-block" style="font-size: 0.9rem;">৳500</span>
                        </div>
                        <div class="d-flex gap-1 gap-sm-2 mt-auto">
                            <button
                                class="btn btn-outline-primary rounded-3 flex-shrink-0 d-flex align-items-center justify-content-center p-0"
                                style="width: 32px; height: 32px;" title="Add to Cart">
                                <i class="fa-solid fa-cart-plus" style="font-size: 0.75rem;"></i>
                            </button>
                            <button class="btn btn-primary rounded-3 flex-grow-1 fw-bold p-0"
                                style="font-size: 0.75rem; height: 32px;">
                                Buy Now
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card border-light shadow-sm rounded-4 h-100 product-card">
                    <div class="position-absolute top-0 start-0 p-2 z-2 d-flex flex-column gap-1">
                        <span class="badge bg-dark rounded-pill text-uppercase shadow-sm"
                            style="font-size: 0.55rem;">Recommended</span>
                    </div>
                    <div class="position-relative bg-light rounded-top-4 overflow-hidden d-flex align-items-center justify-content-center"
                        style="height: 150px;">
                        <a href="#"><img
                                src="https://images.unsplash.com/photo-1549399542-7e3f8b79c341?auto=format&fit=crop&q=80&w=400"
                                alt="Recommended Item"
                                class="w-100 h-100 object-fit-cover transition-all product-img"></a>
                    </div>
                    <div class="card-body d-flex flex-column p-2 p-sm-3">
                        <div class="mb-auto">
                            <span class="text-muted fw-bold text-uppercase d-block mb-1"
                                style="font-size: 0.55rem; letter-spacing: 0.05em;">Exclusive</span>
                            <a href="#" class="text-decoration-none text-dark">
                                <h3 class="h6 fw-bold line-clamp-2 mb-1 hover-text-primary transition-all"
                                    style="font-size: 0.75rem;">Premium Auto Part Model 5</h3>
                            </a>
                            <div class="d-flex text-warning mb-2" style="font-size: 0.6rem;">
                                <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                    class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                    class="fa-solid fa-star-half-stroke"></i>
                            </div>
                            <span class="fw-bolder text-primary mb-2 d-block" style="font-size: 0.9rem;">৳500</span>
                        </div>
                        <div class="d-flex gap-1 gap-sm-2 mt-auto">
                            <button
                                class="btn btn-outline-primary rounded-3 flex-shrink-0 d-flex align-items-center justify-content-center p-0"
                                style="width: 32px; height: 32px;" title="Add to Cart">
                                <i class="fa-solid fa-cart-plus" style="font-size: 0.75rem;"></i>
                            </button>
                            <button class="btn btn-primary rounded-3 flex-grow-1 fw-bold p-0"
                                style="font-size: 0.75rem; height: 32px;">
                                Buy Now
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card border-light shadow-sm rounded-4 h-100 product-card">
                    <div class="position-absolute top-0 start-0 p-2 z-2 d-flex flex-column gap-1">
                        <span class="badge bg-dark rounded-pill text-uppercase shadow-sm"
                            style="font-size: 0.55rem;">Recommended</span>
                    </div>
                    <div class="position-relative bg-light rounded-top-4 overflow-hidden d-flex align-items-center justify-content-center"
                        style="height: 150px;">
                        <a href="#"><img
                                src="https://images.unsplash.com/photo-1549399542-7e3f8b79c341?auto=format&fit=crop&q=80&w=400"
                                alt="Recommended Item"
                                class="w-100 h-100 object-fit-cover transition-all product-img"></a>
                    </div>
                    <div class="card-body d-flex flex-column p-2 p-sm-3">
                        <div class="mb-auto">
                            <span class="text-muted fw-bold text-uppercase d-block mb-1"
                                style="font-size: 0.55rem; letter-spacing: 0.05em;">Exclusive</span>
                            <a href="#" class="text-decoration-none text-dark">
                                <h3 class="h6 fw-bold line-clamp-2 mb-1 hover-text-primary transition-all"
                                    style="font-size: 0.75rem;">Premium Auto Part Model 5</h3>
                            </a>
                            <div class="d-flex text-warning mb-2" style="font-size: 0.6rem;">
                                <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                    class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                    class="fa-solid fa-star-half-stroke"></i>
                            </div>
                            <span class="fw-bolder text-primary mb-2 d-block" style="font-size: 0.9rem;">৳500</span>
                        </div>
                        <div class="d-flex gap-1 gap-sm-2 mt-auto">
                            <button
                                class="btn btn-outline-primary rounded-3 flex-shrink-0 d-flex align-items-center justify-content-center p-0"
                                style="width: 32px; height: 32px;" title="Add to Cart">
                                <i class="fa-solid fa-cart-plus" style="font-size: 0.75rem;"></i>
                            </button>
                            <button class="btn btn-primary rounded-3 flex-grow-1 fw-bold p-0"
                                style="font-size: 0.75rem; height: 32px;">
                                Buy Now
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card border-light shadow-sm rounded-4 h-100 product-card">
                    <div class="position-absolute top-0 start-0 p-2 z-2 d-flex flex-column gap-1">
                        <span class="badge bg-dark rounded-pill text-uppercase shadow-sm"
                            style="font-size: 0.55rem;">Recommended</span>
                    </div>
                    <div class="position-relative bg-light rounded-top-4 overflow-hidden d-flex align-items-center justify-content-center"
                        style="height: 150px;">
                        <a href="#"><img
                                src="https://images.unsplash.com/photo-1549399542-7e3f8b79c341?auto=format&fit=crop&q=80&w=400"
                                alt="Recommended Item"
                                class="w-100 h-100 object-fit-cover transition-all product-img"></a>
                    </div>
                    <div class="card-body d-flex flex-column p-2 p-sm-3">
                        <div class="mb-auto">
                            <span class="text-muted fw-bold text-uppercase d-block mb-1"
                                style="font-size: 0.55rem; letter-spacing: 0.05em;">Exclusive</span>
                            <a href="#" class="text-decoration-none text-dark">
                                <h3 class="h6 fw-bold line-clamp-2 mb-1 hover-text-primary transition-all"
                                    style="font-size: 0.75rem;">Premium Auto Part Model 5</h3>
                            </a>
                            <div class="d-flex text-warning mb-2" style="font-size: 0.6rem;">
                                <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                    class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                    class="fa-solid fa-star-half-stroke"></i>
                            </div>
                            <span class="fw-bolder text-primary mb-2 d-block" style="font-size: 0.9rem;">৳500</span>
                        </div>
                        <div class="d-flex gap-1 gap-sm-2 mt-auto">
                            <button
                                class="btn btn-outline-primary rounded-3 flex-shrink-0 d-flex align-items-center justify-content-center p-0"
                                style="width: 32px; height: 32px;" title="Add to Cart">
                                <i class="fa-solid fa-cart-plus" style="font-size: 0.75rem;"></i>
                            </button>
                            <button class="btn btn-primary rounded-3 flex-grow-1 fw-bold p-0"
                                style="font-size: 0.75rem; height: 32px;">
                                Buy Now
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card border-light shadow-sm rounded-4 h-100 product-card">
                    <div class="position-absolute top-0 start-0 p-2 z-2 d-flex flex-column gap-1">
                        <span class="badge bg-dark rounded-pill text-uppercase shadow-sm"
                            style="font-size: 0.55rem;">Recommended</span>
                    </div>
                    <div class="position-relative bg-light rounded-top-4 overflow-hidden d-flex align-items-center justify-content-center"
                        style="height: 150px;">
                        <a href="#"><img
                                src="https://images.unsplash.com/photo-1549399542-7e3f8b79c341?auto=format&fit=crop&q=80&w=400"
                                alt="Recommended Item"
                                class="w-100 h-100 object-fit-cover transition-all product-img"></a>
                    </div>
                    <div class="card-body d-flex flex-column p-2 p-sm-3">
                        <div class="mb-auto">
                            <span class="text-muted fw-bold text-uppercase d-block mb-1"
                                style="font-size: 0.55rem; letter-spacing: 0.05em;">Exclusive</span>
                            <a href="#" class="text-decoration-none text-dark">
                                <h3 class="h6 fw-bold line-clamp-2 mb-1 hover-text-primary transition-all"
                                    style="font-size: 0.75rem;">Premium Auto Part Model 5</h3>
                            </a>
                            <div class="d-flex text-warning mb-2" style="font-size: 0.6rem;">
                                <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                    class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                    class="fa-solid fa-star-half-stroke"></i>
                            </div>
                            <span class="fw-bolder text-primary mb-2 d-block" style="font-size: 0.9rem;">৳500</span>
                        </div>
                        <div class="d-flex gap-1 gap-sm-2 mt-auto">
                            <button
                                class="btn btn-outline-primary rounded-3 flex-shrink-0 d-flex align-items-center justify-content-center p-0"
                                style="width: 32px; height: 32px;" title="Add to Cart">
                                <i class="fa-solid fa-cart-plus" style="font-size: 0.75rem;"></i>
                            </button>
                            <button class="btn btn-primary rounded-3 flex-grow-1 fw-bold p-0"
                                style="font-size: 0.75rem; height: 32px;">
                                Buy Now
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card border-light shadow-sm rounded-4 h-100 product-card">
                    <div class="position-absolute top-0 start-0 p-2 z-2 d-flex flex-column gap-1">
                        <span class="badge bg-dark rounded-pill text-uppercase shadow-sm"
                            style="font-size: 0.55rem;">Recommended</span>
                    </div>
                    <div class="position-relative bg-light rounded-top-4 overflow-hidden d-flex align-items-center justify-content-center"
                        style="height: 150px;">
                        <a href="#"><img
                                src="https://images.unsplash.com/photo-1549399542-7e3f8b79c341?auto=format&fit=crop&q=80&w=400"
                                alt="Recommended Item"
                                class="w-100 h-100 object-fit-cover transition-all product-img"></a>
                    </div>
                    <div class="card-body d-flex flex-column p-2 p-sm-3">
                        <div class="mb-auto">
                            <span class="text-muted fw-bold text-uppercase d-block mb-1"
                                style="font-size: 0.55rem; letter-spacing: 0.05em;">Exclusive</span>
                            <a href="#" class="text-decoration-none text-dark">
                                <h3 class="h6 fw-bold line-clamp-2 mb-1 hover-text-primary transition-all"
                                    style="font-size: 0.75rem;">Premium Auto Part Model 5</h3>
                            </a>
                            <div class="d-flex text-warning mb-2" style="font-size: 0.6rem;">
                                <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                    class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                    class="fa-solid fa-star-half-stroke"></i>
                            </div>
                            <span class="fw-bolder text-primary mb-2 d-block" style="font-size: 0.9rem;">৳500</span>
                        </div>
                        <div class="d-flex gap-1 gap-sm-2 mt-auto">
                            <button
                                class="btn btn-outline-primary rounded-3 flex-shrink-0 d-flex align-items-center justify-content-center p-0"
                                style="width: 32px; height: 32px;" title="Add to Cart">
                                <i class="fa-solid fa-cart-plus" style="font-size: 0.75rem;"></i>
                            </button>
                            <button class="btn btn-primary rounded-3 flex-grow-1 fw-bold p-0"
                                style="font-size: 0.75rem; height: 32px;">
                                Buy Now
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card border-light shadow-sm rounded-4 h-100 product-card">
                    <div class="position-absolute top-0 start-0 p-2 z-2 d-flex flex-column gap-1">
                        <span class="badge bg-dark rounded-pill text-uppercase shadow-sm"
                            style="font-size: 0.55rem;">Recommended</span>
                    </div>
                    <div class="position-relative bg-light rounded-top-4 overflow-hidden d-flex align-items-center justify-content-center"
                        style="height: 150px;">
                        <a href="#"><img
                                src="https://images.unsplash.com/photo-1549399542-7e3f8b79c341?auto=format&fit=crop&q=80&w=400"
                                alt="Recommended Item"
                                class="w-100 h-100 object-fit-cover transition-all product-img"></a>
                    </div>
                    <div class="card-body d-flex flex-column p-2 p-sm-3">
                        <div class="mb-auto">
                            <span class="text-muted fw-bold text-uppercase d-block mb-1"
                                style="font-size: 0.55rem; letter-spacing: 0.05em;">Exclusive</span>
                            <a href="#" class="text-decoration-none text-dark">
                                <h3 class="h6 fw-bold line-clamp-2 mb-1 hover-text-primary transition-all"
                                    style="font-size: 0.75rem;">Premium Auto Part Model 5</h3>
                            </a>
                            <div class="d-flex text-warning mb-2" style="font-size: 0.6rem;">
                                <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                    class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                    class="fa-solid fa-star-half-stroke"></i>
                            </div>
                            <span class="fw-bolder text-primary mb-2 d-block" style="font-size: 0.9rem;">৳500</span>
                        </div>
                        <div class="d-flex gap-1 gap-sm-2 mt-auto">
                            <button
                                class="btn btn-outline-primary rounded-3 flex-shrink-0 d-flex align-items-center justify-content-center p-0"
                                style="width: 32px; height: 32px;" title="Add to Cart">
                                <i class="fa-solid fa-cart-plus" style="font-size: 0.75rem;"></i>
                            </button>
                            <button class="btn btn-primary rounded-3 flex-grow-1 fw-bold p-0"
                                style="font-size: 0.75rem; height: 32px;">
                                Buy Now
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card border-light shadow-sm rounded-4 h-100 product-card">
                    <div class="position-absolute top-0 start-0 p-2 z-2 d-flex flex-column gap-1">
                        <span class="badge bg-dark rounded-pill text-uppercase shadow-sm"
                            style="font-size: 0.55rem;">Recommended</span>
                    </div>
                    <div class="position-relative bg-light rounded-top-4 overflow-hidden d-flex align-items-center justify-content-center"
                        style="height: 150px;">
                        <a href="#"><img
                                src="https://images.unsplash.com/photo-1549399542-7e3f8b79c341?auto=format&fit=crop&q=80&w=400"
                                alt="Recommended Item"
                                class="w-100 h-100 object-fit-cover transition-all product-img"></a>
                    </div>
                    <div class="card-body d-flex flex-column p-2 p-sm-3">
                        <div class="mb-auto">
                            <span class="text-muted fw-bold text-uppercase d-block mb-1"
                                style="font-size: 0.55rem; letter-spacing: 0.05em;">Exclusive</span>
                            <a href="#" class="text-decoration-none text-dark">
                                <h3 class="h6 fw-bold line-clamp-2 mb-1 hover-text-primary transition-all"
                                    style="font-size: 0.75rem;">Premium Auto Part Model 5</h3>
                            </a>
                            <div class="d-flex text-warning mb-2" style="font-size: 0.6rem;">
                                <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                    class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                    class="fa-solid fa-star-half-stroke"></i>
                            </div>
                            <span class="fw-bolder text-primary mb-2 d-block" style="font-size: 0.9rem;">৳500</span>
                        </div>
                        <div class="d-flex gap-1 gap-sm-2 mt-auto">
                            <button
                                class="btn btn-outline-primary rounded-3 flex-shrink-0 d-flex align-items-center justify-content-center p-0"
                                style="width: 32px; height: 32px;" title="Add to Cart">
                                <i class="fa-solid fa-cart-plus" style="font-size: 0.75rem;"></i>
                            </button>
                            <button class="btn btn-primary rounded-3 flex-grow-1 fw-bold p-0"
                                style="font-size: 0.75rem; height: 32px;">
                                Buy Now
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card border-light shadow-sm rounded-4 h-100 product-card">
                    <div class="position-absolute top-0 start-0 p-2 z-2 d-flex flex-column gap-1">
                        <span class="badge bg-dark rounded-pill text-uppercase shadow-sm"
                            style="font-size: 0.55rem;">Recommended</span>
                    </div>
                    <div class="position-relative bg-light rounded-top-4 overflow-hidden d-flex align-items-center justify-content-center"
                        style="height: 150px;">
                        <a href="#"><img
                                src="https://images.unsplash.com/photo-1549399542-7e3f8b79c341?auto=format&fit=crop&q=80&w=400"
                                alt="Recommended Item"
                                class="w-100 h-100 object-fit-cover transition-all product-img"></a>
                    </div>
                    <div class="card-body d-flex flex-column p-2 p-sm-3">
                        <div class="mb-auto">
                            <span class="text-muted fw-bold text-uppercase d-block mb-1"
                                style="font-size: 0.55rem; letter-spacing: 0.05em;">Exclusive</span>
                            <a href="#" class="text-decoration-none text-dark">
                                <h3 class="h6 fw-bold line-clamp-2 mb-1 hover-text-primary transition-all"
                                    style="font-size: 0.75rem;">Premium Auto Part Model 5</h3>
                            </a>
                            <div class="d-flex text-warning mb-2" style="font-size: 0.6rem;">
                                <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                    class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                    class="fa-solid fa-star-half-stroke"></i>
                            </div>
                            <span class="fw-bolder text-primary mb-2 d-block" style="font-size: 0.9rem;">৳500</span>
                        </div>
                        <div class="d-flex gap-1 gap-sm-2 mt-auto">
                            <button
                                class="btn btn-outline-primary rounded-3 flex-shrink-0 d-flex align-items-center justify-content-center p-0"
                                style="width: 32px; height: 32px;" title="Add to Cart">
                                <i class="fa-solid fa-cart-plus" style="font-size: 0.75rem;"></i>
                            </button>
                            <button class="btn btn-primary rounded-3 flex-grow-1 fw-bold p-0"
                                style="font-size: 0.75rem; height: 32px;">
                                Buy Now
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card border-light shadow-sm rounded-4 h-100 product-card">
                    <div class="position-absolute top-0 start-0 p-2 z-2 d-flex flex-column gap-1">
                        <span class="badge bg-dark rounded-pill text-uppercase shadow-sm"
                            style="font-size: 0.55rem;">Recommended</span>
                    </div>
                    <div class="position-relative bg-light rounded-top-4 overflow-hidden d-flex align-items-center justify-content-center"
                        style="height: 150px;">
                        <a href="#"><img
                                src="https://images.unsplash.com/photo-1549399542-7e3f8b79c341?auto=format&fit=crop&q=80&w=400"
                                alt="Recommended Item"
                                class="w-100 h-100 object-fit-cover transition-all product-img"></a>
                    </div>
                    <div class="card-body d-flex flex-column p-2 p-sm-3">
                        <div class="mb-auto">
                            <span class="text-muted fw-bold text-uppercase d-block mb-1"
                                style="font-size: 0.55rem; letter-spacing: 0.05em;">Exclusive</span>
                            <a href="#" class="text-decoration-none text-dark">
                                <h3 class="h6 fw-bold line-clamp-2 mb-1 hover-text-primary transition-all"
                                    style="font-size: 0.75rem;">Premium Auto Part Model 5</h3>
                            </a>
                            <div class="d-flex text-warning mb-2" style="font-size: 0.6rem;">
                                <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                    class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                    class="fa-solid fa-star-half-stroke"></i>
                            </div>
                            <span class="fw-bolder text-primary mb-2 d-block" style="font-size: 0.9rem;">৳500</span>
                        </div>
                        <div class="d-flex gap-1 gap-sm-2 mt-auto">
                            <button
                                class="btn btn-outline-primary rounded-3 flex-shrink-0 d-flex align-items-center justify-content-center p-0"
                                style="width: 32px; height: 32px;" title="Add to Cart">
                                <i class="fa-solid fa-cart-plus" style="font-size: 0.75rem;"></i>
                            </button>
                            <button class="btn btn-primary rounded-3 flex-grow-1 fw-bold p-0"
                                style="font-size: 0.75rem; height: 32px;">
                                Buy Now
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card border-light shadow-sm rounded-4 h-100 product-card">
                    <div class="position-absolute top-0 start-0 p-2 z-2 d-flex flex-column gap-1">
                        <span class="badge bg-dark rounded-pill text-uppercase shadow-sm"
                            style="font-size: 0.55rem;">Recommended</span>
                    </div>
                    <div class="position-relative bg-light rounded-top-4 overflow-hidden d-flex align-items-center justify-content-center"
                        style="height: 150px;">
                        <a href="#"><img
                                src="https://images.unsplash.com/photo-1549399542-7e3f8b79c341?auto=format&fit=crop&q=80&w=400"
                                alt="Recommended Item"
                                class="w-100 h-100 object-fit-cover transition-all product-img"></a>
                    </div>
                    <div class="card-body d-flex flex-column p-2 p-sm-3">
                        <div class="mb-auto">
                            <span class="text-muted fw-bold text-uppercase d-block mb-1"
                                style="font-size: 0.55rem; letter-spacing: 0.05em;">Exclusive</span>
                            <a href="#" class="text-decoration-none text-dark">
                                <h3 class="h6 fw-bold line-clamp-2 mb-1 hover-text-primary transition-all"
                                    style="font-size: 0.75rem;">Premium Auto Part Model 5</h3>
                            </a>
                            <div class="d-flex text-warning mb-2" style="font-size: 0.6rem;">
                                <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                    class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                    class="fa-solid fa-star-half-stroke"></i>
                            </div>
                            <span class="fw-bolder text-primary mb-2 d-block" style="font-size: 0.9rem;">৳500</span>
                        </div>
                        <div class="d-flex gap-1 gap-sm-2 mt-auto">
                            <button
                                class="btn btn-outline-primary rounded-3 flex-shrink-0 d-flex align-items-center justify-content-center p-0"
                                style="width: 32px; height: 32px;" title="Add to Cart">
                                <i class="fa-solid fa-cart-plus" style="font-size: 0.75rem;"></i>
                            </button>
                            <button class="btn btn-primary rounded-3 flex-grow-1 fw-bold p-0"
                                style="font-size: 0.75rem; height: 32px;">
                                Buy Now
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card border-light shadow-sm rounded-4 h-100 product-card">
                    <div class="position-absolute top-0 start-0 p-2 z-2 d-flex flex-column gap-1">
                        <span class="badge bg-dark rounded-pill text-uppercase shadow-sm"
                            style="font-size: 0.55rem;">Recommended</span>
                    </div>
                    <div class="position-relative bg-light rounded-top-4 overflow-hidden d-flex align-items-center justify-content-center"
                        style="height: 150px;">
                        <a href="#"><img
                                src="https://images.unsplash.com/photo-1549399542-7e3f8b79c341?auto=format&fit=crop&q=80&w=400"
                                alt="Recommended Item"
                                class="w-100 h-100 object-fit-cover transition-all product-img"></a>
                    </div>
                    <div class="card-body d-flex flex-column p-2 p-sm-3">
                        <div class="mb-auto">
                            <span class="text-muted fw-bold text-uppercase d-block mb-1"
                                style="font-size: 0.55rem; letter-spacing: 0.05em;">Exclusive</span>
                            <a href="#" class="text-decoration-none text-dark">
                                <h3 class="h6 fw-bold line-clamp-2 mb-1 hover-text-primary transition-all"
                                    style="font-size: 0.75rem;">Premium Auto Part Model 5</h3>
                            </a>
                            <div class="d-flex text-warning mb-2" style="font-size: 0.6rem;">
                                <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                    class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                    class="fa-solid fa-star-half-stroke"></i>
                            </div>
                            <span class="fw-bolder text-primary mb-2 d-block" style="font-size: 0.9rem;">৳500</span>
                        </div>
                        <div class="d-flex gap-1 gap-sm-2 mt-auto">
                            <button
                                class="btn btn-outline-primary rounded-3 flex-shrink-0 d-flex align-items-center justify-content-center p-0"
                                style="width: 32px; height: 32px;" title="Add to Cart">
                                <i class="fa-solid fa-cart-plus" style="font-size: 0.75rem;"></i>
                            </button>
                            <button class="btn btn-primary rounded-3 flex-grow-1 fw-bold p-0"
                                style="font-size: 0.75rem; height: 32px;">
                                Buy Now
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card border-light shadow-sm rounded-4 h-100 product-card">
                    <div class="position-absolute top-0 start-0 p-2 z-2 d-flex flex-column gap-1">
                        <span class="badge bg-dark rounded-pill text-uppercase shadow-sm"
                            style="font-size: 0.55rem;">Recommended</span>
                    </div>
                    <div class="position-relative bg-light rounded-top-4 overflow-hidden d-flex align-items-center justify-content-center"
                        style="height: 150px;">
                        <a href="#"><img
                                src="https://images.unsplash.com/photo-1549399542-7e3f8b79c341?auto=format&fit=crop&q=80&w=400"
                                alt="Recommended Item"
                                class="w-100 h-100 object-fit-cover transition-all product-img"></a>
                    </div>
                    <div class="card-body d-flex flex-column p-2 p-sm-3">
                        <div class="mb-auto">
                            <span class="text-muted fw-bold text-uppercase d-block mb-1"
                                style="font-size: 0.55rem; letter-spacing: 0.05em;">Exclusive</span>
                            <a href="#" class="text-decoration-none text-dark">
                                <h3 class="h6 fw-bold line-clamp-2 mb-1 hover-text-primary transition-all"
                                    style="font-size: 0.75rem;">Premium Auto Part Model 5</h3>
                            </a>
                            <div class="d-flex text-warning mb-2" style="font-size: 0.6rem;">
                                <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                    class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                    class="fa-solid fa-star-half-stroke"></i>
                            </div>
                            <span class="fw-bolder text-primary mb-2 d-block" style="font-size: 0.9rem;">৳500</span>
                        </div>
                        <div class="d-flex gap-1 gap-sm-2 mt-auto">
                            <button
                                class="btn btn-outline-primary rounded-3 flex-shrink-0 d-flex align-items-center justify-content-center p-0"
                                style="width: 32px; height: 32px;" title="Add to Cart">
                                <i class="fa-solid fa-cart-plus" style="font-size: 0.75rem;"></i>
                            </button>
                            <button class="btn btn-primary rounded-3 flex-grow-1 fw-bold p-0"
                                style="font-size: 0.75rem; height: 32px;">
                                Buy Now
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card border-light shadow-sm rounded-4 h-100 product-card">
                    <div class="position-absolute top-0 start-0 p-2 z-2 d-flex flex-column gap-1">
                        <span class="badge bg-dark rounded-pill text-uppercase shadow-sm"
                            style="font-size: 0.55rem;">Recommended</span>
                    </div>
                    <div class="position-relative bg-light rounded-top-4 overflow-hidden d-flex align-items-center justify-content-center"
                        style="height: 150px;">
                        <a href="#"><img
                                src="https://images.unsplash.com/photo-1549399542-7e3f8b79c341?auto=format&fit=crop&q=80&w=400"
                                alt="Recommended Item"
                                class="w-100 h-100 object-fit-cover transition-all product-img"></a>
                    </div>
                    <div class="card-body d-flex flex-column p-2 p-sm-3">
                        <div class="mb-auto">
                            <span class="text-muted fw-bold text-uppercase d-block mb-1"
                                style="font-size: 0.55rem; letter-spacing: 0.05em;">Exclusive</span>
                            <a href="#" class="text-decoration-none text-dark">
                                <h3 class="h6 fw-bold line-clamp-2 mb-1 hover-text-primary transition-all"
                                    style="font-size: 0.75rem;">Premium Auto Part Model 5</h3>
                            </a>
                            <div class="d-flex text-warning mb-2" style="font-size: 0.6rem;">
                                <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                    class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                    class="fa-solid fa-star-half-stroke"></i>
                            </div>
                            <span class="fw-bolder text-primary mb-2 d-block" style="font-size: 0.9rem;">৳500</span>
                        </div>
                        <div class="d-flex gap-1 gap-sm-2 mt-auto">
                            <button
                                class="btn btn-outline-primary rounded-3 flex-shrink-0 d-flex align-items-center justify-content-center p-0"
                                style="width: 32px; height: 32px;" title="Add to Cart">
                                <i class="fa-solid fa-cart-plus" style="font-size: 0.75rem;"></i>
                            </button>
                            <button class="btn btn-primary rounded-3 flex-grow-1 fw-bold p-0"
                                style="font-size: 0.75rem; height: 32px;">
                                Buy Now
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- View More Button -->
        <div class="mt-5 d-flex justify-content-center">
            <button onclick="showToast('Loading More', 'Fetching more recommended products for you...')"
                class="btn btn-dark fw-bold rounded-pill shadow-sm px-5 py-3 d-inline-flex align-items-center gap-2 transition-all">
                View More Products <i class="fa-solid fa-arrow-rotate-right"></i>
            </button>
        </div>
    </section>

    <footer class="bg-dark text-secondary pt-5 pb-5 border-top border-primary border-4" style="margin-top: 2rem;">
        <div class="container-lg px-3">
            <div class="row g-5">
                <div class="col-12 col-md-3">
                    <span class="fs-4 fw-bolder text-white d-block mb-3">
                        CAR<span class="text-primary">BLISS<i class="fa-solid fa-gauge-high"></i></span>BD
                    </span>
                    <p class="small mb-4 lh-base" style="font-size: 0.8rem;">
                        Bangladesh's most reliable premium car accessory & genuine spare parts store. Offering
                        durability, performance, and luxurious aesthetic upgrades for your vehicle at unbeatable
                        pricing.
                    </p>
                    <div class="d-flex gap-2">
                        <a href="#"
                            class="btn btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center border-0 bg-secondary bg-opacity-25 text-white hover-text-primary transition-all"
                            style="width: 35px; height: 35px;"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="#"
                            class="btn btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center border-0 bg-secondary bg-opacity-25 text-white hover-text-primary transition-all"
                            style="width: 35px; height: 35px;"><i class="fa-brands fa-youtube"></i></a>
                        <a href="#"
                            class="btn btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center border-0 bg-secondary bg-opacity-25 text-white hover-text-primary transition-all"
                            style="width: 35px; height: 35px;"><i class="fa-brands fa-instagram"></i></a>
                    </div>
                </div>

                <div class="col-12 col-md-3">
                    <h5 class="text-white fw-bold text-uppercase mb-4"
                        style="font-size: 0.9rem; letter-spacing: 1px;">Customer Care</h5>
                    <ul class="list-unstyled small d-flex flex-column gap-2" style="font-size: 0.8rem;">
                        <li><a href="#"
                                class="text-secondary text-decoration-none hover-text-primary transition-all">About
                                Us</a></li>
                        <li><a href="#"
                                class="text-secondary text-decoration-none hover-text-primary transition-all">Contact
                                Support</a></li>
                        <li><a href="#"
                                class="text-secondary text-decoration-none hover-text-primary transition-all">Returns
                                &
                                Refunds Policy</a></li>
                        <li><a href="#"
                                class="text-secondary text-decoration-none hover-text-primary transition-all">Privacy
                                Policy</a></li>
                        <li><a href="#"
                                class="text-secondary text-decoration-none hover-text-primary transition-all">Terms of
                                Service</a></li>
                    </ul>
                </div>

                <div class="col-12 col-md-3">
                    <h5 class="text-white fw-bold text-uppercase mb-4"
                        style="font-size: 0.9rem; letter-spacing: 1px;">Information</h5>
                    <ul class="list-unstyled small d-flex flex-column gap-2" style="font-size: 0.8rem;">
                        <li><a href="#"
                                class="text-secondary text-decoration-none hover-text-primary transition-all">High End
                                Car Cover Collection</a></li>
                        <li><a href="#"
                                class="text-secondary text-decoration-none hover-text-primary transition-all">Car
                                Detailing Packages</a></li>
                        <li><a href="#"
                                class="text-secondary text-decoration-none hover-text-primary transition-all">Performance
                                Air Filters</a></li>
                        <li><a href="#"
                                class="text-secondary text-decoration-none hover-text-primary transition-all">Universal
                                Brake Accessories</a></li>
                        <li><a href="#"
                                class="text-secondary text-decoration-none hover-text-primary transition-all">Exclusive
                                LED Lights</a></li>
                    </ul>
                </div>

                <div class="col-12 col-md-3">
                    <h5 class="text-white fw-bold text-uppercase mb-4"
                        style="font-size: 0.9rem; letter-spacing: 1px;">Our Showroom Location</h5>
                    <div class="d-flex align-items-start gap-2 mb-3 small" style="font-size: 0.8rem;">
                        <i class="fa-solid fa-location-dot text-primary mt-1"></i>
                        <span>Flagship Outlet: 220/D/04 Begum Rokeya Sarani, Metro Pillar 328, Mirpur Shewrapara,
                            Dhaka</span>
                    </div>
                    <div class="d-flex align-items-center gap-2 small" style="font-size: 0.8rem;">
                        <i class="fa-solid fa-phone text-primary"></i>
                        <span>09613821382, 01999906676</span>
                    </div>
                </div>
            </div>

            <div class="border-top border-secondary mt-5 pt-4 d-flex flex-column flex-md-row justify-content-between align-items-center small"
                style="font-size: 0.75rem;">
                <p class="mb-0 text-center text-md-start">&copy; 2026 CarBlissBD. All Rights Reserved. Crafted for
                    Ultimate Car Enthusiasts.</p>
                <div class="d-flex gap-3 mt-3 mt-md-0 fs-4">
                    <i
                        class="fa-brands fa-cc-visa text-secondary hover-text-primary cursor-pointer transition-all"></i>
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
        <a href="#"
            class="text-decoration-none text-primary d-flex flex-column align-items-center text-center"
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
            class="flex-grow-1 overflow-auto bg-light pb-5 position-relative border-top d-md-none border-secondary border-opacity-10">
            <div id="main-cat-grid" class="p-3 row row-cols-3 g-2 m-0"></div>
            <div id="sub-cat-list" class="d-none p-3 pb-5 bg-white min-vh-100">
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

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <script>
        // --- Initialize Swiper for Hero Section ---
        var heroSwiper = new Swiper(".hero-swiper", {
            loop: true,
            grabCursor: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            // navigation: {
            //     nextEl: ".swiper-button-next",
            //     prevEl: ".swiper-button-prev",
            // },
        });

        // --- Initialize Swiper for the New Category Section ---
        var newCategorySwiper = new Swiper('.new-category-swiper', {
            slidesPerView: 'auto', // Allows cards to take fixed widths
            spaceBetween: 15,
            grabCursor: true,
            pagination: {
                el: '.category-pagination',
                clickable: true,
            },
            breakpoints: {
                576: {
                    spaceBetween: 20,
                },
                768: {
                    spaceBetween: 25,
                }
            }
        });

        // --- Initialize Swiper for Brands Section ---
        var brandsSwiper = new Swiper('.brands-swiper', {
            slidesPerView: 2,
            spaceBetween: 15,
            grabCursor: true,
            loop: true, // Infinite loop for brands
            autoplay: {
                delay: 2500, // Auto slides every 2.5 seconds
                disableOnInteraction: false,
            },
            // navigation: {
            //     nextEl: '.brand-next',
            //     prevEl: '.brand-prev',
            // },
            breakpoints: {
                576: {
                    slidesPerView: 3,
                    spaceBetween: 15,
                },
                768: {
                    slidesPerView: 4,
                    spaceBetween: 20,
                },
                992: {
                    slidesPerView: 6, // 6 items on large screens
                    spaceBetween: 20,
                }
            }
        });

        // --- Initialize Swiper for New Arrivals Section ---
        var newArrivalsSwiper = new Swiper('.new-arrivals-swiper', {
            slidesPerView: 2, // 2 items on mobile
            spaceBetween: 15,
            grabCursor: true,
            navigation: {
                nextEl: '.prod-next',
                prevEl: '.prod-prev',
            },
            breakpoints: {
                576: {
                    slidesPerView: 2,
                    spaceBetween: 20,
                },
                768: {
                    slidesPerView: 3,
                    spaceBetween: 24,
                },
                992: {
                    slidesPerView: 4,
                    spaceBetween: 24,
                }
            }
        });

        // --- Live Search Logic ---
        function handleSearchSuggestions(query) {
            const suggestionBox = document.getElementById('search-suggestions');

            // যদি ২টির বেশি ক্যারেক্টার টাইপ করা হয়, তবে বক্সটি শো করবে
            if (query.trim().length > 1) {
                suggestionBox.style.display = 'block';

                // এখানে আপনি ভবিষ্যতে AJAX / Fetch API দিয়ে সার্ভার থেকে ডাটা আনতে পারবেন
                // উদাহরণ: fetch(`/api/search?q=${query}`).then(res => res.json()).then(data => showData(data));
            } else {
                suggestionBox.style.display = 'none';
            }
        }


        // Init logic on load
        window.onload = function() {
            loadYouMayLikeProducts();
        };

        // --- Drawer & Modal Management ---
        function toggleModal(id) {
            const el = document.getElementById(id);
            if (el.classList.contains('d-none')) {
                el.classList.remove('d-none');
                el.classList.add('d-flex');
                document.body.style.overflow = 'hidden';
            } else {
                el.classList.remove('d-flex');
                el.classList.add('d-none');
                document.body.style.overflow = '';
            }
        }

        function closeModal(id) {
            const el = document.getElementById(id);
            el.classList.remove('d-flex');
            el.classList.add('d-none');
            document.body.style.overflow = '';
        }

        function closeAllDrawers() {
            document.getElementById('drawer-overlay').classList.add('d-none');
            document.getElementById('cart-drawer').classList.remove('show');
            document.getElementById('category-drawer').classList.remove('show');
            document.body.style.overflow = '';
        }

        // --- Shopping Cart Logic ---
        let cart = [];

        function toggleCartDrawer() {
            const drawer = document.getElementById('cart-drawer');
            const overlay = document.getElementById('drawer-overlay');
            if (drawer.classList.contains('show')) {
                closeAllDrawers();
            } else {
                closeAllDrawers(); // Close others first
                overlay.classList.remove('d-none');
                setTimeout(() => drawer.classList.add('show'), 10);
                document.body.style.overflow = 'hidden';
            }
        }

        function addToCart(id, name, price, img) {
            const existingItem = cart.find(item => item.id === id);
            if (existingItem) existingItem.qty += 1;
            else cart.push({
                id,
                name,
                price,
                img,
                qty: 1
            });
            updateCartUI();
            showToast("Added to Cart", name);
        }

        // নতুন: রিমুভ ফাংশন
        function removeFromCart(id) {
            cart = cart.filter(item => item.id !== id);
            updateCartUI();
            showToast("Item Removed", "Product has been removed from your cart.");
        }

        function buyNow(id) {
            // Mock Buy Now Functionality (Add and trigger checkout or redirect)
            showToast("Processing", "Redirecting to checkout...");
            // In a real app: window.location.href = '/checkout';
        }

        function updateCartUI() {
            const container = document.getElementById('cart-items-container');
            const emptyState = document.getElementById('empty-cart-state');
            const subtotalEl = document.getElementById('cart-subtotal');
            const shippingEl = document.getElementById('cart-shipping');
            const totalEl = document.getElementById('cart-total');
            const badge = document.getElementById('cart-count-badge');
            const mobileBadge = document.getElementById('mobile-cart-badge');

            const totalItems = cart.reduce((acc, item) => acc + item.qty, 0);
            const subtotalValue = cart.reduce((acc, item) => acc + (item.price * item.qty), 0);

            // Calculate shipping (Example: 60 Taka Flat Rate)
            const shippingValue = subtotalValue > 0 ? 60 : 0;
            const totalValue = subtotalValue + shippingValue;

            if (badge) badge.innerText = totalItems;
            if (mobileBadge) mobileBadge.innerText = totalItems;

            if (subtotalEl) subtotalEl.innerText = `৳${subtotalValue.toLocaleString()}`;
            if (shippingEl) shippingEl.innerText = `৳${shippingValue.toLocaleString()}`;
            if (totalEl) totalEl.innerText = `৳${totalValue.toLocaleString()}`;

            container.querySelectorAll('.cart-item-row').forEach(el => el.remove());

            if (cart.length === 0) {
                if (emptyState) emptyState.classList.remove('d-none');
                return;
            }
            if (emptyState) emptyState.classList.add('d-none');

            cart.forEach(item => {
                const itemRow = document.createElement('div');
                // Added position-relative for absolute delete button positioning
                itemRow.className =
                    "cart-item-row d-flex align-items-center justify-content-between border-bottom border-light pb-3 gap-3 position-relative";
                itemRow.innerHTML = `
                    <img src="${item.img}" alt="${item.name}" class="rounded-3 object-fit-cover flex-shrink-0 border border-light" style="width: 65px; height: 65px;">
                    <div class="flex-grow-1 pe-4"> <!-- Added padding-end to prevent text overlap with remove button -->
                        <h5 class="fw-bold text-dark line-clamp-2 m-0" style="font-size: 0.8rem; line-height: 1.4;">${item.name}</h5>
                        <p class="text-primary fw-bolder mt-1 mb-2" style="font-size: 0.85rem;">৳${(item.price * item.qty).toLocaleString()}</p>

                        <!-- Quantity Controls -->
                        <div class="d-flex align-items-center gap-2 mt-1">
                            <button onclick="changeQty(${item.id}, -1)" class="btn btn-light rounded-circle d-flex align-items-center justify-content-center p-0 fw-bold border hover-bg-primary transition-all" style="width: 25px; height: 25px; font-size: 0.8rem;" ${item.qty <= 1 ? 'disabled' : ''}>-</button>
                            <span class="fw-bolder" style="font-size: 0.85rem; width: 15px; text-align: center;">${item.qty}</span>
                            <button onclick="changeQty(${item.id}, 1)" class="btn btn-light rounded-circle d-flex align-items-center justify-content-center p-0 fw-bold border hover-bg-primary transition-all" style="width: 25px; height: 25px; font-size: 0.8rem;">+</button>
                        </div>
                    </div>

                    <!-- Remove Button -->
                    <button onclick="removeFromCart(${item.id})" class="btn btn-link text-danger p-0 position-absolute top-0 end-0 mt-1 hover-text-primary transition-all" title="Remove Item">
                        <i class="fa-solid fa-trash-can" style="font-size: 0.9rem;"></i>
                    </button>
                `;
                container.appendChild(itemRow);
            });
        }

        // আপডেট: কোয়ান্টিটি কন্ট্রোল (মিনিমাম ১)
        function changeQty(id, modifier) {
            const item = cart.find(i => i.id === id);
            if (item) {
                if (item.qty + modifier >= 1) { // কোয়ান্টিটি যেন ১ এর নিচে না যায়
                    item.qty += modifier;
                    updateCartUI();
                } else {
                    showToast("Notice", "Minimum quantity must be 1.");
                }
            }
        }

        // --- Toast Notification ---
        function showToast(title, subtitle) {
            const toast = document.getElementById('toast-notif');
            toast.querySelector('h5').innerText = title;
            toast.querySelector('#toast-subtitle').innerText = subtitle;
            toast.style.transform = 'translateY(0)';
            toast.style.opacity = '1';
            setTimeout(() => {
                toast.style.transform = 'translateY(-150%)';
                toast.style.opacity = '0';
            }, 3000);
        }

        // --- Dynamic "You May Like" Generator ---
        function loadYouMayLikeProducts() {
            const grid = document.getElementById('you-may-like-grid');
            if (!grid) return;
            const placeholderImages = [
                "https://images.unsplash.com/photo-1549399542-7e3f8b79c341?auto=format&fit=crop&q=80&w=400",
                "https://images.unsplash.com/photo-1549399542-7e3f8b79c341?auto=format&fit=crop&q=80&w=400",
                "https://images.unsplash.com/photo-1554223090-7e482851df45?auto=format&fit=crop&q=80&w=400",
                "https://images.unsplash.com/photo-1554223090-7e482851df45?auto=format&fit=crop&q=80&w=400",
                "https://images.unsplash.com/photo-1554223090-7e482851df45?auto=format&fit=crop&q=80&w=400"
            ];

            grid.innerHTML = '';

            // Generate 10 mock products with New Arrivals Design
            for (let i = 101; i <= 110; i++) {
                const img = placeholderImages[i % 5];
                const price = i * 10;
                grid.innerHTML += `
                    <div class="col">
                        <div class="card border-light shadow-sm rounded-4 h-100 product-card">
                            <div class="position-absolute top-0 start-0 p-2 z-2 d-flex flex-column gap-1">
                                <span class="badge bg-dark rounded-pill text-uppercase shadow-sm" style="font-size: 0.55rem;">Recommended</span>
                            </div>
                            <div class="position-relative bg-light rounded-top-4 overflow-hidden d-flex align-items-center justify-content-center" style="height: 150px;">
                                <a href="#"><img src="${img}" alt="Recommended Item" class="w-100 h-100 object-fit-cover transition-all product-img"></a>
                            </div>
                            <div class="card-body d-flex flex-column p-2 p-sm-3">
                                <div class="mb-auto">
                                    <span class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 0.55rem; letter-spacing: 0.05em;">Exclusive</span>
                                    <a href="#" class="text-decoration-none text-dark"><h3 class="h6 fw-bold line-clamp-2 mb-1 hover-text-primary transition-all" style="font-size: 0.75rem;">Premium Auto Part Model ${i}</h3></a>
                                    <div class="d-flex text-warning mb-2" style="font-size: 0.6rem;">
                                        <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star-half-stroke"></i>
                                    </div>
                                    <span class="fw-bolder text-primary mb-2 d-block" style="font-size: 0.9rem;">৳${price.toLocaleString()}</span>
                                </div>
                                <div class="d-flex gap-1 gap-sm-2 mt-auto">
                                    <button onclick="addToCart(${i}, 'Premium Auto Part ${i}', ${price}, '${img}')" class="btn btn-outline-primary rounded-3 flex-shrink-0 d-flex align-items-center justify-content-center p-0" style="width: 32px; height: 32px;" title="Add to Cart">
                                        <i class="fa-solid fa-cart-plus" style="font-size: 0.75rem;"></i>
                                    </button>
                                    <button onclick="buyNow(${i})" class="btn btn-primary rounded-3 flex-grow-1 fw-bold p-0" style="font-size: 0.75rem; height: 32px;">
                                        Buy Now
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }
        }

        // --- Mobile Category Modal Logic ---
        const categoriesDataList = {
            'interior': {
                name: 'Car Interior',
                img: 'https://images.unsplash.com/photo-1549399542-7e3f8b79c341?auto=format&fit=crop&q=80&w=200',
                subs: ['Steering Covers', 'Seat Covers', 'Floor Mats']
            },
            'exterior': {
                name: 'Car Exterior',
                img: 'https://images.unsplash.com/photo-1603386329225-868f9b1ee6c9?auto=format&fit=crop&q=80&w=200',
                subs: ['Body Kits', 'Mirrors', 'Mudguards']
            },
            'electronics': {
                name: 'Electronics',
                img: 'https://images.unsplash.com/photo-1583267746897-ea9cf3c46d9a?auto=format&fit=crop&q=80&w=200',
                subs: ['Dash Cams', 'Android Players', 'Sound Systems']
            }
        };

        function renderMainCategories() {
            const grid = document.getElementById('main-cat-grid');
            if (!grid) return;
            grid.innerHTML = '';
            for (const [key, data] of Object.entries(categoriesDataList)) {
                grid.innerHTML += `
                    <div class="col">
                        <div onclick="openSubCategory('${key}')" class="d-flex flex-column align-items-center bg-white p-3 rounded-4 shadow-sm border border-light cursor-pointer hover-text-primary transition-all h-100">
                            <div class="rounded-circle overflow-hidden mb-2 border border-2 border-primary" style="width: 60px; height: 60px;">
                                <img src="${data.img}" class="w-100 h-100 object-fit-cover">
                            </div>
                            <span class="fw-bold text-center text-dark" style="font-size: 0.7rem;">${data.name}</span>
                        </div>
                    </div>
                `;
            }
        }

        function toggleCategoryModal() {
            const drawer = document.getElementById('category-drawer');
            const overlay = document.getElementById('drawer-overlay');
            if (drawer.classList.contains('show')) {
                closeAllDrawers();
            } else {
                closeAllDrawers();
                renderMainCategories();
                showMainCategories();
                overlay.classList.remove('d-none');
                setTimeout(() => drawer.classList.add('show'), 10);
                document.body.style.overflow = 'hidden';
            }
        }

        function openSubCategory(key) {
            const data = categoriesDataList[key];
            document.getElementById('cat-modal-title').innerText = data.name;
            const backBtn = document.getElementById('cat-back-btn');
            backBtn.classList.remove('d-none');
            backBtn.classList.add('d-flex');

            const subItems = document.getElementById('sub-cat-items');
            subItems.innerHTML = '';
            data.subs.forEach(sub => {
                subItems.innerHTML +=
                    `<li><a href="#" onclick="toggleCategoryModal()" class="d-flex justify-content-between align-items-center p-3 bg-light rounded-3 fw-bold text-dark text-decoration-none hover-text-primary border">${sub} <i class="fa-solid fa-chevron-right small"></i></a></li>`;
            });
            document.getElementById('main-cat-grid').classList.add('d-none');
            document.getElementById('sub-cat-list').classList.remove('d-none');
            document.getElementById('sub-cat-list').classList.add('d-block');
        }

        function showMainCategories() {
            document.getElementById('cat-modal-title').innerText = "All Categories";
            const backBtn = document.getElementById('cat-back-btn');
            backBtn.classList.add('d-none');
            backBtn.classList.remove('d-flex');

            document.getElementById('sub-cat-list').classList.add('d-none');
            document.getElementById('sub-cat-list').classList.remove('d-block');
            document.getElementById('main-cat-grid').classList.remove('d-none');
        }

        function handleDemoLogin(e) {
            e.preventDefault();
            closeModal('user-modal');
            showToast('Login Successful', 'Welcome back to CarBlissBD!');
        }

        function proceedToCheckout() {
            if (cart.length === 0) {
                showToast('Cart is Empty', 'Please add items before checking out.');
            } else {
                showToast('Redirecting...', 'Proceeding to secure checkout');
            }
        }
    </script>
</body>

</html>
