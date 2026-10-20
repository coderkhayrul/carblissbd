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
        Slider::firstOrCreate(['slug' => 'elevate-your-premium-driving-experience'], [
            'name' => 'Elevate Your Premium Driving Experience',
            'tagline' => 'UP TO 30% DISCOUNT',
            'slug' => 'elevate-your-premium-driving-experience',
            'image' => 'https://images.unsplash.com/photo-1619642751034-765dfdf7c58e?auto=format&fit=crop&q=80&w=1600',
            'short_description' => 'Keep your engine young with premium engine oils, high-performance filters, and reliable brake pad assemblies curated for all car makes.',
            'button_title' => 'Shop Accessories Now',
            'button_icon' => '',
            'button_url' => route('category'),
            'orderBy' => 1,
            'button_status' => true,
            'status' => true,
        ]);

        foreach ([
            [
                'name' => 'Genuine Spare Parts & Premium Engine Oils',
                'slug' => 'genuine-spare-parts-premium-engine-oils',
                'tagline' => 'GENUINE QUALITY',
                'image' => 'https://images.unsplash.com/photo-1486262715619-67b85e0b08d3?auto=format&fit=crop&q=80&w=1600',
                'short_description' => 'Keep your engine running smoothly with premium oils, high-performance filters and reliable brake parts.',
                'button_title' => 'Explore Spare Parts',
                'orderBy' => 2,
            ],
            [
                'name' => 'Give Your Car the Care It Deserves',
                'slug' => 'give-your-car-the-care-it-deserves',
                'tagline' => 'CAR CARE ESSENTIALS',
                'image' => 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&q=80&w=1600',
                'short_description' => 'Discover cleaning products, protective covers and detailing essentials for a showroom-ready finish.',
                'button_title' => 'Shop Car Care',
                'orderBy' => 3,
            ],
        ] as $slide) {
            // Preserve admin edits and avoid duplicates when seeding again.
            Slider::firstOrCreate(['slug' => $slide['slug']], array_merge($slide, [
                'button_url' => route('category'),
                'button_icon' => 'fa-solid fa-arrow-right',
                'button_status' => true,
                'status' => true,
            ]));
        }
    }
}
