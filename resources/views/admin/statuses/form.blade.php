@extends('admin.layouts.app')
@section('admin-content')
    <form action="{{ $status->exists ? route('admin.statuses.update', $status) : route('admin.statuses.store') }}" method="POST">
        @csrf
        @if ($status->exists)
            @method('PUT')
        @endif
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-6">
            <h4 class="mb-0">{{ $status->exists ? 'Edit Status' : 'Create Status' }}</h4>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('admin.statuses.index') }}" class="btn btn-label-secondary">Back</a>
                <button type="submit" class="btn btn-primary">{{ $status->exists ? 'Update' : 'Save' }} Status</button>
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
                    <h5 class="card-header">Status Information</h5>
                    <div class="card-body">
                        <div class="mb-4">
                            <label class="form-label" for="name">Name</label>
                            <input class="form-control" type="text" id="name" name="name" value="{{ old('name', $status->name) }}" placeholder="e.g. Pending" required maxlength="255">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="card">
                    <h5 class="card-header">Settings</h5>
                    <div class="card-body">
                        <label class="form-label" for="status">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="1" @selected(old('status', $status->status) == 1)>Active</option>
                            <option value="0" @selected(old('status', $status->status) == 0)>Inactive</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
