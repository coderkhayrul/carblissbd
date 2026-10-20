<?php

namespace Database\Seeders;

use App\Models\PromoAds;
use Illuminate\Database\Seeder;

class PromoAdsSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            ['Car Care Essentials', 'Explore products for everyday car care.', 'Shop Now', '/category', '#00000080'],
            ['Interior Accessories', 'Discover accessories for your car interior.', 'Explore', '/category', '#14213D80'],
            ['Exterior Accessories', 'Browse exterior styling and protection products.', 'View Collection', '/category', '#1B433280'],
            ['New Arrivals', 'Take a look at the latest additions to our store.', 'Discover More', '/category', '#37061780'],
            ['Special Offers', 'Explore the offers available in our store.', 'View Offers', '/offers', '#3C096C80'],
        ] as $index => [$title, $subtitle, $button, $path, $overlay]) {
            $demoImage = 'frontend/demo/' . ($index + 1) . '.jpeg';

            $promoAd = PromoAds::firstOrCreate(['title' => $title], [
                'sub_title' => $subtitle, 'button_text' => $button, 'button_url' => url($path),
                'image' => $demoImage,
                'overlay_color' => $overlay, 'orderBy' => $index + 1, 'status' => true,
            ]);

            if (blank($promoAd->image)) {
                $promoAd->image = $demoImage;
                $promoAd->saveOrFail();
            }
        }
    }
}
