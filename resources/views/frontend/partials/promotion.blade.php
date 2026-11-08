@if ($promoAds->isNotEmpty())
    <div id="promotional-ads" class="swiper promo-ads-swiper rounded-4 shadow-sm">
        <div class="swiper-wrapper">
            @foreach ($promoAds as $promoAd)
                @php
                    $hasButton = $promoAd->button_text && preg_match('~^https?://~i', $promoAd->button_url ?? '');
                    $overlay = preg_match(
                        '/^#(?:[a-fA-F0-9]{3}|[a-fA-F0-9]{6}|[a-fA-F0-9]{8})$/',
                        $promoAd->overlay_color ?? '',
                    )
                        ? $promoAd->overlay_color
                        : null;
                @endphp
                <article class="swiper-slide promo-sidebar-slide">
                    @if ($promoAd->image)
                        <img src="{{ asset($promoAd->image) }}" alt="{{ $promoAd->title }}" class="promo-sidebar-image"
                            loading="{{ $loop->first ? 'eager' : 'lazy' }}" decoding="async">
                    @endif
                    {{-- <div class="promo-sidebar-shade"
                        @if ($overlay) style="background:{{ $overlay }};opacity:.45" @endif>
                    </div> --}}
                    <div class="promo-sidebar-content">
                        {{-- @if ($promoAd->title)<h2 class="h5 fw-bold">{{ $promoAd->title }}</h2>@endif
                        @if ($promoAd->sub_title)<p class="small mb-3">{{ $promoAd->sub_title }}</p>@endif --}}
                        @if ($hasButton)
                            <a href="{{ $promoAd->button_url }}"
                                class="btn btn-light btn-sm rounded-pill fw-bold align-self-start">{{ $promoAd->button_text }}
                                <i class="fa-solid fa-arrow-right ms-1" aria-hidden="true"></i></a>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>
    </div>
@endif
