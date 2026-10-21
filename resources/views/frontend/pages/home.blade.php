@extends('frontend.layouts.master')

@section('front-content')
    @if ($slides->isNotEmpty())
        <section class="container-lg px-0 px-md-3">
            <div
                class="swiper hero-swiper hero-slider-container position-relative bg-white overflow-hidden my-md-4 rounded-0 rounded-md-4 shadow-sm">

                <div class="swiper-wrapper">
                    @foreach ($slides as $slide)
                        <div class="swiper-slide position-relative w-100 h-100">
                            <div class="position-absolute top-0 start-0 w-100 h-100 z-1"
                                style="background: linear-gradient(to right, rgba(0,0,0,.85), rgba(0,0,0,.5), transparent);">
                            </div>
                            @if ($slide->image)
                                <img src="{{ asset($slide->image) }}" alt="{{ $slide->name }}"
                                    class="position-absolute top-0 start-0 w-100 h-100 object-fit-cover"
                                    @if ($loop->first) fetchpriority="high" @else loading="lazy" @endif>
                            @endif
                            <div
                                class="position-relative z-2 h-100 container d-flex flex-column justify-content-center align-items-start text-white p-4 p-md-5">
                                @if ($slide->tagline)
                                    <span class="badge bg-primary rounded-pill text-uppercase mb-3 px-3 py-2 shadow-sm"
                                        style="font-size: .7rem; letter-spacing: 1px;">{{ $slide->tagline }}</span>
                                @endif
                                @if ($loop->first)
                                    <h1 class="display-6 display-md-4 fw-bolder lh-sm mb-3" style="max-width: 650px;">
                                        {{ $slide->name }}</h1>
                                @else
                                    <h2 class="display-6 display-md-4 fw-bolder lh-sm mb-3" style="max-width: 650px;">
                                        {{ $slide->name }}</h2>
                                @endif
                                @if ($slide->short_description)
                                    <p class="d-none d-sm-block text-light mb-4"
                                        style="max-width: 500px; font-size: .9rem;">{{ $slide->short_description }}</p>
                                @endif
                                @if ($slide->button_status && $slide->button_title && preg_match('~^https?://~i', $slide->button_url ?? ''))
                                    <a href="{{ $slide->button_url }}"
                                        class="btn btn-primary rounded-pill fw-bold px-4 py-2 px-md-5 py-md-3 shadow-lg transition-all"
                                        style="font-size: .9rem;">
                                        {{ $slide->button_title }}
                                        @if ($slide->button_icon)
                                            <i class="{{ $slide->button_icon }} ms-2" aria-hidden="true"></i>
                                        @endif
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                <!-- Bullet Pagination -->
                @if ($slides->count() > 1)
                    <div class="swiper-pagination mb-2"></div>
                @endif
            </div>
        </section>
    @endif
    <!-- PROMOTIONAL ADS -->
    @include('frontend.partials.promotion')
    @include('frontend.partials.popular-categories')
    @include('frontend.partials.new-arrivals')
    @include('frontend.partials.brands')
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
