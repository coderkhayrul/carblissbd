@extends('frontend.layouts.master')

@section('front-content')
    <!-- Swiper Hero Section Banner Updates Here -->
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
                        alt="Premium Accessories" class="position-absolute top-0 start-0 w-100 h-100 object-fit-cover">
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
                        alt="Car Engine Oil Care" class="position-absolute top-0 start-0 w-100 h-100 object-fit-cover">
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
    <!-- Promotion Section -->
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
    <!-- Popular Categories Section -->
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
                                    <span class="fw-bolder text-dark text-center d-block" style="font-size: 0.85rem;">Car
                                        Interior</span>
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
                                    <span class="fw-bolder text-dark text-center d-block" style="font-size: 0.85rem;">Car
                                        Exterior</span>
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
                                        alt="Car Care & Wash" class="w-100 h-100 object-fit-contain category-img-zoom">
                                </div>
                                <div class="border-top p-3 bg-white mt-auto"
                                    style="border-color: rgba(241, 90, 41, 0.1) !important;">
                                    <span class="fw-bolder text-dark text-center d-block" style="font-size: 0.85rem;">Car
                                        Care & Wash</span>
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
                                    <span class="fw-bolder text-dark text-center d-block" style="font-size: 0.85rem;">LED
                                        & Lighting</span>
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
    <!-- New Arrivals Section -->
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
    <!-- Brand Section -->
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
    <!-- More Like Products -->
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
@endsection
