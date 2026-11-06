@extends('frontend.layouts.master')
@section('front-content')
@php
    $variation = $product->is_variation ? $product->variations->first() : null;
    $unavailable = $product->is_variation && !$variation;
    $fallback = asset('backend/assets/extra/preview-image.png');
    $defaultImage = asset($product->thumbnail_image ?: (($product->gallery_images ?? [])[0] ?? 'backend/assets/extra/preview-image.png'));
    $initialImage = $variation?->image ? asset($variation->image) : $defaultImage;
    $gallery = collect([$defaultImage])->merge(collect($product->gallery_images ?? [])->map(fn ($path) => asset($path)))->unique()->values();
    $price = (float) ($variation?->sale_price ?? $product->sale_price);
    $mrp = (float) ($variation?->mrp_price ?? $product->mrp_price);
    $features = preg_split('/\r\n|\r|\n/', strip_tags(preg_replace('~</(?:li|p|div)>|<br\s*/?>~i', "\n", $product->landing_page_feature ?: ($product->features ?? ''))));
    $features = array_values(array_filter(array_map('trim', $features)));
@endphp
<main class="product-landing">
    <div class="container-lg px-3 py-4 py-lg-5">
        <nav class="small mb-4" aria-label="breadcrumb"><a href="{{ route('home') }}">Home</a> / <a href="{{ route('product.detail', ['product' => $product->slug]) }}">{{ $product->name }}</a></nav>
        <section class="landing-hero" aria-labelledby="landing-title">
            <div class="row g-4 g-lg-5 align-items-start">
                <div class="col-12 col-lg-6">
                    <div class="landing-image-frame"><img id="landing-main-image" src="{{ $initialImage }}" alt="{{ $product->name }}" fetchpriority="high" decoding="async" width="600" height="600" onerror="this.onerror=null;this.src='{{ $fallback }}'"></div>
                    <div class="landing-gallery mt-3" aria-label="Product image gallery">@foreach ($gallery as $image)<button class="landing-thumb" type="button" data-image="{{ $image }}" aria-label="View product image {{ $loop->iteration }}" aria-pressed="{{ $image === $initialImage ? 'true' : 'false' }}"><img src="{{ $image }}" width="74" height="74" alt="{{ $product->name }} - image {{ $loop->iteration }}" loading="lazy" decoding="async"></button>@endforeach</div>
                </div>
                <div class="col-12 col-lg-6">
                    <span class="landing-eyebrow">{{ $product->brand?->name ?: 'Product Spotlight' }}</span>
                    <h1 id="landing-title">{{ $product->landing_page_title ?: $product->name }}</h1>
                    <p class="landing-lead">{{ strip_tags($product->landing_short_description ?: ($product->short_description ?? '')) }}</p>
                    <div class="landing-price-row" aria-live="polite"><strong id="landing-price">৳{{ number_format($price, 2) }}</strong><del id="landing-mrp" @if ($mrp <= $price) hidden @endif>৳{{ number_format($mrp, 2) }}</del></div>
                    @if ($features)<ul class="landing-feature-list">@foreach (array_slice($features, 0, 5) as $feature)<li><i class="fa-solid fa-circle-check" aria-hidden="true"></i><span>{{ $feature }}</span></li>@endforeach</ul>@endif
                    <a href="#landing-order" class="btn btn-primary fw-bold px-4 py-3">পছন্দের ভেরিয়েশন বেছে অর্ডার করুন <i class="fa-solid fa-arrow-down ms-2" aria-hidden="true"></i></a>
                    @if ($videoId)<a href="#landing-video" class="btn btn-link text-dark fw-semibold">ভিডিও দেখুন</a>@endif
                </div>
            </div>
        </section>
        <section id="landing-order" class="landing-section mt-4" aria-labelledby="landing-order-title">
            <div class="row g-4"><div class="col-12 col-lg-8">
                <span class="landing-eyebrow">সহজে অর্ডার করুন</span><h2 id="landing-order-title">আপনার পছন্দ নির্বাচন করুন</h2>
                <p class="text-secondary">ভেরিয়েশন ও পরিমাণ বেছে পরের ধাপে আপনার নাম, মোবাইল এবং ঠিকানা দিন।</p>
                @if ($errors->any())<div class="alert alert-danger" role="alert"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
                <form id="landing-order-form" action="{{ route('product.landing.checkout', $product->slug) }}" method="POST">
                    @csrf
                    @if ($product->is_variation)
                        <fieldset><legend class="h6 fw-bold mb-3">কালার / সাইজ / ভেরিয়েশন</legend><div class="landing-variations">
                            @foreach ($product->variations as $option)
                                <label class="landing-variation"><input type="radio" name="variation_id" value="{{ $option->id }}" @checked(old('variation_id', $variation?->id) == $option->id) required data-image="{{ $option->image ? asset($option->image) : $defaultImage }}" data-price="{{ $option->sale_price }}" data-mrp="{{ $option->mrp_price }}" data-label="{{ $option->optionLabel($product->name) }}">
                                    <span class="landing-variation-content"><img src="{{ $option->image ? asset($option->image) : $defaultImage }}" alt="{{ $option->optionLabel($product->name) }}" width="90" height="90" loading="lazy" decoding="async"><strong>{{ $option->optionLabel($product->name) }}</strong><small>৳{{ number_format((float) $option->sale_price, 2) }}</small></span>
                                </label>
                            @endforeach
                        </div></fieldset>
                        @if ($unavailable)<p class="alert alert-secondary">এই প্রোডাক্টের কোনো ভেরিয়েশন এখন পাওয়া যাচ্ছে না।</p>@endif
                    @endif
                    <div class="row g-3 align-items-end mt-2"><div class="col-12 col-sm-4"><label class="form-label fw-bold" for="landing-quantity">পরিমাণ</label><input id="landing-quantity" type="number" name="quantity" min="1" max="99" step="1" value="{{ old('quantity', 1) }}" class="form-control form-control-lg" required></div><div class="col-12 col-sm-8"><button class="btn btn-primary btn-lg w-100 fw-bold" type="submit" @disabled($unavailable)>অর্ডার করুন <i class="fa-solid fa-arrow-right ms-2" aria-hidden="true"></i></button></div></div>
                </form>
            </div><div class="col-12 col-lg-4"><aside class="landing-summary"><h3 class="h5 fw-bold">আপনার নির্বাচিত প্রোডাক্ট</h3><p>{{ $product->name }}</p><p id="landing-selected-label" class="text-secondary">{{ $variation?->optionLabel($product->name) }}</p><div class="d-flex justify-content-between gap-3"><span>প্রোডাক্টের মোট দাম</span><strong id="landing-total">৳{{ number_format($price, 2) }}</strong></div><hr><p class="small text-secondary mb-0">ডেলিভারি এলাকা অনুযায়ী চার্জ পরের ধাপে দেখাবে। অর্ডার নিশ্চিত হলে ইনভয়েস পাবেন।</p></aside></div></div>
        </section>
        @if ($features)<section class="landing-section mt-4"><span class="landing-eyebrow">প্রোডাক্টের বৈশিষ্ট্য</span><h2>কেন এই প্রোডাক্টটি বেছে নেবেন?</h2><div class="row g-3 mt-1">@foreach ($features as $feature)<div class="col-12 col-md-6"><div class="landing-benefit"><i class="fa-solid fa-check" aria-hidden="true"></i><span>{{ $feature }}</span></div></div>@endforeach</div></section>@endif
        @if ($videoId)<section id="landing-video" class="landing-section mt-4"><h2>প্রোডাক্টটি ভিডিওতে দেখুন</h2><div class="landing-video mt-3" id="landing-video-frame"><button type="button" id="landing-play-video" data-video="{{ $videoId }}" class="landing-video-button"><i class="fa-brands fa-youtube" aria-hidden="true"></i><span>Play YouTube Video</span></button><noscript><a href="https://www.youtube.com/watch?v={{ $videoId }}" target="_blank" rel="noopener">Watch on YouTube</a></noscript></div></section>@endif
        @if ($product->landing_long_description || $product->description)<section class="landing-section mt-4"><h2>বিস্তারিত জানুন</h2><div class="landing-long-description">{{ strip_tags($product->landing_long_description ?: $product->description) }}</div></section>@endif
        <section class="landing-section mt-4"><h2>মাত্র তিন ধাপে অর্ডার</h2><ol class="landing-steps"><li>পছন্দের ভেরিয়েশন ও পরিমাণ নির্বাচন করুন।</li><li>চেকআউটে নাম, মোবাইল, ঠিকানা ও ডেলিভারি এলাকা দিন।</li><li>বিল দেখে Place Order চাপুন এবং অর্ডার নিশ্চিত করুন।</li></ol><a href="#landing-order" class="btn btn-primary fw-bold">এখনই অর্ডার করুন</a></section>
        @if ($relatedProducts->isNotEmpty())<section class="landing-section mt-4"><h2 class="mb-4">আরও পছন্দ হতে পারে</h2><div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-3">@include('frontend.partials.recommended-product-cards', ['products' => $relatedProducts])</div></section>@endif
    </div>
</main>
<script src="{{ asset('frontend/js/product-landing.js') }}" defer></script>
@endsection
