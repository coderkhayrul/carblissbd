<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CustomerService
{
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
