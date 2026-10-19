<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = [
            [
                'name' => 'মুন্না আহমেদ',
                'mobile' => '01779222892',
                'email' => 'munna@gmail.com',
                'address' => 'Habiganj, Habiganj Sadar',
                'password' => Hash::make('password'),
                'shipping_address' => 'Habiganj, Habiganj Sadar',
                'register_from' => 'Website',
                'gender' => 'Male',
                'country' => 'Bangladesh',
                'is_verified' => true,
                'verified_at' => Carbon::now(),
            ],
            [
                'name' => 'Absari Al Faria',
                'mobile' => '01329338899',
                'email' => 'absarialfaria@gmail.com',
                'address' => 'Dhaka, Vatara',
                'password' => Hash::make('password'),
                'shipping_address' => 'Dhaka, Vatara',
                'register_from' => 'Website',
                'gender' => 'Female',
                'country' => 'Bangladesh',
                'is_verified' => true,
                'verified_at' => Carbon::now(),
            ],
            [
                'name' => 'Murad Hossain',
                'mobile' => '01773287673',
                'email' => 'hossainmurad183@gmail.com',
                'address' => 'Rajshahi, Naogaon, Naogaon Sadar',
                'password' => Hash::make('password'),
                'shipping_address' => 'Rajshahi, Naogaon, Naogaon Sadar',
                'register_from' => 'Shop',
                'gender' => 'Male',
                'country' => 'Bangladesh',
                'is_verified' => true,
                'verified_at' => Carbon::now(),
            ],
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }
    }
}
