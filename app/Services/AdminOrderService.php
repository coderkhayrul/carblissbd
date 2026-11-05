<?php

namespace App\Services;

use App\Models\{Order, Status, Product, ProductVariation, ShippingChargeSetting};
use App\Support\BangladeshPhone;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AdminOrderService
{
    public const COURIERS = ['Pathao', 'Steadfast', 'eCourier', 'Paperfly', 'RedX'];
    public const COPIES = [
        'customer' => 'Customer Copy',
        'accounts' => 'Accounts Copy',
        'store' => 'Store Copy',
        'delivery' => 'Delivery Man Copy',
        'office-challan' => 'Office Challan Copy',
        'customer-challan' => 'Customer Challan Copy',
    ];

    public function editSnapshot(Order $order): array
    {
        $order->loadMissing(['details', 'customerInfo', 'payments']);
        return [
            'order' => $order->getRawOriginal(),
            'details' => $order->details->sortBy('id')->map(fn($line) => $line->getRawOriginal())->values()->all(),
            'customer_info' => $order->customerInfo?->getRawOriginal(),
            'payments' => $order->payments->sortBy('id')->map(fn($payment) => $payment->getRawOriginal())->values()->all(),
        ];
    }

    public function editVersion(Order $order): string
    {
        return hash('sha256', json_encode($this->editSnapshot($order), JSON_THROW_ON_ERROR));
    }

    public function create(array $data, int $userId): Order
    {
        return DB::transaction(function () use ($data, $userId) {
            $year = now('Asia/Dhaka')->format('Y');
            DB::table('next_invoice_nos')->insertOrIgnore(['type' => 'Order', 'year' => $year, 'next_id' => 1, 'created_at' => now(), 'updated_at' => now()]);
            $sequence = DB::table('next_invoice_nos')->where('type', 'Order')->where('year', $year)->lockForUpdate()->first();
            if ($existing = Order::where('checkout_token', $data['checkout_token'])->first()) {
                abort_unless((int) $existing->created_by === $userId && $existing->order_source === 'GSale', 403);
                return $existing;
            }
            $customer = \App\Models\Customer::whereKey($data['customer_id'])->lockForUpdate()->firstOrFail();
            if (!$customer->status) throw ValidationException::withMessages(['customer_id' => 'Select an active customer.']);
            Status::whereKey($data['status_id'])->where('status', true)->firstOrFail();
            ShippingChargeSetting::whereKey($data['shipping_id'])->where('status', true)->firstOrFail();
            $next = max(1, (int) $sequence->next_id);
            do {
                $invoice = 'ORD-' . $year . '-' . str_pad((string) $next++, 6, '0', STR_PAD_LEFT);
            } while (Order::where('order_no', $invoice)->exists());
            $order = Order::create([
                'customer_id' => $customer->id,
                'created_by' => $userId,
                'order_no' => $invoice,
                'order_source' => 'GSale',
                'date' => $data['date'],
                'payment_type' => $data['payment_type'],
                'shipping_method' => 'Regular Shipping',
                'grand_total' => 0,
                'paid_amount' => 0,
                'due_amount' => 0,
                'current_status' => $data['status_id'],
                'checkout_token' => $data['checkout_token'],
                'public_token' => (string) Str::uuid(),
            ]);
            $order->refresh();
            $data['edit_version'] = $this->editVersion($order);
            $this->update($order, $data, $userId, false);
            $order->refresh();
            $paid = $this->cents($data['paid_amount']);
            $total = $this->cents($order->grand_total);
            if ($paid > $total) throw ValidationException::withMessages(['paid_amount' => 'Received payment cannot exceed the order total.']);
            if ($paid > 0) $order->payments()->create([
                'customer_id' => $customer->id,
                'payment_method' => $data['payment_method'],
                'payment_status' => 'Completed',
                'amount' => $paid / 100,
                'transaction_id' => $data['transaction_id'] ?? null,
                'payment_tnx_no' => $data['transaction_id'] ?? null,
            ]);
            if ($total > $paid || $paid === 0) $order->payments()->create([
                'customer_id' => $customer->id,
                'payment_method' => $data['payment_type'] === 'COD' ? 'Cash' : $data['payment_method'],
                'payment_status' => 'Pending',
                'amount' => ($total - $paid) / 100,
            ]);
            $order->update(['paid_amount' => $paid / 100, 'due_amount' => ($total - $paid) / 100]);
            $order->statusHistory()->create(['status_id' => $data['status_id'], 'cancel_reason' => $order->cancel_reason]);
            DB::table('next_invoice_nos')->where('id', $sequence->id)->update(['next_id' => $next, 'updated_at' => now()]);
            return $order;
        }, 3);
    }

    public function update(Order $order, array $data, int $userId, bool $logEdit = true): void
    {
        DB::transaction(function () use ($order, $data, $userId, $logEdit) {
            $current = Order::whereKey($order->id)->lockForUpdate()->firstOrFail();
            $current->load(['details', 'customerInfo', 'payments']);
            if (!hash_equals($this->editVersion($current), $data['edit_version'])) {
                throw ValidationException::withMessages(['order' => 'This order changed after you opened it. Reload the page before editing again.']);
            }
            $before = $this->editSnapshot($current);
            $existing = $current->details->keyBy('id');
            $retained = array_filter(array_column($data['items'], 'id'));
            foreach ($existing as $line) {
                if (!in_array($line->id, $retained) && ((int) $line->returned_qty > 0 || DB::table('order_return_records')->where('order_detail_id', $line->id)->exists())) {
                    throw ValidationException::withMessages(['items' => 'A returned product cannot be removed from the order.']);
                }
            }
            $products = Product::whereIn('id', array_unique(array_column($data['items'], 'product_id')))->orderBy('id')->lockForUpdate()->get()->keyBy('id');
            $variations = ProductVariation::with(['variationAttributes.attribute', 'variationAttributes.attributeType'])
                ->whereIn('id', array_filter(array_column($data['items'], 'product_variation_id')))->orderBy('id')->lockForUpdate()->get()->keyBy('id');
            $subtotal = 0;
            $quantity = 0;
            $combinations = [];
            foreach ($data['items'] as $index => $item) {
                $line = !empty($item['id']) ? $existing->get((int) $item['id']) : null;
                if (!empty($item['id']) && !$line) throw ValidationException::withMessages(["items.$index.id" => 'This item does not belong to this order.']);
                $product = $products->get((int) $item['product_id']);
                $variationId = !empty($item['product_variation_id']) ? (int) $item['product_variation_id'] : null;
                $variation = $variationId ? $variations->get($variationId) : null;
                $same = $line && (int) $line->product_id === (int) $item['product_id'] && (int) $line->product_variation_id === (int) $variationId;
                if (!$product || (!$same && (!$product->status || ($product->is_variation && !$variation) || (!$product->is_variation && $variation)))) {
                    throw ValidationException::withMessages(["items.$index.product_id" => 'Choose an active product and a valid variation.']);
                }
                if ($variationId && (!$variation || (int) $variation->product_id !== (int) $product->id || (!$same && !$variation->status))) {
                    throw ValidationException::withMessages(["items.$index.product_variation_id" => 'This variation is unavailable or does not belong to this product.']);
                }
                $key = $product->id . ':' . ($variationId ?? 0);
                if (isset($combinations[$key])) throw ValidationException::withMessages(['items' => 'The same product and variation appears twice. Combine its quantity into one row.']);
                $combinations[$key] = true;
                $qty = (int) $item['quantity'];
                $sale = $this->cents($item['sale_price']);
                if (
                    $line && ((int) $line->returned_qty > 0 || DB::table('order_return_records')->where('order_detail_id', $line->id)->exists())
                    && (!$same || $qty !== (int) $line->quantity || $sale !== $this->cents($line->sale_price))
                ) {
                    throw ValidationException::withMessages(['items' => 'A returned product’s identity, quantity and sale price must remain unchanged.']);
                }
                $purchase = $same ? $this->cents($line->purchase_price) : $this->cents($variation?->purchase_price ?? $product->purchase_price);
                if ($purchase * $qty > 999999999999 || $sale * $qty > 999999999999) {
                    throw ValidationException::withMessages(["items.$index.quantity" => 'The product line total exceeds the supported range.']);
                }
                $mrp = $same ? $line->mrp_price : ($variation?->mrp_price ?? $product->mrp_price);
                $image = $same ? $line->image : asset($variation?->image ?: ($product->thumbnail_image ?: (($product->gallery_images ?? [])[0] ?? 'backend/assets/extra/preview-image.png')));
                $values = [
                    'product_id' => $product->id,
                    'product_variation_id' => $variationId,
                    'product_name' => $same ? $line->product_name : $product->name,
                    'variation_name' => $same ? $line->variation_name : $variation?->optionLabel($product->name),
                    'sku' => $same ? $line->sku : ($variation?->sku ?? $product->sku),
                    'image' => $image,
                    'attributes' => $same ? $line->attributes : $variation?->variationAttributes->map(fn($pair) => ['type' => $pair->attributeType?->name, 'value' => $pair->attribute?->name])->all(),
                    'quantity' => $qty,
                    'sale_price' => $sale / 100,
                    'unit_price' => $sale / 100,
                    'purchase_price' => $purchase / 100,
                    'mrp_price' => $mrp,
                    'total_cost' => ($purchase * $qty) / 100,
                    'total_amount' => ($sale * $qty) / 100,
                    'line_total' => ($sale * $qty) / 100,
                ];
                if ($line) $line->update($values);
                else $current->details()->create($values + ['returned_qty' => 0, 'exist_in_return' => false]);
                $subtotal += $sale * $qty;
                $quantity += $qty;
            }
            $current->details()->whereIn('id', $existing->keys()->diff($retained))->delete();
            $shipping = $this->cents($data['shipping_cost']);
            $extraShipping = $this->cents($current->extra_shipping_cost_amount);
            $discount = $this->cents($data['extra_discount_amount']);
            $totalDiscount = $discount + $this->cents($current->affiliate_discount) + $this->cents($current->coupon_discount_amount);
            $grand = $subtotal + $shipping + $extraShipping + $this->cents($current->total_vat_amount) - $totalDiscount;
            $paid = $this->cents($current->paid_amount);
            if ($grand < 0 || $grand > 9999999999 || $subtotal > 9999999999 || $quantity > 99999999 || $shipping + $extraShipping > 9999999999 || $totalDiscount > 9999999999) {
                throw ValidationException::withMessages(['items' => 'The order totals exceed the supported range, or discounts exceed the total.']);
            }
            if ($grand < $paid) throw ValidationException::withMessages(['items' => 'The new total cannot be less than the amount already paid. Record the payment adjustment first.']);
            $statusId = (int) $data['status_id'];
            $statusChanged = $statusId !== (int) $current->current_status;
            $status = Status::whereKey($statusId)->firstOrFail();
            if ($statusChanged && !$status->status) throw ValidationException::withMessages(['status_id' => 'Select an active status.']);
            $cancelReason = $this->cancellationReason($status, $data['cancel_reason'] ?? null);
            $reasonChanged = $current->cancel_reason !== $cancelReason;
            $shippingId = !empty($data['shipping_id']) ? (int) $data['shipping_id'] : null;
            if ($shippingId && $shippingId !== (int) $current->shipping_charge_setting_id) {
                $area = ShippingChargeSetting::whereKey($shippingId)->where('status', true)->firstOrFail();
                $current->shipping_charge_setting_id = $area->id;
                $current->delivery_area = $area->area_name;
                $current->delivery_time = $area->delivery_time;
            }
            $current->fill([
                'subtotal' => $subtotal / 100,
                'total_quantity' => $quantity,
                'shipping_cost' => $shipping / 100,
                'total_shipping_cost' => ($shipping + $extraShipping) / 100,
                'extra_discount_amount' => $discount / 100,
                'total_discount_amount' => $totalDiscount / 100,
                'grand_total' => $grand / 100,
                'due_amount' => ($grand - $paid) / 100,
                'current_status' => $statusId,
                'cancel_reason' => $cancelReason,
                'notes' => $data['notes'] ?? null,
            ]);
            $current->saveOrFail();
            $current->customerInfo()->updateOrCreate(['order_id' => $current->id], [
                'customer_id' => $current->customer_id,
                'name' => $data['name'],
                'phone' => BangladeshPhone::normalize($data['phone']),
                'email' => $data['email'] ?? null,
                'address' => $data['address'],
                'order_note' => $data['notes'] ?? null,
            ]);
            if ($logEdit && ($statusChanged || $reasonChanged)) $current->statusHistory()->create(['status_id' => $statusId, 'cancel_reason' => $cancelReason]);
            $pending = $current->payments()->where('payment_status', 'Pending')
                ->whereNull('transaction_id')->whereNull('payment_tnx_no')->get();
            if ($pending->count() === 1) $pending->first()->update(['amount' => ($grand - $paid) / 100]);
            $current->unsetRelations();
            $current->refresh();
            if ($logEdit) DB::table('order_edit_logs')->insert([
                'order_id' => $current->id,
                'edited_by' => $userId,
                'before' => json_encode($before, JSON_THROW_ON_ERROR),
                'after' => json_encode($this->editSnapshot($current), JSON_THROW_ON_ERROR),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }, 3);
    }

    private function cents($amount): int
    {
        return (int) round((float) ($amount ?? 0) * 100);
    }

    private function cancellationReason(Status $status, ?string $reason): ?string
    {
        if (!in_array(strtolower(trim($status->name)), ['cancelled', 'canceled', 'cancel'], true)) return null;
        $reason = trim($reason ?? '');
        if (!preg_match('/\S/u', $reason) || mb_strlen($reason) > 2000) {
            throw ValidationException::withMessages(['cancel_reason' => 'Explain why the customer cancelled the order (maximum 2000 characters).']);
        }
        return $reason;
    }

    public function status(Order $order, int $statusId, ?string $reason = null): void
    {
        DB::transaction(function () use ($order, $statusId, $reason) {
            $current = Order::whereKey($order->id)->lockForUpdate()->firstOrFail();
            $status = Status::whereKey($statusId)->where('status', true)->firstOrFail();
            $cancelReason = $this->cancellationReason($status, $reason);
            if ((int) $current->current_status === $statusId && $current->cancel_reason === $cancelReason) return;
            $current->current_status = $statusId;
            $current->cancel_reason = $cancelReason;
            $current->saveOrFail();
            $current->statusHistory()->create(['status_id' => $statusId, 'cancel_reason' => $cancelReason]);
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
            'order_id' => $order->id,
            'created_by' => $userId,
            'subject' => $data['subject'],
            'message' => $data['message'],
            'status' => 'Open',
            'created_at' => now(),
            'updated_at' => now(),
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
                    'order_id' => $order->id,
                    'order_detail_id' => $detail->id,
                    'return_reason_id' => $data['return_reason_id'],
                    'created_by' => $userId,
                    'request_token' => $data['request_token'],
                    'quantity' => $qty,
                    'amount' => (int) round((float) $detail->sale_price * 100) * $qty / 100,
                    'note' => $data['note'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $detail->returned_qty += $qty;
                $detail->exist_in_return = true;
                $detail->saveOrFail();
                $count += $qty;
            }
            if (!$count) throw ValidationException::withMessages(['items' => 'Enter a return quantity for at least one product.']);
            if ($details->isNotEmpty() && $details->every(fn($detail) => (int) $detail->returned_qty >= (int) $detail->quantity)) {
                $status = Status::firstOrCreate(['name' => 'Returned'], ['status' => true]);
                if ($status->status && (int) $current->current_status !== (int) $status->id) {
                    $current->current_status = $status->id;
                    $current->cancel_reason = null;
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
            do {
                $invoice = 'ORD-' . $year . '-' . str_pad((string) $next++, 6, '0', STR_PAD_LEFT);
            } while (Order::where('order_no', $invoice)->exists());
            $copy = $original->replicate();
            $copy->fill([
                'order_no' => $invoice,
                'date' => now('Asia/Dhaka')->toDateString(),
                'delivery_date' => null,
                'order_source' => 'GSale',
                'payment_type' => 'COD',
                'paid_amount' => 0,
                'due_amount' => $original->grand_total,
                'current_status' => $pending->id,
                'checkout_token' => $token,
                'public_token' => (string) Str::uuid(),
                'courier_name' => null,
                'courier_status' => null,
                'is_courier_assigned' => false,
                'cancel_reason' => null,
                'draft_source' => 'Duplicate of ' . $original->order_no,
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
