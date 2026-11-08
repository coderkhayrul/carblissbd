    <nav class="category-navbar border-top border-bottom border-light w-100 shadow-sm"
        aria-label="Product categories">
        <div class="container-lg px-3">
            <div class="category-navbar-items d-flex align-items-center gap-2 overflow-x-auto no-scrollbar py-2">
                <button type="button" onclick="toggleCategoryModal()" class="header-all-categories btn text-nowrap fw-bold d-flex align-items-center gap-3 flex-shrink-0"><i class="fa-solid fa-bars" aria-hidden="true"></i>All Categories<i class="fa-solid fa-chevron-down small" aria-hidden="true"></i></button>
                <a href="{{ route('brand') }}"
                    class="btn bg-light text-dark fw-bold border border-secondary border-opacity-10 rounded-pill px-3 py-1 text-nowrap d-flex align-items-center gap-2 hover-bg-primary transition-all flex-shrink-0"
                    style="font-size: .75rem;">
                    Brand
                </a>
                <a href="{{ route('offers') }}"
                    class="btn {{ request()->routeIs('offers') ? 'btn-primary active' : 'bg-light text-dark border-secondary border-opacity-10 hover-bg-primary' }} fw-bold border rounded-pill px-3 py-1 text-nowrap d-flex align-items-center gap-2 transition-all flex-shrink-0"
                    style="font-size: .75rem;" @if (request()->routeIs('offers')) aria-current="page" @endif>
                    Offer
                </a>
                @foreach ($menuCategories->whereNull('parent_id') as $menuCategory)
                    <div class="navbar-category-item d-flex align-items-center flex-shrink-0">
                    @if ($menuCategory->children->isNotEmpty())
                        <button type="button" class="navbar-category-trigger navbar-submenu-toggle btn text-dark fw-bold text-nowrap" aria-expanded="false" aria-controls="navbar-submenu-{{ $menuCategory->id }}" @if (request('category') === $menuCategory->slug || $menuCategory->children->contains('slug', request('category'))) aria-current="page" @endif>{{ $menuCategory->name }}</button>
                        <div id="navbar-submenu-{{ $menuCategory->id }}" class="navbar-category-submenu" aria-label="{{ $menuCategory->name }} subcategories" hidden>
                            <div class="navbar-submenu-heading">{{ $menuCategory->name }}</div>
                            <a href="{{ route('category', ['category' => $menuCategory->slug]) }}" class="navbar-submenu-link navbar-submenu-view-all"><span>View All {{ $menuCategory->name }}</span><i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
                            @foreach ($menuCategory->children as $child)
                                <a href="{{ route('category', ['category' => $child->slug]) }}" class="navbar-submenu-link" @if (request('category') === $child->slug) aria-current="page" @endif><span>{{ $child->name }}</span><i class="fa-solid fa-chevron-right" aria-hidden="true"></i></a>
                            @endforeach
                        </div>
                    @else
                    <a href="{{ route('category', ['category' => $menuCategory->slug]) }}"
                        class="btn bg-light text-dark fw-bold border border-secondary border-opacity-10 rounded-pill px-3 py-1 text-nowrap d-flex align-items-center gap-2 hover-bg-primary transition-all flex-shrink-0"
                        style="font-size: .75rem;" @if (request('category') === $menuCategory->slug) aria-current="page" @endif>
                        {{ $menuCategory->name }}
                    </a>
                    @endif
                    </div>
                @endforeach
            </div>
        </div>
    </nav>
    <script>
        (() => {
            let active = null;
            const hover = window.matchMedia('(hover: hover) and (pointer: fine)');
            const close = () => {
                if (!active) return;
                active.panel.hidden = true;
                active.button.setAttribute('aria-expanded', 'false');
                active = null;
            };
            document.querySelectorAll('.navbar-category-item').forEach(item => {
                const button = item.querySelector('.navbar-submenu-toggle');
                const panel = item.querySelector('.navbar-category-submenu');
                if (!button || !panel) return;
                // Keep the dropdown outside the horizontally scrolling navbar.
                document.body.appendChild(panel);
                let timer;
                const open = () => {
                    clearTimeout(timer);
                    close();
                    panel.hidden = false;
                    button.setAttribute('aria-expanded', 'true');
                    const rect = item.getBoundingClientRect();
                    const width = panel.getBoundingClientRect().width;
                    panel.style.left = Math.max(8, Math.min(rect.left, window.innerWidth - width - 8)) + 'px';
                    panel.style.top = rect.bottom + 'px';
                    panel.style.maxHeight = Math.max(0, window.innerHeight - rect.bottom - 12) + 'px';
                    active = {panel, button, item};
                };
                const leave = () => { timer = setTimeout(() => { if (active?.panel === panel) close(); }, 160); };
                item.addEventListener('pointerenter', () => { if (hover.matches) open(); });
                item.addEventListener('pointerleave', () => { if (hover.matches) leave(); });
                panel.addEventListener('pointerenter', () => clearTimeout(timer));
                panel.addEventListener('pointerleave', () => { if (hover.matches) leave(); });
                button.addEventListener('click', () => { if (hover.matches) open(); else if (active?.panel === panel) close(); else open(); });
                button.addEventListener('keydown', event => {
                    if (event.key === 'ArrowDown') { event.preventDefault(); open(); panel.querySelector('a')?.focus(); }
                });
            });
            document.addEventListener('pointerdown', event => {
                if (active && !active.item.contains(event.target) && !active.panel.contains(event.target)) close();
            });
            document.addEventListener('focusin', event => {
                if (active && !active.item.contains(event.target) && !active.panel.contains(event.target)) close();
            });
            document.addEventListener('keydown', event => {
                if (event.key === 'Escape' && active) { const button = active.button; close(); button.focus(); }
            });
            window.addEventListener('resize', close, {passive: true});
            document.addEventListener('scroll', event => {
                if (active && !active.panel.contains(event.target)) close();
            }, {capture: true, passive: true});
        })();
    </script>
