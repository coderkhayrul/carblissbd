@extends('admin.layouts.app')
@section('admin-content')
    <form action="{{ $coupon->exists ? route('admin.coupons.update', $coupon) : route('admin.coupons.store') }}" method="POST">
        @csrf
        @if ($coupon->exists)
            @method('PUT')
        @endif
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-6">
            <h4 class="mb-0">{{ $coupon->exists ? 'Edit Coupon' : 'Create Coupon' }}</h4>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('admin.coupons.index') }}" class="btn btn-label-secondary">Back</a>
                <button type="submit" class="btn btn-primary">{{ $coupon->exists ? 'Update' : 'Save' }} Coupon</button>
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
                    <h5 class="card-header">Coupon Information</h5>
                    <div class="card-body">
                        <div class="mb-4">
                            <label class="form-label" for="name">Name</label>
                            <input class="form-control" type="text" id="name" name="name" value="{{ old('name', $coupon->name) }}" placeholder="e.g. Welcome Discount" required maxlength="255">
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="code">Coupon Code</label>
                            <input class="form-control" type="text" id="code" name="code" value="{{ old('code', $coupon->code) }}" maxlength="255" required placeholder="WELCOME10" pattern="[A-Za-z0-9_-]+">
                            <div class="form-text">Letters, numbers, hyphens and underscores. Saved in uppercase.</div>
                        </div>
                        <div class="row g-4 mb-4">
                            <div class="col-12 col-sm-6">
                                <label class="form-label" for="discount_type">Discount Type</label>
                                <select class="form-select" name="discount_type" id="discount_type" required>
                                    @foreach (['Percentage', 'Fixed'] as $type)
                                        <option value="{{ $type }}" @selected(old('discount_type', $coupon->discount_type) === $type)>{{ $type }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-sm-6">
                                <label class="form-label" for="amount">Discount Amount</label>
                                <input class="form-control" type="number" name="amount" id="amount" value="{{ old('amount', $coupon->amount) }}" min="0.01" step="0.01" required>
                                <div class="form-text">Percentage: up to 100%. Fixed: amount in BDT.</div>
                            </div>
                        </div>
                        <div class="row g-4">
                            <div class="col-12 col-sm-6">
                                <label class="form-label" for="start_date">Start Date</label>
                                <input class="form-control" type="date" id="start_date" name="start_date" value="{{ old('start_date', $coupon->start_date?->format('Y-m-d')) }}">
                            </div>
                            <div class="col-12 col-sm-6">
                                <label class="form-label" for="end_date">End Date</label>
                                <input class="form-control" type="date" id="end_date" name="end_date" value="{{ old('end_date', $coupon->end_date?->format('Y-m-d')) }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="card">
                    <h5 class="card-header">Settings</h5>
                    <div class="card-body">
                        <div class="mb-4">
                            <label class="form-label" for="use_type">Use Type</label>
                            <select class="form-select" name="use_type" id="use_type">
                                @foreach (['Once', 'Multiple'] as $type)
                                    <option value="{{ $type }}" @selected(old('use_type', $coupon->use_type) === $type)>{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="is_highlighted">Highlighted</label>
                            <select class="form-select" name="is_highlighted" id="is_highlighted">
                                <option value="1" @selected(old('is_highlighted', $coupon->is_highlighted) == 1)>Yes</option>
                                <option value="0" @selected(old('is_highlighted', $coupon->is_highlighted) == 0)>No</option>
                            </select>
                        </div>
                        <label class="form-label" for="status">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="1" @selected(old('status', $coupon->status) == 1)>Active</option>
                            <option value="0" @selected(old('status', $coupon->status) == 0)>Inactive</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@push('backend_script')
    <script>
        const discountType = document.getElementById('discount_type');
        const discountAmount = document.getElementById('amount');
        const startDate = document.getElementById('start_date');
        const endDate = document.getElementById('end_date');
        function updateCouponLimits() {
            discountAmount.max = discountType.value === 'Percentage' ? '100' : '99999999.99';
            endDate.min = startDate.value;
        }
        discountType.addEventListener('change', updateCouponLimits);
        startDate.addEventListener('change', updateCouponLimits);
        updateCouponLimits();
    </script>
@endpush
