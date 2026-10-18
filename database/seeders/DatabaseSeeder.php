<?php

namespace Database\Seeders;

use App\Models\PaymentSetting;
use App\Models\User;
use App\Models\WebSocialLink;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            UserSeeder::class,
            SliderSeeder::class,
            PageSeeder::class,
            EmailSettingSeeder::class,
            SMSApiSettingSeeder::class,
            CompanySettingSeeder::class,
            PaymentSettingSeeder::class,
            ShippingChargeSettingSeeder::class,
            SocialLinkSeeder::class,
        ]);
    }
}
