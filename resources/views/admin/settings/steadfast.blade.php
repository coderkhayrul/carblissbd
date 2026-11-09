@extends('admin.layouts.app')
@section('admin-content')
<h4 class="mb-4">Steadfast Courier Configuration</h4>
@if ($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
<div class="row g-4">
    <div class="col-12 col-xl-8"><div class="card"><div class="card-body">
        <form method="POST" action="{{ route('admin.steadfast.save') }}" autocomplete="off">
            @csrf
            <input type="hidden" name="enabled" value="0">
            <label class="form-check form-switch mb-4"><input type="checkbox" class="form-check-input" role="switch" name="enabled" value="1" @checked(old('enabled', $settings->enabled))><span class="form-check-label">Enable Steadfast booking and status sync</span></label>
            <p class="small text-muted">Keys are encrypted in the database. Leave credentials blank to keep the saved values. Use the API address shown in your merchant documentation.</p>
            @if ($settings->auth_blocked)<div class="alert alert-danger">API calls are paused after a 401/403 response. Correct the API keys or account access, then enter and save the keys again. Signed webhook events can still be received.</div>@endif
            @if ($settings->api_paused_until?->isFuture())<div class="alert alert-warning">API rate limit: requests are paused until {{ $settings->api_paused_until->timezone('Asia/Dhaka')->format('d M Y h:i A') }}.</div>@endif
            <div class="row g-3">
                <div class="col-12"><label for="steadfast-base-url" class="form-label">API Address</label><select id="steadfast-base-url" class="form-select" name="base_url">@foreach (\App\Services\SteadfastService::API_URLS as $apiUrl)<option value="{{ $apiUrl }}" @selected(old('base_url', $settings->base_url) === $apiUrl)>{{ $apiUrl }}</option>@endforeach</select></div>
                <div class="col-12 col-md-6"><label class="form-label" for="steadfast-api-key">API Key</label><input id="steadfast-api-key" type="password" name="steadfast_api_key" class="form-control" maxlength="1000" autocomplete="new-password" placeholder="{{ $settings->api_key ? 'Saved — leave blank to keep' : 'Enter API Key' }}"></div>
                <div class="col-12 col-md-6"><label class="form-label" for="steadfast-secret-key">Secret Key</label><input id="steadfast-secret-key" type="password" name="steadfast_secret_key" class="form-control" maxlength="1000" autocomplete="new-password" placeholder="{{ $settings->secret_key ? 'Saved — leave blank to keep' : 'Enter Secret Key' }}"></div>
                <div class="col-12"><label class="form-label" for="steadfast-token">Replace Webhook Bearer Token (optional)</label><input id="steadfast-token" name="steadfast_webhook_token" type="password" class="form-control" minlength="32" maxlength="128" autocomplete="new-password"><small class="text-muted">32–128 letters, numbers or - _ . = characters. Use the identical token in both panels. A token is generated automatically.</small></div>
            </div>
            <h5 class="mt-4">Courier Status → Order Status</h5>
            <p class="small">Select the order status to apply automatically. “Keep current status” still updates the courier status and history. Approval-pending and partial delivery statuses need review; keep them unmapped unless you have an appropriate order status.</p>
            <div class="row g-3">
                @foreach (\App\Services\SteadfastService::MAPPABLE_STATUSES as $providerStatus)
                    <div class="col-12 col-md-6"><label for="map-{{ $providerStatus }}" class="form-label">{{ str_replace('_', ' ', ucfirst($providerStatus)) }}</label><select id="map-{{ $providerStatus }}" class="form-select" name="mapping[{{ $providerStatus }}]"><option value="">Keep current status</option>@foreach ($statuses as $status)<option value="{{ $status->id }}" @selected((string) old('mapping.' . $providerStatus, $mapping[$providerStatus] ?? '') === (string) $status->id)>{{ $status->name }}</option>@endforeach</select></div>
                @endforeach
            </div>
            <button class="btn btn-primary mt-4" type="submit">Save Configuration</button>
        </form>
        <form class="mt-3" method="POST" action="{{ route('admin.steadfast.connection') }}">@csrf<button class="btn btn-label-primary" type="submit">Check Connection &amp; Balance</button><small class="d-block mt-2 text-muted">Save credentials first. This only reads your balance; it does not book a parcel.</small></form>
    </div></div></div>
    <div class="col-12 col-xl-4"><div class="card"><h5 class="card-header">Realtime Webhook Setup</h5><div class="card-body">
        <p>In your Steadfast merchant panel, save this Callback URL and Auth Token (Bearer):</p>
        <label class="form-label" for="steadfast-callback">Callback URL</label><input id="steadfast-callback" class="form-control mb-3" readonly value="{{ route('steadfast.webhook') }}">
        <label class="form-label" for="steadfast-current-token">Auth Token (Bearer)</label><input id="steadfast-current-token" class="form-control mb-3" type="password" readonly value="{{ $settings->webhook_token }}"><button type="button" class="btn btn-sm btn-label-secondary mb-3" onclick="const field=document.getElementById('steadfast-current-token');field.type=field.type==='password'?'text':'password'">Show / Hide Token</button>
        <p class="small">Copy only the token; Steadfast sends Authorization: Bearer TOKEN and X-Signature (HMAC-SHA256). Both are verified against the original request body. Duplicate Idempotency-Key events are accepted once.</p>
        <div class="alert alert-info small">The callback must use your public HTTPS domain. The .test URL in your screenshot is local and cannot receive Steadfast callbacks. Set the production APP_URL and register the public callback URL in Steadfast.</div>
        <p class="small">Events are saved and acknowledged immediately with HTTP 202. A background worker applies updates without waiting for a courier API response.</p>
        <p class="small">For realtime updates, keep this worker running: <code>php artisan queue:work database --queue=default --tries=3 --timeout=60</code>.</p>
        <p class="small">Run <code>php artisan schedule:run</code> every minute in server cron. It recovers pending webhook events every minute and reconciles missed courier statuses every five minutes. API status reads are spaced at least 60 seconds apart per parcel.</p>
        <p class="small">Order List → courier icon → Assign Steadfast sends a real booking. The order view shows tracking, sync controls and courier history.</p>
        <div class="alert alert-warning mb-0">Cancel Order cancels our local order. For a booked parcel, also cancel in the Steadfast merchant panel. The published API does not provide a direct parcel cancellation endpoint. Courier sync reflects Steadfast's actual status.</div>
    </div></div></div>
</div>
<div class="card mt-4"><h5 class="card-header">Recent Webhook Deliveries</h5><div class="table-responsive"><table class="table"><thead><tr><th>Received</th><th>Event</th><th>Processing</th><th>Details</th></tr></thead><tbody>
    @forelse ($webhookEvents as $event)<tr><td class="text-nowrap">{{ $event->created_at->timezone('Asia/Dhaka')->format('d M Y h:i A') }}</td><td>{{ $event->notification_type }}</td><td>{{ $event->status }} ({{ $event->attempts }} attempts)</td><td>{{ $event->last_error ?: ($event->processed_at ? 'Handled' : 'Waiting for worker / scheduler') }}</td></tr>@empty<tr><td colspan="4">No authenticated webhook events received yet.</td></tr>@endforelse
</tbody></table></div></div>
@endsection
