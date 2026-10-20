@extends('admin.layouts.app')
@push('backend_style')
<style>
    .product-tabs { flex-wrap: nowrap; overflow-x: auto; white-space: nowrap; }
    .product-tabs .nav-link { white-space: nowrap; }
    .variation-card { min-width: 0; }
    .product-preview { max-width: 100%; max-height: 200px; object-fit: contain; }
    .product-editor .row > * { min-width: 0; }
</style>
@endpush
@section('admin-content')
    @php
        $initialVariations = old('variations', $product->variations->map(function ($variation) {
            return [
                'id' => $variation->id, 'name' => $variation->name, 'sku' => $variation->sku,
                'purchase_price' => $variation->purchase_price, 'sale_price' => $variation->sale_price,
                'bangla_sale_price' => $variation->bangla_sale_price, 'mrp_price' => $variation->mrp_price,
                'status' => (int) $variation->status,
                'attributes' => $variation->variationAttributes->map(fn ($pair) => [
                    'attribute_type_id' => $pair->attribute_type_id, 'attribute_id' => $pair->attribute_id,
                ])->all(),
            ];
        })->all());
        $tabs = ['basic' => '1. Basic Info', 'pricing' => '2. Pricing', 'media' => '3. Images & Video',
            'content' => '4. Descriptions', 'variations' => '5. Variations', 'landing' => '6. Landing Page', 'seo' => '7. SEO'];
    @endphp
    <form class="product-editor" id="productForm" action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if ($product->exists) @method('PUT') @endif
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-5">
            <div><h4 class="mb-1">{{ $product->exists ? 'Edit Product' : 'Create Product' }}</h4><p class="text-muted mb-0">Complete each tab, then save the product.</p></div>
            <div class="d-flex gap-2">
                <a class="btn btn-label-secondary" href="{{ route('admin.products.index') }}">Back to List</a>
                <button class="btn btn-primary" type="submit">Save Product</button>
            </div>
        </div>
        @if ($errors->any())
            <div class="alert alert-danger" role="alert"><strong>Please correct these fields:</strong><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul><div class="mt-2">Please reselect any images before saving again.</div></div>
        @endif
        <div class="card">
            <div class="card-header border-bottom p-3">
                <ul class="nav nav-pills product-tabs gap-2" role="tablist">
                    @foreach ($tabs as $key => $label)
                        <li class="nav-item" role="presentation"><button class="nav-link {{ $loop->first ? 'active' : '' }}" type="button" id="tab-{{ $key }}" data-bs-toggle="tab" data-bs-target="#panel-{{ $key }}" role="tab" aria-controls="panel-{{ $key }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">{{ $label }}</button></li>
                    @endforeach
                </ul>
            </div>
            <div class="card-body pt-5 tab-content">
                <div class="tab-pane fade show active" id="panel-basic" role="tabpanel" aria-labelledby="tab-basic">
                    <h5>Product identity & organization</h5>
                    <p class="text-muted">Name, SKU, category and brand identify your product. SKU and slug must be unique.</p>
                    <div class="row">
                        @include('admin.products.field', ['field' => 'name', 'label' => 'Product Name', 'required' => true])
                        @include('admin.products.field', ['field' => 'bangla_name', 'label' => 'Bangla Name'])
                        @include('admin.products.field', ['field' => 'sku', 'label' => 'Product SKU', 'required' => true])
                        @include('admin.products.field', ['field' => 'slug', 'label' => 'Slug', 'help' => 'Leave empty to generate from the name.'])
                        @foreach (['category_id' => ['Category', $categories], 'brand_id' => ['Brand', $brands]] as $field => [$label, $options])
                            <div class="col-12 col-md-6 mb-4">
                                <label class="form-label" for="{{ $field }}">{{ $label }}</label>
                                <select class="form-select" name="{{ $field }}" id="{{ $field }}" required>
                                    <option value="">Select {{ $label }}</option>
                                    @foreach ($options as $option)<option value="{{ $option->id }}" @selected(old($field, $product->{$field}) == $option->id)>{{ $option->name }}{{ $option->status ? '' : ' (Inactive)' }}</option>@endforeach
                                </select>
                            </div>
                        @endforeach
                        @include('admin.products.field', ['field' => 'barcode', 'label' => 'Barcode'])
                        <div class="col-12 col-md-6 mb-4">
                            <label class="form-label" for="tags">Tags</label>
                            <select class="form-select" id="tags" name="tags[]" multiple size="5">
                                @foreach ($tags as $tag)<option value="{{ $tag->id }}" @selected(in_array($tag->id, old('tags', $product->tags->pluck('id')->all()) ?? []))>{{ $tag->name }}</option>@endforeach
                            </select>
                            <div class="form-text">Optional. Select multiple tags with Ctrl / Command, or tap on mobile.</div>
                        </div>
                        @foreach (['status' => 'Active', 'publish' => 'Published', 'is_refundable' => 'Refundable', 'is_new_arrival' => 'New Arrival'] as $field => $label)
                            @include('admin.products.field', ['field' => $field, 'label' => $label, 'type' => 'boolean'])
                        @endforeach
                    </div>
                </div>
                <div class="tab-pane fade" id="panel-pricing" role="tabpanel" aria-labelledby="tab-pricing">
                    <h5>Base prices (BDT)</h5><p class="text-muted">Set base prices for the product. Each variation has its own prices in the Variations tab.</p>
                    <div class="row">
                        @foreach (['purchase_price' => 'Purchase Price', 'sale_price' => 'Sale Price', 'bangla_sale_price' => 'Bangla Sale Price', 'mrp_price' => 'MRP Price'] as $field => $label)
                            @include('admin.products.field', ['field' => $field, 'label' => $label, 'type' => 'number', 'required' => true])
                        @endforeach
                    </div>
                </div>
                <div class="tab-pane fade" id="panel-media" role="tabpanel" aria-labelledby="tab-media">
                    <h5>Images & video</h5><p class="text-muted">Images are optional. Leave an upload empty to keep the saved image.</p>
                    <div class="row">
                        @foreach (['thumbnail_image' => 'Thumbnail Image', 'size_chart_image' => 'Size Chart Image'] as $field => $label)
                            <div class="col-12 col-md-6 mb-4">
                                <label class="form-label" for="{{ $field }}">{{ $label }}</label>
                                <img class="product-preview d-block rounded mb-3" id="preview-{{ $field }}" src="{{ $product->{$field} ? asset($product->{$field}) : asset('backend/assets/extra/preview-image.png') }}" alt="{{ $label }} preview">
                                <input class="form-control product-image" type="file" id="{{ $field }}" name="{{ $field }}" data-preview="preview-{{ $field }}" accept="image/jpeg,image/png,image/webp,image/gif">
                                <div class="form-text">JPG, PNG, WebP or GIF, maximum 5 MB.</div>
                            </div>
                        @endforeach
                        @include('admin.products.field', ['field' => 'video_link', 'label' => 'Video URL', 'type' => 'url'])
                    </div>
                </div>
                <div class="tab-pane fade" id="panel-content" role="tabpanel" aria-labelledby="tab-content">
                    <h5>English & Bangla descriptions</h5><p class="text-muted">Use the matching fields for each language.</p>
                    <div class="row">
                        @foreach (['features' => 'Features', 'bangla_features' => 'Bangla Features', 'short_description' => 'Short Description', 'bangla_short_description' => 'Bangla Short Description', 'description' => 'Full Description', 'bangla_description' => 'Bangla Full Description'] as $field => $label)
                            @include('admin.products.field', ['field' => $field, 'label' => $label, 'type' => 'textarea'])
                        @endforeach
                    </div>
                </div>
                <div class="tab-pane fade" id="panel-variations" role="tabpanel" aria-labelledby="tab-variations">
                    <h5>Product variations</h5>
                    <p class="text-muted">Example: Color → Black and Size → Large form one variation. Give each combination a unique SKU and its own prices.</p>
                    <div class="row">@include('admin.products.field', ['field' => 'is_variation', 'label' => 'Does this product have variations?', 'type' => 'boolean'])</div>
                    <div id="variationEditor">
                        @if ($attributeTypes->isEmpty() || $attributes->isEmpty())
                            <div class="alert alert-info">Create <a href="{{ route('admin.attribute-types.index') }}">Attribute Types</a> and <a href="{{ route('admin.attributes.index') }}">Attributes</a> before adding variations.</div>
                        @endif
                        <div id="variationRows"></div>
                        <button class="btn btn-label-primary" type="button" id="addVariation">+ Add Variation</button>
                        <div class="form-text mt-3">Turning variations off removes the saved variations when you save. Variations linked to other records cannot be removed.</div>
                    </div>
                </div>
                <div class="tab-pane fade" id="panel-landing" role="tabpanel" aria-labelledby="tab-landing">
                    <h5>Landing page content</h5><p class="text-muted">Optional promotional content. Text fields below allow up to 255 characters, matching the database.</p>
                    <div class="row">
                        @foreach (['landing_page_title' => 'Landing Page Title', 'landing_page_feature' => 'Landing Page Feature', 'landing_short_description' => 'Landing Short Description', 'landing_long_description' => 'Landing Long Description'] as $field => $label)
                            @include('admin.products.field', ['field' => $field, 'label' => $label, 'type' => 'textarea', 'max' => 255])
                        @endforeach
                        @include('admin.products.field', ['field' => 'landing_youtube_link', 'label' => 'Landing YouTube URL', 'type' => 'url', 'max' => 10000])
                    </div>
                </div>
                <div class="tab-pane fade" id="panel-seo" role="tabpanel" aria-labelledby="tab-seo">
                    <h5>Search engine information</h5>
                    <div class="row">
                        @include('admin.products.field', ['field' => 'meta_title', 'label' => 'Meta Title'])
                        @include('admin.products.field', ['field' => 'meta_keywords', 'label' => 'Meta Keywords'])
                        @include('admin.products.field', ['field' => 'meta_description', 'label' => 'Meta Description', 'type' => 'textarea', 'max' => 255, 'wide' => true])
                    </div>
                </div>
                <div class="d-flex justify-content-between flex-wrap gap-3 border-top pt-4 mt-4">
                    <div class="d-flex gap-2"><button class="btn btn-label-secondary" type="button" id="previousTab">Previous</button><button class="btn btn-label-primary" type="button" id="nextTab">Next Tab</button></div>
                    <button class="btn btn-primary" type="submit">Save Product</button>
                </div>
            </div>
        </div>
    </form>
@endsection
@push('backend_script')
    <script>
        window.productFormData = {
            types: @json($attributeTypes), attributes: @json($attributes),
            variations: @json($initialVariations), errors: @json($errors->keys())
        };
    </script>
    <script src="{{ asset('backend/assets/js/product-form.js') }}"></script>
@endpush
