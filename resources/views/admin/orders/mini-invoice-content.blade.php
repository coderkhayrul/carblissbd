@php
    $consignment = $order->pathaoShipment?->consignment_id ?: $order->steadfastShipment?->consignment_id;
    $barcodeValue = $consignment ?: $order->order_no;
    $barcodeSvg = \App\Support\InvoiceBarcode::svg($barcodeValue);
@endphp
<article class="mini-invoice">
    <header class="mini-heading">
        @if ($company->logo)<img class="mini-logo" src="{{ asset($company->logo) }}" alt="{{ $company->name }}">@endif
        <div><strong class="mini-company">{{ $company->name ?: config('app.name') }}</strong><div>{{ $company->address }}</div><div>Contact: {{ $company->hotline ?: $company->phone }}</div>
            @if ($order->is_courier_assigned)<div><strong>{{ trim($order->courier_name ?? '') }}</strong></div>@endif
        </div>
    </header>
    @if ($barcodeSvg)<div class="mini-barcode">{!! $barcodeSvg !!}</div>@endif
    <div class="mini-tracking">{{ $barcodeValue }}</div>
    <strong class="mini-customer">{{ $order->customerInfo?->name ?? $order->customer?->name }}</strong>
    <div>{{ $order->customerInfo?->phone ?? $order->customer?->mobile }} <strong class="mini-cod">COD: BDT {{ number_format(max(0, (float) $order->due_amount), 2) }}</strong></div>
    <div>Order ID: <strong>{{ $order->order_no }}</strong></div>
    <div class="mini-address">{{ $order->customerInfo?->address }}</div>
    <div class="mini-products">@foreach ($order->details as $detail)<div>{{ $detail->product_name }}@if ($detail->variation_name) / {{ $detail->variation_name }}@endif × {{ $detail->quantity }}</div>@endforeach</div>
    @if ($order->customerInfo?->order_note ?? $order->notes)<div class="mini-note">Note: {{ $order->customerInfo?->order_note ?? $order->notes }}</div>@endif
</article>
