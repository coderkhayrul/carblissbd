@extends('frontend.layouts.master')

@section('front-content')
    <main class="container-md px-3 my-4">
        <article class="bg-white rounded-4 border border-light shadow-sm p-3 p-md-4 overflow-hidden">
            <nav aria-label="breadcrumb" class="small mb-3"><a href="{{ route('home') }}">Home</a> / {{ $page->name }}</nav>
            @if ($page->banner_image)
                <img src="{{ asset($page->banner_image) }}" alt="{{ $page->name }}" class="w-100 rounded-3 mb-4" style="max-height: 320px; object-fit: cover;">
            @endif
            <h1 class="h3 fw-bold mb-4">{{ $page->name }}</h1>
            @if ($page->image)
                <img src="{{ asset($page->image) }}" alt="{{ $page->name }}" class="img-fluid rounded-3 mb-4" style="max-height: 400px; object-fit: contain;">
            @endif
            <div class="customer-care-content" style="overflow-wrap: anywhere;">{!! $page->content !!}</div>
        </article>
    </main>
@endsection
