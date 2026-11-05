@extends('admin.layouts.app')
@section('admin-content')
    @php
        $savedLines = $order->details->mapWithKeys(fn ($line) => [$line->id => [
            'id' => $line->id, 'product_id' => $line->product_id, 'product_variation_id' => $line->product_variation_id,
            'product_name' => $line->product_name, 'variation_name' => $line->variation_name, 'sku' => $line->sku,
            'image' => $line->image, 'quantity' => $line->quantity, 'sale_price' => $line->sale_price, 'returned_qty' => $line->returned_qty,
        ]])->all();
    @endphp
    <form id="editOrderForm" action="{{ route('admin.orders.update', $order) }}" method="POST">
        @csrf @method('PUT')
        <input type="hidden" name="edit_version" value="{{ old('edit_version', $editVersion) }}">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div><h4 class="mb-1">Edit Order · {{ $order->order_no }}</h4><span class="text-muted small">Edit products, variations, delivery information and status.</span></div>
            <div class="d-flex flex-wrap gap-2"><a class="btn btn-label-secondary" href="{{ route('admin.orders.show', $order) }}">Cancel</a><a class="btn btn-label-secondary" href="{{ route('admin.orders.edit', $order) }}">Reload Saved Order</a><button type="submit" class="btn btn-primary order-save">Save Changes</button></div>
        </div>
        @if ($errors->any())<div class="alert alert-danger" role="alert"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
        @if (session('error'))<div class="alert alert-danger" role="alert">{{ session('error') }}</div>@endif
        <div class="row g-4 align-items-start">
            <div class="col-12 col-xl-8">
                <section class="card mb-4">
                    <h5 class="card-header">1. Search &amp; Add Products</h5>
                    <div class="card-body">
                        <label class="form-label" for="order-product-search">Product Name / SKU / Variation SKU</label>
                        <div class="d-flex gap-2"><input class="form-control" id="order-product-search" type="search" maxlength="120" autocomplete="off" placeholder="Type at least 2 characters..."><button type="button" class="btn btn-label-secondary" id="cancel-product-replacement" hidden>Cancel Replace</button></div>
                        <p class="form-text mb-0" id="order-search-status" role="status" aria-live="polite">Search for an active product to add it to this order.</p>
                        <div id="order-product-results" class="mt-3"></div>
                    </div>
                </section>
                <section class="card mb-4">
                    <h5 class="card-header">2. Order Products</h5>
                    <div class="card-body">
                        <p class="small text-muted">Select Color / Size using the variation menu. You can edit the sale price and quantity. Products already returned cannot be replaced, removed or repriced.</p>
                        <div id="order-edit-items"></div>
                        <p class="text-muted text-center py-3" id="order-edit-empty" hidden>Add at least one product to save the order.</p>
                    </div>
                </section>
                <section class="card mb-4">
                    <h5 class="card-header">3. Customer &amp; Delivery Details</h5>
                    <div class="card-body"><div class="row g-3">
                        <div class="col-12 col-md-6"><label class="form-label" for="name">Recipient Name</label><input class="form-control" name="name" id="name" required maxlength="255" value="{{ old('name', $order->customerInfo?->name ?? $order->customer?->name) }}"></div>
                        <div class="col-12 col-md-6"><label class="form-label" for="phone">Mobile Number</label><input class="form-control" type="tel" name="phone" id="phone" required maxlength="30" value="{{ old('phone', \App\Support\BangladeshPhone::normalize($order->customerInfo?->phone ?? $order->customer?->mobile ?? '')) }}"></div>
                        <div class="col-12"><label class="form-label" for="email">Email</label><input class="form-control" type="email" name="email" id="email" maxlength="255" value="{{ old('email', $order->customerInfo?->email) }}"></div>
                        <div class="col-12"><label class="form-label" for="address">Delivery Address</label><textarea class="form-control" name="address" id="address" rows="3" required maxlength="2000">{{ old('address', $order->customerInfo?->address ?? $order->customer?->address) }}</textarea></div>
                        <div class="col-12"><label class="form-label" for="notes">Order Note</label><textarea class="form-control" name="notes" id="notes" rows="3" maxlength="2000">{{ old('notes', $order->customerInfo?->order_note ?? $order->notes) }}</textarea></div>
                    </div><p class="form-text mt-3 mb-0">These changes apply to this order only. The customer's login and profile remain unchanged.</p></div>
                </section>
            </div>
            <div class="col-12 col-xl-4">
                <section class="card mb-4"><h5 class="card-header">4. Status &amp; Delivery</h5><div class="card-body">
                    <div class="mb-4"><label class="form-label" for="status_id">Order Status</label><select class="form-select" name="status_id" id="status_id" required>@foreach ($statuses as $status)<option value="{{ $status->id }}" @selected(old('status_id', $order->current_status) == $status->id)>{{ $status->name }}{{ $status->status ? '' : ' (Inactive)' }}</option>@endforeach</select></div>
                    <div class="mb-4"><label class="form-label" for="shipping_id">Delivery Area</label><select class="form-select" name="shipping_id" id="shipping_id"><option value="">Keep current area: {{ $order->delivery_area ?? 'Not selected' }}</option>@foreach ($shippingOptions as $option)<option value="{{ $option->id }}" data-charge="{{ $option->delivery_charge }}" @selected(old('shipping_id', $order->shipping_charge_setting_id) == $option->id)>{{ $option->area_name }}{{ $option->status ? '' : ' (Inactive)' }}</option>@endforeach</select><p class="form-text">Selecting an area fills its current delivery charge. You may adjust the charge below.</p></div>
                    <div class="mb-4"><label class="form-label" for="shipping_cost">Delivery Charge (BDT)</label><input class="form-control" type="number" name="shipping_cost" id="shipping_cost" value="{{ old('shipping_cost', $order->shipping_cost) }}" required min="0" max="99999999.99" step="0.01"></div>
                    <label class="form-label" for="extra_discount_amount">Additional Discount (BDT)</label><input class="form-control" type="number" name="extra_discount_amount" id="extra_discount_amount" value="{{ old('extra_discount_amount', $order->extra_discount_amount) }}" required min="0" max="99999999.99" step="0.01">
                </div></section>
                <section class="card"><h5 class="card-header">Updated Summary</h5><div class="card-body">
                    <div class="d-flex justify-content-between gap-3 mb-3"><span>Quantity</span><strong id="edit-quantity"></strong></div>
                    <div class="d-flex justify-content-between gap-3 mb-3"><span>Subtotal</span><strong id="edit-subtotal"></strong></div>
                    <div class="d-flex justify-content-between gap-3 mb-3"><span>Delivery + Extra Shipping</span><strong id="edit-shipping"></strong></div>
                    <div class="d-flex justify-content-between gap-3 mb-3"><span>VAT</span><strong>&#2547;{{ number_format((float) $order->total_vat_amount, 2) }}</strong></div>
                    <div class="d-flex justify-content-between gap-3 mb-3"><span>All Discounts</span><strong id="edit-discount"></strong></div>
                    <div class="d-flex justify-content-between gap-3 border-top pt-3 mb-3"><strong>Grand Total</strong><strong id="edit-total" class="text-primary"></strong></div>
                    <div class="d-flex justify-content-between gap-3 mb-3"><span>Already Paid</span><strong>&#2547;{{ number_format((float) $order->paid_amount, 2) }}</strong></div>
                    <div class="d-flex justify-content-between gap-3 mb-3"><span>Due</span><strong id="edit-due"></strong></div>
                    <p class="text-danger small" id="edit-total-error" role="alert" hidden></p>
                    <button type="submit" class="btn btn-primary w-100 order-save">Save Changes</button>
                    <p class="form-text mt-3 mb-0">The invoice number and completed payments are preserved. All totals are recalculated when saved.</p>
                </div></section>
            </div>
        </div>
    </form>
@endsection
@push('backend_script')
    <script>
        window.orderEditData = {
            catalog: @json($catalog), items: @json($initialItems), savedLines: @json($savedLines),
            searchUrl: @json(route('admin.orders.products.search')),
            fallbackImage: @json(asset('backend/assets/extra/preview-image.png')),
            paid: @json((float) $order->paid_amount), vat: @json((float) $order->total_vat_amount),
            extraShipping: @json((float) $order->extra_shipping_cost_amount),
            fixedDiscount: @json((float) $order->affiliate_discount + (float) $order->coupon_discount_amount)
        };
    </script>
    <script src="{{ asset('backend/assets/js/order-edit.js') }}"></script>
@endpush
