<!DOCTYPE html>
<html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>{{ $order->order_no }} — {{ $copyLabel }}</title>
<style>
    *{box-sizing:border-box}body{margin:0;background:#eee;font:14px Arial,sans-serif;color:#20252b}.invoice{max-width:950px;margin:24px auto;background:#fff;padding:32px}.toolbar{max-width:950px;margin:20px auto;display:flex;gap:12px}.toolbar button,.toolbar a{padding:10px 18px;border:1px solid #aaa;background:white;color:#222;text-decoration:none;cursor:pointer}.heading,.summary-row{display:flex;justify-content:space-between;gap:20px}.heading>div{min-width:0}h1{font-size:25px;margin:0 0 8px}h2{font-size:18px}p{line-height:1.6;margin:6px 0;overflow-wrap:anywhere}.muted{color:#666}.copy{border:1px solid #555;padding:6px 10px;display:inline-block}table{border-collapse:collapse;width:100%;margin:24px 0}th,td{border:1px solid #ccc;padding:10px;text-align:left;overflow-wrap:anywhere}th{background:#eee}.money{text-align:right;white-space:nowrap}.totals{max-width:370px;margin-left:auto}.summary-row{padding:8px 0;border-bottom:1px solid #ddd}.table-wrap{overflow-x:auto}@media(max-width:600px){.invoice{padding:16px;margin:12px}.toolbar{margin:12px}.heading{flex-direction:column}th,td{padding:7px}}@media print{body{background:white}.toolbar{display:none}.invoice{max-width:none;margin:0;padding:0}.table-wrap{overflow:visible}thead{display:table-header-group}tr{break-inside:avoid}a{color:inherit;text-decoration:none}@page{size:A4;margin:14mm}}
</style></head><body>
@php($financial = !in_array($copy, ['delivery', 'office-challan', 'customer-challan']))
<div class="toolbar"><button onclick="window.print()">Print / Save PDF</button><a href="{{ route('admin.orders.show', $order) }}">View Order</a></div>
<main class="invoice">
    <div class="heading"><div><h1>{{ config('app.name', 'CarBlissBD') }}</h1><p class="copy">{{ $copyLabel }}</p></div><div><h2>{{ $order->order_no }}</h2><p>Date: {{ $order->date?->format('d M Y') }}</p><p>Status: {{ $order->currentStatus?->name ?? 'Pending' }}</p></div></div>
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
</main></body></html>
