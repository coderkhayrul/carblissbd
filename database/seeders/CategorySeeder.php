<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Engine Parts', 'Brake Parts', 'Suspension & Steering', 'Car Batteries', 'Tyres & Wheels',
            'Oils & Lubricants', 'Car Lighting', 'Interior Accessories', 'Exterior Accessories', 'Car Care Products'] as $index => $name) {
            Category::firstOrCreate(['slug' => Str::slug($name)], [
                'name' => $name, 'status' => true, 'menu_serial' => $index + 1,
                'tab_serial' => $index + 1, 'show_on_menu' => true, 'show_on_side_menu' => true,
                'is_top_ten' => true, 'meta_title' => $name,
            ]);
        }
    }
}
