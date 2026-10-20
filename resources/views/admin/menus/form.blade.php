@extends('admin.layouts.app')
@section('admin-content')
    <form action="{{ $menu->exists ? route('admin.menus.update', $menu) : route('admin.menus.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if ($menu->exists)
            @method('PUT')
        @endif
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-6">
            <h4 class="mb-0">{{ $menu->exists ? 'Edit Menu' : 'Create Menu' }}</h4>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('admin.menus.index') }}" class="btn btn-label-secondary">Back</a>
                <button type="submit" class="btn btn-primary">{{ $menu->exists ? 'Update' : 'Save' }} Menu</button>
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
                    <h5 class="card-header">Menu Information</h5>
                    <div class="card-body">
                        <div class="mb-4">
                            <label class="form-label" for="name">Name</label>
                            <input class="form-control" type="text" id="name" name="name" value="{{ old('name', $menu->name) }}" placeholder="e.g. Home" required maxlength="255">
                        </div>
                        <label class="form-label" for="slug">Slug</label>
                        <input class="form-control" type="text" id="slug" name="slug" value="{{ old('slug', $menu->slug) }}" maxlength="255">
                        <div class="form-text">Leave empty to generate from the name.</div>
                        <div class="mt-4">
                            <label class="form-label" for="parent_id">Parent Menu</label>
                            <select class="form-select" name="parent_id" id="parent_id">
                                <option value="">None (Main Menu)</option>
                                @foreach ($parents as $parent)
                                    <option value="{{ $parent->id }}" @selected(old('parent_id', $menu->parent_id) == $parent->id)>{{ $parent->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mt-4">
                            <label class="form-label" for="icon">Icon Class</label>
                            <input class="form-control" type="text" name="icon" id="icon" maxlength="255" value="{{ old('icon', $menu->icon) }}" placeholder="bx bx-home">
                        </div>
                        <div class="mt-4">
                            <label class="form-label" for="image">Image</label>
                            @if ($menu->image)
                                <img src="{{ asset($menu->image) }}" alt="Menu image" class="img-fluid d-block rounded mb-3" style="max-height: 160px;">
                            @endif
                            <input class="form-control" type="file" name="image" id="image" accept="image/jpeg,image/png,image/webp,image/gif">
                            <div class="form-text">Optional. JPG, PNG, WebP or GIF, maximum 5 MB. Leave empty to keep the current image.</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="card">
                    <h5 class="card-header">Settings</h5>
                    <div class="card-body">
                        <div class="mb-4">
                            <label class="form-label" for="serial_no">Serial Number</label>
                            <input class="form-control" type="number" id="serial_no" name="serial_no" min="0" max="2147483647" required value="{{ old('serial_no', $menu->serial_no ?? 0) }}">
                        </div>
                        <label class="form-label" for="status">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="1" @selected(old('status', $menu->status) == 1)>Active</option>
                            <option value="0" @selected(old('status', $menu->status) == 0)>Inactive</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
