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
