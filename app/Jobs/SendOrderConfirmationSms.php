<?php

namespace App\Jobs;

use App\Models\{CompanySetting, OrderSmsNotification, SMSApiSetting};
use App\Support\BangladeshPhone;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;

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
            $result = app(\App\Services\SmsGatewayService::class)->send($settings, $phone, $message);
            $accepted = $result['accepted'];
            $notification->update([
                'status' => $accepted ? 'submitted' : 'failed', 'http_status' => $result['http_status'],
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
