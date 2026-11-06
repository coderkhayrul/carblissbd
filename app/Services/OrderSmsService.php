<?php

namespace App\Services;

use App\Jobs\SendOrderConfirmationSms;
use App\Models\{Order, OrderSmsNotification, SMSApiSetting};
use Illuminate\Support\Facades\{DB, Log};

class OrderSmsService
{
    public function schedule(Order $order): void
    {
        // An unconfigured integration must never call the seeded demo provider.
        if (!SMSApiSetting::orderBy('id')->first()?->enabled) return;

        $notification = OrderSmsNotification::firstOrCreate(['order_id' => $order->id]);
        if (!$notification->wasRecentlyCreated) return;

        DB::afterCommit(function () use ($notification) {
            try {
                SendOrderConfirmationSms::dispatch($notification->id)->onConnection('database');
            } catch (\Throwable) {
                // The durable pending record can be recovered by the scheduled dispatcher.
                Log::warning('Order SMS could not be queued.', ['notification_id' => $notification->id]);
            }
        });
    }
}
