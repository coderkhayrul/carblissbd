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
