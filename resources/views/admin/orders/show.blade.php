@extends('admin.layouts.app')
@section('admin-content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div><h4 class="mb-1">{{ $order->order_no }}</h4><span class="text-muted">{{ $order->date?->format('d M Y') }} · {{ $order->order_source }}</span></div>
        <a class="btn btn-label-secondary" href="{{ route('admin.orders.index') }}">Back to Orders</a>
    </div>
    <div class="row g-4">
        <div class="col-12 col-lg-8">
            <div class="card mb-4">
                <h5 class="card-header">Products</h5>
                <div class="table-responsive"><table class="table table-bordered align-middle">
                    <thead><tr><th>Product / Variation</th><th>SKU</th><th>Qty</th><th>Unit Price</th><th>Total</th></tr></thead>
                    <tbody>@foreach ($order->details as $item)
                        <tr><td>{{ $item->product_name }}
                            @if ($item->variation_name)<div class="small text-muted">{{ $item->variation_name }}</div>@endif
                            @foreach ($item->attributes ?? [] as $attribute)<div class="small">{{ $attribute['type'] ?? '' }}: {{ $attribute['value'] ?? '' }}</div>@endforeach
                        </td><td>{{ $item->sku }}</td><td>{{ $item->quantity }}</td><td>{{ number_format((float) $item->sale_price, 2) }}</td><td>{{ number_format((float) $item->total_amount, 2) }}</td></tr>
                    @endforeach</tbody>
                </table></div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2"><span>Subtotal</span><strong>&#2547;{{ number_format((float) $order->subtotal, 2) }}</strong></div>
                    <div class="d-flex justify-content-between mb-2"><span>Shipping</span><strong>&#2547;{{ number_format((float) $order->shipping_cost, 2) }}</strong></div>
                    <div class="d-flex justify-content-between mb-2"><strong>Grand Total</strong><strong>&#2547;{{ number_format((float) $order->grand_total, 2) }}</strong></div>
                    <div class="d-flex justify-content-between mb-2"><span>Paid</span><strong>&#2547;{{ number_format((float) $order->paid_amount, 2) }}</strong></div>
                    <div class="d-flex justify-content-between"><span>Due</span><strong>&#2547;{{ number_format((float) $order->due_amount, 2) }}</strong></div>
                </div>
            </div>
            <div class="card mb-4">
                <h5 class="card-header">Payment Records</h5>
                <div class="table-responsive"><table class="table"><thead><tr><th>Method</th><th>Status</th><th>Amount</th><th>Transaction</th></tr></thead><tbody>
                    @foreach ($order->payments as $payment)<tr><td>{{ $payment->payment_method }}</td><td>{{ $payment->payment_status }}</td><td>&#2547;{{ number_format((float) $payment->amount, 2) }}</td><td>{{ $payment->transaction_id ?? '—' }}</td></tr>@endforeach
                </tbody></table></div>
            </div>
        </div>
        <div class="col-12 col-lg-4">
            <div class="card mb-4"><h5 class="card-header">Customer & Delivery</h5><div class="card-body">
                <h6>{{ $order->customerInfo?->name ?? $order->customer?->name }}</h6>
                <p class="mb-1">{{ $order->customerInfo?->phone ?? $order->customer?->mobile }}</p>
                <p class="mb-1">{{ $order->customerInfo?->email }}</p>
                <p style="white-space: pre-line; overflow-wrap: anywhere;">{{ $order->customerInfo?->address }}</p>
                <p class="small">Area: {{ $order->delivery_area }}<br>Delivery Time: {{ $order->delivery_time }}</p>
                <p class="small">Payment: {{ $order->payment_type }}<br>Current Status: {{ $order->currentStatus?->name }}</p>
                @if ($order->customerInfo?->order_note ?? $order->notes)<div class="small" style="white-space: pre-line; overflow-wrap: anywhere;">Notes: {{ $order->customerInfo?->order_note ?? $order->notes }}</div>@endif
                @if ($order->customer)<a class="btn btn-sm btn-label-primary mt-3" href="{{ route('admin.customers.show', $order->customer) }}">Customer Profile</a>@endif
            </div></div>
            <div class="card"><h5 class="card-header">Status History</h5><div class="card-body">
                @foreach ($order->statusHistory as $history)<div class="mb-3"><strong>{{ $history->status?->name }}</strong><br><small class="text-muted">{{ $history->created_at?->timezone('Asia/Dhaka')->format('d M Y h:i A') }}</small></div>@endforeach
            </div></div>
        </div>
    </div>
@endsection
