@if ($promoAds->isNotEmpty())
    <section class="container-lg px-3 py-5">
        <div class="row g-4">
            @foreach ($promoAds as $promoAd)
                @php
                    $overlayColor = preg_match(
                        '/^#(?:[0-9a-fA-F]{3}|[0-9a-fA-F]{6}|[0-9a-fA-F]{8})$/',
                        $promoAd->overlay_color ?? '',
                    )
                        ? $promoAd->overlay_color
                        : null;
                    $overlayBackground = null;
                    if ($overlayColor) {
                        $hex = substr($overlayColor, 1);
                        if (strlen($hex) === 3) {
                            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
                        }
                        $red = hexdec(substr($hex, 0, 2));
                        $green = hexdec(substr($hex, 2, 2));
                        $blue = hexdec(substr($hex, 4, 2));
                        // Keep even solid hex colors translucent so the image stays visible.
                        $opacity = strlen($hex) === 8 ? min(0.7, hexdec(substr($hex, 6, 2)) / 255) : 0.7;
                        $overlayBackground = "linear-gradient(to right, rgba({$red}, {$green}, {$blue}, {$opacity}), rgba(0, 0, 0, .5))";
                    }
                    $hasButton = $promoAd->button_text && preg_match('~^https?://~i', $promoAd->button_url ?? '');
                @endphp
                <div class="col-12 col-md-4">
                    <div class="position-relative overflow-hidden rounded-4 shadow-sm group bg-dark"
                        style="min-height: 200px;">
                        <div class="position-absolute top-0 start-0 w-100 h-100 z-1 promo-overlay"
                            @if ($overlayBackground) style="background: {{ $overlayBackground }};" @endif>
                        </div>
                        @if ($promoAd->image)
                            <img src="{{ asset($promoAd->image) }}" alt="{{ $promoAd->title }}" loading="lazy"
                                class="position-absolute top-0 start-0 w-100 h-100 object-fit-cover transition-all"
                                style="transition-duration: 500ms;">
                        @endif
                        <div class="position-relative z-2 p-4 d-flex flex-column justify-content-end text-white"
                            style="min-height: 200px; overflow-wrap: anywhere;">
                            <h3 class="h5 fw-bold mb-1">{{ $promoAd->title }}</h3>
                            <p class="small text-light mb-3" style="font-size: .75rem;">{{ $promoAd->sub_title }}</p>
                            @if ($hasButton)
                                <a href="{{ $promoAd->button_url }}"
                                    class="btn btn-light rounded-pill btn-sm text-primary fw-bold d-inline-flex align-items-center gap-2 align-self-start">
                                    {{ $promoAd->button_text }} <i class="fa-solid fa-circle-arrow-right"
                                        aria-hidden="true"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@endif
