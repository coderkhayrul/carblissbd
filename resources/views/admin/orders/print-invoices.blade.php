<!DOCTYPE html>
<html lang="bn"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="robots" content="noindex,nofollow"><title>{{ $mode === 'mini' ? 'Mini' : 'Bulk' }} Invoices — {{ $orders->count() }} Orders</title>
<style>
@include('admin.orders.invoice-styles')
.toolbar{max-width:1000px;flex-wrap:wrap;align-items:center}.print-help{max-width:1000px;margin:12px auto;padding:0 12px;font-size:13px}.print-help p{margin:4px 0}.mini-sheet{width:194mm;max-width:100%;margin:16px auto;background:#fff;padding:0}.mini-table{width:100%;table-layout:fixed;border-collapse:separate;border-spacing:2mm;margin:0}.mini-table td{border:0;padding:0;vertical-align:top}.mini-invoice{border:1px solid #bbb;padding:2mm;min-height:60mm;font:10px/1.25 Arial,"Noto Sans Bengali",sans-serif;color:#000;overflow-wrap:anywhere;break-inside:avoid;page-break-inside:avoid}.mini-heading{display:flex;gap:2mm;font-size:9px}.mini-heading>div{min-width:0;flex:1}.mini-company,.mini-customer{display:block;font-size:11px}.mini-logo{width:12mm;height:12mm;object-fit:contain;flex-shrink:0}.mini-barcode{margin-top:2mm;height:11mm;width:100%}.mini-barcode svg{display:block;width:100%;height:100%}.mini-tracking{font-size:9px;text-align:center;margin-bottom:1mm}.mini-cod{display:inline-block;background:#000;color:#fff;padding:.3mm 1mm}.mini-address{margin:1mm 0;white-space:pre-line}.mini-products{border-top:1px dotted #aaa;padding-top:1mm}.mini-note{margin-top:1mm}.batch-full .invoice{break-after:page;page-break-after:always}.batch-full .invoice:last-child{break-after:auto;page-break-after:auto}
@media screen and (max-width:600px){.mini-sheet{overflow-x:auto}.mini-table{min-width:580px}.toolbar{margin:12px}.print-help{margin:8px}}
@media print{.toolbar,.print-help{display:none!important}body{background:#fff;-webkit-print-color-adjust:exact;print-color-adjust:exact}.mini-sheet{width:100%;max-width:none;margin:0;padding:0}.mini-table{width:100%;min-width:0}.mini-table tr,.mini-table td,.mini-invoice{break-inside:avoid;page-break-inside:avoid}.batch-full .invoice{padding:0;margin:0}.mini-heading img{max-width:12mm} @page{size:A4 portrait;margin:{{ $mode === 'mini' ? '8mm' : '14mm' }}}}
</style></head><body class="{{ $mode === 'full' ? 'batch-full' : 'batch-mini' }}">
<div class="toolbar"><button type="button" id="print-invoices-button">Print / Save PDF</button><a href="{{ route('admin.orders.index') }}">Back to Orders</a><strong>{{ $orders->count() }} {{ $mode === 'mini' ? 'mini invoices' : $copyLabel . ' invoices' }}</strong></div>
<div class="print-help"><p>Paper: <strong>A4 Portrait</strong> · Scale: <strong>100%</strong> · Turn off browser headers and footers.</p>@if ($mode === 'mini')<p>{{ $columns }} invoices per row. With short orders, approximately {{ $columns * 4 }} fit per A4 page. Long addresses, notes or product lists use more space; no product lines are hidden.</p><p>The barcode uses the courier consignment ID when available; otherwise it uses the order invoice number.</p>@endif</div>
@if ($mode === 'mini')
<main class="mini-sheet"><table class="mini-table" aria-label="Mini invoices"><tbody>@foreach ($orders->chunk($columns) as $row)<tr>@foreach ($row as $order)<td style="width:{{ 100 / $columns }}%">@include('admin.orders.mini-invoice-content')</td>@endforeach @for ($empty = $row->count(); $empty < $columns; $empty++)<td></td>@endfor</tr>@endforeach</tbody></table></main>
@else
@php
    $financial = !in_array($copy, ['delivery', 'office-challan', 'customer-challan']);
@endphp
@foreach ($orders as $order)@include('admin.orders.invoice-content')@endforeach
@endif
<script>
document.getElementById('print-invoices-button').addEventListener('click', async function () {
    this.disabled = true;
    await Promise.all(Array.from(document.images).map(image => image.complete ? Promise.resolve() : new Promise(resolve => { image.addEventListener('load', resolve, {once:true}); image.addEventListener('error', resolve, {once:true}); setTimeout(resolve, 3000); })));
    if (document.fonts?.ready) await document.fonts.ready;
    window.print();
    this.disabled = false;
});
</script></body></html>
