<div class="product-search position-relative" data-search-url="{{ route('products.search') }}">
    <form action="{{ route('products.search') }}" method="GET" role="search" class="d-flex w-100">
        <label for="{{ $searchId }}" class="visually-hidden">Search products</label>
        <input type="search" id="{{ $searchId }}" name="q" value="{{ request()->routeIs('products.search') ? request('q') : '' }}" placeholder="Search products, brands or SKU..." minlength="2" maxlength="120" autocomplete="off" required
            class="form-control rounded-start-pill py-2 px-3 bg-light border-0 product-search-input" aria-controls="{{ $searchId }}-results" aria-expanded="false">
        <button type="submit" class="btn btn-primary rounded-end-pill px-3" aria-label="Search products"><i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i></button>
    </form>
    <div id="{{ $searchId }}-results" class="product-search-results" hidden aria-label="Product search suggestions">
        <p class="product-search-status small text-muted p-3 mb-0" role="status" aria-live="polite"></p>
        <div class="product-search-items"></div>
        <a class="product-search-all d-block text-center fw-semibold p-3 border-top text-decoration-none" hidden>View all results</a>
    </div>
</div>
