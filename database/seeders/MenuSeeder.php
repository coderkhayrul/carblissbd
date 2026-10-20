<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Home', 'Shop', 'Categories', 'Brands', 'Offers', 'New Arrivals', 'About Us', 'Contact Us'] as $index => $name) {
            Menu::firstOrCreate(['slug' => Str::slug($name)], [
                'name' => $name, 'serial_no' => $index + 1, 'status' => true,
            ]);
        }
    }
}
