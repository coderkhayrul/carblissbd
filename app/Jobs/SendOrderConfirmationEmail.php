<?php

namespace App\Jobs;

use App\Models\{CompanySetting, EmailSetting, Order};
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;

class SendOrderConfirmationEmail implements ShouldQueue
{
    use Queueable;
    public int $tries = 3;
    public int $timeout = 60;
    public function __construct(public int $notificationId) {}
    public function backoff(): array { return [60, 300, 900]; }

    public function handle(): void
    {
        try {
            DB::transaction(function () {
                $notification = DB::table('order_email_notifications')->where('id', $this->notificationId)->lockForUpdate()->first();
                if (!$notification || $notification->sent_at) return;
                $settings = EmailSetting::orderBy('id')->first();
                if (!$settings?->enabled) {
                    DB::table('order_email_notifications')->where('id', $notification->id)->update(['status' => 'skipped', 'last_error' => 'Email notifications are disabled.', 'updated_at' => now()]);
                    return;
                }
                $order = Order::with(['details', 'customerInfo', 'currentStatus'])->find($notification->order_id);
                if (!$order) return;
                $mailer = app(\App\Services\SmtpMailerService::class)->build($settings);
                $company = CompanySetting::first();
                $mailer->send('emails.order-confirmation', compact('order', 'company'), function ($message) use ($settings, $notification, $order) {
                    $message->from($settings->sender_email, $settings->sender_name)->to($notification->recipient)
                        ->subject('Order Confirmation - ' . $order->order_no);
                });
                DB::table('order_email_notifications')->where('id', $notification->id)->update(['status' => 'sent', 'sent_at' => now(), 'last_error' => null, 'updated_at' => now()]);
            });
        } catch (\Throwable) {
            DB::table('order_email_notifications')->where('id', $this->notificationId)->whereNull('sent_at')->update(['last_error' => 'Delivery failed. Check SMTP credentials, encryption and provider availability.', 'updated_at' => now()]);
            throw new \RuntimeException('Order email delivery failed. Check Email Settings and SMTP provider availability.');
        }
    }

    public function failed(?\Throwable $exception): void
    {
        DB::table('order_email_notifications')->where('id', $this->notificationId)->whereNull('sent_at')->update(['status' => 'failed', 'last_error' => 'Delivery failed after automatic retries. Check Email Settings.', 'updated_at' => now()]);
    }
}
