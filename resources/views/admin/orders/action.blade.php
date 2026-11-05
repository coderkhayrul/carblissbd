@extends('admin.layouts.app')
@section('admin-content')
    @php($titles = ['status' => 'Update Status', 'return' => 'Record Return', 'complaint' => 'Add Complaint', 'duplicate' => 'Duplicate Order'])
    <div class="d-flex justify-content-between flex-wrap gap-3 align-items-center mb-4">
        <div><h4 class="mb-1">{{ $titles[$action] }}</h4><span class="text-muted">{{ $order->order_no }}</span></div>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-label-secondary">Back to Orders</a>
    </div>
    @if ($errors->any())<div class="alert alert-danger" role="alert"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
    <form class="card" id="orderActionForm" action="{{ route('admin.orders.action.store', ['order' => $order, 'action' => $action]) }}" method="POST">
        @csrf
        <input type="hidden" name="request_token" value="{{ old('request_token', $requestToken) }}">
        <div class="card-body">
            @if ($action === 'status')
                <p class="text-muted">Current status: {{ $order->currentStatus?->name ?? 'Unknown' }}</p>
                <label class="form-label" for="status_id">New Status</label>
                <select class="form-select mb-4" name="status_id" id="status_id" required>
                    <option value="">Select Status</option>
                    @foreach ($statuses as $status)<option value="{{ $status->id }}" @selected(old('status_id', $order->current_status) == $status->id)>{{ $status->name }}</option>@endforeach
                </select>
                @include('admin.orders.cancellation-field')
                <p class="small text-muted">The change will also be added to the order's status history.</p>
            @elseif ($action === 'complaint')
                <div class="mb-4"><label class="form-label" for="subject">Subject</label><input class="form-control" id="subject" name="subject" value="{{ old('subject') }}" required maxlength="255"></div>
                <div class="mb-4"><label class="form-label" for="message">Complaint Details</label><textarea class="form-control" name="message" id="message" rows="6" required maxlength="10000">{{ old('message') }}</textarea></div>
            @elseif ($action === 'return')
                <div class="alert alert-info">Record products received back from the customer. This records returned quantities and values; it does not issue a payment refund automatically.</div>
                <div class="table-responsive mb-4"><table class="table table-bordered align-middle">
                    <thead><tr><th>Product</th><th>Ordered</th><th>Already Returned</th><th>Return Now</th></tr></thead>
                    <tbody>@foreach ($order->details as $index => $detail)
                        <tr><td>{{ $detail->product_name }}<br><small>{{ $detail->variation_name }} · {{ $detail->sku }}</small></td><td>{{ $detail->quantity }}</td><td>{{ $detail->returned_qty }}</td><td>
                            <input type="hidden" name="items[{{ $index }}][id]" value="{{ $detail->id }}">
                            <label class="visually-hidden" for="return-{{ $detail->id }}">Return quantity for {{ $detail->product_name }}</label>
                            <input class="form-control" style="min-width: 90px;" type="number" name="items[{{ $index }}][quantity]" id="return-{{ $detail->id }}" value="{{ old('items.' . $index . '.quantity', 0) }}" required min="0" max="{{ max(0, $detail->quantity - $detail->returned_qty) }}">
                        </td></tr>
                    @endforeach</tbody>
                </table></div>
                <div class="mb-4"><label class="form-label" for="return_reason_id">Return Reason</label><select class="form-select" id="return_reason_id" name="return_reason_id" required><option value="">Select Reason</option>@foreach ($reasons as $reason)<option value="{{ $reason->id }}" @selected(old('return_reason_id') == $reason->id)>{{ $reason->title }}</option>@endforeach</select></div>
                <div class="mb-4"><label class="form-label" for="note">Return Note</label><textarea class="form-control" id="note" name="note" maxlength="2000" rows="3">{{ old('note') }}</textarea></div>
            @elseif ($action === 'duplicate')
                <div class="alert alert-warning">Create a new order for the same customer using the original products, prices and delivery charges. It will receive a new invoice number, Pending status and Cash on Delivery payment. Previous payments and returns will not be copied.</div>
                <p><strong>Original invoice:</strong> {{ $order->order_no }}<br><strong>New order total:</strong> &#2547;{{ number_format((float) $order->grand_total, 2) }}</p>
                <label class="d-flex gap-2 mb-4"><input class="form-check-input flex-shrink-0" type="checkbox" name="confirm" value="1" required @checked(old('confirm'))><span>I confirm that a new order should be created.</span></label>
            @endif
            <button type="submit" class="btn btn-primary">{{ $titles[$action] }}</button>
        </div>
    </form>
@endsection
@push('backend_script')
    <script>
        document.getElementById('orderActionForm').addEventListener('submit', function(event) {
            if (event.submitter) {
                event.submitter.disabled = true;
                event.submitter.textContent = 'Saving...';
            }
        });
    </script>
@endpush
