<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

// Durable inbox fallback if the webhook queue worker / dispatch was temporarily unavailable.
Schedule::call(function () {
    if (!\App\Models\SteadfastSetting::find(1)?->enabled) return;
    \App\Models\SteadfastWebhook::whereNull('processed_at')->where(function ($query) {
        $query->whereNull('retry_at')->orWhere('retry_at', '<=', now());
    })->orderBy('id')->limit(30)->pluck('id')->each(function ($id) {
        app(\App\Services\SteadfastWebhookService::class)->process($id);
    });
})->name('recover-steadfast-webhooks')->everyMinute()->withoutOverlapping(30);

// Webhooks handle immediate updates; this also recovers missed callbacks / uncertain bookings.
Schedule::call(function () {
    if (!\App\Models\SteadfastSetting::find(1)?->enabled) return;
    \App\Models\SteadfastShipment::where(function ($query) {
        $query->whereNull('delivery_status')->orWhereNotIn('delivery_status', ['delivered', 'partial_delivered', 'cancelled'])
            ->orWhere(function ($returns) {
                $returns->whereIn('delivery_status', ['partial_delivered', 'cancelled'])->where(function ($status) {
                    $status->whereNull('return_status')->orWhereNotIn('return_status', ['partial_delivered_return_received', 'cancelled_return_received']);
                });
            });
    })->where(function ($query) {
        $query->whereNull('checked_at')->orWhere('checked_at', '<', now()->subMinutes(5));
    })->orderBy('checked_at')->orderBy('id')->limit(30)->get()->each(function ($shipment) {
        try {
            app(\App\Services\SteadfastService::class)->sync($shipment, 'scheduled');
        } catch (\Throwable $exception) {
            // Persist a safe error and move this invoice behind other shipments for the next pass.
            $shipment->update(['checked_at' => now(), 'last_error' => 'Scheduled sync failed. Use Sync Status or check configuration.']);
        }
    });
})->name('sync-steadfast-shipments')->everyFiveMinutes()->withoutOverlapping(30);

// Recover durable notifications if a queue insertion was interrupted after order commit.
Schedule::call(function () {
    \App\Models\OrderSmsNotification::where('status', 'pending')->where('created_at', '<', now()->subMinute())
        ->orderBy('id')->chunkById(100, function ($notifications) {
            foreach ($notifications as $notification) {
                \App\Jobs\SendOrderConfirmationSms::dispatch($notification->id)->onConnection('database');
            }
        });
})->name('dispatch-pending-order-sms')->everyFiveMinutes()->withoutOverlapping();

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    if (!\App\Models\EmailSetting::orderBy('id')->first()?->enabled) return;
    \Illuminate\Support\Facades\DB::table('order_email_notifications')->where('status', 'pending')
        ->where('updated_at', '<', now()->subMinutes(15))->orderBy('id')->chunkById(100, function ($notifications) {
            foreach ($notifications as $notification) {
                \App\Jobs\SendOrderConfirmationEmail::dispatch($notification->id)->onConnection('database');
                \Illuminate\Support\Facades\DB::table('order_email_notifications')->where('id', $notification->id)->update(['updated_at' => now()]);
            }
        });
})->name('dispatch-pending-order-email')->everyFiveMinutes()->withoutOverlapping();
