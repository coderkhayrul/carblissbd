<?php

namespace App\Services;

use App\Models\{Order, Customer, User, Product, ProductVariation, ShippingChargeSetting, Status};
use Illuminate\Support\Facades\{DB, Hash};
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Support\BangladeshPhone;

class OrderService
{
    public function place(array $data, ?User $authenticatedUser): Order
    {
        $data['phone'] = BangladeshPhone::normalize($data['phone']);
        if (!BangladeshPhone::valid($data['phone'])) {
            throw ValidationException::withMessages(['phone' => 'Enter an 11-digit Bangladesh mobile number, with or without +88.']);
        }
        return DB::transaction(function () use ($data, $authenticatedUser) {
            $year = now('Asia/Dhaka')->format('Y');
            DB::table('next_invoice_nos')->insertOrIgnore([
                'type' => 'Order', 'year' => $year, 'next_id' => 1, 'created_at' => now(), 'updated_at' => now(),
            ]);
            $sequence = DB::table('next_invoice_nos')->where('type', 'Order')->where('year', $year)->lockForUpdate()->first();
            if ($existing = Order::where('checkout_token', $data['checkout_token'])->first()) return $existing;

            $ids = collect($data['items'])->pluck('id');
            $variantRows = ProductVariation::whereIn('id', $ids->filter(fn ($id) => $id < 0)->map(fn ($id) => abs($id)))
                ->orderBy('id')->lockForUpdate()->get();
            $products = Product::whereIn('id', $ids->filter(fn ($id) => $id > 0)->merge($variantRows->pluck('product_id')))
                ->orderBy('id')->lockForUpdate()->get()->keyBy('id');
            ShippingChargeSetting::whereKey($data['shipping_id'])->lockForUpdate()->firstOrFail();
            $summary = app(CheckoutService::class)->summary($data['items'], (int) $data['shipping_id']);
            if (!$summary['items'] || !$summary['shipping']) {
                throw ValidationException::withMessages(['items' => 'Choose products and an available delivery area.']);
            }
            if ($summary['total'] > 99999999.99) {
                throw ValidationException::withMessages(['items' => 'This order exceeds the maximum order amount. Please reduce the quantities.']);
            }
            $customer = $this->customer($data, $authenticatedUser);
            $pending = Status::firstOrCreate(['name' => 'Pending'], ['status' => true]);
            if (!$pending->status) throw ValidationException::withMessages(['order' => 'New orders are currently unavailable. Please contact the store.']);

            $next = max(1, (int) $sequence->next_id);
            do {
                $invoice = 'ORD-' . $year . '-' . str_pad((string) $next++, 6, '0', STR_PAD_LEFT);
            } while (Order::where('order_no', $invoice)->exists());

            $order = Order::create([
                'customer_id' => $customer->id, 'date' => now('Asia/Dhaka')->toDateString(),
                'order_no' => $invoice, 'order_source' => 'Website', 'payment_type' => 'COD',
                'shipping_method' => 'Regular Shipping', 'current_status' => $pending->id,
                'total_quantity' => array_sum(array_column($summary['items'], 'qty')),
                'subtotal' => $summary['subtotal'], 'shipping_cost' => $summary['shipping_charge'],
                'total_shipping_cost' => $summary['shipping_charge'], 'grand_total' => $summary['total'],
                'due_amount' => $summary['total'], 'paid_amount' => 0,
                'shipping_charge_setting_id' => $summary['shipping']->id,
                'delivery_area' => $summary['shipping']->area_name, 'delivery_time' => $summary['shipping']->delivery_time,
                'notes' => $data['notes'] ?? null, 'checkout_token' => $data['checkout_token'], 'public_token' => (string) Str::uuid(),
            ]);
            $variants = ProductVariation::with(['variationAttributes.attributeType', 'variationAttributes.attribute'])
                ->whereIn('id', array_filter(array_column($summary['items'], 'variation_id')))->get()->keyBy('id');
            foreach ($summary['items'] as $item) {
                $variant = $item['variation_id'] ? $variants->get($item['variation_id']) : null;
                $purchasePrice = (float) ($variant?->purchase_price ?? $products->get($item['product_id'])->purchase_price);
                $mrpPrice = (float) ($variant?->mrp_price ?? $products->get($item['product_id'])->mrp_price);
                $order->details()->create([
                    'product_id' => $item['product_id'], 'product_variation_id' => $item['variation_id'],
                    'product_name' => $item['product_name'], 'variation_name' => $variant?->name,
                    'sku' => $item['sku'], 'image' => $item['img'], 'quantity' => $item['qty'],
                    'unit_price' => $item['price'], 'line_total' => $item['line_total'],
                    'purchase_price' => $purchasePrice, 'sale_price' => $item['price'], 'mrp_price' => $mrpPrice,
                    'total_cost' => (int) round($purchasePrice * 100) * $item['qty'] / 100,
                    'total_amount' => $item['line_total'], 'returned_qty' => 0, 'exist_in_return' => false,
                    'attributes' => $variant?->variationAttributes->map(fn ($pair) => [
                        'type' => $pair->attributeType?->name, 'value' => $pair->attribute?->name,
                    ])->all(),
                ]);
            }
            $order->customerInfo()->create([
                'customer_id' => $customer->id, 'name' => $data['name'], 'phone' => $data['phone'],
                'email' => $data['email'] ?? null, 'address' => $data['address'],
                'order_note' => $data['notes'] ?? null,
            ]);
            $order->payments()->create([
                'customer_id' => $customer->id, 'payment_method' => 'Cash', 'payment_status' => 'Pending', 'amount' => $summary['total'],
            ]);
            $order->statusHistory()->create(['status_id' => $pending->id]);
            DB::table('next_invoice_nos')->where('id', $sequence->id)->update(['next_id' => $next, 'updated_at' => now()]);
            return $order;
        }, 3);
    }

