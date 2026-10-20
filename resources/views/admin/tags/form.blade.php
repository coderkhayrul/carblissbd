@extends('admin.layouts.app')
@section('admin-content')
    <form action="{{ $tag->exists ? route('admin.tags.update', $tag) : route('admin.tags.store') }}" method="POST">
        @csrf
        @if ($tag->exists)
            @method('PUT')
        @endif
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-6">
            <h4 class="mb-0">{{ $tag->exists ? 'Edit Tag' : 'Create Tag' }}</h4>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('admin.tags.index') }}" class="btn btn-label-secondary">Back</a>
                <button type="submit" class="btn btn-primary">{{ $tag->exists ? 'Update' : 'Save' }} Tag</button>
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
                    <h5 class="card-header">Tag Information</h5>
                    <div class="card-body">
                        <div class="mb-4">
                            <label class="form-label" for="name">Name</label>
                            <input class="form-control" type="text" id="name" name="name" value="{{ old('name', $tag->name) }}" placeholder="e.g. New Arrival" required maxlength="255">
                        </div>
                        <label class="form-label" for="slug">Slug</label>
                        <input class="form-control" type="text" id="slug" name="slug" value="{{ old('slug', $tag->slug) }}" maxlength="255">
                        <div class="form-text">Leave empty to generate from the name.</div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="card">
                    <h5 class="card-header">Settings</h5>
                    <div class="card-body">
                        <label class="form-label" for="status">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="1" @selected(old('status', $tag->status) == 1)>Active</option>
                            <option value="0" @selected(old('status', $tag->status) == 0)>Inactive</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
