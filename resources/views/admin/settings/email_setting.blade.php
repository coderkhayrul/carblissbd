@extends('admin.layouts.app')
@section('admin-content')
<div class="row g-4">
    @include('admin.settings.settings_sidebar')
    <div class="col-12 col-lg-9">
        <div class="card mb-4"><h5 class="card-header">Email Settings</h5><div class="card-body">
            @if ($errors->any())<div class="alert alert-danger" role="alert"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
            <p>These SMTP settings send customer confirmations and new-order notifications to your Sender Email. Customer confirmations require a customer email address; admin notifications do not.</p>
            <form action="{{ route('admin.email.settings.update') }}" method="POST">
                @csrf
                <input type="hidden" name="enabled" value="0">
                <label class="form-check form-switch mb-4"><input class="form-check-input" type="checkbox" role="switch" name="enabled" value="1" @checked(old('enabled', $emailSetting->enabled))><span class="form-check-label">Enable Emails (Order Confirmations &amp; Customer Messages)</span></label>
                <div class="row g-3">
                    <div class="col-12 col-sm-6"><label for="sender_name" class="form-label">Sender Name</label><input class="form-control" id="sender_name" name="sender_name" maxlength="255" value="{{ old('sender_name', $emailSetting->sender_name) }}"></div>
                    <div class="col-12 col-sm-6"><label for="sender_email" class="form-label">Sender Email</label><input class="form-control" type="email" id="sender_email" name="sender_email" maxlength="255" value="{{ old('sender_email', $emailSetting->sender_email) }}"><small class="form-text">Use an address verified by your email provider.</small></div>
                    <div class="col-12 col-sm-6"><label for="mail_mailer" class="form-label">Mailer</label><select class="form-select" id="mail_mailer" name="mail_mailer"><option value="smtp">SMTP</option></select></div>
                    <div class="col-12 col-sm-6"><label for="mail_host" class="form-label">SMTP Host</label><input class="form-control" id="mail_host" name="mail_host" maxlength="255" placeholder="smtp.example.com" value="{{ old('mail_host', $emailSetting->mail_host) }}"></div>
                    <div class="col-12 col-sm-6"><label for="mail_port" class="form-label">SMTP Port</label><input class="form-control" type="number" id="mail_port" name="mail_port" min="1" max="65535" value="{{ old('mail_port', $emailSetting->mail_port ?: 587) }}"></div>
                    <div class="col-12 col-sm-6"><label for="mail_encryption" class="form-label">Encryption</label><select class="form-select" id="mail_encryption" name="mail_encryption">@foreach (['tls' => 'TLS / STARTTLS (usually 587)', 'ssl' => 'SSL (usually 465)', 'none' => 'None'] as $value => $label)<option value="{{ $value }}" @selected(old('mail_encryption', strtolower($emailSetting->mail_encryption ?: 'tls')) === $value)>{{ $label }}</option>@endforeach</select></div>
                    <div class="col-12 col-sm-6"><label for="mail_username" class="form-label">SMTP Username</label><input class="form-control" id="mail_username" name="mail_username" maxlength="255" autocomplete="off" value="{{ old('mail_username', $emailSetting->mail_username) }}"></div>
                    <div class="col-12 col-sm-6"><label for="mail_password" class="form-label">SMTP Password / App Password</label><input class="form-control" type="password" id="mail_password" name="mail_password" maxlength="2000" autocomplete="new-password" placeholder="{{ $emailSetting->mail_password ? 'Saved password - leave blank to keep' : 'Enter provider password' }}"><small class="form-text">Saved encrypted. Leave blank to keep the current password.</small></div>
                </div>
                <button type="submit" class="btn btn-primary mt-4">Save Email Settings</button>
            </form>
        </div></div>
        <div class="card mb-4"><h5 class="card-header">Send Test Email</h5><div class="card-body">
            @if (session('email_test_success'))<div class="alert alert-success text-break" role="status">{{ session('email_test_success') }}</div>@endif
            @if (session('email_test_error'))<div class="alert alert-danger text-break" role="alert">{{ session('email_test_error') }}</div>@endif
            <p>Save Email Settings first. This sends immediately using the saved configuration without waiting for the queue. You can test while automatic emails are disabled.</p>
            <form action="{{ route('admin.email.settings.test') }}" method="POST" id="smtp-test-form">
                @csrf
                <div class="row g-3 align-items-end"><div class="col-12 col-md-8"><label for="test_recipient" class="form-label">Test Recipient Email</label><input type="email" class="form-control" id="test_recipient" name="test_recipient" required maxlength="255" value="{{ old('test_recipient', $emailSetting->sender_email) }}"></div>
                <div class="col-12 col-md-4"><button type="submit" class="btn btn-primary w-100" id="smtp-test-button">Send Test Email</button></div></div>
            </form>
            <p class="small text-muted mt-3 mb-0">If the test succeeds but order emails do not arrive, check that Enable Emails is on and the database queue worker is running. Existing failed emails need to be retried after correcting the configuration.</p>
        </div></div>
        <script>
            document.getElementById('smtp-test-form').addEventListener('submit', function () {
                const button = document.getElementById('smtp-test-button');
                button.disabled = true;
                button.textContent = 'Sending…';
            });
        </script>
        <div class="card mb-4"><h5 class="card-header">Delivery Setup</h5><div class="card-body">
            <p>After configuring your provider, enable emails above and run a persistent database queue worker on the server:</p>
            <pre class="bg-light p-3 rounded text-wrap">php artisan queue:work database --queue=default --tries=3 --timeout=60</pre>
            <p class="mb-2">Keep the Laravel scheduler running to recover pending notifications if queue insertion is interrupted:</p>
            <pre class="bg-light p-3 rounded text-wrap">php artisan schedule:run</pre>
            <p class="small text-muted mb-0">Configure schedule:run every minute in server cron. New orders created while emails are disabled will not receive emails retroactively. SMTP failures never cancel an order. Failed jobs can be retried with Laravel queue:retry after fixing the configuration.</p>
        </div></div>
        <div class="card"><h5 class="card-header">Recent Order Emails</h5><div class="table-responsive"><table class="table"><thead><tr><th>Invoice</th><th>Recipient</th><th>Status</th><th>Sent At</th><th>Details</th></tr></thead><tbody>
            @forelse ($emailNotifications as $notification)<tr><td><a href="{{ route('admin.orders.show', $notification->order_id) }}">{{ $notification->order_no }}</a></td><td class="text-break">{{ $notification->recipient }}</td><td>{{ ucfirst($notification->status) }}</td><td class="text-nowrap">{{ $notification->sent_at ? \Illuminate\Support\Carbon::parse($notification->sent_at)->timezone('Asia/Dhaka')->format('d M Y h:i A') : '-' }}</td><td>{{ $notification->last_error ?: '-' }}</td></tr>@empty<tr><td colspan="5" class="text-center py-4">No order emails scheduled yet.</td></tr>@endforelse
        </tbody></table></div></div>
    </div>
</div>
@endsection
