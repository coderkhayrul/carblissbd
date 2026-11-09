@php
    $shipment = $order->steadfastShipment;
@endphp
@if ($shipment || trim($order->courier_name ?? '') === 'Steadfast')
<div class="card mb-4"><h5 class="card-header">Steadfast Courier</h5><div class="card-body">
    @if ($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
    @if ($shipment)
        <dl class="row small"><dt class="col-5">Booking</dt><dd class="col-7">{{ ucfirst($shipment->booking_state) }}</dd><dt class="col-5">Consignment ID</dt><dd class="col-7">{{ $shipment->consignment_id ?: 'Not available' }}</dd><dt class="col-5">Tracking Code</dt><dd class="col-7">{{ $shipment->tracking_code ?: 'Not available' }}</dd><dt class="col-5">Courier Status</dt><dd class="col-7">{{ $shipment->delivery_status ?: 'Unconfirmed' }}</dd><dt class="col-5">Booked COD</dt><dd class="col-7">BDT {{ number_format((float) $shipment->cod_amount, 2) }}</dd><dt class="col-5">Last Sync</dt><dd class="col-7">{{ $shipment->synced_at?->timezone('Asia/Dhaka')->format('d M Y h:i A') ?: 'Not yet' }}</dd></dl>
        @if ($shipment->last_error)<p class="text-danger small">{{ $shipment->last_error }}</p>@endif
        @if ($shipment->return_status)<p class="small">Return Status: <strong>{{ $shipment->return_status }}</strong></p>@endif
        <form method="POST" action="{{ route('admin.steadfast.sync', $order) }}" class="mb-3">@csrf<button type="submit" class="btn btn-sm btn-primary">Sync Status Now</button></form>
        @if ($shipment->booking_state !== 'booked' && !$shipment->consignment_id && !$shipment->delivery_status)
            <details class="mb-3"><summary>Resolve an unconfirmed booking</summary><p class="small mt-2">First search invoice {{ $shipment->invoice }} in Steadfast. If found, use Sync Status. Only if no parcel exists, confirm below after waiting at least two minutes.</p><form method="POST" action="{{ route('admin.steadfast.release', $order) }}">@csrf<label class="form-check mb-2"><input class="form-check-input" type="checkbox" name="confirmed_absent" value="1" required><span class="form-check-label small">I checked the Steadfast merchant panel and confirmed that this invoice has no parcel.</span></label><button class="btn btn-sm btn-label-warning" type="submit">Clear Unconfirmed Reservation</button></form></details>
        @endif
        <p class="small text-muted">Booked items, recipient and COD are fixed. Edit the parcel in Steadfast if changes are needed. This page shows the saved status; refresh after a webhook update.</p>
        @foreach ($shipment->events()->latest('id')->limit(10)->get() as $event)<div class="small border-top py-2"><strong>{{ $event->delivery_status }}</strong> <span class="text-muted">({{ $event->source }})<br>{{ $event->created_at->timezone('Asia/Dhaka')->format('d M Y h:i A') }}</span></div>@endforeach
    @else
        <p class="small">Previous local assignment only. Use Assign Steadfast from the order list to send a booking.</p>
    @endif
    <a href="{{ str_replace('/api/v1', '', \App\Models\SteadfastSetting::configuration()->base_url) }}" class="btn btn-sm btn-label-secondary my-3" target="_blank" rel="noopener noreferrer">Open Steadfast Merchant Panel</a>
    <details><summary class="text-danger">Cancel Order</summary><form class="mt-3" method="POST" action="{{ route('admin.steadfast.cancel', $order) }}">@csrf<label for="steadfast-cancel-reason" class="form-label">Cancellation Reason</label><textarea id="steadfast-cancel-reason" name="cancel_reason" class="form-control" required maxlength="2000" rows="3">{{ old('cancel_reason') }}</textarea><p class="small mt-2">This cancels our order only. Also cancel the booked parcel in Steadfast. A later sync follows the actual courier status.</p><button type="submit" class="btn btn-sm btn-danger">Cancel Local Order</button></form></details>
</div></div>
@endif
