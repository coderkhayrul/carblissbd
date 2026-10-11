<?php

namespace Database\Seeders;

use App\Models\PaymentSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PaymentSetting::create([
            'outside_dhaka_cod' => 1,
            'bkash_active' => 1,
            'bkash_app_key' => '4f6o0cjiki2rfm34kfdadl1eqq',
            'bkash_app_secret' => '2is7hdktrekvrbljjh44ll3d9l1dtjo4pasmjvs5vl5qr3fug4b',
            'bkash_username' => 'sandboxTokenizedUser02',
            'bkash_password' => 'sandboxTokenizedUser02@12345',
            'bkash_url' => 'https://tokenized.sandbox.bka.sh/v1.2.0-beta',
            'ssl_active' => 1,
            'ssl_store_id' => 3840573053,
            'ssl_store_password' => 'sandboxTokenizedUser02@12345',
            'ssl_store_url' => 'https://tokenized.sandbox.ssl/v1.2.0-beta',
            'ssl_store_callback_url' => 'https://tokenized.sandbox.ssl/v1.2.0-beta',
            'ssl_store_failure_url' => 'https://tokenized.sandbox.ssl/v1.2.0-beta',
            'ssl_is_localhost' => 1,
        ]);
    }
}
