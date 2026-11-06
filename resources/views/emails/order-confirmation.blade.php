<!DOCTYPE html>
<html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Order Confirmation</title></head>
<body style="margin:0;background:#f4f6f8;font-family:Arial,sans-serif;color:#263238">
<div style="max-width:680px;margin:24px auto;padding:24px;background:#fff;border-radius:8px">
    <h2>{{ $company?->name ?? config('app.name') }}</h2>
    <h1 style="font-size:24px">Thank you! Your order has been received.</h1>
    <p>Hello {{ $order->customerInfo?->name }},</p><p>We have received your order. Our team will contact you to arrange delivery.</p>
    <p><strong>Invoice:</strong> {{ $order->order_no }}<br><strong>Date:</strong> {{ $order->date?->format('d M Y') }}<br><strong>Status:</strong> {{ $order->currentStatus?->name }}<br><strong>Payment:</strong> {{ $order->payment_type }}</p>
    <table style="width:100%;border-collapse:collapse;font-size:14px"><thead><tr style="background:#eef2f7"><th style="padding:10px;text-align:left">Product</th><th style="padding:10px">Qty</th><th style="padding:10px;text-align:right">Amount (BDT)</th></tr></thead><tbody>
        @foreach ($order->details as $item)<tr><td style="padding:10px;border-bottom:1px solid #eee">{{ $item->product_name }}@if ($item->variation_name)<br><small>{{ $item->variation_name }}</small>@endif</td><td style="padding:10px;text-align:center">{{ $item->quantity }}</td><td style="padding:10px;text-align:right">{{ number_format((float) $item->total_amount, 2) }}</td></tr>@endforeach
    </tbody></table>
    <p style="text-align:right">Subtotal: BDT {{ number_format((float) $order->subtotal, 2) }}<br>Delivery: BDT {{ number_format((float) $order->total_shipping_cost, 2) }}<br>VAT: BDT {{ number_format((float) $order->total_vat_amount, 2) }}<br>Discount: BDT {{ number_format((float) $order->total_discount_amount, 2) }}<br><strong>Total: BDT {{ number_format((float) $order->grand_total, 2) }}</strong><br>Paid: BDT {{ number_format((float) $order->paid_amount, 2) }}<br>Due: BDT {{ number_format((float) $order->due_amount, 2) }}</p>
    <h3>Delivery Information</h3><p>{{ $order->customerInfo?->name }}<br>{{ $order->customerInfo?->phone }}</p><p style="white-space:pre-line;overflow-wrap:anywhere">{{ $order->customerInfo?->address }}</p>
    @if ($order->customerInfo?->order_note)<p style="white-space:pre-line;overflow-wrap:anywhere"><strong>Order Note:</strong> {{ $order->customerInfo->order_note }}</p>@endif
    <p>For assistance: {{ $company?->hotline ?: $company?->phone }} {{ $company?->email }}</p>
</div></body></html>
