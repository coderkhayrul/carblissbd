<?php

namespace App\Services;

use App\Models\{Order, Status};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AdminOrderService
{
    public const COURIERS = ['Pathao', 'Steadfast', 'eCourier', 'Paperfly', 'RedX'];
    public const COPIES = [
        'customer' => 'Customer Copy', 'accounts' => 'Accounts Copy', 'store' => 'Store Copy',
        'delivery' => 'Delivery Man Copy', 'office-challan' => 'Office Challan Copy', 'customer-challan' => 'Customer Challan Copy',
    ];

    public function status(Order $order, int $statusId): void
    {
        DB::transaction(function () use ($order, $statusId) {
            $current = Order::whereKey($order->id)->lockForUpdate()->firstOrFail();
            Status::whereKey($statusId)->where('status', true)->firstOrFail();
            if ((int) $current->current_status === $statusId) return;
            $current->current_status = $statusId;
            $current->saveOrFail();
            $current->statusHistory()->create(['status_id' => $statusId]);
        });
    }

    public function courier(Order $order, string $courier): void
    {
        DB::transaction(function () use ($order, $courier) {
            $current = Order::whereKey($order->id)->lockForUpdate()->firstOrFail();
            $current->courier_name = $courier;
            $current->is_courier_assigned = true;
            $current->courier_status = 'Assigned';
            $current->saveOrFail();
        });
    }

    public function complaint(Order $order, array $data, int $userId): void
    {
        DB::table('order_complaints')->insert([
            'order_id' => $order->id, 'created_by' => $userId, 'subject' => $data['subject'],
            'message' => $data['message'], 'status' => 'Open', 'created_at' => now(), 'updated_at' => now(),
        ]);
    }

    public function recordReturn(Order $order, array $data, int $userId): void
    {
        DB::transaction(function () use ($order, $data, $userId) {
            $current = Order::whereKey($order->id)->lockForUpdate()->firstOrFail();
            if (DB::table('order_return_records')->where('order_id', $order->id)->where('request_token', $data['request_token'])->exists()) return;
            $details = $current->details()->lockForUpdate()->get()->keyBy('id');
            $count = 0;
            foreach ($data['items'] as $index => $item) {
                $qty = (int) $item['quantity'];
                if ($qty === 0) continue;
                $detail = $details->get((int) $item['id']);
                if (!$detail || $qty > (int) $detail->quantity - (int) $detail->returned_qty) {
                    throw ValidationException::withMessages(["items.$index.quantity" => 'The return quantity exceeds the remaining ordered quantity.']);
                }
                DB::table('order_return_records')->insert([
                    'order_id' => $order->id, 'order_detail_id' => $detail->id,
                    'return_reason_id' => $data['return_reason_id'], 'created_by' => $userId,
                    'request_token' => $data['request_token'], 'quantity' => $qty,
                    'amount' => (int) round((float) $detail->sale_price * 100) * $qty / 100,
                    'note' => $data['note'] ?? null, 'created_at' => now(), 'updated_at' => now(),
                ]);
                $detail->returned_qty += $qty;
                $detail->exist_in_return = true;
                $detail->saveOrFail();
                $count += $qty;
            }
            if (!$count) throw ValidationException::withMessages(['items' => 'Enter a return quantity for at least one product.']);
            if ($details->isNotEmpty() && $details->every(fn ($detail) => (int) $detail->returned_qty >= (int) $detail->quantity)) {
                $status = Status::firstOrCreate(['name' => 'Returned'], ['status' => true]);
                if ($status->status && (int) $current->current_status !== (int) $status->id) {
                    $current->current_status = $status->id;
                    $current->saveOrFail();
                    $current->statusHistory()->create(['status_id' => $status->id]);
                }
            }
        });
    }

    public function duplicate(Order $order, string $token): Order
    {
        return DB::transaction(function () use ($order, $token) {
            $year = now('Asia/Dhaka')->format('Y');
            DB::table('next_invoice_nos')->insertOrIgnore(['type' => 'Order', 'year' => $year, 'next_id' => 1, 'created_at' => now(), 'updated_at' => now()]);
            $sequence = DB::table('next_invoice_nos')->where('type', 'Order')->where('year', $year)->lockForUpdate()->first();
            if ($existing = Order::where('checkout_token', $token)->first()) return $existing;
            $original = Order::whereKey($order->id)->lockForUpdate()->firstOrFail()->load(['details', 'customerInfo']);
            if ($original->details->isEmpty()) {
                throw ValidationException::withMessages(['order' => 'An order without product details cannot be duplicated.']);
            }
            $pending = Status::firstOrCreate(['name' => 'Pending'], ['status' => true]);
            if (!$pending->status) throw ValidationException::withMessages(['order' => 'The Pending status must be active to duplicate an order.']);
            $next = max(1, (int) $sequence->next_id);
            do { $invoice = 'ORD-' . $year . '-' . str_pad((string) $next++, 6, '0', STR_PAD_LEFT); }
            while (Order::where('order_no', $invoice)->exists());
            $copy = $original->replicate();
            $copy->fill([
                'order_no' => $invoice, 'date' => now('Asia/Dhaka')->toDateString(), 'delivery_date' => null,
                'order_source' => 'GSale', 'payment_type' => 'COD', 'paid_amount' => 0,
                'due_amount' => $original->grand_total, 'current_status' => $pending->id,
                'checkout_token' => $token, 'public_token' => (string) Str::uuid(),
                'courier_name' => null, 'courier_status' => null, 'is_courier_assigned' => false,
                'cancel_reason' => null, 'draft_source' => 'Duplicate of ' . $original->order_no,
            ]);
            $copy->saveOrFail();
            foreach ($original->details as $detail) {
                $line = $detail->replicate();
                $line->order_id = $copy->id;
                $line->returned_qty = 0;
                $line->exist_in_return = false;
                $line->saveOrFail();
            }
            if ($original->customerInfo) {
                $info = $original->customerInfo->replicate();
                $info->order_id = $copy->id;
                $info->saveOrFail();
            }
            $copy->payments()->create(['customer_id' => $copy->customer_id, 'payment_method' => 'Cash', 'payment_status' => 'Pending', 'amount' => $copy->grand_total]);
            $copy->statusHistory()->create(['status_id' => $pending->id]);
            DB::table('next_invoice_nos')->where('id', $sequence->id)->update(['next_id' => $next, 'updated_at' => now()]);
            return $copy;
        }, 3);
    }
}
