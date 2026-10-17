<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pages = [
            [
                'name' => 'About Us',
                'group_name' => 'Customer Care',
                'bangla_name' => 'আমাদের সম্পর্কে',
                'slug' => 'about-us',
                'banner_image' => '',
                'image' => '',
                'orderBy' => 1,
                'content' => 'Malesuada sem hac class molestias, hic magni integer enim purus placeat ac, maecenas repellat?',
                'status' => true,
                'show_in_quick_links' => true,
                'meta_title' => 'About Us',
                'meta_description' => 'Malesuada sem hac class molestias, hic magni integer enim purus placeat ac, maecenas repellat?',
                'meta_tags' => 'about-us',
            ],
            [
                'name' => 'Contact Support',
                'group_name' => 'Customer Care',
                'bangla_name' => 'সহায়তার জন্য',
                'slug' => 'contact-support',
                'banner_image' => '',
                'image' => '',
                'orderBy' => 2,
                'content' => 'Malesuada sem hac class molestias, hic magni integer enim purus placeat ac, maecenas repellat?',
                'status' => true,
                'show_in_quick_links' => true,
                'meta_title' => 'Contact Support',
                'meta_description' => 'Malesuada sem hac class molestias, hic magni integer enim purus placeat ac, maecenas repellat?',
                'meta_tags' => 'contact-support',
            ],
            [
                'name' => 'High End Car Cover Collection',
                'group_name' => 'Information',
                'bangla_name' => 'উচ্চমানের গাড়ি কভার সংগ্রহ',
                'slug' => 'high-end-car-cover',
                'banner_image' => '',
                'image' => '',
                'orderBy' => 3,
                'content' => 'Malesuada sem hac class molestias, hic magni integer enim purus placeat ac, maecenas repellat?',
                'status' => true,
                'show_in_quick_links' => true,
                'meta_title' => 'High End Car Cover Collection',
                'meta_description' => 'Malesuada sem hac class molestias, hic magni integer enim purus placeat ac, maecenas repellat?',
                'meta_tags' => 'high, car, collection',
            ],
            [
                'name' => 'Car Detailing Packages',
                'group_name' => 'Information',
                'bangla_name' => 'উচ্চমানের গাড়ি কভার সংগ্রহ',
                'slug' => 'car-detailing-packages',
                'banner_image' => '',
                'image' => '',
                'orderBy' => 4,
                'content' => 'Malesuada sem hac class molestias, hic magni integer enim purus placeat ac, maecenas repellat?',
                'status' => true,
                'show_in_quick_links' => true,
                'meta_title' => 'Car Detailing Packages',
                'meta_description' => 'Malesuada sem hac class molestias, hic magni integer enim purus placeat ac, maecenas repellat?',
                'meta_tags' => 'car, packages',
            ],
        ];

        foreach ($pages as $key => $page) {
            Page::create($page);
        }
    }
}
