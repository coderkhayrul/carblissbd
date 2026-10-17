@extends('admin.layouts.app')
@push('backend_style')
    <style>
        .slide-short-description {
            width: 260px;
            max-width: 260px;
            white-space: normal;
            overflow-wrap: anywhere;
        }

        #preview-image {
            display: block;
            width: 100%;
            max-width: 865px;
            height: auto;
            margin-inline: auto;
        }

        .slider-create .row>*,
        .slider-create .ecommerce-select2-dropdown {
            min-width: 0;
        }
    </style>
@endpush
@section('admin-content')
    <div class="app-ecommerce slider-edit">
        <form action="{{ route('admin.sliders.update', $slider) }}" method="POST" enctype="multipart/form-data">
            <!-- Add Slider -->
            <div
                class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-6 row-gap-4">
                <div class="d-flex flex-column justify-content-center">
                    <h4 class="mb-1">Edit Slider</h4>
                    <p class="mb-0">Slider placed across your Edit</p>
                </div>
                <div class="d-flex align-content-center flex-wrap gap-4">
                    <div class="d-flex gap-4">
                        <a href="{{ route('admin.sliders.index') }}" class="btn btn-label-secondary"><i
                                class="bx bx-arrow-back me-2 fs-5"></i> Back</a>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="bx bx-sync me-2 fs-5"></i> Update
                        Slider</button>
                </div>
            </div>


            @csrf
            @method('PUT')
            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <div class="mt-2">If replacing the image, please select the new image again.</div>
                </div>
            @endif
            <div class="row">
                <!-- First column-->
                <div class="col-12 col-lg-8">
                    <!-- Slider Information -->
                    <div class="card mb-6">
                        <div class="card-header">
                            <h5 class="card-tile mb-0">Slider information</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-6">
                                <label class="form-label" for="name">Name</label>
                                <input type="text" class="form-control" id="name" placeholder="Slider Title"
                                    name="name" value="{{ old('name', $slider->name) }}" required maxlength="255"
                                    aria-label="Slider Title" />
                            </div>
                            <div class="row g-4 mb-6">
                                <div class="col-12 col-sm-6">
                                    <label class="form-label" for="slug">Slug</label>
                                    <input type="text" class="form-control" id="slug" placeholder="Slider Slug"
                                        name="slug" value="{{ old('slug', $slider->slug) }}" maxlength="255"
                                        aria-label="Slider Slug" />
                                </div>
                                <div class="col-12 col-sm-6">
                                    <label class="form-label" for="tagline">Tagline</label>
                                    <input type="text" class="form-control" id="tagline" placeholder="Slider Tagline"
                                        name="tagline" value="{{ old('tagline', $slider->tagline) }}" maxlength="255"
                                        aria-label="Slider Tagline" />
                                </div>
                            </div>
                            <!-- Description -->
                            <div>
                                <label class="mb-1">Short Description (Optional)</label>
                                <textarea name="short_description" id="short_description" class="form-control" rows="4">{{ old('short_description', $slider->short_description) }}</textarea>
                            </div>
                        </div>
                    </div>
                    <!-- Media -->
                    <div class="card mb-6">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 card-title">Slider Image</h5>
                        </div>
                        <div class="card-body">
                            <img class="rounded" id="preview-image" width="865" height="300"
                                src="{{ $slider->image ? asset($slider->image) : asset('backend/assets/extra/preview-image.png') }}"
                                alt="Preview Image">
                            <hr>
                            <input type="file" class="form-control" name="image" id="input-Image"
                                accept="image/jpeg,image/png,image/webp,image/gif">
                            <small class="text-muted">JPG, PNG, WebP or GIF. Maximum 5 MB. Leave empty to keep the current
                                image.</small>
                        </div>
                    </div>
                    <!-- /Media -->
                </div>
                <!-- /Second column -->

                <!-- Extra Info -->
                <div class="col-12 col-lg-4">
                    <div class="card mb-6">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Extra Info</h5>
                        </div>
                        <div class="card-body">
                            <!-- Button Title -->
                            <div class="mb-6">
                                <label class="form-label" for="button_title">Button Title</label>
                                <input type="text" class="form-control" id="button_title" placeholder="Button Title"
                                    name="button_title" value="{{ old('button_title', $slider->button_title) }}"
                                    maxlength="255" aria-label="Button Title" />
                            </div>
                            <!-- Button Icon -->
                            <div class="mb-6">
                                <label class="form-label" for="button_icon">Button Icon</label>
                                <input type="text" class="form-control" id="button_icon" placeholder="Button Icon"
                                    name="button_icon" value="{{ old('button_icon', $slider->button_icon) }}"
                                    maxlength="255" aria-label="Button Icon" />
                            </div>
                            <!-- Button Url -->
                            <div class="mb-6">
                                <label class="form-label" for="button_url">Button Url</label>
                                <input type="text" class="form-control" id="button_url" placeholder="Button Url"
                                    name="button_url" value="{{ old('button_url', $slider->button_url) }}"
                                    maxlength="255" aria-label="Button Url" />
                            </div>

                            <!-- Button Status -->
                            <div class="mb-6 col ecommerce-select2-dropdown">
                                <label class="form-label mb-1" for="button_status">Button Status </label>
                                <select name="button_status" id="button_status" class="select2 form-select"
                                    data-placeholder="Select Status">
                                    <option value="1" @selected(old('button_status', $slider->button_status) == '1')>Active</option>
                                    <option value="0" @selected(old('button_status', $slider->button_status) == '0')>Inactive</option>
                                </select>
                            </div>
                            <!-- Order By -->
                            <div class="mb-6">
                                <label class="form-label" for="orderBy">Order By</label>
                                <input type="number" class="form-control" id="orderBy" placeholder="Order By"
                                    name="orderBy" value="{{ old('orderBy', $slider->orderBy) }}" min="0"
                                    max="2147483647" aria-label="Order By" />
                            </div>
                            <!-- Status -->
                            <div class="mb-6 col ecommerce-select2-dropdown">
                                <label class="form-label mb-1" for="status-org">Status </label>
                                <select name="status" id="status-org" class="select2 form-select"
                                    data-placeholder="Select Status">
                                    <option value="1" @selected(old('status', $slider->status) == '1')>Active</option>
                                    <option value="0" @selected(old('status', $slider->status) == '0')>Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- /Extra Info -->
                </div>
                <!-- /Second column -->
            </div>
        </form>
    </div>
