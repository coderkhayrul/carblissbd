@extends('frontend.layouts.master')

@section('front-content')
    @include('frontend.partials.new-arrivals', ['showAllArrivals' => true])
    @if ($newArrivals->isEmpty())
        <div class="container py-5 text-center"><h1 class="h4">New Arrivals</h1><p class="text-muted">No new arrivals available yet.</p></div>
    @endif
@endsection
