@extends('admin.layouts.app')
@section('admin-content')
    <form action="{{ $attribute->exists ? route('admin.attributes.update', $attribute) : route('admin.attributes.store') }}" method="POST">
        @csrf
        @if ($attribute->exists)
            @method('PUT')
        @endif
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-6">
            <h4 class="mb-0">{{ $attribute->exists ? 'Edit Attribute' : 'Create Attribute' }}</h4>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('admin.attributes.index') }}" class="btn btn-label-secondary">Back</a>
                <button type="submit" class="btn btn-primary">{{ $attribute->exists ? 'Update' : 'Save' }} Attribute</button>
            </div>
        </div>
        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif
        <div class="row g-4">
            <div class="col-12 col-lg-8">
                <div class="card">
                    <h5 class="card-header">Attribute Information</h5>
                    <div class="card-body">
                        <div class="mb-4">
                            <label class="form-label" for="attribute_type_id">Attribute Type</label>
                            <select class="form-select" name="attribute_type_id" id="attribute_type_id" required>
                                <option value="">Select Attribute Type</option>
                                @foreach ($attributeTypes as $type)
                                    <option value="{{ $type->id }}" @selected(old('attribute_type_id', $attribute->attribute_type_id) == $type->id)>{{ $type->name }}{{ $type->status ? '' : ' (Inactive)' }}</option>
                                @endforeach
                            </select>
                            @if ($attributeTypes->isEmpty())
                                <div class="form-text">Create an <a href="{{ route('admin.attribute-types.create') }}">Attribute Type</a> first.</div>
                            @endif
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="name">Name</label>
                            <input class="form-control" type="text" id="name" name="name" value="{{ old('name', $attribute->name) }}" placeholder="e.g. Color" required maxlength="255">
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="slug">Slug</label>
                            <input type="text" class="form-control" id="slug" name="slug" maxlength="255" value="{{ old('slug', $attribute->slug) }}">
                            <div class="form-text">Leave empty to generate from the name. Each slug must be unique.</div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="color_code">Color Code</label>
                            <input type="text" class="form-control" id="color_code" name="color_code" value="{{ old('color_code', $attribute->color_code) }}" placeholder="#FF0000" maxlength="7" pattern="#[0-9a-fA-F]{3}([0-9a-fA-F]{3})?">
                            <div class="form-text">Optional. Enter a hex color such as #FFF or #FF0000.</div>
                        </div>
                        <label class="form-label" for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="5" maxlength="255">{{ old('description', $attribute->description) }}</textarea>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="card">
                    <h5 class="card-header">Settings</h5>
                    <div class="card-body">
                        <label class="form-label" for="status">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="1" @selected(old('status', $attribute->status) == 1)>Active</option>
                            <option value="0" @selected(old('status', $attribute->status) == 0)>Inactive</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
