    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <script src="{{ asset('frontend/js/script.js') }}"></script>

    <script>
        // --- Initialize Swiper for Hero Section ---
        const heroElement = document.querySelector('.hero-swiper');
        const heroSlideCount = heroElement?.querySelectorAll('.swiper-slide').length ?? 0;
        var heroSwiper = heroElement ? new Swiper(heroElement, {
            loop: heroSlideCount > 1,
            grabCursor: true,
            autoplay: heroSlideCount > 1 ? {
                delay: 5000,
                disableOnInteraction: false,
            } : false,
            pagination: {
                el: heroElement.querySelector('.swiper-pagination'),
                clickable: true,
            },
            // navigation: {
            //     nextEl: ".swiper-button-next",
            //     prevEl: ".swiper-button-prev",
            // },
        }) : null;

        // --- Initialize Swiper for the New Category Section ---
        var newCategorySwiper = new Swiper('.new-category-swiper', {
            slidesPerView: 2,
            spaceBetween: 8,
            watchOverflow: true,
            grabCursor: true,
            pagination: {
                el: '.category-pagination',
                clickable: true,
            },
            breakpoints: {
                576: {
                    slidesPerView: 4,
                    spaceBetween: 10,
                },
                768: {
                    slidesPerView: 6,
                    spaceBetween: 10,
                },
                992: {
                    slidesPerView: 8,
                    spaceBetween: 10,
                }
            }
        });

        // --- Initialize Swiper for Brands Section ---
        const brandsElement = document.querySelector('#popular-brands .brands-swiper');
        const brandCount = brandsElement?.querySelectorAll('.swiper-slide').length ?? 0;
        var brandsSwiper = brandsElement ? new Swiper(brandsElement, {
            slidesPerView: 2,
            spaceBetween: 15,
            grabCursor: true,
            watchOverflow: true,
            loop: brandCount > 6,
            autoplay: brandCount > 1 ? {
                delay: 2500, // Auto slides every 2.5 seconds
                disableOnInteraction: false,
            } : false,
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
                    slidesPerView: 3,
                    spaceBetween: 20,
                },
                992: {
                    slidesPerView: 6, // 6 items on large screens
                    spaceBetween: 20,
                }
            }
        }) : null;

        // --- Initialize Swiper for New Arrivals Section ---
        const arrivalsElement = document.querySelector('#new-arrivals .new-arrivals-swiper');
        var newArrivalsSwiper = arrivalsElement ? new Swiper(arrivalsElement, {
            threshold: 8,
            touchAngle: 35,
            touchStartPreventDefault: false,
            slidesPerView: 2,
            spaceBetween: 12,
            watchOverflow: true,
            grabCursor: true,
            navigation: {
                nextEl: '#new-arrivals .prod-next',
                prevEl: '#new-arrivals .prod-prev',
            },
            breakpoints: {
                576: {
                    slidesPerView: 3,
                    spaceBetween: 14,
                },
                768: {
                    slidesPerView: 4,
                    spaceBetween: 16,
                },
                992: {
                    slidesPerView: 5,
                    spaceBetween: 16,
                },
                1200: {
                    slidesPerView: 5,
                    spaceBetween: 16,
                }
            }
        }) : null;

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
                const escapeCartHtml = value => String(value).replace(/[&<>"']/g, character => ({
                    '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
                })[character]);
                const itemRow = document.createElement('div');
                // Added position-relative for absolute delete button positioning
                itemRow.className =
                    "cart-item-row d-flex align-items-center justify-content-between border-bottom border-light pb-3 gap-3 position-relative";
                itemRow.innerHTML = `
                    <img src="${escapeCartHtml(item.img)}" alt="${escapeCartHtml(item.name)}" class="rounded-3 object-fit-cover flex-shrink-0 border border-light" style="width: 65px; height: 65px;">
                    <div class="flex-grow-1 pe-4"> <!-- Added padding-end to prevent text overlap with remove button -->
                        <h5 class="fw-bold text-dark line-clamp-2 m-0" style="font-size: 0.8rem; line-height: 1.4;">${escapeCartHtml(item.name)}</h5>
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

        // --- Mobile Category Modal Logic ---
        @php
            $mobileMenuData = $menuCategories->map(fn ($category) => [
                'id' => $category->id,
                'parent_id' => $category->parent_id,
                'name' => $category->name,
                'img' => asset($category->image ?: 'backend/assets/extra/preview-image.png'),
                'url' => route('category', ['category' => $category->slug]),
            ])->values();
        @endphp
        const categoriesDataList = {{ Illuminate\Support\Js::from($mobileMenuData) }};

        function renderMainCategories() {
            const grid = document.getElementById('main-cat-grid');
            if (!grid) return;
            grid.replaceChildren();
            if (!categoriesDataList.length) {
                const empty = document.createElement('p');
                empty.className = 'text-muted small w-100 text-center py-4';
                empty.textContent = 'No categories available.';
                grid.appendChild(empty);
                return;
            }
            categoriesDataList.forEach(data => {
                const column = document.createElement('div');
                column.className = 'col';
                const hasChildren = categoriesDataList.some(item => item.parent_id === data.id);
                const card = document.createElement(hasChildren ? 'button' : 'a');
                card.className = 'mobile-category-card d-flex flex-column align-items-center bg-white p-3 rounded-4 shadow-sm border border-light text-decoration-none hover-text-primary h-100 w-100';
                if (hasChildren) {
                    card.type = 'button';
                    card.addEventListener('click', () => openSubCategory(data.id));
                } else {
                    card.href = data.url;
                }
                const frame = document.createElement('div');
                frame.className = 'rounded-circle overflow-hidden mb-2 border border-2 border-primary flex-shrink-0';
                frame.style.cssText = 'width:60px;height:60px;';
                const image = document.createElement('img');
                image.src = data.img;
                image.alt = data.name;
                image.loading = 'lazy';
                image.className = 'w-100 h-100 object-fit-contain';
                frame.appendChild(image);
                const name = document.createElement('span');
                name.className = 'fw-bold text-center text-dark';
                name.style.fontSize = '.7rem';
                name.textContent = data.name;
                card.append(frame, name);
                column.appendChild(card);
                grid.appendChild(column);
            });
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
            const data = categoriesDataList.find(item => item.id === key);
            if (!data) return;
            document.getElementById('cat-modal-title').textContent = data.name;
            const backBtn = document.getElementById('cat-back-btn');
            backBtn.classList.remove('d-none');
            backBtn.classList.add('d-flex');
            const subItems = document.getElementById('sub-cat-items');
            subItems.replaceChildren();
            const items = [{name: 'View all ' + data.name, url: data.url},
                ...categoriesDataList.filter(item => item.parent_id === data.id)];
            items.forEach(item => {
                const row = document.createElement('li');
                const link = document.createElement('a');
                link.href = item.url;
                link.className = 'd-flex justify-content-between align-items-center gap-2 p-3 bg-light rounded-3 fw-bold text-dark text-decoration-none hover-text-primary border';
                const label = document.createElement('span');
                label.textContent = item.name;
                label.style.overflowWrap = 'anywhere';
                const icon = document.createElement('i');
                icon.className = 'fa-solid fa-chevron-right small flex-shrink-0';
                icon.setAttribute('aria-hidden', 'true');
                link.append(label, icon);
                row.appendChild(link);
                subItems.appendChild(row);
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

        // --- Password Visibility Toggle ---
        function togglePasswordVisibility() {
            const pwdInput = document.getElementById('login-password');
            const toggleIcon = document.getElementById('toggle-pwd-icon');

            if (pwdInput.type === 'password') {
                pwdInput.type = 'text';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            } else {
                pwdInput.type = 'password';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            }
        }
    </script>
    </body>

    </html>
