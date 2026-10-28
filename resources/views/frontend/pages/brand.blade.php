@extends('frontend.layouts.master')

@section('front-content')
    <header class="bg-dark text-white position-relative overflow-hidden mb-4 py-4 py-md-5">
        @if ($selectedBrand?->banner_image)
            <img src="{{ asset($selectedBrand->banner_image) }}" alt="" class="position-absolute top-0 start-0 w-100 h-100 object-fit-cover opacity-25">
        @endif
        <div class="container-md px-3 position-relative">
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb small mb-0">
                    <li class="breadcrumb-item"><a class="text-white text-decoration-none" href="{{ route('home') }}">Home</a></li>
                    @if ($selectedBrand)
                        <li class="breadcrumb-item"><a class="text-white text-decoration-none" href="{{ route('brand') }}">Brands</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">{{ $selectedBrand->name }}</li>
                    @else
                        <li class="breadcrumb-item active text-white" aria-current="page">All Products</li>
                    @endif
                </ol>
            </nav>
            <h1 class="h2 fw-bolder mb-2">{{ $selectedBrand?->name ?? 'All Products' }}</h1>
            @if ($selectedBrand?->title)
                <p class="text-light mb-0" style="max-width: 760px; overflow-wrap: anywhere;">{{ $selectedBrand->title }}</p>
            @else
                <p class="text-light mb-0">Explore products for your car.</p>
            @endif
        </div>
    </header>

    <main class="container-md px-3 mb-5">
        <section class="bg-white rounded-4 border border-light shadow-sm p-3 p-md-4" aria-labelledby="brand-products-title">
            <div class="row g-3 align-items-end mb-4">
                <div class="col-12 col-md-6">
                    <h2 class="h4 fw-bolder text-dark mb-1" id="brand-products-title">{{ $selectedBrand ? $selectedBrand->name . ' Products' : 'Browse Products' }}</h2>
                    <p class="text-muted small mb-0">
                        @if ($products->total())
                            Showing {{ $products->firstItem() }}–{{ $products->lastItem() }} of {{ $products->total() }} products
                        @else
                            0 products
                        @endif
                    </p>
                </div>
                <div class="col-12 col-md-6">
                    <form action="{{ route('brand') }}" method="GET" class="d-flex align-items-end gap-2">
                        <div class="flex-grow-1" style="min-width: 0;">
                            <label class="form-label small fw-bold" for="browse-brand">Brand</label>
                            <select class="form-select" id="browse-brand" name="brand">
                                <option value="">All Brands</option>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->slug }}" @selected($selectedBrand?->id === $brand->id)>{{ $brand->name }}</option>
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
                    <nav class="d-flex justify-content-between align-items-center gap-3 mt-4" aria-label="Brand product pages">
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
                    <p class="text-muted">Products are not available in this brand yet.</p>
                    <a class="btn btn-outline-primary rounded-pill" href="{{ route('brand') }}">Browse All Products</a>
                </div>
            @endif
        </section>
    </main>
@endsection
