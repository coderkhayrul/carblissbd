<?php

namespace App\Jobs;

use App\Models\{CompanySetting, OrderSmsNotification, SMSApiSetting};
use App\Support\BangladeshPhone;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\{DB, Http};

class SendOrderConfirmationSms implements ShouldQueue
{
    use Queueable;

    // Do not automatically resend an ambiguous request: the provider may have accepted it.
    public int $tries = 1;
    public int $timeout = 30;

    public function __construct(public int $notificationId) {}

    public function handle(): void
    {
        $notification = DB::transaction(function () {
            $row = OrderSmsNotification::whereKey($this->notificationId)->lockForUpdate()->first();
            if (!$row || $row->status !== 'pending') return null;
            $row->update(['status' => 'processing']);
            return $row;
        });
        if (!$notification) return;

        try {
            $settings = SMSApiSetting::orderBy('id')->first();
            if (!$settings?->enabled) {
                $notification->update(['status' => 'skipped', 'error' => 'SMS is disabled.']);
                return;
            }
            if (!$settings->base_url || !$settings->api_key || !$settings->sender_id) {
                $notification->update(['status' => 'failed', 'error' => 'SMS provider configuration is incomplete.']);
                return;
            }
            $order = $notification->order()->with(['customerInfo', 'customer'])->firstOrFail();
            $phone = BangladeshPhone::normalize($order->customerInfo?->phone ?: $order->customer?->mobile ?: '');
            if (!BangladeshPhone::valid($phone)) {
                $notification->update(['status' => 'failed', 'error' => 'Invalid customer mobile number.']);
                return;
            }
            $company = CompanySetting::orderBy('id')->first();
            $message = strtr($settings->message_template ?: config('sms.message'), [
                '{company}' => $company?->name ?: config('app.name'),
                '{order_no}' => $order->order_no, '{total}' => number_format((float) $order->grand_total, 2, '.', ''),
                '{customer}' => $order->customerInfo?->name ?: 'Customer', '{hotline}' => $company?->hotline ?: '',
            ]);
            $notification->update(['phone' => $phone, 'message' => $message]);
            $phone = match ($settings->phone_format) {
                'local' => substr($phone, 3), 'digits' => ltrim($phone, '+'), default => $phone,
            };
            $values = ['{phone}' => $phone, '{message}' => $message, '{api_key}' => $settings->api_key,
                '{api_secret}' => $settings->api_secret ?? '', '{sender_id}' => $settings->sender_id];
            $parameters = $settings->request_parameters ? json_decode($settings->request_parameters, true, 512, JSON_THROW_ON_ERROR) : config('sms.parameters');
            $headers = $settings->request_headers ? json_decode($settings->request_headers, true, 512, JSON_THROW_ON_ERROR) : [];
            array_walk_recursive($parameters, function (&$value) use ($values) { if (is_string($value)) $value = strtr($value, $values); });
            array_walk_recursive($headers, function (&$value) use ($values) { if (is_string($value)) $value = strtr($value, $values); });
            $client = Http::connectTimeout(5)->timeout(15)->withoutRedirecting()->withHeaders($headers);
            if ($settings->request_format === 'form') $client = $client->asForm();
            $response = $settings->request_method === 'GET'
                ? $client->get($settings->base_url, $parameters)
                : $client->post($settings->base_url, $parameters);
            $accepted = $response->successful();
            if ($settings->success_path) {
                $actual = data_get($response->json(), $settings->success_path);
                $actual = is_bool($actual) ? ($actual ? 'true' : 'false') : $actual;
                $accepted = $accepted && is_scalar($actual) && (string) $actual === (string) $settings->success_value;
            }
            $notification->update([
                'status' => $accepted ? 'submitted' : 'failed', 'http_status' => $response->status(),
                'submitted_at' => $accepted ? now() : null,
                'error' => $accepted ? null : 'Provider rejected the request or the configured success response did not match.',
            ]);
        } catch (\Throwable) {
            // Provider URLs, credentials and response bodies must not enter error logs.
            $notification->update(['status' => 'failed', 'error' => 'SMS request failed. Check provider configuration/connectivity before resending.']);
        }
    }

    public function failed(?\Throwable $exception): void
    {
        OrderSmsNotification::whereKey($this->notificationId)->where('status', 'processing')
            ->update(['status' => 'failed', 'error' => 'SMS processing stopped. Verify provider records before resending.']);
    }
}
