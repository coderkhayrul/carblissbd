<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['New Arrival', 'Best Seller', 'Popular', 'Featured', 'Genuine Parts', 'Premium Quality', 'Special Offer', 'Limited Stock'] as $name) {
            Tag::firstOrCreate(['slug' => Str::slug($name)], ['name' => $name, 'status' => true]);
        }
    }
}
