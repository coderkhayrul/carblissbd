@extends('admin.layouts.app')
@section('admin-content')
<h4 class="mb-4">Pathao Courier Configuration</h4>
@if ($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
<div class="row g-4">
    <div class="col-12 col-xl-8"><div class="card mb-4"><h5 class="card-header">Merchant API Settings</h5><div class="card-body">
        <form method="POST" action="{{ route('admin.pathao.save') }}" autocomplete="off">@csrf
            <input type="hidden" name="enabled" value="0"><label class="form-check form-switch mb-3"><input type="checkbox" class="form-check-input" role="switch" name="enabled" value="1" @checked(old('enabled', $settings->enabled))><span class="form-check-label">Enable Pathao</span></label>
            <input type="hidden" name="webhook_enabled" value="0"><label class="form-check form-switch mb-3"><input type="checkbox" class="form-check-input" role="switch" name="webhook_enabled" value="1" @checked(old('webhook_enabled', $settings->webhook_enabled))><span class="form-check-label">Enable Pathao Webhooks</span></label>
            <p class="small text-muted">Credentials and tokens are encrypted. Leave credential fields blank to keep saved values. Changing environment clears tokens, store list and selected pickup store.</p>
            @if ($settings->auth_blocked)<div class="alert alert-danger">Authentication is paused. Correct and save the credentials before reconnecting.</div>@endif
            <div class="row g-3">
                <div class="col-12"><label class="form-label" for="pathao-environment">Environment</label><select id="pathao-environment" class="form-select" name="environment">@foreach (\App\Services\PathaoService::HOSTS as $environment => $host)<option value="{{ $environment }}" @selected(old('environment', $settings->environment) === $environment)>{{ ucfirst($environment) }} — {{ $host }}</option>@endforeach</select></div>
                <div class="col-12 col-md-6"><label class="form-label" for="pathao-client-id">Client ID</label><input id="pathao-client-id" class="form-control" type="password" name="pathao_client_id" autocomplete="new-password" maxlength="255" placeholder="{{ $settings->client_id ? 'Saved - leave blank to keep' : 'Enter Client ID' }}"></div>
                <div class="col-12 col-md-6"><label class="form-label" for="pathao-client-secret">Client Secret</label><input id="pathao-client-secret" class="form-control" type="password" name="pathao_client_secret" autocomplete="new-password" maxlength="1000" placeholder="{{ $settings->client_secret ? 'Saved - leave blank to keep' : 'Enter Client Secret' }}"></div>
                <div class="col-12 col-md-6"><label class="form-label" for="pathao-username">Merchant Login Email</label><input id="pathao-username" class="form-control" type="email" name="pathao_username" autocomplete="off" maxlength="255" placeholder="{{ $settings->username ? 'Saved - leave blank to keep' : 'Pathao merchant email' }}"></div>
                <div class="col-12 col-md-6"><label class="form-label" for="pathao-password">Merchant Login Password</label><input id="pathao-password" class="form-control" type="password" name="pathao_password" autocomplete="new-password" maxlength="1000" placeholder="{{ $settings->password ? 'Saved - leave blank to keep' : 'Pathao merchant password' }}"></div>
                <div class="col-12"><label class="form-label" for="pathao-webhook-secret">Replace Webhook Secret (optional)</label><input id="pathao-webhook-secret" class="form-control" type="password" name="pathao_webhook_secret" autocomplete="new-password" minlength="32" maxlength="128"><small class="text-muted">Leave blank to keep the generated secret. If replaced, save the identical new secret in Pathao too.</small></div>
                <div class="col-12"><label class="form-label" for="pathao-store-id">Default Pickup Store</label><select class="form-select" id="pathao-store-id" name="store_id"><option value="">Load stores and select a pickup store</option>@foreach ($settings->stores ?? [] as $store)@if ((int) ($store['is_active'] ?? 0) === 1)<option value="{{ $store['store_id'] }}" @selected((string) old('store_id', $settings->store_id) === (string) $store['store_id'])>{{ $store['store_name'] ?? $store['store_id'] }} — {{ $store['store_address'] ?? '' }}</option>@endif @endforeach</select></div>
                <div class="col-12 col-md-4"><label class="form-label">Delivery Type<select name="delivery_type" class="form-select"><option value="48" @selected((int) old('delivery_type', $settings->delivery_type) === 48)>Normal (48)</option><option value="12" @selected((int) old('delivery_type', $settings->delivery_type) === 12)>On Demand (12)</option></select></label></div>
                <div class="col-12 col-md-4"><label class="form-label">Item Type<select name="item_type" class="form-select"><option value="2" @selected((int) old('item_type', $settings->item_type) === 2)>Parcel</option><option value="1" @selected((int) old('item_type', $settings->item_type) === 1)>Document</option></select></label></div>
                <div class="col-12 col-md-4"><label class="form-label">Default Weight (kg)<input type="number" name="item_weight" class="form-control" min="0.5" max="10" step="0.01" required value="{{ old('item_weight', $settings->item_weight) }}"></label></div>
            </div>
            <h6 class="mt-4">Status Mapping</h6><p class="small">Map exact Pathao webhook events (such as order.delivered) or Order Info status slugs. Unmapped states retain the current order status. Payment Invoice, Order Updated and Return ID Created store information without changing delivery status. A returned partial / exchange remainder never marks the whole order returned automatically.</p>
            @php
                $defaultPending = $statuses->first(fn ($status) => strcasecmp($status->name, 'Processing') === 0)?->id;
                $savedMapping = $settings->status_mapping ?? ['pending' => $defaultPending];
                $eventKeys = array_filter(array_keys(\App\Services\PathaoWebhookService::EVENTS), fn ($key) => str_starts_with($key, 'order.'));
                $allKeys = array_values(array_unique(array_merge(array_keys($savedMapping), $eventKeys)));
                $mappingKeys = old('mapping_keys', $allKeys);
                $mappingValues = old('mapping_values', array_map(fn ($key) => $savedMapping[$key] ?? '', $allKeys));
            @endphp
            @for ($index = 0; $index < min(50, max(6, count($mappingKeys) + 2)); $index++)<div class="row g-2 mb-2"><div class="col-6"><input class="form-control" name="mapping_keys[]" maxlength="100" placeholder="Pathao status / slug" aria-label="Pathao status {{ $index + 1 }}" value="{{ $mappingKeys[$index] ?? '' }}"></div><div class="col-6"><select class="form-select" name="mapping_values[]" aria-label="Order status {{ $index + 1 }}"><option value="">Keep current order status</option>@foreach ($statuses as $status)<option value="{{ $status->id }}" @selected((string) ($mappingValues[$index] ?? '') === (string) $status->id)>{{ $status->name }}</option>@endforeach</select></div></div>@endfor
            <button type="submit" class="btn btn-primary mt-3">Save Configuration</button>
        </form>
        <form method="POST" class="mt-3" action="{{ route('admin.pathao.connect') }}">@csrf<button type="submit" class="btn btn-label-primary">Connect / Load Stores</button></form>
    </div></div></div>
    <div class="col-12 col-xl-4"><div class="card"><h5 class="card-header">Setup Steps</h5><div class="card-body">
        <ol class="ps-3"><li class="mb-2">Save merchant Client ID, Secret, login email and password in the correct environment. Enable Pathao.</li><li class="mb-2">Click Connect / Load Stores to obtain a token and fetch your stores.</li><li class="mb-2">Choose an active pickup store and save configuration again.</li><li class="mb-2">Order List → courier menu → Assign Pathao opens the booking form.</li><li>Review recipient, parcel weight, quantity and COD. Optionally select location and calculate the courier fee before booking.</li></ol>
        <p class="small">Access tokens are persisted encrypted and refreshed automatically before expiry. They are never sent to the browser.</p>
        <p class="small">Token expiry: {{ $settings->token_expires_at?->timezone('Asia/Dhaka')->format('d M Y h:i A') ?: 'Not connected' }}</p>
        <p class="small">A price quote does not change the order's customer delivery charge. Booked COD is the outstanding order amount, in whole taka.</p>
        <h6 class="mt-4">Webhook Setup</h6>
        <label class="form-label" for="pathao-callback">Callback URL</label><input id="pathao-callback" class="form-control mb-3" readonly value="{{ route('pathao.webhook') }}">
        <label class="form-label" for="pathao-current-webhook-secret">Webhook Secret</label><input id="pathao-current-webhook-secret" class="form-control mb-2" type="password" readonly value="{{ $settings->webhook_secret }}"><button class="btn btn-sm btn-label-secondary mb-3" type="button" onclick="const field=document.getElementById('pathao-current-webhook-secret');field.type=field.type==='password'?'text':'password';field.select()">Show / Hide Secret</button>
        <p class="small">Save this callback and the same secret in Pathao → Webhook Integration → Add Webhook. Select the events you want to receive. All documented events are accepted. The fixed integration response header from Pathao's documentation is handled automatically; it is different from this private secret.</p>
        <p class="small">Last authenticated callback: {{ $settings->webhook_verified_at?->timezone('Asia/Dhaka')->format('d M Y h:i A') ?: 'Not received yet' }}</p>
        <div class="alert alert-info small">A .test / localhost URL is not reachable from Pathao. Register your public HTTPS domain with a valid certificate. Update APP_URL on the live server.</div>
        <p class="small">Keep this worker running for realtime updates: <code>php artisan queue:work database --queue=default --tries=3 --timeout=60</code>.</p>
        <p class="small mb-0">Run <code>php artisan schedule:run</code> every minute in server cron. It recovers queued webhook events if dispatch or the worker is temporarily unavailable. Open orders show saved updates after refresh.</p>
    </div></div></div>
</div>
<div class="card mt-4"><h5 class="card-header">Recent Pathao Webhook Events</h5><div class="table-responsive"><table class="table"><thead><tr><th>Received</th><th>Event</th><th>Processing</th><th>Details</th></tr></thead><tbody>@forelse ($webhookEvents as $webhookEvent)<tr><td class="text-nowrap">{{ $webhookEvent->created_at->timezone('Asia/Dhaka')->format('d M Y h:i A') }}</td><td>{{ $webhookEvent->event }}</td><td>{{ $webhookEvent->status }} ({{ $webhookEvent->attempts }} attempts)</td><td>{{ $webhookEvent->last_error ?: ($webhookEvent->processed_at ? 'Handled' : 'Waiting for worker / scheduler') }}</td></tr>@empty<tr><td colspan="4">No authenticated webhook events received yet.</td></tr>@endforelse</tbody></table></div></div>
<div class="card mt-4"><h5 class="card-header">Create New Pathao Store</h5><div class="card-body">
    <p class="small">Use this only to register a new pickup store. Pathao may require an hour for approval. If submission times out, check the merchant panel before submitting again.</p>
    <form method="POST" action="{{ route('admin.pathao.stores.create') }}">@csrf<div class="row g-3">
        <div class="col-12 col-md-6"><label class="form-label">Store Name<input class="form-control" name="name" required minlength="3" maxlength="50" value="{{ old('name') }}"></label></div>
        <div class="col-12 col-md-6"><label class="form-label">Contact Name<input class="form-control" name="contact_name" required minlength="3" maxlength="50" value="{{ old('contact_name') }}"></label></div>
        <div class="col-12 col-md-4"><label class="form-label">Contact Mobile<input class="form-control" name="contact_number" required inputmode="tel" pattern="01[3-9][0-9]{8}" placeholder="01XXXXXXXXX" value="{{ old('contact_number') }}"></label></div>
        <div class="col-12 col-md-4"><label class="form-label">Secondary Mobile (optional)<input class="form-control" name="secondary_contact" inputmode="tel" pattern="01[3-9][0-9]{8}" value="{{ old('secondary_contact') }}"></label></div>
        <div class="col-12 col-md-4"><label class="form-label">OTP Mobile (optional)<input class="form-control" name="otp_number" inputmode="tel" pattern="01[3-9][0-9]{8}" value="{{ old('otp_number') }}"></label></div>
        <div class="col-12"><label class="form-label w-100">Store Address<textarea class="form-control" name="address" minlength="15" maxlength="120" required rows="2">{{ old('address') }}</textarea></label></div>
    </div><div class="my-3">@include('admin.partials.pathao-locations')</div><button class="btn btn-primary" type="submit">Create Pathao Store</button></form>
</div></div>
@endsection
@push('backend_script')<script src="{{ asset('backend/assets/js/pathao-courier.js') }}" defer></script>@endpush
