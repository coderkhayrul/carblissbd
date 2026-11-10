<main class="invoice">
    <div class="heading"><div>@if ($company->logo)<img src="{{ asset($company->logo) }}" alt="{{ $company->name }}" style="max-width:150px;max-height:60px;object-fit:contain">@endif<h1>{{ $company->name ?: config('app.name', 'CarBlissBD') }}</h1><p>{{ $company->address }}</p><p>{{ $company->hotline ?: $company->phone }}</p><p class="copy">{{ $copyLabel }}</p></div><div><h2>{{ $order->order_no }}</h2><p>Date: {{ $order->date?->format('d M Y') }}</p><p>Status: {{ $order->currentStatus?->name ?? 'Pending' }}</p></div></div>
    <hr>
    <div class="heading"><div><h2>Customer / Delivery</h2><p><strong>{{ $order->customerInfo?->name ?? $order->customer?->name }}</strong></p><p>{{ $order->customerInfo?->phone ?? $order->customer?->mobile }}</p><p style="white-space:pre-line">{{ $order->customerInfo?->address }}</p><p>{{ $order->delivery_area }}</p></div><div><h2>Order Information</h2><p>Source: {{ $order->order_source }}</p><p>Payment: {{ $order->payment_type }}</p><p>Courier: {{ $order->is_courier_assigned ? trim($order->courier_name ?? '') : 'Not Assigned' }}</p></div></div>
    <div class="table-wrap"><table><thead><tr><th>SL</th><th>Product / Variation</th><th>SKU</th><th>Qty</th>@if ($copy === 'accounts')<th>Purchase Price</th><th>Total Cost</th>@endif @if ($financial)<th>Sale Price</th><th>Total</th>@endif</tr></thead><tbody>
        @foreach ($order->details as $detail)<tr><td>{{ $loop->iteration }}</td><td>{{ $detail->product_name }}@if ($detail->variation_name)<p class="muted">{{ $detail->variation_name }}</p>@endif</td><td>{{ $detail->sku }}</td><td>{{ $detail->quantity }}</td>@if ($copy === 'accounts')<td class="money">{{ number_format((float) $detail->purchase_price, 2) }}</td><td class="money">{{ number_format((float) $detail->total_cost, 2) }}</td>@endif @if ($financial)<td class="money">{{ number_format((float) $detail->sale_price, 2) }}</td><td class="money">{{ number_format((float) $detail->total_amount, 2) }}</td>@endif</tr>@endforeach
    </tbody></table></div>
    @if ($financial)<div class="totals">
        <div class="summary-row"><span>Subtotal</span><strong>BDT {{ number_format((float) $order->subtotal, 2) }}</strong></div>
        <div class="summary-row"><span>Delivery</span><strong>BDT {{ number_format((float) $order->total_shipping_cost, 2) }}</strong></div>
        @if ((float) $order->total_discount_amount > 0)<div class="summary-row"><span>Discount</span><strong>BDT {{ number_format((float) $order->total_discount_amount, 2) }}</strong></div>@endif
        <div class="summary-row"><strong>Grand Total</strong><strong>BDT {{ number_format((float) $order->grand_total, 2) }}</strong></div>
        <div class="summary-row"><span>Paid</span><strong>BDT {{ number_format((float) $order->paid_amount, 2) }}</strong></div>
        <div class="summary-row"><span>Due</span><strong>BDT {{ number_format((float) $order->due_amount, 2) }}</strong></div>
    </div>@else<p><strong>Total Quantity: {{ $order->total_quantity }}</strong></p>@endif
    @if ($order->customerInfo?->order_note ?? $order->notes)<p>Note: {{ $order->customerInfo?->order_note ?? $order->notes }}</p>@endif
    @if ($copy === 'delivery')<p><strong>Amount to collect: BDT {{ number_format((float) $order->due_amount, 2) }}</strong></p>@endif
    <p class="muted" style="margin-top:40px">Customer signature: ____________________ &nbsp; Authorized signature: ____________________</p>
</main>
