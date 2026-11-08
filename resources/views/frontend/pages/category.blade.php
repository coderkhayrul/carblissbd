@extends('frontend.layouts.master')
@section('front-content')
    @include('frontend.partials.catalog-listing', ['catalogEntity' => $selectedCategory, 'catalogDefaultName' => 'All Products'])
@endsection