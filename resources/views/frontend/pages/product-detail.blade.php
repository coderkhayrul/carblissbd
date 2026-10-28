@extends('frontend.layouts.master')

@section('front-content')
    @php
        $selectedVariation = $product->is_variation ? $product->variations->first() : null;
        $unavailable = $product->is_variation && !$selectedVariation;
        $salePrice = (float) ($selectedVariation?->sale_price ?? $product->sale_price);
        $mrpPrice = (float) ($selectedVariation?->mrp_price ?? $product->mrp_price);
        $variationData = $product->is_variation ? $product->variations->map(fn ($variation) => [
            'id' => $variation->id, 'name' => $variation->name, 'sku' => $variation->sku,
            'price' => (float) $variation->sale_price, 'mrp' => (float) $variation->mrp_price,
            'attributes' => $variation->variationAttributes->map(fn ($pair) => [
                'type' => $pair->attributeType?->name ?? 'Attribute',
                'value' => $pair->attribute?->name ?? '—',
            ])->values()->all(),
        ])->values()->all() : [];
    @endphp
    <main class="container-md px-3 py-4">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb small flex-wrap">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                @if ($product->category?->status)
                    <li class="breadcrumb-item"><a class="text-decoration-none" href="{{ route('category', ['category' => $product->category->slug]) }}">{{ $product->category->name }}</a></li>
                @endif
                <li class="breadcrumb-item active" aria-current="page" style="overflow-wrap: anywhere;">{{ $product->name }}</li>
            </ol>
        </nav>
        <section class="bg-white rounded-4 border border-light shadow-sm p-3 p-md-4 mb-4">
            <div class="row g-4 g-lg-5">
                <div class="col-12 col-lg-6">
                    <a class="d-flex align-items-center justify-content-center bg-light rounded-4 p-3" href="{{ asset($product->thumbnail_image ?: 'backend/assets/extra/preview-image.png') }}" target="_blank" rel="noopener" aria-label="Open full product image">
                        <img class="img-fluid w-100 object-fit-contain" src="{{ asset($product->thumbnail_image ?: 'backend/assets/extra/preview-image.png') }}" alt="{{ $product->name }}" fetchpriority="high" style="height: clamp(240px, 50vw, 460px);">
                    </a>
                </div>
                <div class="col-12 col-lg-6" style="min-width: 0;">
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        @if ($product->is_new_arrival)<span class="badge bg-primary">New Arrival</span>@endif
                        @if ($product->is_offer)<span class="badge bg-danger">Offer</span>@endif
                        @if ($product->is_refundable)<span class="badge bg-light text-dark border">Refundable</span>@endif
                    </div>
                    <h1 class="h3 fw-bolder text-dark" style="overflow-wrap: anywhere;">{{ $product->name }}</h1>
                    @if ($product->bangla_name)<p class="text-muted">{{ $product->bangla_name }}</p>@endif
                    <div class="small mb-3 d-flex flex-wrap gap-3">
                        @if ($product->brand?->status)<a class="text-decoration-none" href="{{ route('brand', ['brand' => $product->brand->slug]) }}">{{ $product->brand->name }}</a>@endif
                        <span class="text-muted">SKU: <span id="detail-sku" class="text-dark">{{ $selectedVariation?->sku ?? $product->sku }}</span></span>
                    </div>
                    <div id="detail-prices" class="{{ $unavailable ? 'd-none' : 'd-flex' }} flex-wrap align-items-center gap-3 mb-3" aria-live="polite">
                        <span id="detail-price" class="h3 fw-bolder text-primary mb-0">&#2547;{{ number_format($salePrice, 2) }}</span>
                        <del id="detail-mrp" class="text-muted" @if ($mrpPrice <= $salePrice) hidden @endif>&#2547;{{ number_format($mrpPrice, 2) }}</del>
                        <span id="detail-saving" class="badge bg-success" @if ($mrpPrice <= $salePrice) hidden @endif>Save {{ $mrpPrice > 0 ? round(max(0, ($mrpPrice - $salePrice) / $mrpPrice * 100)) : 0 }}%</span>
                    </div>
                    @if ($product->short_description)<p class="text-secondary" style="white-space: pre-line; overflow-wrap: anywhere;">{{ strip_tags($product->short_description) }}</p>@endif
                    @if ($product->is_variation)
                        @if ($product->variations->isNotEmpty())
                            <div class="border rounded-3 p-3 mb-4">
                                <label class="form-label fw-bold" for="detail-variation">Choose a Variation</label>
                                <select class="form-select" id="detail-variation" aria-describedby="detail-variation-help">
                                    @foreach ($product->variations as $variation)
                                        <option value="{{ $variation->id }}" @selected($selectedVariation?->id === $variation->id)>{{ $variation->name }}@if ($variation->variationAttributes->isNotEmpty()) — {{ $variation->variationAttributes->map(fn ($pair) => ($pair->attributeType?->name ?? 'Attribute') . ': ' . ($pair->attribute?->name ?? '—'))->implode(', ') }}@endif</option>
                                    @endforeach
                                </select>
                                <div id="detail-variation-help" class="form-text">Select an option to see its price, SKU and attributes.</div>
                                <dl id="detail-attributes" class="row small mt-3 mb-0" aria-live="polite">
                                    @foreach ($selectedVariation->variationAttributes as $pair)
                                        <dt class="col-5 mb-2">{{ $pair->attributeType?->name ?? 'Attribute' }}</dt><dd class="col-7 mb-2">{{ $pair->attribute?->name ?? '—' }}</dd>
                                    @endforeach
                                </dl>
                            </div>
                        @else
                            <div class="alert alert-secondary" role="status">No variations are currently available for this product.</div>
                        @endif
                    @endif
                    <div class="row g-2 mb-4">
                        <div class="col-12 col-sm-6">
                            <button type="button" id="detail-add-cart" class="btn btn-outline-primary rounded-3 w-100 fw-bold py-3" @disabled($unavailable)>
                                <i class="fa-solid fa-cart-shopping me-2" aria-hidden="true"></i>Add to Cart
                            </button>
                        </div>
                        <div class="col-12 col-sm-6">
                            <button type="button" id="detail-order-now" class="btn btn-primary rounded-3 w-100 fw-bold py-3" @disabled($unavailable)>
                                <i class="fa-solid fa-bolt me-2" aria-hidden="true"></i>Order Now
                            </button>
                        </div>
                    </div>
                    @if ($product->features)
                        <h2 class="h6 fw-bold">Key Features</h2>
                        <div class="text-secondary small mb-3" style="white-space: pre-line; overflow-wrap: anywhere;">{{ strip_tags($product->features) }}</div>
                    @endif
                    @if ($product->barcode)<p class="small text-muted">Barcode: {{ $product->barcode }}</p>@endif
                    @if ($product->tags->where('status', true)->isNotEmpty())
                        <div class="d-flex flex-wrap gap-2">@foreach ($product->tags->where('status', true) as $tag)<span class="badge bg-light text-dark border">{{ $tag->name }}</span>@endforeach</div>
                    @endif
                </div>
            </div>
        </section>
        @if ($product->description || $product->bangla_description || $product->bangla_features || $product->bangla_short_description)
            <section class="bg-white rounded-4 border border-light shadow-sm p-3 p-md-4 mb-4" aria-labelledby="detail-description-title">
                <h2 class="h5 fw-bold mb-3" id="detail-description-title">Product Description</h2>
                @if ($product->description)<div class="text-secondary" style="white-space: pre-line; overflow-wrap: anywhere;">{{ strip_tags($product->description) }}</div>@endif
                @if ($product->bangla_description || $product->bangla_features || $product->bangla_short_description)
                    <div lang="bn" class="mt-4">
                        <h3 class="h6 fw-bold">বাংলা বিবরণ</h3>
                        @foreach (['bangla_short_description', 'bangla_features', 'bangla_description'] as $field)
                            @if ($product->{$field})<div class="text-secondary mb-3" style="white-space: pre-line; overflow-wrap: anywhere;">{{ strip_tags($product->{$field}) }}</div>@endif
                        @endforeach
                    </div>
                @endif
            </section>
        @endif
        @if ($product->size_chart_image)
            <section class="bg-white rounded-4 border border-light shadow-sm p-3 p-md-4 mb-4">
                <h2 class="h5 fw-bold mb-3">Size Chart</h2>
                <img class="img-fluid rounded" src="{{ asset($product->size_chart_image) }}" alt="Size chart for {{ $product->name }}" loading="lazy">
            </section>
        @endif
        @if ($product->video_link && preg_match('~^https?://~i', $product->video_link))
            <section class="bg-white rounded-4 border border-light shadow-sm p-3 p-md-4 mb-4">
                <h2 class="h5 fw-bold mb-3">Product Video</h2>
                <a class="btn btn-outline-primary" href="{{ $product->video_link }}" target="_blank" rel="noopener noreferrer"><i class="fa-solid fa-play me-2" aria-hidden="true"></i>Watch Video</a>
            </section>
        @endif
        @if ($relatedProducts->isNotEmpty())
            <section class="bg-white rounded-4 border border-light shadow-sm p-3 p-md-4">
                <h2 class="h4 fw-bold mb-4">You May Also Like</h2>
                <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-2 g-md-4">@include('frontend.partials.recommended-product-cards', ['products' => $relatedProducts])</div>
            </section>
        @endif
    </main>
    <script>
        (() => {
            const selector = document.getElementById('detail-variation');
            const variations = @json($variationData);
            const productId = @json($product->id);
            const productName = @json($product->name);
            const productPrice = @json((float) $product->sale_price);
            const productImage = @json(asset($product->thumbnail_image ?: 'backend/assets/extra/preview-image.png'));
            const hasVariations = @json((bool) $product->is_variation);

            function addSelectedProduct(openCart) {
                const variation = selector ? variations.find(item => String(item.id) === selector.value) : null;
                if (hasVariations && !variation) return;
                // Negative keys keep variation items separate from ordinary product IDs.
                const key = variation ? -Number(variation.id) : Number(productId);
                const name = variation ? productName + ' — ' + variation.name : productName;
                addToCart(key, name, variation ? variation.price : productPrice, productImage);
                if (openCart) toggleCartDrawer();
            }
            document.getElementById('detail-add-cart').addEventListener('click', () => addSelectedProduct(false));
            document.getElementById('detail-order-now').addEventListener('click', () => addSelectedProduct(true));

            if (!selector) return;
            const money = value => '\u09f3' + Number(value).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            selector.addEventListener('change', () => {
                const variation = variations.find(item => String(item.id) === selector.value);
                if (!variation) return;
                document.getElementById('detail-sku').textContent = variation.sku;
                document.getElementById('detail-price').textContent = money(variation.price);
                const mrp = document.getElementById('detail-mrp');
                const saving = document.getElementById('detail-saving');
                mrp.hidden = saving.hidden = variation.mrp <= variation.price;
                mrp.textContent = money(variation.mrp);
                saving.textContent = 'Save ' + (variation.mrp > 0 ? Math.round(Math.max(0, (variation.mrp - variation.price) / variation.mrp * 100)) : 0) + '%';
                const attributes = document.getElementById('detail-attributes');
                attributes.replaceChildren();
                variation.attributes.forEach(pair => {
                    const label = document.createElement('dt');
                    const value = document.createElement('dd');
                    label.className = 'col-5 mb-2';
                    value.className = 'col-7 mb-2';
                    label.textContent = pair.type;
                    value.textContent = pair.value;
                    attributes.append(label, value);
                });
            });
        })();
    </script>
@endsection
