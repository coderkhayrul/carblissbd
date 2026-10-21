@extends('frontend.layouts.master')

@section('front-content')
    @if ($slides->isNotEmpty())
        <section class="container-lg px-0 px-md-3">
            <div
                class="swiper hero-swiper hero-slider-container position-relative bg-white overflow-hidden my-md-4 rounded-0 rounded-md-4 shadow-sm">

                <div class="swiper-wrapper">
                    @foreach ($slides as $slide)
                        <div class="swiper-slide position-relative w-100 h-100">
                            <div class="position-absolute top-0 start-0 w-100 h-100 z-1"
                                style="background: linear-gradient(to right, rgba(0,0,0,.85), rgba(0,0,0,.5), transparent);">
                            </div>
                            @if ($slide->image)
                                <img src="{{ asset($slide->image) }}" alt="{{ $slide->name }}"
                                    class="position-absolute top-0 start-0 w-100 h-100 object-fit-cover"
                                    @if ($loop->first) fetchpriority="high" @else loading="lazy" @endif>
                            @endif
                            <div
                                class="position-relative z-2 h-100 container d-flex flex-column justify-content-center align-items-start text-white p-4 p-md-5">
                                @if ($slide->tagline)
                                    <span class="badge bg-primary rounded-pill text-uppercase mb-3 px-3 py-2 shadow-sm"
                                        style="font-size: .7rem; letter-spacing: 1px;">{{ $slide->tagline }}</span>
                                @endif
                                @if ($loop->first)
                                    <h1 class="display-6 display-md-4 fw-bolder lh-sm mb-3" style="max-width: 650px;">
                                        {{ $slide->name }}</h1>
                                @else
                                    <h2 class="display-6 display-md-4 fw-bolder lh-sm mb-3" style="max-width: 650px;">
                                        {{ $slide->name }}</h2>
                                @endif
                                @if ($slide->short_description)
                                    <p class="d-none d-sm-block text-light mb-4"
                                        style="max-width: 500px; font-size: .9rem;">{{ $slide->short_description }}</p>
                                @endif
                                @if ($slide->button_status && $slide->button_title && preg_match('~^https?://~i', $slide->button_url ?? ''))
                                    <a href="{{ $slide->button_url }}"
                                        class="btn btn-primary rounded-pill fw-bold px-4 py-2 px-md-5 py-md-3 shadow-lg transition-all"
                                        style="font-size: .9rem;">
                                        {{ $slide->button_title }}
                                        @if ($slide->button_icon)
                                            <i class="{{ $slide->button_icon }} ms-2" aria-hidden="true"></i>
                                        @endif
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                <!-- Bullet Pagination -->
                @if ($slides->count() > 1)
                    <div class="swiper-pagination mb-2"></div>
                @endif
            </div>
        </section>
    @endif
    <!-- PROMOTIONAL ADS -->
    @include('frontend.partials.popular-categories')
    @include('frontend.partials.promotion')
    @include('frontend.partials.new-arrivals')
    @include('frontend.partials.brands')
    @include('frontend.partials.recommended-products')
@endsection
