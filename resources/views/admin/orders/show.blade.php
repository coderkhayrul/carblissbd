@extends('admin.layouts.app')
@section('admin-content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div><h4 class="mb-1">{{ $order->order_no }}</h4><span class="text-muted">{{ $order->date?->format('d M Y') }} · {{ $order->order_source }}</span></div>
        <div class="d-flex gap-2"><a class="btn btn-primary" href="{{ route('admin.orders.edit', $order) }}">Edit Order</a><a class="btn btn-label-secondary" href="{{ route('admin.orders.index') }}">Back to Orders</a></div>
    </div>
    <div class="row g-4">
        <div class="col-12 col-lg-8">
            <div class="card mb-4">
                <h5 class="card-header">Products</h5>
                <div class="table-responsive"><table class="table table-bordered align-middle">
                    <thead><tr><th>Image</th><th>Product / Variation</th><th>SKU</th><th>Qty</th><th>Unit Price</th><th>Total</th></tr></thead>
                    <tbody>@foreach ($order->details as $item)
                        @php
                            $productImage = $item->product?->thumbnail_image ?: (($item->product?->gallery_images ?? [])[0] ?? 'backend/assets/extra/preview-image.png');
                            $orderedImage = $item->variation?->image ?: ($item->image ?: $productImage);
                        @endphp
                        <tr><td><img class="rounded border" src="{{ asset($orderedImage) }}" width="72" height="72" style="object-fit:contain;min-width:72px" alt="{{ $item->product_name }} {{ $item->variation_name }}" onerror="this.onerror=null;this.src='{{ asset('backend/assets/extra/preview-image.png') }}'"></td><td>{{ $item->product_name }}
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
                <h5 class="card-header" id="order-payments">Payment Records</h5>
                <div class="table-responsive"><table class="table"><thead><tr><th>Method</th><th>Status</th><th>Amount</th><th>Transaction</th></tr></thead><tbody>
                    @foreach ($order->payments as $payment)<tr><td>{{ $payment->payment_method }}</td><td>{{ $payment->payment_status }}</td><td>&#2547;{{ number_format((float) $payment->amount, 2) }}</td><td>{{ $payment->transaction_id ?? '—' }}</td></tr>@endforeach
                </tbody></table></div>
            </div>
        </div>
        <div class="col-12 col-lg-4">
            @include('admin.orders.steadfast')
            @if ($order->cancel_reason)
                <div class="card mb-4 border border-danger"><h5 class="card-header text-danger">Cancellation Reason</h5><div class="card-body" style="white-space:pre-line;overflow-wrap:anywhere">{{ $order->cancel_reason }}</div></div>
            @endif
            <div class="card mb-4"><h5 class="card-header">Customer & Delivery</h5><div class="card-body">
                <h6>{{ $order->customerInfo?->name ?? $order->customer?->name }}</h6>
                <p class="mb-1">{{ $order->customerInfo?->phone ?? $order->customer?->mobile }}</p>
                <p class="mb-1">{{ $order->customerInfo?->email }}</p>
                <p style="white-space: pre-line; overflow-wrap: anywhere;">{{ $order->customerInfo?->address }}</p>
                <p class="small">Area: {{ $order->delivery_area }}<br>Delivery Time: {{ $order->delivery_time }}</p>
                <p class="small">Payment: {{ $order->payment_type }}<br>Current Status: {{ $order->currentStatus?->name }}</p>
                <p class="small">Order Entry: <strong>{{ $order->entry_channel }}</strong></p>
                @if ($order->entry_channel === 'Landing Page' && $order->landing_page_url)<a class="btn btn-sm btn-label-success mb-3" href="{{ $order->landing_page_url }}" target="_blank" rel="noopener">View Product Landing Page</a>@endif
                @if ($order->createdBy)<p class="small">Created By: {{ $order->createdBy->name }}<br>Source: {{ $order->order_source }}</p>@endif
                @if ($order->customerInfo?->order_note ?? $order->notes)<div class="small" style="white-space: pre-line; overflow-wrap: anywhere;">Notes: {{ $order->customerInfo?->order_note ?? $order->notes }}</div>@endif
                @if ($order->customer)<a class="btn btn-sm btn-label-primary mt-3" href="{{ route('admin.customers.show', $order->customer) }}">Customer Profile</a>@endif
            </div></div>
            <div class="card"><h5 class="card-header">Status History</h5><div class="card-body">
                @foreach ($order->statusHistory as $history)<div class="mb-3"><strong>{{ $history->status?->name }}</strong><br><small class="text-muted">{{ $history->created_at?->timezone('Asia/Dhaka')->format('d M Y h:i A') }}</small>@if ($history->cancel_reason)<p class="small mt-2 mb-0 text-danger" style="white-space:pre-line;overflow-wrap:anywhere">Reason: {{ $history->cancel_reason }}</p>@endif</div>@endforeach
            </div></div>
        </div>
    </div>
    @include('admin.orders.action-history')
    @if ($editLogs->isNotEmpty())
        <div class="card mt-4"><h5 class="card-header">Order Edit History</h5>
            <div class="table-responsive"><table class="table"><thead><tr><th>Date</th><th>Edited By</th><th>Previous Total</th><th>Updated Total</th></tr></thead><tbody>
                @foreach ($editLogs as $log)
                    @php($beforeEdit = json_decode($log->before, true))
                    @php($afterEdit = json_decode($log->after, true))
                    <tr><td>{{ \Illuminate\Support\Carbon::parse($log->created_at)->timezone('Asia/Dhaka')->format('d M Y h:i A') }}</td><td>{{ $log->editor ?? 'Deleted user' }}</td><td>&#2547;{{ number_format((float) ($beforeEdit['order']['grand_total'] ?? 0), 2) }}</td><td>&#2547;{{ number_format((float) ($afterEdit['order']['grand_total'] ?? 0), 2) }}</td></tr>
                @endforeach
            </tbody></table></div>
        </div>
    @endif
@endsection
