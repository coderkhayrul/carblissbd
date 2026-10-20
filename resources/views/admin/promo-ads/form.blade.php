@extends('admin.layouts.app')
@section('admin-content')
    <form action="{{ $promoAd->exists ? route('admin.promo-ads.update', $promoAd) : route('admin.promo-ads.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if ($promoAd->exists) @method('PUT') @endif
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-5">
            <h4 class="mb-0">{{ $promoAd->exists ? 'Edit Promo Ad' : 'Create Promo Ad' }}</h4>
            <div class="d-flex gap-2">
                <a class="btn btn-label-secondary" href="{{ route('admin.promo-ads.index') }}">Back</a>
                <button class="btn btn-primary" type="submit">{{ $promoAd->exists ? 'Update' : 'Save' }} Promo Ad</button>
            </div>
        </div>
        @if ($errors->any())
            <div class="alert alert-danger" role="alert"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
        @endif
        <div class="row g-4">
            <div class="col-12 col-lg-8">
                <div class="card mb-4">
                    <h5 class="card-header">Promo Information</h5>
                    <div class="card-body">
                        @foreach (['title' => 'Title', 'sub_title' => 'Subtitle', 'button_text' => 'Button Text', 'button_url' => 'Button URL'] as $field => $label)
                            <div class="mb-4">
                                <label class="form-label" for="{{ $field }}">{{ $label }}</label>
                                <input class="form-control" type="{{ $field === 'button_url' ? 'url' : 'text' }}" id="{{ $field }}" name="{{ $field }}" value="{{ old($field, $promoAd->{$field}) }}" maxlength="255" @required(in_array($field, ['title', 'sub_title']))>
                            </div>
                        @endforeach
                        <div class="form-text">Button text and URL are optional. Fill in both to add a button.</div>
                    </div>
                </div>
                <div class="card mb-4">
                    <h5 class="card-header">Promo Image</h5>
                    <div class="card-body">
                        <img class="img-fluid d-block rounded mb-3" id="promoImagePreview" src="{{ $promoAd->image ? asset($promoAd->image) : asset('backend/assets/extra/preview-image.png') }}" alt="Promo image preview" style="max-height: 280px; object-fit: contain;">
                        <input class="form-control" type="file" id="image" name="image" accept="image/jpeg,image/png,image/webp,image/gif">
                        <div class="form-text">Optional. JPG, PNG, WebP or GIF, maximum 5 MB. Leave empty to keep the saved image.</div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="card">
                    <h5 class="card-header">Settings</h5>
                    <div class="card-body">
                        <div class="mb-4">
                            <label class="form-label" for="overlay_color">Overlay Color</label>
                            <input class="form-control" type="text" id="overlay_color" name="overlay_color" value="{{ old('overlay_color', $promoAd->overlay_color) }}" maxlength="9" placeholder="#00000080" pattern="#[0-9a-fA-F]{3}([0-9a-fA-F]{3}([0-9a-fA-F]{2})?)?">
                            <div class="form-text">Optional hex color: #f60, #ff6600 or #ff660080. Displays as a translucent gradient over the image, capped at 70% opacity to keep the image visible.</div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="orderBy">Display Order</label>
                            <input class="form-control" type="number" id="orderBy" name="orderBy" value="{{ old('orderBy', $promoAd->orderBy ?? 0) }}" required min="0" max="2147483647">
                        </div>
                        <label class="form-label" for="status">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="1" @selected(old('status', $promoAd->status) == 1)>Active</option>
                            <option value="0" @selected(old('status', $promoAd->status) == 0)>Inactive</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@push('backend_script')
    <script>
        const promoImageInput = document.getElementById('image');
        const promoImagePreview = document.getElementById('promoImagePreview');
        const originalPromoImage = promoImagePreview.src;
        let promoObjectUrl;
        promoImageInput.addEventListener('change', function() {
            if (promoObjectUrl) URL.revokeObjectURL(promoObjectUrl);
            promoImagePreview.src = originalPromoImage;
            promoImageInput.setCustomValidity('');
            const file = promoImageInput.files[0];
            if (!file) return;
            if (!['image/jpeg', 'image/png', 'image/webp', 'image/gif'].includes(file.type) || file.size > 5 * 1024 * 1024) {
                promoImageInput.value = '';
                promoImageInput.setCustomValidity('Choose a JPG, PNG, WebP or GIF up to 5 MB.');
                promoImageInput.reportValidity();
                return;
            }
            promoObjectUrl = URL.createObjectURL(file);
            promoImagePreview.src = promoObjectUrl;
        });
    </script>
@endpush
