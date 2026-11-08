@extends('frontend.layouts.master')
@section('front-content')
    @include('frontend.partials.catalog-listing', ['catalogEntity' => $selectedBrand, 'catalogDefaultName' => 'All Brand Products'])
@endsection