@php
    $catalogName = $catalogEntity?->name ?: $catalogDefaultName;
    $catalogTitle = $catalogEntity?->meta_title ?: ($catalogEntity?->title ?: $catalogName);
    $catalogDescription = $catalogEntity?->meta_description ?: ($catalogEntity?->description ?: 'Explore products and accessories for your car.');
@endphp
<main class="catalog-listing container-lg px-3 py-3 py-md-4">
    <header class="catalog-introduction mb-3">
        <nav aria-label="breadcrumb"><ol class="breadcrumb mb-3"><li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li><li class="breadcrumb-item active" aria-current="page">{{ $catalogName }}</li></ol></nav>
        <h1>{{ $catalogTitle }}</h1>
        <p>{{ strip_tags($catalogDescription) }}</p>
    </header>
    <section class="catalog-products-panel" aria-labelledby="catalog-products-title">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4"><h2 id="catalog-products-title" class="mb-0">{{ $catalogName }}</h2><span class="catalog-result-count">@if ($products->total()){{ $products->firstItem() }}–{{ $products->lastItem() }} of {{ $products->total() }} products @else 0 products @endif</span></div>
        @if ($products->isNotEmpty())
            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-2 g-md-4">
                @include('frontend.partials.recommended-product-cards', ['products' => $products])
            </div>
            @if ($products->hasPages())<nav class="catalog-pagination mt-4 pt-3" aria-label="Product pages">{{ $products->onEachSide(1)->links('pagination::bootstrap-5') }}</nav>@endif
        @else
            <div class="text-center py-5"><i class="fa-solid fa-box-open text-secondary fs-2 mb-3" aria-hidden="true"></i><p class="text-muted">No products are available here yet.</p><a href="{{ route('home') }}" class="btn btn-primary btn-sm">Continue Shopping</a></div>
        @endif
    </section>
</main>
