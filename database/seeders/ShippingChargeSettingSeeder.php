<?php

namespace Database\Seeders;

use App\Models\ShippingChargeSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShippingChargeSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $shippingChargeSettings = [
            [
                'area_name' => 'Inside Dhaka',
                'delivery_charge' => 80,
                'delivery_time' => '1-2 Days',
                'status' => true,
            ],
            [
                'area_name' => 'Dhaka Sub City',
                'delivery_charge' => 100,
                'delivery_time' => '2-3 Days',
                'status' => true,
            ],
            [
                'area_name' => 'Outside Dhaka',
                'delivery_charge' => 130,
                'delivery_time' => '2-5 Days',
                'status' => true,
            ],
        ];

        foreach ($shippingChargeSettings as $key => $shippingCharge) {
            ShippingChargeSetting::create($shippingCharge);
        }
    }
}
