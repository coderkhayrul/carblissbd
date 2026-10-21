<?php

namespace Database\Seeders;

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
            CustomerSeeder::class,
            BrandSeeder::class,
            CategorySeeder::class,
            CategoryHierarchySeeder::class,
            AttributeTypeSeeder::class,
            AttributeSeeder::class,
            StatusSeeder::class,
            TagSeeder::class,
            SeasonSeeder::class,
            MenuSeeder::class,
            CouponSeeder::class,
            ProductSeeder::class,
            PromoAdsSeeder::class,
            ReturnReasonSeeder::class,
        ]);
    }
}
