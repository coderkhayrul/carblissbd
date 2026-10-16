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
    <div class="app-ecommerce slider-create">
        <!-- Add Product -->
        <div
            class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-6 row-gap-4">
            <div class="d-flex flex-column justify-content-center">
                <h4 class="mb-1">Add a new Slider</h4>
                <p class="mb-0">Slider placed across your store</p>
            </div>
            <button type="submit" class="btn btn-primary"><i class=""></i> Save Slider</button>
        </div>

        <div class="row">
            <!-- First column-->
            <div class="col-12 col-lg-8">
                <!-- Product Information -->
                <div class="card mb-6">
                    <div class="card-header">
                        <h5 class="card-tile mb-0">Slider information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-6">
                            <label class="form-label" for="name">Name</label>
                            <input type="text" class="form-control" id="name" placeholder="Slider Title"
                                name="name" aria-label="Slider Title" />
                        </div>
                        <div class="row g-4 mb-6">
                            <div class="col-12 col-sm-6">
                                <label class="form-label" for="slug">Slug</label>
                                <input type="number" class="form-control" id="slug" placeholder="Slider Slug"
                                    name="slug" aria-label="Slider SKU" />
                            </div>
                            <div class="col-12 col-sm-6">
                                <label class="form-label" for="tagline">Tagline</label>
                                <input type="text" class="form-control" id="tagline" placeholder="Slider Tagline"
                                    name="tagline" aria-label="Slider Tagline" />
                            </div>
                        </div>
                        <!-- Description -->
                        <div>
                            <label class="mb-1">Short Description (Optional)</label>
                            <textarea name="short_description" id="short_description" class="form-control" rows="4"></textarea>
                        </div>
                    </div>
                </div>
                <!-- /Product Information -->
                <!-- Media -->
                <div class="card mb-6">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 card-title">Slider Image</h5>
                    </div>
                    <div class="card-body">
                        <img class="rounded" id="preview-image" width="865" height="300"
                            src="{{ asset('backend/assets/extra/preview-image.png') }}" alt="Preview Image">
                        <hr>
                        <input type="file" class="form-control" name="image" id="input-Image" accept="image/*">
                    </div>
                </div>
                <!-- /Media -->
            </div>
            <!-- /Second column -->

            <!-- Second column -->
            <div class="col-12 col-lg-4">
                <!-- Pricing Card -->
                <div class="card mb-6">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Extra Info</h5>
                    </div>
                    <div class="card-body">
                        <!-- Button Title -->
                        <div class="mb-6">
                            <label class="form-label" for="button_title">Button Title</label>
                            <input type="text" class="form-control" id="button_title" placeholder="Button Title"
                                name="button_title" aria-label="Button Title" />
                        </div>
                        <!-- Button Icon -->
                        <div class="mb-6">
                            <label class="form-label" for="button_icon">Button Icon</label>
                            <input type="text" class="form-control" id="button_icon" placeholder="Button Icon"
                                name="button_icon" aria-label="Button Icon" />
                        </div>
                        <!-- Button Url -->
                        <div class="mb-6">
                            <label class="form-label" for="button_url">Button Url</label>
                            <input type="text" class="form-control" id="button_url" placeholder="Button Url"
                                name="button_url" aria-label="Button Url" />
                        </div>

                        <!-- Button Status -->
                        <div class="mb-6 col ecommerce-select2-dropdown">
                            <label class="form-label mb-1" for="button_status">Button Status </label>
                            <select name="button_status" id="button_status" class="select2 form-select"
                                data-placeholder="Select Status">
                                <option value="">Select Status</option>
                                <option value="Published">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                        <!-- Button Url -->
                        <div class="mb-6">
                            <label class="form-label" for="orderBy">Order By</label>
                            <input type="number" class="form-control" id="orderBy" placeholder="Order By"
                                name="orderBy" aria-label="Order By" />
                        </div>
                        <!-- Status -->
                        <div class="mb-6 col ecommerce-select2-dropdown">
                            <label class="form-label mb-1" for="status-org">Status </label>
                            <select id="status-org" class="select2 form-select" data-placeholder="Select Status">
                                <option value="">Select Status</option>
                                <option value="Published">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <!-- /Pricing Card -->
            </div>
            <!-- /Second column -->
        </div>
    </div>
@endsection

@push('backend_script')
    <script>
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
