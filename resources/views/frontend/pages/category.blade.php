@extends('frontend.layouts.master')

@section('front-content')
    <!-- Breadcrumb & Header Banner -->
    <div class="bg-dark text-white position-relative overflow-hidden mb-4 py-4 py-md-5">
        <div class="position-absolute top-0 start-0 w-100 h-100 z-1"
            style="background: linear-gradient(135deg, rgba(17,24,39,0.9) 0%, rgba(241,90,41,0.8) 100%);"></div>
        <!-- Background Pattern -->
        <div class="position-absolute top-0 start-0 w-100 h-100 z-0 opacity-25"
            style="background-image: url('https://images.unsplash.com/photo-1549399542-7e3f8b79c341?auto=format&fit=crop&q=80&w=1200'); background-size: cover; background-position: center;">
        </div>

        <div class="container-lg px-3 position-relative z-2">
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb small mb-0 d-flex flex-nowrap overflow-auto no-scrollbar"
                    style="font-size: 0.8rem; white-space: nowrap;">
                    <li class="breadcrumb-item"><a href="carblissbd_home.html"
                            class="text-white text-decoration-none hover-text-primary transition-all"><i
                                class="fa-solid fa-house"></i> Home</a></li>
                    <li class="breadcrumb-item active text-light fw-bold" aria-current="page">Car Interior</li>
                </ol>
            </nav>
            <h1 class="display-5 fw-black text-white mb-2">Car Interior Accessories</h1>
            <p class="text-light mb-0" style="max-width: 600px; font-size: 0.95rem;">Enhance your driving comfort and
                vehicle aesthetics with our premium selection of seat covers, steering wraps, and customized floor mats.</p>
        </div>
    </div>

    <main class="container-lg px-3 pb-5">

        <!-- Mobile Filter Action Bar -->
        <div class="d-flex d-lg-none justify-content-between align-items-center bg-white p-3 rounded-3 shadow-sm border border-light mb-4 sticky-top"
            style="top: 115px; z-index: 1040;">
            <button onclick="toggleFilterDrawer()"
                class="btn btn-outline-dark fw-bold btn-sm d-flex align-items-center gap-2">
                <i class="fa-solid fa-sliders"></i> Filters
            </button>

            <div class="d-flex align-items-center gap-2">
                <span class="small text-muted fw-bold">Sort:</span>
                <select class="form-select form-select-sm border-0 bg-light focus-ring-0 fw-bold cursor-pointer"
                    style="width: auto;">
                    <option selected>Newest First</option>
                    <option value="1">Price: Low to High</option>
                    <option value="2">Price: High to Low</option>
                    <option value="3">Popularity</option>
                </select>
            </div>
        </div>

        <div class="row g-4">

            <!-- Left Sidebar (Filters) - Desktop -->
            <div class="col-12 col-lg-3 d-none d-lg-block">
                <div class="bg-white rounded-4 shadow-sm border border-light p-4 sticky-top" style="top: 125px;">

                    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom border-light pb-2">
                        <h3 class="h6 fw-bolder text-dark m-0"><i class="fa-solid fa-filter text-primary"></i> Filter
                            Products</h3>
                        <button
                            class="btn btn-link p-0 text-muted small text-decoration-none hover-text-primary transition-all"
                            style="font-size: 0.75rem;">Clear All</button>
                    </div>

                    <!-- Sub Categories -->
                    <div class="mb-4">
                        <h4 class="fw-bold text-dark mb-3"
                            style="font-size: 0.85rem; letter-spacing: 0.05em; text-transform: uppercase;">Sub Categories
                        </h4>
                        <ul class="list-unstyled d-flex flex-column gap-2 m-0 small custom-checkbox">
                            <li>
                                <div class="form-check">
                                    <input class="form-check-input cursor-pointer" type="checkbox" id="cat1" checked>
                                    <label
                                        class="form-check-label text-secondary cursor-pointer hover-text-primary transition-all d-flex justify-content-between"
                                        for="cat1">
                                        <span>Steering Covers</span> <span class="badge bg-light text-muted">24</span>
                                    </label>
                                </div>
                            </li>
                            <li>
                                <div class="form-check">
                                    <input class="form-check-input cursor-pointer" type="checkbox" id="cat2">
                                    <label
                                        class="form-check-label text-secondary cursor-pointer hover-text-primary transition-all d-flex justify-content-between"
                                        for="cat2">
                                        <span>Seat Covers</span> <span class="badge bg-light text-muted">15</span>
                                    </label>
                                </div>
                            </li>
                            <li>
                                <div class="form-check">
                                    <input class="form-check-input cursor-pointer" type="checkbox" id="cat3">
                                    <label
                                        class="form-check-label text-secondary cursor-pointer hover-text-primary transition-all d-flex justify-content-between"
                                        for="cat3">
                                        <span>Floor Mats</span> <span class="badge bg-light text-muted">32</span>
                                    </label>
                                </div>
                            </li>
                            <li>
                                <div class="form-check">
                                    <input class="form-check-input cursor-pointer" type="checkbox" id="cat4">
                                    <label
                                        class="form-check-label text-secondary cursor-pointer hover-text-primary transition-all d-flex justify-content-between"
                                        for="cat4">
                                        <span>Organizers & Storage</span> <span class="badge bg-light text-muted">8</span>
                                    </label>
                                </div>
                            </li>
                            <li>
                                <div class="form-check">
                                    <input class="form-check-input cursor-pointer" type="checkbox" id="cat5">
                                    <label
                                        class="form-check-label text-secondary cursor-pointer hover-text-primary transition-all d-flex justify-content-between"
                                        for="cat5">
                                        <span>Pillows & Cushions</span> <span class="badge bg-light text-muted">12</span>
                                    </label>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <!-- Price Filter -->
                    <div class="mb-4 pt-3 border-top border-light">
                        <h4 class="fw-bold text-dark mb-3"
                            style="font-size: 0.85rem; letter-spacing: 0.05em; text-transform: uppercase;">Price Range</h4>

                        <div class="mb-3">
                            <input type="range" class="form-range" min="0" max="15000" id="priceRange"
                                oninput="document.getElementById('priceVal').innerText = '৳' + this.value">
                        </div>
                        <div class="d-flex justify-content-between align-items-center small fw-bold text-secondary">
                            <span>৳0</span>
                            <span id="priceVal" class="text-primary">৳7500</span>
                            <span>৳15k+</span>
                        </div>
                    </div>

                    <!-- Brands -->
                    <div class="mb-4 pt-3 border-top border-light">
                        <h4 class="fw-bold text-dark mb-3"
                            style="font-size: 0.85rem; letter-spacing: 0.05em; text-transform: uppercase;">Brands</h4>
                        <div class="input-group input-group-sm mb-3">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i
                                    class="fa-solid fa-magnifying-glass"></i></span>
                            <input type="text" class="form-control bg-light border-start-0 focus-ring-0"
                                placeholder="Search brand...">
                        </div>
                        <ul class="list-unstyled d-flex flex-column gap-2 m-0 small custom-checkbox"
                            style="max-height: 150px; overflow-y: auto;">
                            <li>
                                <div class="form-check">
                                    <input class="form-check-input cursor-pointer" type="checkbox" id="brand1">
                                    <label
                                        class="form-check-label text-secondary cursor-pointer hover-text-primary transition-all"
                                        for="brand1">OrenMart Custom</label>
                                </div>
                            </li>
                            <li>
                                <div class="form-check">
                                    <input class="form-check-input cursor-pointer" type="checkbox" id="brand2">
                                    <label
                                        class="form-check-label text-secondary cursor-pointer hover-text-primary transition-all"
                                        for="brand2">Sparco</label>
                                </div>
                            </li>
                            <li>
                                <div class="form-check">
                                    <input class="form-check-input cursor-pointer" type="checkbox" id="brand3">
                                    <label
                                        class="form-check-label text-secondary cursor-pointer hover-text-primary transition-all"
                                        for="brand3">Momo</label>
                                </div>
                            </li>
                            <li>
                                <div class="form-check">
                                    <input class="form-check-input cursor-pointer" type="checkbox" id="brand4">
                                    <label
                                        class="form-check-label text-secondary cursor-pointer hover-text-primary transition-all"
                                        for="brand4">Generic</label>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <!-- Apply Button -->
                    <button class="btn btn-primary w-100 rounded-pill fw-bold btn-sm py-2">Apply Filters</button>
                </div>
            </div>

            <!-- Right Content (Products Grid) -->
            <div class="col-12 col-lg-9">

                <!-- Desktop Top Bar -->
                <div
                    class="d-none d-lg-flex justify-content-between align-items-center mb-4 pb-2 border-bottom border-light">
                    <span class="text-secondary fw-bold small">Showing 1-20 of 91 products in <span class="text-dark">Car
                            Interior</span></span>

                    <div class="d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center gap-2">
                            <span class="small text-muted fw-bold">Sort by:</span>
                            <select
                                class="form-select form-select-sm border border-light bg-white focus-ring-0 fw-bold cursor-pointer rounded-pill px-3 shadow-sm"
                                style="width: auto;">
                                <option selected>Featured</option>
                                <option value="1">Price: Low to High</option>
                                <option value="2">Price: High to Low</option>
                                <option value="3">Newest Arrivals</option>
                                <option value="4">Top Rated</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Product Grid -->
                <div class="row row-cols-2 row-cols-md-3 row-cols-xl-4 g-2 g-md-4" id="category-product-grid">

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
                                    <span class="fw-bolder text-primary mb-2 d-block"
                                        style="font-size: 0.9rem;">৳500</span>
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
                                    <span class="fw-bolder text-primary mb-2 d-block"
                                        style="font-size: 0.9rem;">৳500</span>
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
                                    <span class="fw-bolder text-primary mb-2 d-block"
                                        style="font-size: 0.9rem;">৳500</span>
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
                                    <span class="fw-bolder text-primary mb-2 d-block"
                                        style="font-size: 0.9rem;">৳500</span>
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
                                    <span class="fw-bolder text-primary mb-2 d-block"
                                        style="font-size: 0.9rem;">৳500</span>
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
                                    <span class="fw-bolder text-primary mb-2 d-block"
                                        style="font-size: 0.9rem;">৳500</span>
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
                                    <span class="fw-bolder text-primary mb-2 d-block"
                                        style="font-size: 0.9rem;">৳500</span>
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
                                    <span class="fw-bolder text-primary mb-2 d-block"
                                        style="font-size: 0.9rem;">৳500</span>
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
                    <!-- Dynamic Items will be injected here via JS for demo -->

                </div>

                <!-- Pagination -->
                <nav aria-label="Category Pagination" class="mt-5 d-flex justify-content-center">
                    <ul class="pagination pagination-sm mb-0">
                        <li class="page-item disabled">
                            <a class="page-link rounded-start-pill px-3 fw-bold border-light shadow-sm" href="#"
                                tabindex="-1">Previous</a>
                        </li>
                        <li class="page-item active"><a class="page-link fw-bold shadow-sm" href="#">1</a></li>
                        <li class="page-item"><a class="page-link fw-bold text-dark border-light shadow-sm"
                                href="#">2</a></li>
                        <li class="page-item"><a class="page-link fw-bold text-dark border-light shadow-sm"
                                href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link rounded-end-pill px-3 fw-bold text-dark border-light shadow-sm"
                                href="#">Next</a>
                        </li>
                    </ul>
                </nav>

            </div>
        </div>
    </main>

    <!-- Mobile Filter Drawer -->
    <div id="mobile-filter-drawer" class="filter-drawer d-flex flex-column shadow-lg d-lg-none">
        <div class="p-3 border-bottom border-light d-flex justify-content-between align-items-center bg-dark text-white">
            <h3 class="h6 fw-bolder mb-0 d-flex align-items-center gap-2"><i class="fa-solid fa-filter text-primary"></i>
                Filters</h3>
            <button onclick="toggleFilterDrawer()"
                class="btn btn-dark bg-secondary bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center p-0 border-0 hover-bg-primary transition-all"
                style="width: 30px; height: 30px;">
                <i class="fa-solid fa-xmark text-white"></i>
            </button>
        </div>
        <div class="flex-grow-1 overflow-auto bg-light p-3 position-relative">
            <!-- Copy of Desktop Filters for Mobile -->
            <div class="bg-white rounded-3 p-3 shadow-sm mb-3 border border-light">
                <h4 class="fw-bold text-dark mb-2" style="font-size: 0.8rem; text-transform: uppercase;">Sub Categories
                </h4>
                <ul class="list-unstyled d-flex flex-column gap-2 m-0 small custom-checkbox">
                    <li>
                        <div class="form-check">
                            <input class="form-check-input cursor-pointer" type="checkbox" id="m-cat1" checked>
                            <label class="form-check-label text-secondary cursor-pointer d-flex justify-content-between"
                                for="m-cat1">
                                <span>Steering Covers</span> <span class="badge bg-light text-muted">24</span>
                            </label>
                        </div>
                    </li>
                    <li>
                        <div class="form-check">
                            <input class="form-check-input cursor-pointer" type="checkbox" id="m-cat2">
                            <label class="form-check-label text-secondary cursor-pointer d-flex justify-content-between"
                                for="m-cat2">
                                <span>Seat Covers</span> <span class="badge bg-light text-muted">15</span>
                            </label>
                        </div>
                    </li>
                </ul>
            </div>

            <div class="bg-white rounded-3 p-3 shadow-sm mb-3 border border-light">
                <h4 class="fw-bold text-dark mb-3" style="font-size: 0.8rem; text-transform: uppercase;">Price Range</h4>
                <input type="range" class="form-range" min="0" max="15000" id="m-priceRange"
                    oninput="document.getElementById('m-priceVal').innerText = '৳' + this.value">
                <div class="d-flex justify-content-between align-items-center small fw-bold text-secondary mt-2">
                    <span>৳0</span>
                    <span id="m-priceVal" class="text-primary">৳7500</span>
                    <span>৳15k+</span>
                </div>
            </div>
        </div>
        <div class="p-3 bg-white border-top border-light d-flex gap-2">
            <button onclick="toggleFilterDrawer()"
                class="btn btn-outline-secondary w-50 fw-bold rounded-pill">Cancel</button>
            <button onclick="toggleFilterDrawer()" class="btn btn-primary w-50 fw-bold rounded-pill">Apply</button>
        </div>
    </div>
@endsection
