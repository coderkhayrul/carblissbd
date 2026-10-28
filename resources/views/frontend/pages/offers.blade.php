@extends('frontend.layouts.master')

@section('front-content')
    <header class="bg-dark text-white py-4 py-md-5 mb-4">
        <div class="container-md px-3">
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb small mb-0">
                    <li class="breadcrumb-item"><a class="text-white text-decoration-none" href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active text-white" aria-current="page">Offers</li>
                </ol>
            </nav>
            <h1 class="h2 fw-bolder mb-2">Special Offers</h1>
            <p class="text-light mb-0">Explore our featured offers for your car.</p>
        </div>
    </header>
    <main class="container-md px-3 mb-5">
        <section class="bg-white rounded-4 border border-light shadow-sm p-3 p-md-4" aria-labelledby="offer-products-title">
            <div class="mb-4">
                <h2 id="offer-products-title" class="h4 fw-bolder text-dark mb-1"><i class="fa-solid fa-tags text-primary me-2" aria-hidden="true"></i>Offer Products</h2>
                <p class="text-muted small mb-0">
                    @if ($products->total())
                        Showing {{ $products->firstItem() }}–{{ $products->lastItem() }} of {{ $products->total() }} products
                    @else
                        0 products
                    @endif
                </p>
            </div>
            @if ($products->isNotEmpty())
                <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-2 g-md-4">
                    @include('frontend.partials.recommended-product-cards', ['products' => $products])
                </div>
                @if ($products->hasPages())
                    <nav class="d-flex justify-content-between align-items-center gap-3 mt-4" aria-label="Offer product pages">
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
                    <i class="fa-solid fa-tags text-muted fs-1 mb-3" aria-hidden="true"></i>
                    <h3 class="h5 fw-bold">No offers available right now</h3>
                    <p class="text-muted">Please check back later for new offers.</p>
                    <a class="btn btn-outline-primary rounded-pill" href="{{ route('category') }}">Browse All Products</a>
                </div>
            @endif
        </section>
    </main>
@endsection
