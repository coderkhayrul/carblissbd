@extends('admin.layouts.app')
@section('admin-content')
    <form action="{{ $season->exists ? route('admin.seasons.update', $season) : route('admin.seasons.store') }}" method="POST">
        @csrf
        @if ($season->exists)
            @method('PUT')
        @endif
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-6">
            <h4 class="mb-0">{{ $season->exists ? 'Edit Season' : 'Create Season' }}</h4>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('admin.seasons.index') }}" class="btn btn-label-secondary">Back</a>
                <button type="submit" class="btn btn-primary">{{ $season->exists ? 'Update' : 'Save' }} Season</button>
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
                    <h5 class="card-header">Season Information</h5>
                    <div class="card-body">
                        <div class="mb-4">
                            <label class="form-label" for="name">Name</label>
                            <input class="form-control" type="text" id="name" name="name" value="{{ old('name', $season->name) }}" placeholder="e.g. Summer" required maxlength="255">
                        </div>
                        <label class="form-label" for="slug">Slug</label>
                        <input class="form-control" type="text" id="slug" name="slug" value="{{ old('slug', $season->slug) }}" maxlength="255">
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
                            <option value="1" @selected(old('status', $season->status) == 1)>Active</option>
                            <option value="0" @selected(old('status', $season->status) == 0)>Inactive</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
