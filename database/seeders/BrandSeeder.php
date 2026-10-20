<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (['Toyota', 'Honda', 'Nissan', 'Mitsubishi', 'Suzuki', 'Hyundai', 'Kia', 'BMW', 'Mercedes-Benz', 'Audi'] as $index => $name) {
            \App\Models\Brand::firstOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($name)],
                ['name' => $name, 'title' => $name, 'serial' => $index + 1, 'status' => true,
                    'is_top_ten' => true, 'is_highlight' => false, 'meta_title' => $name]
            );
        }
    }
}
