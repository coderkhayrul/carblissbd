@extends('admin.layouts.app')
@section('admin-content')
    <form action="{{ $attributeType->exists ? route('admin.attribute-types.update', $attributeType) : route('admin.attribute-types.store') }}" method="POST">
        @csrf
        @if ($attributeType->exists)
            @method('PUT')
        @endif
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-6">
            <h4 class="mb-0">{{ $attributeType->exists ? 'Edit Attribute Type' : 'Create Attribute Type' }}</h4>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('admin.attribute-types.index') }}" class="btn btn-label-secondary">Back</a>
                <button type="submit" class="btn btn-primary">{{ $attributeType->exists ? 'Update' : 'Save' }} Attribute Type</button>
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
                    <h5 class="card-header">Attribute Type Information</h5>
                    <div class="card-body">
                        <div class="mb-4">
                            <label class="form-label" for="name">Name</label>
                            <input class="form-control" type="text" id="name" name="name" value="{{ old('name', $attributeType->name) }}" placeholder="e.g. Color" required maxlength="255">
                        </div>
                        <label class="form-label" for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="5" maxlength="255">{{ old('description', $attributeType->description) }}</textarea>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="card">
                    <h5 class="card-header">Settings</h5>
                    <div class="card-body">
                        <label class="form-label" for="status">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="1" @selected(old('status', $attributeType->status) == 1)>Active</option>
                            <option value="0" @selected(old('status', $attributeType->status) == 0)>Inactive</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
