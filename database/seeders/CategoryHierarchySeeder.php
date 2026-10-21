<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\{DB, File};
use Illuminate\Support\Str;

class CategoryHierarchySeeder extends Seeder
{
    public function run(): void
    {
        $groups = [
            ['Engine Parts', 'fa-solid fa-gears', ['Spark Plugs', 'Engine Mounts', 'Timing Belts']],
            ['Brake Parts', 'fa-solid fa-car-burst', ['Brake Pads', 'Brake Discs']],
            ['Suspension & Steering', 'fa-solid fa-car-side', ['Shock Absorbers', 'Steering Racks', 'Control Arms']],
            ['Car Batteries', 'fa-solid fa-car-battery', ['Maintenance Free Batteries', 'Battery Chargers']],
            ['Tyres & Wheels', 'fa-solid fa-circle-dot', ['Car Tyres', 'Alloy Wheels', 'Wheel Accessories']],
            ['Oils & Lubricants', 'fa-solid fa-oil-can', ['Engine Oils', 'Transmission Oils']],
            ['Car Lighting', 'fa-solid fa-lightbulb', ['LED Headlights', 'Fog Lights', 'Interior Lights']],
            ['Interior Accessories', 'fa-solid fa-couch', ['Steering Wheel Covers', 'Seat Cushions']],
            ['Exterior Accessories', 'fa-solid fa-car', ['Car Body Covers', 'Door Visors', 'Mud Flaps']],
            ['Car Care Products', 'fa-solid fa-spray-can-sparkles', ['Car Cleaning Supplies', 'Polish & Wax']],
        ];

        File::ensureDirectoryExists(public_path('uploads/categories'));
        DB::transaction(function () use ($groups) {
            foreach ($groups as $index => [$name, $icon, $children]) {
                $parent = $this->category($name, $icon, $index);
                $parent->update([
                    'parent_id' => null, 'menu_serial' => $index + 1, 'tab_serial' => $index + 1,
                    'status' => true, 'show_on_menu' => true, 'show_on_side_menu' => true, 'is_top_ten' => true,
                ]);

                foreach ($children as $childIndex => $childName) {
                    $child = $this->category($childName, $icon, $index + $childIndex + 1);
                    $child->update([
                        'parent_id' => $parent->id, 'menu_serial' => $childIndex + 1,
                        'tab_serial' => $childIndex + 1, 'status' => true,
                        'show_on_menu' => true, 'show_on_side_menu' => true, 'is_top_ten' => false,
                    ]);
                }
            }
        });
    }

    private function category(string $name, string $icon, int $imageIndex): Category
    {
        $slug = Str::slug($name);
        $image = 'uploads/categories/demo-' . $slug . '.jpeg';
        if (!File::exists(public_path($image))) {
            File::copy(public_path('frontend/demo/' . (($imageIndex % 15) + 1) . '.jpeg'), public_path($image));
        }
        $category = Category::firstOrCreate(['slug' => $slug], [
            'name' => $name, 'icon' => $icon, 'image' => $image, 'meta_title' => $name,
        ]);
        $missing = [];
        if (!$category->icon) $missing['icon'] = $icon;
        if (!$category->image) $missing['image'] = $image;
        if ($missing) $category->update($missing);

        return $category;
    }
}
