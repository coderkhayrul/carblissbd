@foreach ($products as $product)
    @php
        $variation = $product->is_variation ? $product->variations->first() : null;
        $price = (float) ($variation?->sale_price ?? $product->sale_price);
        $mrp = (float) ($variation?->mrp_price ?? $product->mrp_price);
        $productUrl = route('product.detail', ['product' => $product->slug]);
    @endphp
    <div class="col">
        <article class="card border-light shadow-sm rounded-4 h-100 product-card">
            <a href="{{ $productUrl }}" class="position-relative d-block bg-light rounded-top-4 overflow-hidden" style="height: 150px; flex: 0 0 150px;" aria-label="View {{ $product->name }}">
                <img src="{{ asset($product->thumbnail_image ?: 'backend/assets/extra/preview-image.png') }}" alt="{{ $product->name }}" loading="lazy" class="position-absolute top-0 start-0 w-100 h-100 object-fit-contain transition-all product-img">
            </a>
            <div class="card-body d-flex flex-column p-2 p-sm-3">
                <div class="mb-auto">
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 text-truncate" style="font-size: .55rem; letter-spacing: .05em;">{{ $product->category?->name ?: $product->brand?->name }}</span>
                    <a href="{{ $productUrl }}" class="text-decoration-none text-dark">
                        <h3 class="h6 fw-bold line-clamp-2 mb-2 hover-text-primary transition-all" style="font-size: .75rem; line-height: 1.4; min-height: 2.8em; overflow-wrap: anywhere;">{{ $product->name }}</h3>
                    </a>
                    <div class="d-flex flex-wrap align-items-baseline gap-2 mb-3">
                        <span class="fw-bolder text-primary" style="font-size: .9rem;">{{ $product->is_variation ? 'From ' : '' }}&#2547;{{ number_format($price, $price == floor($price) ? 0 : 2) }}</span>
                        @if ($mrp > $price)<del class="text-muted small">&#2547;{{ number_format($mrp, $mrp == floor($mrp) ? 0 : 2) }}</del>@endif
                    </div>
                </div>
                <a href="{{ $productUrl }}" class="btn btn-primary rounded-3 w-100 fw-bold mt-auto" style="font-size: .75rem;">Order Now</a>
            </div>
        </article>
    </div>
@endforeach
