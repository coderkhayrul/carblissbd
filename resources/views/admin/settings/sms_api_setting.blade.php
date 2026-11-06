@extends('admin.layouts.app')
@section('admin-content')
    <div class="row g-6">
        @include('admin.settings.settings_sidebar')
        <div class="col-12 col-lg-9">
            <div class="card mb-6">
                <h5 class="card-header">SMS API Settings</h5>
                <div class="card-body">
                    <p class="text-muted">Order confirmations from website checkout and admin sales use these settings. SMS stays disabled until you configure your provider and enable it.</p>
                    @if ($errors->any())
                        <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
                    @endif
                    <form action="{{ route('admin.sms.api.settings.update') }}" method="POST">
                        @csrf
                        <div class="row g-4">
                            <div class="col-12">
                                <label class="form-label" for="enabled">Automatic order confirmation SMS</label>
                                <select class="form-select" id="enabled" name="enabled">
                                    <option value="0" @selected(!old('enabled', $smsApiSetting->enabled))>Disabled</option>
                                    <option value="1" @selected(old('enabled', $smsApiSetting->enabled))>Enabled</option>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label" for="base_url">Provider API endpoint</label>
                                <input class="form-control" type="url" id="base_url" name="base_url" value="{{ old('base_url', $smsApiSetting->base_url) }}" placeholder="https://provider.example/api/send" maxlength="255">
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label" for="sender_id">Sender ID</label>
                                <input class="form-control" id="sender_id" name="sender_id" value="{{ old('sender_id', $smsApiSetting->sender_id) }}" maxlength="255">
                            </div>
                            @foreach (['api_key' => 'API Key', 'api_secret' => 'API Secret (optional)'] as $field => $label)
                                <div class="col-sm-6">
                                    <label class="form-label" for="{{ $field }}">{{ $label }}</label>
                                    <input class="form-control" type="password" id="{{ $field }}" name="{{ $field }}" autocomplete="new-password" maxlength="255" placeholder="{{ $smsApiSetting->{$field} ? 'Saved — leave blank to keep' : 'Not configured' }}">
                                </div>
                            @endforeach
                            <div class="col-sm-4">
                                <label class="form-label" for="request_method">HTTP method</label>
                                <select class="form-select" id="request_method" name="request_method">
                                    @foreach (['POST', 'GET'] as $method)<option value="{{ $method }}" @selected(old('request_method', $smsApiSetting->request_method ?: 'POST') === $method)>{{ $method }}</option>@endforeach
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <label class="form-label" for="request_format">Request format</label>
                                <select class="form-select" id="request_format" name="request_format">
                                    @foreach (['form' => 'Form fields', 'json' => 'JSON'] as $value => $label)<option value="{{ $value }}" @selected(old('request_format', $smsApiSetting->request_format ?: 'form') === $value)>{{ $label }}</option>@endforeach
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <label class="form-label" for="phone_format">Recipient number format</label>
                                <select class="form-select" id="phone_format" name="phone_format">
                                    @foreach (['international' => '+8801XXXXXXXXX', 'digits' => '8801XXXXXXXXX', 'local' => '01XXXXXXXXX'] as $value => $label)<option value="{{ $value }}" @selected(old('phone_format', $smsApiSetting->phone_format ?: 'international') === $value)>{{ $label }}</option>@endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="request_parameters">Provider request parameters (JSON object)</label>
                                <textarea class="form-control font-monospace" id="request_parameters" name="request_parameters" rows="7" maxlength="10000">{{ old('request_parameters', $smsApiSetting->request_parameters ?: json_encode(config('sms.parameters'), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)) }}</textarea>
                                <div class="form-text">Match field names to the provider documentation. Available values: {phone}, {message}, {api_key}, {api_secret}, {sender_id}. Add fixed fields such as a message type if required. GET requests send parameters in the query string.</div>
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="request_headers">Request headers (JSON object, optional)</label>
                                <textarea class="form-control font-monospace" id="request_headers" name="request_headers" rows="3" maxlength="10000" placeholder='{"Authorization": "Bearer {api_key}"}'>{{ old('request_headers', $smsApiSetting->request_headers) }}</textarea>
                                <div class="form-text">Use placeholders for credentials instead of typing secrets directly into this field.</div>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label" for="success_path">JSON success field (optional)</label>
                                <input class="form-control" id="success_path" name="success_path" value="{{ old('success_path', $smsApiSetting->success_path) }}" placeholder="status or data.status" maxlength="255">
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label" for="success_value">Expected success value</label>
                                <input class="form-control" id="success_value" name="success_value" value="{{ old('success_value', $smsApiSetting->success_value) }}" placeholder="success, 202, or true" maxlength="255">
                            </div>
                            <div class="col-12"><p class="form-text mb-0">Configure the provider's success field when available: some APIs return HTTP 200 for rejected SMS. Without it, any HTTP 2xx response is recorded as submitted. Submitted means sent to the API, not confirmed delivery to the handset.</p></div>
                            <div class="col-12">
                                <label class="form-label" for="message_template">Order confirmation message</label>
                                <textarea class="form-control" id="message_template" name="message_template" rows="4" maxlength="1000">{{ old('message_template', $smsApiSetting->message_template ?: config('sms.message')) }}</textarea>
                                <div class="form-text">Available placeholders: {company}, {customer}, {order_no}, {total}, {hotline}. Bangla text is supported when your provider supports Unicode; set its Unicode parameter above if needed. Long messages may use multiple SMS credits.</div>
                            </div>
                            <div class="col-12 text-end"><button class="btn btn-primary" type="submit">Save SMS Settings</button></div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card">
                <h5 class="card-header">Recent Order SMS</h5>
                <div class="card-body">
                    <p class="text-muted small">Orders remain saved if SMS fails. Failed or interrupted requests are not automatically resent to avoid duplicate messages. Pending messages require the queue worker.</p>
                    <div class="table-responsive">
                        <table class="table"><thead><tr><th>Order</th><th>Mobile</th><th>Status</th><th>Details</th><th>Created</th></tr></thead><tbody>
                            @forelse ($smsNotifications as $notification)
                                <tr>
                                    <td>{{ $notification->order?->order_no ?: $notification->order_id }}</td>
                                    <td>{{ $notification->phone ? '••••' . substr($notification->phone, -4) : '—' }}</td>
                                    <td>{{ ucfirst($notification->status) }}</td>
                                    <td>{{ $notification->error ?: ($notification->http_status ? 'HTTP ' . $notification->http_status : '—') }}</td>
                                    <td>{{ $notification->created_at->format('d M Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-muted">No order SMS recorded yet.</td></tr>
                            @endforelse
                        </tbody></table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection