@extends('frontend.layouts.master')

@section('front-content')
    <header class="bg-dark text-white position-relative overflow-hidden mb-4 py-4 py-md-5">
        @if ($selectedCategory?->banner_image)
            <img src="{{ asset($selectedCategory->banner_image) }}" alt="" class="position-absolute top-0 start-0 w-100 h-100 object-fit-cover opacity-25">
        @endif
        <div class="container-md px-3 position-relative">
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb small mb-0">
                    <li class="breadcrumb-item"><a class="text-white text-decoration-none" href="{{ route('home') }}">Home</a></li>
                    @if ($selectedCategory)
                        <li class="breadcrumb-item"><a class="text-white text-decoration-none" href="{{ route('category') }}">Categories</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">{{ $selectedCategory->name }}</li>
                    @else
                        <li class="breadcrumb-item active text-white" aria-current="page">All Products</li>
                    @endif
                </ol>
            </nav>
            <h1 class="h2 fw-bolder mb-2">{{ $selectedCategory?->name ?? 'All Products' }}</h1>
            @if ($selectedCategory?->description)
                <p class="text-light mb-0" style="max-width: 760px; overflow-wrap: anywhere;">{{ $selectedCategory->description }}</p>
            @else
                <p class="text-light mb-0">Explore products for your car.</p>
            @endif
        </div>
    </header>

    <main class="container-md px-3 mb-5">
        <section class="bg-white rounded-4 border border-light shadow-sm p-3 p-md-4" aria-labelledby="category-products-title">
            <div class="row g-3 align-items-end mb-4">
                <div class="col-12 col-md-6">
                    <h2 class="h4 fw-bolder text-dark mb-1" id="category-products-title">{{ $selectedCategory ? $selectedCategory->name . ' Products' : 'Browse Products' }}</h2>
                    <p class="text-muted small mb-0">
                        @if ($products->total())
                            Showing {{ $products->firstItem() }}–{{ $products->lastItem() }} of {{ $products->total() }} products
                        @else
                            0 products
                        @endif
                    </p>
                </div>
                <div class="col-12 col-md-6">
                    <form action="{{ route('category') }}" method="GET" class="d-flex align-items-end gap-2">
                        <div class="flex-grow-1" style="min-width: 0;">
                            <label class="form-label small fw-bold" for="browse-category">Category</label>
                            <select class="form-select" id="browse-category" name="category">
                                <option value="">All Categories</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->slug }}" @selected($selectedCategory?->id === $category->id)>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary fw-bold">Browse</button>
                    </form>
                </div>
            </div>

            @if ($products->isNotEmpty())
                <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-2 g-md-4">
                    @include('frontend.partials.recommended-product-cards', ['products' => $products])
                </div>
                @if ($products->hasPages())
                    <nav class="d-flex justify-content-between align-items-center gap-3 mt-4" aria-label="Category product pages">
                        @if ($products->previousPageUrl())
                            <a class="btn btn-outline-primary btn-sm" href="{{ $products->previousPageUrl() }}" rel="prev">Previous</a>
                        @else
                            <span></span>
                        @endif
                        <span class="small text-muted text-center">Page {{ $products->currentPage() }} of {{ $products->lastPage() }}</span>
                        @if ($products->nextPageUrl())
                            <a class="btn btn-outline-primary btn-sm" href="{{ $products->nextPageUrl() }}" rel="next">Next</a>
                        @else
                            <span></span>
                        @endif
                    </nav>
                @endif
            @else
                <div class="text-center py-5" role="status">
                    <i class="fa-solid fa-box-open text-muted fs-1 mb-3" aria-hidden="true"></i>
                    <h3 class="h5 fw-bold">No products found</h3>
                    <p class="text-muted">Products are not available in this category yet.</p>
                    <a class="btn btn-outline-primary rounded-pill" href="{{ route('category') }}">Browse All Products</a>
                </div>
            @endif
        </section>
    </main>
@endsection
