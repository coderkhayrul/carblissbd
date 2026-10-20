<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        $coupons = [
            ['Welcome Discount', 'WELCOME10', 10, 'Percentage', 'Once'],
            ['First Order Offer', 'FIRST15', 15, 'Percentage', 'Once'],
            ['Summer Savings', 'SUMMER20', 20, 'Percentage', 'Multiple'],
            ['Car Care Special', 'CARE5', 5, 'Percentage', 'Multiple'],
            ['Save 100', 'SAVE100', 100, 'Fixed', 'Once'],
            ['Save 200', 'SAVE200', 200, 'Fixed', 'Multiple'],
            ['Weekend Offer', 'WEEKEND10', 10, 'Percentage', 'Multiple'],
            ['Premium Savings', 'PREMIUM500', 500, 'Fixed', 'Once'],
        ];
        foreach ($coupons as [$name, $code, $amount, $discountType, $useType]) {
            Coupon::firstOrCreate(['code' => $code], [
                'name' => $name, 'amount' => $amount, 'discount_type' => $discountType, 'use_type' => $useType,
                'start_date' => now('Asia/Dhaka')->toDateString(),
                'end_date' => now('Asia/Dhaka')->addMonths(3)->toDateString(),
                'status' => true, 'is_highlighted' => false,
            ]);
        }
    }
}
