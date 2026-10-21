<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Brand;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        File::ensureDirectoryExists(public_path('uploads/brands'));
        foreach (['Toyota', 'Honda', 'Nissan', 'Mitsubishi', 'Suzuki', 'Hyundai', 'Kia', 'BMW', 'Mercedes-Benz', 'Audi'] as $index => $name) {
            $slug = Str::slug($name);
            $images = [];
            foreach (['logo', 'banner_image'] as $field) {
                $filename = 'demo-' . $slug . '-' . $field . '.png';
                $images[$field] = 'uploads/brands/' . $filename;
                if (!File::exists(public_path($images[$field]))) {
                    File::copy(public_path('frontend/demo/brands/' . $filename), public_path($images[$field]));
                }
            }
            $brand = Brand::firstOrCreate(
                ['slug' => $slug],
                ['name' => $name, 'title' => $name, 'serial' => $index + 1, 'status' => true,
                    'is_top_ten' => true, 'is_highlight' => false, 'meta_title' => $name] + $images
            );
            // Fill missing demo images without replacing uploaded assets or admin settings.
            $missing = [];
            foreach ($images as $field => $path) {
                if (!$brand->{$field}) $missing[$field] = $path;
            }
            if ($missing) $brand->update($missing);
        }
    }
}
