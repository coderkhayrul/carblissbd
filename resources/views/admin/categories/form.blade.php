@extends('admin.layouts.app')
@section('admin-content')
    <form action="{{ $category->exists ? route('admin.categories.update', $category) : route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if ($category->exists)
            @method('PUT')
        @endif
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-6">
            <h4 class="mb-0">{{ $category->exists ? 'Edit Category' : 'Create Category' }}</h4>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.categories.index') }}" class="btn btn-label-secondary">Back</a>
                <button type="submit" class="btn btn-primary">{{ $category->exists ? 'Update Category' : 'Save Category' }}</button>
            </div>
        </div>
        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif
        <div class="row g-4">
            <div class="col-12 col-lg-8">
                <div class="card mb-4">
                    <h5 class="card-header">Category information</h5>
                    <div class="card-body">
                        @foreach (['name' => 'Name', 'slug' => 'Slug', 'icon' => 'Icon Class'] as $field => $label)
                            <div class="mb-4">
                                <label class="form-label" for="{{ $field }}">{{ $label }}</label>
                                <input type="text" class="form-control" name="{{ $field }}" id="{{ $field }}"
                                    value="{{ old($field, $category->{$field}) }}" maxlength="255" @required($field === 'name')>
                                @if ($field === 'slug')<div class="form-text">Leave empty to generate from the name.</div>@endif
                            </div>
                        @endforeach
                        <div class="mb-4">
                            <label class="form-label" for="parent_id">Parent Category</label>
                            <select class="form-select" id="parent_id" name="parent_id">
                                <option value="">None (Main Category)</option>
                                @foreach ($parents as $parent)
                                    <option value="{{ $parent->id }}" @selected(old('parent_id', $category->parent_id) == $parent->id)>{{ $parent->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <label class="form-label" for="description">Description</label>
                        <textarea class="form-control" name="description" id="description" rows="5" maxlength="10000">{{ old('description', $category->description) }}</textarea>
                    </div>
                </div>
                <div class="card mb-4">
                    <h5 class="card-header">Images</h5>
                    <div class="card-body">
                        @foreach (['image' => 'Image', 'banner_image' => 'Banner Image'] as $field => $label)
                            <div class="mb-4">
                                <label class="form-label" for="{{ $field }}">{{ $label }}</label>
                                <img id="preview-{{ $field }}" class="d-block rounded mb-3 img-fluid"
                                    src="{{ $category->{$field} ? asset($category->{$field}) : asset('backend/assets/extra/preview-image.png') }}"
                                    alt="{{ $label }} preview" style="max-height: 200px; object-fit: contain;">
                                <input type="file" class="form-control category-image" name="{{ $field }}" id="{{ $field }}"
                                    accept="image/jpeg,image/png,image/webp,image/gif" data-preview="preview-{{ $field }}">
                                <div class="form-text">Optional. JPG, PNG, WebP or GIF, maximum 5 MB. Leave empty to keep the current image.</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="card mb-4">
                    <h5 class="card-header">Settings</h5>
                    <div class="card-body">
                        <div class="mb-4">
                            <label class="form-label" for="menu_serial">Serial</label>
                            <input type="number" class="form-control" id="menu_serial" name="menu_serial" required min="0" max="2147483647" value="{{ old('menu_serial', $category->menu_serial ?? 0) }}">
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="tab_serial">Tab Serial</label>
                            <input type="number" class="form-control" id="tab_serial" name="tab_serial" required min="0" max="2147483647" value="{{ old('tab_serial', $category->tab_serial ?? 0) }}">
                        </div>
                        @foreach (['status' => 'Status', 'is_highlight' => 'Highlight', 'is_top_ten' => 'Top Ten', 'show_on_menu' => 'Show on Menu', 'show_on_side_menu' => 'Show on Side Menu', 'is_tab' => 'Show as Tab'] as $field => $label)
                            <div class="mb-4">
                                <label class="form-label" for="{{ $field }}">{{ $label }}</label>
                                <select name="{{ $field }}" id="{{ $field }}" class="form-select">
                                    <option value="1" @selected(old($field, $category->{$field} ?? false) == 1)>{{ $field === 'status' ? 'Active' : 'Yes' }}</option>
                                    <option value="0" @selected(old($field, $category->{$field} ?? false) == 0)>{{ $field === 'status' ? 'Inactive' : 'No' }}</option>
                                </select>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="card mb-4">
                    <h5 class="card-header">SEO information</h5>
                    <div class="card-body">
                        @foreach (['meta_title' => 'Meta Title', 'meta_tags' => 'Meta Tags'] as $field => $label)
                            <div class="mb-4">
                                <label class="form-label" for="{{ $field }}">{{ $label }}</label>
                                <input type="text" class="form-control" name="{{ $field }}" id="{{ $field }}" maxlength="255" value="{{ old($field, $category->{$field}) }}">
                            </div>
                        @endforeach
                        <label class="form-label" for="meta_description">Meta Description</label>
                        <textarea class="form-control" id="meta_description" name="meta_description" rows="4" maxlength="10000">{{ old('meta_description', $category->meta_description) }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@push('backend_script')
    <script>
        document.querySelectorAll('.category-image').forEach(function(input) {
            const preview = document.getElementById(input.dataset.preview);
            const original = preview.src;
            let objectUrl;
            input.addEventListener('change', function() {
                if (objectUrl) URL.revokeObjectURL(objectUrl);
                preview.src = original;
                const file = input.files[0];
                input.setCustomValidity('');
                if (!file) return;
                if (!['image/jpeg', 'image/png', 'image/webp', 'image/gif'].includes(file.type) || file.size > 5 * 1024 * 1024) {
                    input.value = '';
                    input.setCustomValidity('Choose a JPG, PNG, WebP or GIF up to 5 MB.');
                    input.reportValidity();
                    return;
                }
                objectUrl = URL.createObjectURL(file);
                preview.src = objectUrl;
            });
        });
    </script>
@endpush