@endsection

@push('backend_script')
    <script>
        const sliderNameInput = document.getElementById('name');
        const sliderSlugInput = document.getElementById('slug');

        function updateSliderSlug() {
            sliderSlugInput.value = sliderNameInput.value
                .normalize('NFKD')
                .toLowerCase()
                .replace(/[\u0300-\u036f]/g, '')
                .replace(/[^\p{L}\p{M}\p{N}]+/gu, '-')
                .replace(/^-+|-+$/g, '');
        }

        sliderNameInput.addEventListener('input', updateSliderSlug);
        if (sliderNameInput.value && !sliderSlugInput.value) updateSliderSlug();

        const imageInput = document.getElementById('input-Image');
        const imagePreview = document.getElementById('preview-image');
        const defaultPreview = imagePreview.src;
        let previewReader;

        imageInput.addEventListener('change', function() {
            if (previewReader) previewReader.abort();

            const file = this.files[0];
            imagePreview.src = defaultPreview;

            if (!file) return;

            if (!file.type.startsWith('image/')) {
                this.value = '';
                return;
            }

            previewReader = new FileReader();
            previewReader.onload = function(event) {
                imagePreview.src = event.target.result;
            };
            previewReader.onerror = function() {
                imageInput.value = '';
                imagePreview.src = defaultPreview;
            };
            previewReader.readAsDataURL(file);
        });

        imagePreview.addEventListener('error', function() {
            if (this.src !== defaultPreview) {
                this.src = defaultPreview;
                imageInput.value = '';
            }
        });
    </script>
@endpush