    private function customer(array $data, ?User $authenticatedUser): Customer
    {
        if ($authenticatedUser?->role === 'customer') {
            // Logged-in identity takes priority over the recipient phone entered at checkout.
            $user = User::whereKey($authenticatedUser->id)->lockForUpdate()->firstOrFail();
            $this->assertActiveUser($user);
            $customer = Customer::where('user_id', $user->id)->lockForUpdate()->first();

            if (!$customer) {
                $customer = Customer::whereIn('mobile', $this->mobileVariants($user->mobile))
                    ->whereNull('user_id')->orderBy('id')->lockForUpdate()->first();
                if ($customer) {
                    $customer->user_id = $user->id;
                    $customer->saveOrFail();
                }
            }
            return $customer
                ? $this->activeCustomer($customer)
                : $this->createCustomerForUser($user, $data);
        }

        $phones = $this->mobileVariants($data['phone']);
        $customer = Customer::whereIn('mobile', $phones)->orderBy('id')->lockForUpdate()->first();
        if ($customer) return $this->activeCustomer($customer);

        // A user may exist without a customer profile; reuse that login instead of duplicating it.
        $user = User::whereIn('mobile', $phones)->lockForUpdate()->first();
        if ($user) {
            if ($user->role !== 'customer') {
                throw ValidationException::withMessages([
                    'phone' => 'This mobile number belongs to a non-customer account. Please use a different mobile number.',
                ]);
            }
            $this->assertActiveUser($user);
            $customer = Customer::where('user_id', $user->id)->lockForUpdate()->first();
            if ($customer) return $this->activeCustomer($customer);
        } else {
            $email = $data['email'] ?? null;
            // Login emails must be unique; the original submitted email remains on the order.
            if ($email && User::where('email', $email)->exists()) $email = null;
            $user = User::create([
                'name' => $data['name'], 'mobile' => $phones[0], 'email' => $email,
                'password' => Hash::make($data['password'] ?? Str::random(48)),
                'role' => 'customer', 'status' => true,
                'address' => Str::limit($data['address'], 255, ''),
            ]);
        }

        return $this->createCustomerForUser($user, $data);
    }

    private function createCustomerForUser(User $user, array $data): Customer
    {
        $customer = new Customer;
        $customer->fill([
            'user_id' => $user->id, 'name' => $user->name,
            'mobile' => BangladeshPhone::normalize($user->mobile), 'email' => $user->email,
            'address' => Str::limit($data['address'], 255, ''),
            'shipping_address' => Str::limit($data['address'], 255, ''),
            'password' => $user->password, 'register_from' => 'Website', 'status' => true,
        ]);
        $customer->saveOrFail();
        return $customer;
    }

    private function activeCustomer(Customer $customer): Customer
    {
        if (!$customer->status) {
            throw ValidationException::withMessages(['phone' => 'This customer account is inactive. Please contact the store.']);
        }
        $normalized = BangladeshPhone::normalize($customer->mobile ?? '');
        if (BangladeshPhone::valid($normalized) && $customer->mobile !== $normalized) {
            $customer->mobile = $normalized;
            $customer->saveOrFail();
        }
        if ($customer->user_id) {
            $user = User::whereKey($customer->user_id)->lockForUpdate()->first();
            if ($user?->role === 'customer') $this->normalizeUserMobile($user);
        }
        return $customer;
    }

    private function assertActiveUser(User $user): void
    {
        if (!$user->status) {
            throw ValidationException::withMessages(['phone' => 'This account cannot place an order. Please contact the store.']);
        }
        $this->normalizeUserMobile($user);
    }

    private function normalizeUserMobile(User $user): void
    {
        $normalized = BangladeshPhone::normalize($user->mobile);
        if (!BangladeshPhone::valid($normalized) || $user->mobile === $normalized) return;
        if (User::where('mobile', $normalized)->where('id', '!=', $user->id)->exists()) {
            throw ValidationException::withMessages(['phone' => 'This phone has conflicting account records. Please contact the store.']);
        }
        $user->mobile = $normalized;
        $user->saveOrFail();
    }
    private function mobileVariants(string $phone): array
    {
        return BangladeshPhone::variants($phone);
    }
}
