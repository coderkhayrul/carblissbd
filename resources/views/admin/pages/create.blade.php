@extends('admin.layouts.app')
@push('backend_style')
    <style>
        .page-short-description {
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

        #preview-banner {
            display: block;
            width: 100%;
            max-width: 865px;
            height: auto;
            margin-inline: auto;
        }

        .banner-create .row>*,
        .banner-create .ecommerce-select2-dropdown {
            min-width: 0;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('backend') }}/assets/vendor/libs/quill/typography.css" />
    <link rel="stylesheet" href="{{ asset('backend') }}/assets/vendor/libs/highlight/highlight.css" />
    <link rel="stylesheet" href="{{ asset('backend') }}/assets/vendor/libs/quill/katex.css" />
    <link rel="stylesheet" href="{{ asset('backend') }}/assets/vendor/libs/quill/editor.css" />
@endpush
@section('admin-content')
    <div class="app-ecommerce page-create">
        <form action="{{ route('admin.pages.store') }}" method="POST" enctype="multipart/form-data">
            <!-- Add Slider -->
            <div
                class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-6 row-gap-4">
                <div class="d-flex flex-column justify-content-center">
                    <h4 class="mb-1">Add a new Page</h4>
                    <p class="mb-0">Page placed across your store</p>
                </div>
                <div class="d-flex align-content-center flex-wrap gap-4">
                    <div class="d-flex gap-4">
                        <a href="{{ route('admin.pages.index') }}" class="btn btn-label-secondary"><i
                                class="bx bx-arrow-back me-2 fs-5"></i> Back</a>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="bx bx-save me-2 fs-5"></i> Save Page</button>
                </div>
            </div>


            @csrf
            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <div class="mt-2">Please select the Page image again before submitting.</div>
                </div>
            @endif
            <div class="row">
                <!-- First column-->
                <div class="col-12 col-lg-8">
                    <!-- Page Information -->
                    <div class="card mb-6">
                        <div class="card-header">
                            <h5 class="card-tile mb-0">Page information</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-6">
                                <label class="form-label" for="name">Name</label>
                                <input type="text" class="form-control" id="name" placeholder="Page Title"
                                    name="name" value="{{ old('name') }}" required maxlength="255"
                                    aria-label="Slider Title" />
                            </div>
                            <div class="row g-4 mb-6">
                                <div class="col-12 col-sm-6">
                                    <label class="form-label" for="slug">Slug</label>
                                    <input type="text" class="form-control" id="slug" placeholder="Page Slug"
                                        name="slug" value="{{ old('slug') }}" maxlength="255"
                                        aria-label="Slider Slug" />
                                </div>
                                <div class="col-12 col-sm-6">
                                    <label class="form-label" for="bangla_name">Bangla Name</label>
                                    <input type="text" class="form-control" id="bangla_name" placeholder="Bangla Name"
                                        name="bangla_name" value="{{ old('bangla_name') }}" maxlength="255"
                                        aria-label="Bangla Name" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Banner Image -->

                    <div class="card mb-6">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 card-title">Banner Image</h5>
                        </div>
                        <div class="card-body">
                            <img class="rounded" id="preview-banner" width="865" height="300"
                                src="{{ asset('backend/assets/extra/preview-image.png') }}" alt="Preview Image">
                            <hr>
                            <input type="file" class="form-control" name="banner_image" id="input-banner"
                                accept="image/jpeg,image/png,image/webp,image/gif" required>
                            <small class="text-muted">JPG, PNG, WebP or GIF. Maximum 5 MB.</small>
                        </div>
                    </div>
                    <!-- Image Media -->
                    <div class="card mb-6">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 card-title">Image</h5>
                        </div>
                        <div class="card-body">
                            <img class="rounded" id="preview-image" width="865" height="300"
                                src="{{ asset('backend/assets/extra/preview-image.png') }}" alt="Preview Image">
                            <hr>
                            <input type="file" class="form-control" name="image" id="input-Image"
                                accept="image/jpeg,image/png,image/webp,image/gif" required>
                            <small class="text-muted">JPG, PNG, WebP or GIF. Maximum 5 MB.</small>
                        </div>
                    </div>
                    <!-- /Media -->

                    <!-- CONTENT -->
                    <div class="card mb-6">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 card-title">Contents</h5>
                        </div>
                        <div class="card-body">
                            <div id="full-editor"></div>
                        </div>
                    </div>
                </div>
                <!-- /Second column -->

                <!-- Extra Info -->
                <div class="col-12 col-lg-4">
                    <div class="card mb-6">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Extra Info</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-6 col ecommerce-select2-dropdown">
                                <label class="form-label mb-1" for="group_name">Group Name </label>
                                <select name="group_name" id="group_name" class="select2 form-select"
                                    data-placeholder="Select Group Name">
                                    <option value="Customer Care" @selected(old('group_name', 'Customer Care') == 'Customer Care')>Customer Care</option>
                                    <option value="Information" @selected(old('group_name', 'Information') == 'Information')>Information</option>
                                </select>
                            </div>

                            <!-- Button Status -->
                            <div class="mb-6 col ecommerce-select2-dropdown">
                                <label class="form-label mb-1" for="show_in_quick_links">Show Quick Link </label>
                                <select name="show_in_quick_links" id="show_in_quick_links" class="select2 form-select"
                                    data-placeholder="Select Status">
                                    <option value="1" @selected(old('show_in_quick_links', '0') == '1')>Active</option>
                                    <option value="0" @selected(old('show_in_quick_links', '0') == '0')>Inactive</option>
                                </select>
                            </div>
                            <!-- Order By -->
                            <div class="mb-6">
                                <label class="form-label" for="orderBy">Order By</label>
                                <input type="number" class="form-control" id="orderBy" placeholder="Order By"
                                    name="orderBy" value="{{ old('orderBy', 0) }}" min="0" max="2147483647"
                                    aria-label="Order By" />
                            </div>
                            <!-- Status -->
                            <div class="mb-6 col ecommerce-select2-dropdown">
                                <label class="form-label mb-1" for="status-org">Status </label>
                                <select name="status" id="status-org" class="select2 form-select"
                                    data-placeholder="Select Status">
                                    <option value="1" @selected(old('status', '0') == '1')>Active</option>
                                    <option value="0" @selected(old('status', '0') == '0')>Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- SEO -->
                    <div class="card mb-6">
                        <div class="card-header">
                            <h5 class="card-title mb-0">SEO INFO</h5>
                        </div>
                        <div class="card-body">
                            <!-- Button Title -->
                            <div class="mb-6">
                                <label class="form-label" for="meta_title">Meta Title</label>
                                <input type="text" class="form-control" id="meta_title" placeholder="Meta Title"
                                    name="meta_title" value="{{ old('meta_title') }}" maxlength="255"
                                    aria-label="Meta Title" />
                            </div>
                            <!-- Button Icon -->
                            <div class="mb-6">
                                <label class="form-label" for="meta_tags">Meta Tags</label>
                                <input type="text" class="form-control" id="meta_tags" placeholder="Meta Tags"
                                    name="meta_tags" value="{{ old('meta_tags') }}" maxlength="255"
                                    aria-label="Meta Tags" />
                            </div>
                            <!-- Button Url -->
                            <div class="mb-6">
                                <label class="form-label" for="meta_description">Meta Description</label>
                                <textarea name="meta_description" class="form-control" id="meta_description" rows="5"></textarea>
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
    <script src="{{ asset('backend') }}/assets/vendor/libs/quill/katex.js"></script>
    <script src="{{ asset('backend') }}/assets/vendor/libs/highlight/highlight.js"></script>
    <script src="{{ asset('backend') }}/assets/vendor/libs/quill/quill.js"></script>
    <script>
        const PageNameInput = document.getElementById('name');
        const sliderSlugInput = document.getElementById('slug');

        function updateSliderSlug() {
            sliderSlugInput.value = PageNameInput.value
                .normalize('NFKD')
                .toLowerCase()
                .replace(/[\u0300-\u036f]/g, '')
                .replace(/[^\p{L}\p{M}\p{N}]+/gu, '-')
                .replace(/^-+|-+$/g, '');
        }

        PageNameInput.addEventListener('input', updateSliderSlug);
        if (PageNameInput.value && !sliderSlugInput.value) updateSliderSlug();

        // IMAGE SCRIPT


        // IMAGE SCRIPT
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


        // BANNER IMAGE SCRIPT
        const bannerInput = document.getElementById('input-banner');
        const bannerPreview = document.getElementById('preview-banner');
        const defaultPreviewBanner = bannerPreview.src;
        let previewReaderBanner;

        bannerInput.addEventListener('change', function() {
            if (previewReaderBanner) previewReaderBanner.abort();

            const file = this.files[0];
            bannerPreview.src = defaultPreviewBanner;

            if (!file) return;

            if (!file.type.startsWith('image/')) {
                this.value = '';
                return;
            }

            previewReaderBanner = new FileReader();
            previewReaderBanner.onload = function(event) {
                bannerPreview.src = event.target.result;
            };
            previewReaderBanner.onerror = function() {
                bannerInput.value = '';
                bannerPreview.src = defaultPreviewBanner;
            };
            previewReaderBanner.readAsDataURL(file);
        });

        bannerPreview.addEventListener('error', function() {
            if (this.src !== defaultPreviewBanner) {
                this.src = defaultPreviewBanner;
                bannerInput.value = '';
            }
        });

        // FULL EDITOR
        // --------------------------------------------------------------------
        const fullToolbar = [
            [{
                    font: []
                },
                {
                    size: []
                }
            ],
            ['bold', 'italic', 'underline', 'strike'],
            [{
                    color: []
                },
                {
                    background: []
                }
            ],
            [{
                    script: 'super'
                },
                {
                    script: 'sub'
                }
            ],
            [{
                    header: '1'
                },
                {
                    header: '2'
                },
                'blockquote',
                'code-block'
            ],
            [{
                    list: 'ordered'
                },
                {
                    indent: '-1'
                },
                {
                    indent: '+1'
                }
            ],
            [{
                direction: 'rtl'
            }, {
                align: []
            }],
            ['link', 'image', 'video', 'formula'],
            ['clean']
        ];
        const fullEditor = new Quill('#full-editor', {
            bounds: '#full-editor',
            placeholder: 'Type Something...',
            modules: {
                syntax: true,
                toolbar: fullToolbar
            },
            theme: 'snow'
        });
    </script>
@endpush
