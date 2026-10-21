@extends('frontend.layouts.master')

@section('front-content')
    <main class="container-md px-3 my-4">
        <div class="bg-white rounded-4 shadow-sm p-3 p-md-4">
            <h1 class="h4 fw-bold">Search results</h1>
            <p class="text-muted small">{{ $products->total() }} products found for “{{ $term }}”</p>
            @if (mb_strlen($term) < 2)
                <p>Enter at least 2 characters to search.</p>
            @elseif ($products->isEmpty())
                <p>No products found. Try a different name, brand or SKU.</p>
            @else
                <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-2 g-md-4">
                    @include('frontend.partials.recommended-product-cards', ['products' => $products])
                </div>
                <nav class="d-flex justify-content-between align-items-center gap-3 mt-4" aria-label="Search results pages">
                    @if ($products->previousPageUrl())<a class="btn btn-outline-primary btn-sm" href="{{ $products->previousPageUrl() }}">Previous</a>@else<span></span>@endif
                    <span class="small text-muted">{{ $products->currentPage() }} / {{ $products->lastPage() }}</span>
                    @if ($products->nextPageUrl())<a class="btn btn-outline-primary btn-sm" href="{{ $products->nextPageUrl() }}">Next</a>@else<span></span>@endif
                </nav>
            @endif
        </div>
    </main>
@endsection
