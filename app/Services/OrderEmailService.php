<?php

namespace App\Services;

use App\Models\{EmailSetting, Order};
use App\Jobs\SendOrderConfirmationEmail;
use Illuminate\Support\Facades\{DB, Log};

class OrderEmailService
{
    public function schedule(Order $order): void
    {
        // Queue only after the complete order has committed. Demo settings stay disabled.
        DB::afterCommit(function () use ($order) {
            try {
                if (!EmailSetting::orderBy('id')->first()?->enabled) return;
                $email = $order->customerInfo()->value('email') ?: $order->customer()->value('email');
                if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) return;
                $notification = DB::table('order_email_notifications')->where('order_id', $order->id)->first();
                if ($notification) return;
                $id = DB::table('order_email_notifications')->insertGetId([
                    'order_id' => $order->id, 'recipient' => $email, 'status' => 'pending', 'created_at' => now(), 'updated_at' => now(),
                ]);
                SendOrderConfirmationEmail::dispatch($id)->onConnection('database');
            } catch (\Throwable) {
                Log::warning('Order confirmation email could not be queued.', ['order_id' => $order->id]);
            }
        });
    }
}
