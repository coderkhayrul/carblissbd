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

        /* Anti-jitter fix for mobile sticky header */
        @media (max-width: 767px) {
            .sticky-top {
                position: -webkit-sticky !important;
                position: sticky !important;
                top: 0;
            }

            html {
                scroll-behavior: auto !important;
            }
        }

        @media (min-width: 768px) {
            html {
                scroll-behavior: smooth;
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

        /* Custom Checkbox */
        .custom-checkbox .form-check-input:checked {
            background-color: var(--bs-primary);
            border-color: var(--bs-primary);
        }

        .custom-checkbox .form-check-input:focus {
            box-shadow: 0 0 0 0.25rem rgba(241, 90, 41, 0.25);
            border-color: var(--bs-primary);
        }

        /* Range Slider Customization */
        .form-range::-webkit-slider-thumb {
            background: var(--bs-primary);
        }

        .form-range::-webkit-slider-thumb:active {
            background-color: var(--bs-primary-hover);
        }
    </style>
</head>
