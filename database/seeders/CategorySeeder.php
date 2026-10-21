<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $directory = public_path('uploads/categories');
        File::ensureDirectoryExists($directory);

        foreach (['Engine Parts', 'Brake Parts', 'Suspension & Steering', 'Car Batteries', 'Tyres & Wheels',
            'Oils & Lubricants', 'Car Lighting', 'Interior Accessories', 'Exterior Accessories', 'Car Care Products',
            'Car Audio & Multimedia', 'Car Security Systems', 'Wipers & Washers', 'Air Filters', 'Cooling Systems',
            'Transmission Parts', 'Exhaust Systems', 'Car Tools & Equipment', 'Seat Covers & Floor Mats',
            'Car Perfumes & Air Fresheners'] as $index => $name) {
            $slug = Str::slug($name);
            // Import bundled demo photos locally; no external image service is needed.
            $image = 'uploads/categories/demo-' . $slug . '.jpeg';
            if (!File::exists(public_path($image))) {
                File::copy(public_path('frontend/demo/' . (($index % 15) + 1) . '.jpeg'), public_path($image));
            }
            $category = Category::firstOrCreate(['slug' => $slug], [
                'name' => $name, 'status' => true, 'menu_serial' => $index + 1,
                'tab_serial' => $index + 1, 'show_on_menu' => true, 'show_on_side_menu' => true,
                'is_top_ten' => $index < 10, 'meta_title' => $name, 'image' => $image,
            ]);
            // Add a demo photo to existing seed records without replacing admin uploads or settings.
            if (!$category->image) {
                $category->update(['image' => $image]);
            }
        }
    }
}
