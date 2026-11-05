<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Support\BangladeshPhone;
use Illuminate\Validation\ValidationException;

class CustomerService
{
    public function profileSummary(Customer $customer): array
    {
        $orders = $customer->orders();
        $totals = (clone $orders)->selectRaw('COUNT(*) as order_count, COALESCE(SUM(grand_total), 0) as order_total, COALESCE(SUM(paid_amount), 0) as paid_total, COALESCE(SUM(due_amount), 0) as due_total')->first();
        $completed = (clone $orders)->whereHas('currentStatus', fn ($query) => $query->whereIn(DB::raw('LOWER(TRIM(name))'), ['delivered', 'completed', 'complete']));
        return [
            'total_orders' => (int) $totals->order_count,
            'order_total' => (float) $totals->order_total,
            'paid_total' => (float) $totals->paid_total,
            'due_total' => (float) $totals->due_total,
            'completed_orders' => (clone $completed)->count(),
            'total_purchase' => (float) (clone $completed)->sum('grand_total'),
            'pending_orders' => (clone $orders)->whereHas('currentStatus', fn ($query) => $query->whereRaw('LOWER(TRIM(name)) = ?', ['pending']))->count(),
        ];
    }

    public function updateAccount(User $user, array $data): void
    {
        DB::transaction(function () use ($user, $data) {
            $current = User::whereKey($user->id)->lockForUpdate()->firstOrFail();
            abort_unless($current->role === 'customer', 403);
            $customer = Customer::where('user_id', $current->id)->lockForUpdate()->first();
            if (!$customer) {
                $customer = Customer::whereNull('user_id')->whereIn('mobile', BangladeshPhone::variants($current->mobile))
                    ->lockForUpdate()->first() ?? new Customer;
            }
            $mobile = BangladeshPhone::normalize($data['mobile']);
            $email = $data['email'] ?? null;
            $sensitive = $mobile !== BangladeshPhone::normalize($current->mobile)
                || $email !== ($current->email ? strtolower($current->email) : null) || !empty($data['password']);
            if ($sensitive && !Hash::check($data['current_password'] ?? '', $current->password)) {
                throw ValidationException::withMessages(['current_password' => 'Enter your current password to change your email, phone or password.']);
            }
            if (User::whereIn('mobile', BangladeshPhone::variants($mobile))->where('id', '!=', $current->id)->exists()
                || Customer::whereIn('mobile', BangladeshPhone::variants($mobile))
                    ->when($customer->exists, fn ($query) => $query->where('id', '!=', $customer->id))->exists()) {
                throw ValidationException::withMessages(['mobile' => 'This mobile number belongs to another account.']);
            }
            $current->name = trim($data['first_name'] . ' ' . ($data['last_name'] ?? ''));
            if ($email !== $current->email) $current->email_verified_at = null;
            $current->email = $email;
            $current->mobile = $mobile;
            $current->address = $data['address'] ?? null;
            if (!empty($data['password'])) {
                $current->password = Hash::make($data['password']);
                $current->remember_token = Str::random(60);
            }
            $current->saveOrFail();
            if (!$customer->exists) {
                $customer->register_from = 'Website';
                $customer->status = (bool) $current->status;
            }
            $customer->user_id = $current->id;
            $customer->name = $current->name;
            $customer->email = $current->email;
            $customer->mobile = $current->mobile;
            $customer->address = $current->address;
            $customer->password = $current->password;
            $customer->saveOrFail();
        });
    }

    public function updateStatus(Customer $customer, bool $status): void
    {
        $customer->status = $status;
        $customer->saveOrFail();
    }

    public function changePassword(Customer $customer, string $password): void
    {
        DB::transaction(function () use ($customer, $password) {
            $hash = Hash::make($password);

            if ($customer->user_id) {
                $user = User::where('role', 'customer')->findOrFail($customer->user_id);
                $user->password = $hash;
                $user->remember_token = Str::random(60);
                $user->saveOrFail();
            }

            $customer->password = $hash;
            $customer->saveOrFail();
        });
    }
}
