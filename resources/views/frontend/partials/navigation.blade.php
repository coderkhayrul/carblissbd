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
            <input type="search" id="main-search" placeholder="Search genuine accessories, engine oils, brake pads..."
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
            <div class="p-3 border-bottom border-light d-flex justify-content-between align-items-center bg-light">
                <span class="fw-bold text-secondary text-uppercase"
                    style="font-size: 0.7rem; letter-spacing: 0.05em;">Top Results for your search</span>
                <a href="#" class="fw-bold text-primary text-decoration-none" style="font-size: 0.7rem;">View All
                    Results</a>
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
