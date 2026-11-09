@extends('admin.layouts.app')
@section('admin-content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4"><h4 class="mb-0">Assign Pathao — {{ $order->order_no }}</h4><a class="btn btn-label-secondary" href="{{ route('admin.orders.show', $order) }}">Back to Order</a></div>
@if ($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
@if ($order->pathaoShipment)
    @include('admin.orders.pathao')
@else
<div class="row g-4"><div class="col-12 col-xl-8"><div class="card"><div class="card-body">
    <form method="POST" action="{{ route('admin.pathao.book', $order) }}" data-pathao-booking data-price-url="{{ route('admin.pathao.price', $order) }}">@csrf
        <label class="form-label w-100">Pickup Store<select class="form-select" name="store_id" required><option value="">Select active pickup store</option>@foreach ($settings->stores ?? [] as $store)@if ((int) ($store['is_active'] ?? 0) === 1)<option value="{{ $store['store_id'] }}" @selected((string) old('store_id', $settings->store_id) === (string) $store['store_id'])>{{ $store['store_name'] ?? $store['store_id'] }}</option>@endif @endforeach</select></label>
        <p class="small">No store? <a href="{{ route('admin.pathao.settings') }}">Configure Pathao / Load Stores</a></p>
        <div class="row g-3 my-2"><div class="col-12 col-md-6"><label class="form-label">Delivery Type<select name="delivery_type" class="form-select"><option value="48" @selected((int) old('delivery_type', $settings->delivery_type) === 48)>Normal Delivery</option><option value="12" @selected((int) old('delivery_type', $settings->delivery_type) === 12)>On Demand Delivery</option></select></label></div>
        <div class="col-12 col-md-6"><label class="form-label">Item Type<select class="form-select" name="item_type"><option value="2" @selected((int) old('item_type', $settings->item_type) === 2)>Parcel</option><option value="1" @selected((int) old('item_type', $settings->item_type) === 1)>Document</option></select></label></div>
        <div class="col-12 col-md-6"><label class="form-label">Total Weight (kg)<input class="form-control" type="number" name="item_weight" min="0.5" max="10" step="0.01" value="{{ old('item_weight', $settings->item_weight) }}" required></label></div>
        <div class="col-12 col-md-6"><label class="form-label">Parcel Quantity<input class="form-control" type="number" name="item_quantity" min="1" max="99999" value="{{ old('item_quantity', 1) }}" required></label></div></div>
        <h6 class="mt-3">Recipient Location (optional for booking)</h6><p class="small">If omitted, Pathao resolves location from the address. City and zone are required for a price quote.</p>
        @include('admin.partials.pathao-locations')
        <label class="form-label w-100 mt-3">Special Instructions<textarea name="special_instruction" class="form-control" rows="3" maxlength="1000">{{ old('special_instruction', $order->customerInfo?->order_note) }}</textarea></label>
        <div class="d-flex flex-wrap gap-2 mt-3"><button class="btn btn-label-primary" type="button" data-pathao-price>Calculate Courier Charge</button><button class="btn btn-primary" type="submit">Book Pathao Parcel</button></div>
        <p class="small mt-3 mb-0" role="status" data-pathao-price-result></p>
    </form>
</div></div></div><div class="col-12 col-xl-4"><div class="card"><h5 class="card-header">Recipient &amp; Collection</h5><div class="card-body"><h6>{{ $order->customerInfo?->name }}</h6><p>{{ $order->customerInfo?->phone }}</p><p style="white-space:pre-line;overflow-wrap:anywhere">{{ $order->customerInfo?->address }}</p><p><strong>Collect: BDT {{ number_format(max(0, (float) $order->due_amount), 2) }}</strong></p><p class="small">Full order amount already paid? COD will be zero. Collection must be a whole taka amount. Use Edit Order to correct the recipient before booking.</p><p class="small">Environment: <strong>{{ ucfirst($settings->environment) }}</strong>. Submitting creates a parcel in this environment.</p><p class="small mb-0">If a booking response is interrupted, do not submit a new booking. Check this invoice in Pathao and recover the consignment ID from the order view.</p></div></div></div></div>
@endif
@endsection
@push('backend_script')<script src="{{ asset('backend/assets/js/pathao-courier.js') }}" defer></script>@endpush
