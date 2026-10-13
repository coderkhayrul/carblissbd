<?php

namespace Database\Seeders;

use App\Models\Slider;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SliderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Slider::create([
            'name' => 'Elevate Your Premium Driving Experience',
            'tagline' => 'UP TO 30% DISCOUNT',
            'slug' => 'elevate-your-premium-driving-experience',
            'image' => null,
            'short_description' => 'Keep your engine young with premium engine oils, high-performance filters, and reliable brake pad assemblies curated for all car makes.',
            'button_title' => 'Shop Accessories Now',
            'button_icon' => '',
            'button_url' => '',
            'orderBy' => 1,
            'button_status' => true,
            'status' => true,
        ]);
    }
}
