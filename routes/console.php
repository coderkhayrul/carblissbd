<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

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
