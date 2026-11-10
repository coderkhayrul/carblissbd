<!DOCTYPE html>
<html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>{{ $order->order_no }} — {{ $copyLabel }}</title>
<style>
    *{box-sizing:border-box}body{margin:0;background:#eee;font:14px Arial,sans-serif;color:#20252b}.invoice{max-width:950px;margin:24px auto;background:#fff;padding:32px}.toolbar{max-width:950px;margin:20px auto;display:flex;gap:12px}.toolbar button,.toolbar a{padding:10px 18px;border:1px solid #aaa;background:white;color:#222;text-decoration:none;cursor:pointer}.heading,.summary-row{display:flex;justify-content:space-between;gap:20px}.heading>div{min-width:0}h1{font-size:25px;margin:0 0 8px}h2{font-size:18px}p{line-height:1.6;margin:6px 0;overflow-wrap:anywhere}.muted{color:#666}.copy{border:1px solid #555;padding:6px 10px;display:inline-block}table{border-collapse:collapse;width:100%;margin:24px 0}th,td{border:1px solid #ccc;padding:10px;text-align:left;overflow-wrap:anywhere}th{background:#eee}.money{text-align:right;white-space:nowrap}.totals{max-width:370px;margin-left:auto}.summary-row{padding:8px 0;border-bottom:1px solid #ddd}.table-wrap{overflow-x:auto}@media(max-width:600px){.invoice{padding:16px;margin:12px}.toolbar{margin:12px}.heading{flex-direction:column}th,td{padding:7px}}@media print{body{background:white}.toolbar{display:none}.invoice{max-width:none;margin:0;padding:0}.table-wrap{overflow:visible}thead{display:table-header-group}tr{break-inside:avoid}a{color:inherit;text-decoration:none}@page{size:A4;margin:14mm}}
</style></head><body>
@php
    $financial = !in_array($copy, ['delivery', 'office-challan', 'customer-challan']);
@endphp
<div class="toolbar"><button onclick="window.print()">Print / Save PDF</button><a href="{{ route('admin.orders.show', $order) }}">View Order</a></div>
@include('admin.orders.invoice-content')</body></html>
