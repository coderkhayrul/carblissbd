@if ($newArrivals->isNotEmpty())
    <section id="new-arrivals" class="container-md px-3 my-4">
        <div class="bg-white rounded-4 border border-light shadow-sm p-3 p-md-4" style="background-color: #FFFFFF;">
        <div class="d-flex align-items-center justify-content-between gap-3 mb-4">
            <div>
                <h2 class="h4 fw-bolder text-dark text-uppercase d-flex align-items-center gap-2 mb-1">
                    <i class="fa-solid fa-fire text-primary" aria-hidden="true"></i> New Arrivals
                </h2>
                <p class="text-muted small mb-0">Freshest stock, direct from global manufacturers</p>
            </div>
            <div class="d-flex gap-2 flex-shrink-0">
                <button type="button" class="btn btn-outline-primary btn-sm rounded-circle prod-prev" aria-label="Previous products"><i class="fa-solid fa-chevron-left" aria-hidden="true"></i></button>
                <button type="button" class="btn btn-outline-primary btn-sm rounded-circle prod-next" aria-label="Next products"><i class="fa-solid fa-chevron-right" aria-hidden="true"></i></button>
            </div>
        </div>
        <div class="swiper new-arrivals-swiper">
            <div class="swiper-wrapper">
            @foreach ($newArrivals as $product)
                @php
                    $variation = $product->is_variation ? $product->variations->first() : null;
                    $price = (float) ($variation?->sale_price ?? $product->sale_price);
                    $mrp = (float) ($variation?->mrp_price ?? $product->mrp_price);
                    $productUrl = route('product.detail', ['product' => $product->slug]);
                @endphp
                <div class="swiper-slide">
                    <article class="card border-light shadow-sm rounded-4 h-100 product-card">
                        <div class="position-absolute top-0 start-0 p-2 z-2">
                            <span class="badge bg-dark rounded-pill text-uppercase shadow-sm"
                                style="font-size: .55rem;">New Arrival</span>
                        </div>
                        <a href="{{ $productUrl }}" class="arrival-image-frame bg-light rounded-top-4 overflow-hidden"
                            aria-label="View {{ $product->name }}">
                            <img height="250px" width="100%"
                                src="{{ asset($product->thumbnail_image ?: 'backend/assets/extra/preview-image.png') }}"
                                alt="{{ $product->name }}" loading="lazy" class="arrival-image product-img">
                        </a>
                        <div class="card-body d-flex flex-column p-2 p-sm-3">
                            <div class="mb-auto">
                                <span
                                    class="text-muted fw-bold text-uppercase d-block mb-1 arrival-category">{{ $product->category?->name ?: $product->brand?->name }}</span>
                                <a href="{{ $productUrl }}" class="text-decoration-none text-dark">
                                    <h3
                                        class="h6 fw-bold line-clamp-2 mb-2 hover-text-primary transition-all arrival-name">
                                        {{ $product->name }}</h3>
                                </a>
                                <div class="d-flex flex-wrap align-items-baseline gap-2 mb-3">
                                    <span
                                        class="fw-bolder text-primary arrival-price">{{ $product->is_variation ? 'From ' : '' }}&#2547;{{ number_format($price, $price == floor($price) ? 0 : 2) }}</span>
                                    @if ($mrp > $price)
                                        <del
                                            class="text-muted small">&#2547;{{ number_format($mrp, $mrp == floor($mrp) ? 0 : 2) }}</del>
                                    @endif
                                </div>
                            </div>
                            <a href="{{ $productUrl }}"
                                class="btn btn-primary rounded-3 w-100 fw-bold mt-auto arrival-order">Order Now</a>
                        </div>
                    </article>
                </div>
            @endforeach
            </div>
        </div>
        </div>
    </section>
@endif
