@extends('admin.layouts.app')
@section('admin-content')
    <form action="{{ $returnReason->exists ? route('admin.return-reasons.update', $returnReason) : route('admin.return-reasons.store') }}" method="POST">
        @csrf
        @if ($returnReason->exists)
            @method('PUT')
        @endif
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-6">
            <h4 class="mb-0">{{ $returnReason->exists ? 'Edit Return Reason' : 'Create Return Reason' }}</h4>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('admin.return-reasons.index') }}" class="btn btn-label-secondary">Back</a>
                <button type="submit" class="btn btn-primary">{{ $returnReason->exists ? 'Update' : 'Save' }} Return Reason</button>
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
                    <h5 class="card-header">Return Reason Information</h5>
                    <div class="card-body">
                        <div class="mb-4">
                            <label class="form-label" for="title">Title</label>
                            <input class="form-control" type="text" id="title" name="title" value="{{ old('title', $returnReason->title) }}" placeholder="e.g. Damaged Product" required maxlength="255">
                        </div>
                        <label class="form-label" for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="5" maxlength="10000">{{ old('description', $returnReason->description) }}</textarea>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="card">
                    <h5 class="card-header">Settings</h5>
                    <div class="card-body">
                        <div class="mb-4">
                            <label class="form-label" for="serial_no">Serial Number</label>
                            <input class="form-control" type="number" id="serial_no" name="serial_no" min="0" max="2147483647" required value="{{ old('serial_no', $returnReason->serial_no ?? 0) }}">
                        </div>
                        <label class="form-label" for="status">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="1" @selected(old('status', $returnReason->status) == 1)>Active</option>
                            <option value="0" @selected(old('status', $returnReason->status) == 0)>Inactive</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
