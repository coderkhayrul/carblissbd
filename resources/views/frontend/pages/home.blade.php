@extends('frontend.layouts.master')

@section('front-content')
    @if ($slides->isNotEmpty() || $promoAds->isNotEmpty())
        <section class="container-lg px-3 my-3 my-md-4" aria-label="Featured products and promotions">
            <div class="home-hero-grid {{ $slides->isNotEmpty() && $promoAds->isNotEmpty() ? 'has-promo-sidebar' : '' }}">
                @if ($slides->isNotEmpty())
                    <div class="home-hero-main">
                        <div
                            class="swiper hero-swiper hero-slider-container position-relative bg-white overflow-hidden rounded-4 shadow-sm">

                            <div class="swiper-wrapper">
                                @foreach ($slides as $slide)
                                    <div class="swiper-slide position-relative w-100 h-100 rounded-4 overflow-hidden">
                                        @if (preg_match('~^https?://~i', $slide->button_url ?? ''))
                                            <a href="{{ $slide->button_url }}"
                                                class="position-absolute top-0 start-0 w-100 h-100 z-1"
                                                aria-label="{{ $slide->name ?: 'View featured offer' }}"
                                                draggable="false"></a>
                                        @endif
                                        @if ($slide->image)
                                            <img src="{{ asset($slide->image) }}" alt="{{ $slide->name }}"
                                                class="position-absolute top-0 start-0 w-100 h-100 object-fit-cover" draggable="false"
                                                @if ($loop->first) fetchpriority="high" @else loading="lazy" @endif>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            <!-- Bullet Pagination -->
                            @if ($slides->count() > 1)
                                <div class="swiper-pagination mb-2"></div>
                            @endif
                        </div>
                    </div>
                @endif
                @if ($promoAds->isNotEmpty())
                    <aside class="home-hero-promos d-none d-md-flex" aria-label="Promotional offers">
                        @include('frontend.partials.promotion')</aside>
                @endif
            </div>
        </section>
    @endif
    <!-- PROMOTIONAL ADS -->
    @include('frontend.partials.popular-categories')
    @include('frontend.partials.new-arrivals')
    @include('frontend.partials.brands')
    @include('frontend.partials.recommended-products')
@endsection
