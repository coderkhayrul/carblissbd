<?php

namespace App\Jobs;

use App\Models\{CompanySetting, EmailSetting, Order};
use App\Services\SmtpMailerService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;

class SendAdminOrderEmail implements ShouldQueue
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
                $notification = DB::table('admin_order_email_notifications')->where('id', $this->notificationId)->lockForUpdate()->first();
                if (!$notification || $notification->sent_at) return;
                $settings = EmailSetting::orderBy('id')->first();
                // Leave pending until SMTP is enabled and a valid configured sender exists.
                if (!$settings?->enabled || !filter_var($settings->sender_email, FILTER_VALIDATE_EMAIL)) return;
                $order = Order::with(['details', 'customerInfo', 'currentStatus'])->find($notification->order_id);
                if (!$order) return;
                $recipient = $notification->recipient ?: $settings->sender_email;
                $company = CompanySetting::orderBy('id')->first();
                app(SmtpMailerService::class)->build($settings)->send('emails.admin-new-order', compact('order', 'company'), function ($message) use ($settings, $recipient, $order) {
                    $message->from($settings->sender_email, $settings->sender_name)
                        ->to($recipient)->subject('New Order Received | ' . $order->order_no);
                });
                DB::table('admin_order_email_notifications')->where('id', $notification->id)->update([
                    'recipient' => $recipient, 'status' => 'sent', 'sent_at' => now(), 'last_error' => null, 'updated_at' => now(),
                ]);
            });
        } catch (\Throwable $exception) {
            DB::table('admin_order_email_notifications')->where('id', $this->notificationId)->whereNull('sent_at')->update([
                'last_error' => app(SmtpMailerService::class)->failureMessage($exception), 'updated_at' => now(),
            ]);
            throw new \RuntimeException('Admin order email delivery failed. Check Email Settings.');
        }
    }

    public function failed(?\Throwable $exception): void
    {
        DB::table('admin_order_email_notifications')->where('id', $this->notificationId)->whereNull('sent_at')->update([
            'status' => 'failed', 'updated_at' => now(),
        ]);
    }
}
