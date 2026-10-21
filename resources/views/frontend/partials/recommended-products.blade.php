@if ($recommendedProducts->isNotEmpty())
    <section id="you-may-like" class="container-md px-3 my-4">
        <div class="bg-white rounded-4 border border-light shadow-sm p-3 p-md-4" style="background-color: #FFFFFF;">
        <div class="mb-4">
            <h2 class="h4 fw-bolder text-dark text-uppercase d-flex align-items-center gap-2 mb-1"><i class="fa-solid fa-thumbs-up text-primary" aria-hidden="true"></i> You May Like</h2>
            <p class="text-muted small mb-0">Explore more products for your car</p>
        </div>
        <div id="you-may-like-grid" class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-2 g-md-4" aria-busy="false">
            @include('frontend.partials.recommended-product-cards', ['products' => $recommendedProducts])
        </div>
        <div class="text-center mt-4">
            <p id="more-products-status" class="small text-muted" role="status" aria-live="polite"></p>
            @if ($hasMoreProducts)
                <button type="button" id="load-more-products" class="btn btn-outline-primary rounded-pill fw-bold px-4 py-2" data-url="{{ route('products.more') }}" data-cursor="{{ $recommendedProducts->last()->id }}" aria-controls="you-may-like-grid">View More Products</button>
            @endif
        </div>
        </div>
    </section>
    <script>
        (() => {
            const button = document.getElementById('load-more-products');
            if (!button) return;
            const grid = document.getElementById('you-may-like-grid');
            const status = document.getElementById('more-products-status');
            let loading = false;
            button.addEventListener('click', async () => {
                if (loading) return;
                loading = true;
                button.disabled = true;
                button.textContent = 'Loading...';
                grid.setAttribute('aria-busy', 'true');
                status.textContent = '';
                try {
                    const url = new URL(button.dataset.url, window.location.href);
                    url.searchParams.set('before', button.dataset.cursor);
                    const response = await fetch(url, {headers: {'Accept': 'application/json'}});
                    if (!response.ok) throw new Error('Unable to load products');
                    const data = await response.json();
                    grid.insertAdjacentHTML('beforeend', data.html);
                    if (data.has_more && data.next_cursor) {
                        button.dataset.cursor = data.next_cursor;
                    } else {
                        button.hidden = true;
                        status.textContent = 'All products have been loaded.';
                    }
                } catch (error) {
                    status.textContent = 'Could not load products. Please try again.';
                } finally {
                    loading = false;
                    button.disabled = false;
                    button.textContent = 'View More Products';
                    grid.setAttribute('aria-busy', 'false');
                }
            });
        })();
    </script>
@endif
