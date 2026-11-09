@php
    $pathaoShipment = $order->pathaoShipment;
@endphp
@if ($pathaoShipment || trim($order->courier_name ?? '') === 'Pathao')
<div class="card mb-4"><h5 class="card-header">Pathao Courier</h5><div class="card-body">
    @if ($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
    @if ($pathaoShipment)
        <p class="small">Booking: <strong>{{ ucfirst($pathaoShipment->booking_state) }}</strong><br>Environment: {{ ucfirst($pathaoShipment->environment) }}<br>Consignment: {{ $pathaoShipment->consignment_id ?: 'Not confirmed' }}<br>Courier Status: {{ $pathaoShipment->order_status ?: 'Not confirmed' }}<br>Booked COD: BDT {{ number_format($pathaoShipment->amount_to_collect, 2) }}<br>Courier Fee: {{ $pathaoShipment->delivery_fee !== null ? 'BDT ' . $pathaoShipment->delivery_fee : 'Not available' }}<br>Last Sync: {{ $pathaoShipment->synced_at?->timezone('Asia/Dhaka')->format('d M Y h:i A') ?: 'Not yet' }}</p>
        @if ($pathaoShipment->last_error)<p class="small text-danger">{{ $pathaoShipment->last_error }}</p>@endif
        @if ($pathaoShipment->courier_reason)<p class="small" style="white-space:pre-line;overflow-wrap:anywhere">Courier Reason: {{ $pathaoShipment->courier_reason }}</p>@endif
        @if ($pathaoShipment->return_consignment_id)<p class="small">Return Consignment: {{ $pathaoShipment->return_consignment_id }}<br>Return Type: {{ $pathaoShipment->return_type }}</p>@endif
        @if ($pathaoShipment->collected_amount !== null)<p class="small">Courier-reported Collection: BDT {{ $pathaoShipment->collected_amount }}<br><span class="text-muted">Reported by Pathao; not automatically posted as a customer payment.</span></p>@endif
        @if ($pathaoShipment->invoice_id)<p class="small">Pathao Payment Invoice: {{ $pathaoShipment->invoice_id }}</p>@endif
        @if ($pathaoShipment->webhook_received_at)<p class="small text-muted">Last Webhook: {{ $pathaoShipment->webhook_received_at->timezone('Asia/Dhaka')->format('d M Y h:i A') }}</p>@endif
        @if ($pathaoShipment->consignment_id)<form method="POST" action="{{ route('admin.pathao.sync', $order) }}">@csrf<button class="btn btn-sm btn-primary" type="submit">Sync Pathao Status</button></form>@else
            <details class="mb-3"><summary>Recover / Resolve Unconfirmed Booking</summary><p class="small mt-2">Search invoice {{ $pathaoShipment->merchant_order_id }} in Pathao. If found, enter its consignment ID. The server verifies the invoice before linking it.</p><form method="POST" action="{{ route('admin.pathao.recover', $order) }}">@csrf<label class="form-label">Consignment ID<input class="form-control" name="consignment_id" maxlength="100" required></label><button class="btn btn-sm btn-primary" type="submit">Recover Consignment</button></form>
            <form class="mt-3" method="POST" action="{{ route('admin.pathao.release', $order) }}">@csrf<label class="form-check"><input class="form-check-input" type="checkbox" required name="confirmed_absent" value="1"><span class="form-check-label small">I checked Pathao and confirmed no parcel exists for this invoice. At least two minutes have passed since submission.</span></label><button class="btn btn-sm btn-label-warning mt-2" type="submit">Clear Unconfirmed Reservation</button></form></details>
        @endif
        <p class="small text-muted mt-3">Authenticated webhooks update the order automatically while the worker / scheduler runs. Manual sync is also available. Change booked parcels in the Pathao merchant panel. Local order edits and courier replacement are blocked while a shipment exists.</p>
        @foreach ($pathaoShipment->events()->latest('id')->limit(10)->get() as $event)<div class="small border-top py-2"><strong>{{ $event->order_status }}</strong> ({{ $event->source }})<br>{{ $event->created_at->timezone('Asia/Dhaka')->format('d M Y h:i A') }}</div>@endforeach
    @else
        <p class="small">This is a previous local assignment. No API parcel has been booked.</p><a class="btn btn-sm btn-primary" href="{{ route('admin.pathao.booking', $order) }}">Book with Pathao</a>
    @endif
</div></div>
@endif
