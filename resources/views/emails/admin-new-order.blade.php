<!DOCTYPE html>
<html lang="en">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>New Order Received</title></head>
<body style="margin:0;padding:0;background:#f3f5f8;color:#243246;font-family:Arial,Helvetica,sans-serif">
<div style="display:none;max-height:0;overflow:hidden">New order {{ $order->order_no }} received — BDT {{ number_format((float) $order->grand_total, 2) }}. Review the order in your admin panel.</div>
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f3f5f8"><tr><td align="center" style="padding:24px 12px">
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:680px;background:#ffffff;border:1px solid #e4e9f0;border-radius:12px;overflow:hidden">
    <tr><td style="padding:26px;background:#F15A29;color:#ffffff">
        <p style="margin:0 0 18px;font-size:16px;font-weight:bold">{{ $company?->name ?: config('app.name') }}</p>
        <p style="margin:0 0 8px;font-size:12px;font-weight:bold;letter-spacing:1px">ORDER NOTIFICATION</p>
        <h1 style="margin:0;font-size:28px;line-height:1.3">A new order has arrived!</h1>
        <p style="margin:12px 0 0;font-size:14px;line-height:1.6">Review the details below and follow up with your customer.</p>
    </td></tr>
    <tr><td style="padding:24px">
        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f6f8fb;border:1px solid #e4e9f0;border-radius:8px">
            <tr><td style="padding:16px;font-size:14px;line-height:1.8">
                <strong style="font-size:18px">{{ $order->order_no }}</strong><br>
                Date: {{ $order->created_at?->timezone('Asia/Dhaka')->format('d M Y, h:i A') }} (BDT)<br>
                Source: {{ $order->order_source }} / {{ $order->entry_channel ?: 'Checkout' }}<br>
                Status: {{ $order->currentStatus?->name ?: 'New' }}<br>
                Payment: {{ $order->payment_type }}
            </td></tr>
        </table>
        <h2 style="margin:24px 0 12px;font-size:18px">Customer &amp; delivery</h2>
        <p style="margin:0;font-size:14px;line-height:1.8;overflow-wrap:anywhere">
            <strong>{{ $order->customerInfo?->name }}</strong><br>
            {{ $order->customerInfo?->phone }}<br>
            @if ($order->customerInfo?->email){{ $order->customerInfo->email }}<br>@endif
            <span style="white-space:pre-line">{{ $order->customerInfo?->address }}</span>
        </p>
        <h2 style="margin:24px 0 12px;font-size:18px">Ordered products</h2>
        <table width="100%" cellspacing="0" cellpadding="0" style="border-collapse:collapse;font-size:14px;table-layout:fixed">
            <thead><tr style="background:#edf2f7"><th style="padding:12px 8px;text-align:left;width:52%">Product</th><th style="padding:12px 4px;text-align:center;width:12%">Qty</th><th style="padding:12px 8px;text-align:right;width:36%">Amount (BDT)</th></tr></thead>
            <tbody>@foreach ($order->details as $item)
                <tr><td style="padding:14px 8px;border-bottom:1px solid #e4e9f0;overflow-wrap:anywhere">{{ $item->product_name }}@if ($item->variation_name)<br><span style="color:#64748b;font-size:12px">{{ $item->variation_name }}</span>@endif</td>
                <td style="padding:14px 4px;text-align:center;border-bottom:1px solid #e4e9f0">{{ $item->quantity }}</td>
                <td style="padding:14px 8px;text-align:right;border-bottom:1px solid #e4e9f0">{{ number_format((float) $item->total_amount, 2) }}</td></tr>
            @endforeach</tbody>
        </table>
        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-top:16px;font-size:14px;line-height:1.8">
            <tr><td>Subtotal</td><td align="right">BDT {{ number_format((float) $order->subtotal, 2) }}</td></tr>
            <tr><td>Delivery</td><td align="right">BDT {{ number_format((float) $order->total_shipping_cost, 2) }}</td></tr>
            <tr><td>VAT</td><td align="right">BDT {{ number_format((float) $order->total_vat_amount, 2) }}</td></tr>
            <tr><td>Discount</td><td align="right">BDT {{ number_format((float) $order->total_discount_amount, 2) }}</td></tr>
            <tr><td style="padding-top:10px;font-size:18px"><strong>Total</strong></td><td align="right" style="padding-top:10px;font-size:18px;color:#F15A29"><strong>BDT {{ number_format((float) $order->grand_total, 2) }}</strong></td></tr>
            <tr><td>Paid</td><td align="right">BDT {{ number_format((float) $order->paid_amount, 2) }}</td></tr>
            <tr><td>Outstanding</td><td align="right">BDT {{ number_format((float) $order->due_amount, 2) }}</td></tr>
        </table>
        @if ($order->customerInfo?->order_note)
            <div style="margin-top:20px;padding:16px;background:#fff7ed;border-left:3px solid #F15A29;font-size:14px;line-height:1.7"><strong>Customer note</strong><br><span style="white-space:pre-line;overflow-wrap:anywhere">{{ $order->customerInfo->order_note }}</span></div>
        @endif
        <p style="margin:28px 0;text-align:center"><a href="{{ route('admin.orders.show', $order) }}" style="display:inline-block;background:#F15A29;color:#ffffff;text-decoration:none;font-size:15px;font-weight:bold;padding:15px 28px;border-radius:6px">View order in admin panel</a></p>
        <p style="margin:0;font-size:12px;color:#64748b;line-height:1.7;text-align:center">Admin sign-in is required to view or manage this order.</p>
    </td></tr>
    <tr><td style="padding:20px 24px;background:#f8fafc;border-top:1px solid #e4e9f0;text-align:center;color:#64748b;font-size:12px;line-height:1.7">This automatic notification was sent to your configured Sender Email.<br>{{ $company?->name ?: config('app.name') }}</td></tr>
</table>
</td></tr></table>
</body></html>
