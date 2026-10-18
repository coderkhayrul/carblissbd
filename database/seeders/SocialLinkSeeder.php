<?php

namespace Database\Seeders;

use App\Models\WebSocialLink;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SocialLinkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $socials = [
            [
                'name' => 'Facebook',
                'url' => 'https://www.facebook.com/coderkhayrul',
                'icon' => 'fab fa-facebook-f',
                'background_color' => '',
                'foreground_color' => '',
                'status' => true,
            ],
            [
                'name' => 'Youtube',
                'url' => '	https://www.youtube.com/@coderkhayrrul',
                'icon' => 'fab fa-youtube',
                'background_color' => '',
                'foreground_color' => '',
                'status' => true,
            ],
            [
                'name' => 'Linkedin',
                'url' => 'https://www.linkedin.com/coderkhayrul',
                'icon' => 'fab fa-linkedin',
                'background_color' => '',
                'foreground_color' => '',
                'status' => true,
            ],
            [
                'name' => 'Instagram',
                'url' => '	https://www.instagram.com/coderkhayrul',
                'icon' => 'fab fa-instagram',
                'background_color' => '',
                'foreground_color' => '',
                'status' => true,
            ],
        ];

        foreach ($socials as $key => $social) {
            WebSocialLink::create($social);
        }
    }
}
