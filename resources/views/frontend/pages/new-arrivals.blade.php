@extends('frontend.layouts.master')

@section('front-content')
    @if ($newArrivals->isNotEmpty())<h1 class="visually-hidden">New Arrivals</h1>@endif
    @include('frontend.partials.new-arrivals', ['showAllArrivals' => true])
    @if ($newArrivals->isEmpty())
        <div class="container py-5 text-center"><h1 class="h4">New Arrivals</h1><p class="text-muted">No new arrivals available yet.</p></div>
    @endif
@endsection
