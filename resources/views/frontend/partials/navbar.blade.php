    <nav class="category-navbar bg-white border-top border-bottom border-light w-100 shadow-sm"
        aria-label="Product categories">
        <div class="container-md px-3">
            <div class="category-navbar-items d-flex align-items-center gap-2 overflow-x-auto no-scrollbar py-2">
                <a href="{{ route('home') }}#popular-brands"
                    class="btn bg-light text-dark fw-bold border border-secondary border-opacity-10 rounded-pill px-3 py-1 text-nowrap d-flex align-items-center gap-2 hover-bg-primary transition-all flex-shrink-0"
                    style="font-size: .75rem;">
                    <i class="fa-solid fa-tags text-muted" aria-hidden="true"></i> Brand
                </a>
                <a href="{{ route('home') }}#promotional-ads"
                    class="btn bg-light text-dark fw-bold border border-secondary border-opacity-10 rounded-pill px-3 py-1 text-nowrap d-flex align-items-center gap-2 hover-bg-primary transition-all flex-shrink-0"
                    style="font-size: .75rem;">
                    <i class="fa-solid fa-percent text-muted" aria-hidden="true"></i> Offer
                </a>
                @foreach ($menuCategories->take(5) as $menuCategory)
                    <a href="{{ route('category', ['category' => $menuCategory->slug]) }}"
                        class="btn bg-light text-dark fw-bold border border-secondary border-opacity-10 rounded-pill px-3 py-1 text-nowrap d-flex align-items-center gap-2 hover-bg-primary transition-all flex-shrink-0"
                        style="font-size: .75rem;" @if (request('category') === $menuCategory->slug) aria-current="page" @endif>
                        <i class="{{ $menuCategory->icon ?: 'fa-solid fa-layer-group' }} text-muted"
                            aria-hidden="true"></i>
                        {{ $menuCategory->name }}
                    </a>
                @endforeach
            </div>
        </div>
    </nav>
