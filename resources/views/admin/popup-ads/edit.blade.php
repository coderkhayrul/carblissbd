@extends('admin.layouts.app')
@section('admin-content')
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-6">
        <h4 class="mb-0">Edit PopUp Ads</h4>
        <form action="{{ route('admin.popup-ads.status.update') }}" method="POST">
            @csrf
            @method('PATCH')
            <input type="hidden" name="status" value="{{ $popup->status ? 0 : 1 }}">
            <button type="submit" class="btn btn-label-{{ $popup->status ? 'danger' : 'success' }}">
                {{ $popup->status ? 'Turn Off' : 'Turn On' }}
            </button>
        </form>
    </div>
    @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            <ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif
    <form action="{{ route('admin.popup-ads.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row g-4">
            <div class="col-12 col-lg-8">
                <div class="card mb-4">
                    <h5 class="card-header">Popup Information</h5>
                    <div class="card-body">
                        <div class="mb-4">
                            <label class="form-label" for="title">Title</label>
                            <input class="form-control" id="title" name="title" maxlength="255" value="{{ old('title', $popup->title) }}">
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="url">URL</label>
                            <input class="form-control" type="url" id="url" name="url" maxlength="255" placeholder="https://example.com" value="{{ old('url', $popup->url) }}">
                            <div class="form-text">Destination link, or video URL when video mode is selected.</div>
                        </div>
                        <label class="form-label" for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="5" maxlength="10000">{{ old('description', $popup->description) }}</textarea>
                    </div>
                </div>
                <div class="card mb-4">
                    <h5 class="card-header">Image</h5>
                    <div class="card-body">
                        <img id="popupPreview" class="img-fluid d-block rounded mb-4" style="max-height: 320px; object-fit: contain;"
                            src="{{ $popup->image ? asset($popup->image) : asset('backend/assets/extra/preview-image.png') }}" alt="Popup image preview">
                        <input class="form-control" type="file" id="image" name="image" accept="image/jpeg,image/png,image/webp,image/gif">
                        <div class="form-text">Optional. JPG, PNG, WebP or GIF, maximum 5 MB. Leave empty to keep the current image.</div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="card">
                    <h5 class="card-header">Settings <span class="badge bg-label-{{ $popup->status ? 'success' : 'secondary' }} ms-2">{{ $popup->status ? 'Active' : 'Inactive' }}</span></h5>
                    <div class="card-body">
                        <div class="mb-4">
                            <label class="form-label" for="is_video">Content Type</label>
                            <select class="form-select" id="is_video" name="is_video">
                                <option value="0" @selected(old('is_video', $popup->is_video) == 0)>Image / Text</option>
                                <option value="1" @selected(old('is_video', $popup->is_video) == 1)>Video</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="status">Status</label>
                            <select class="form-select" name="status" id="status">
                                <option value="1" @selected(old('status', $popup->status) == 1)>Active</option>
                                <option value="0" @selected(old('status', $popup->status) == 0)>Inactive</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Save Changes</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@push('backend_script')
    <script>
        const popupImage = document.getElementById('image');
        const popupPreview = document.getElementById('popupPreview');
        const originalPopupImage = popupPreview.src;
        let popupImageUrl;
        popupImage.addEventListener('change', function() {
            if (popupImageUrl) URL.revokeObjectURL(popupImageUrl);
            popupPreview.src = originalPopupImage;
            popupImage.setCustomValidity('');
            const file = popupImage.files[0];
            if (!file) return;
            if (!['image/jpeg', 'image/png', 'image/webp', 'image/gif'].includes(file.type) || file.size > 5 * 1024 * 1024) {
                popupImage.value = '';
                popupImage.setCustomValidity('Choose a JPG, PNG, WebP or GIF up to 5 MB.');
                popupImage.reportValidity();
                return;
            }
            popupImageUrl = URL.createObjectURL(file);
            popupPreview.src = popupImageUrl;
        });
        const popupType = document.getElementById('is_video');
        const popupUrl = document.getElementById('url');
        function updatePopupUrlRequirement() { popupUrl.required = popupType.value === '1'; }
        popupType.addEventListener('change', updatePopupUrlRequirement);
        updatePopupUrlRequirement();
    </script>
@endpush
