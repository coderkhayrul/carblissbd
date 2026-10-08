<?php

namespace Database\Seeders;

use App\Models\CompanySetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CompanySetting::create([
            'name' => 'Car Bliess BD',
            'title' => 'Car Bliess BD',
            'phone' => '01999906676',
            'hotline' => '09613821382',
            'email' => 'carbliessbd@gmail.com',
            'address' => 'Flagship Outlet: 220/D/04 Begum Rokeya Sarani, Metro Pillar 328, Mirpur Shewrapara, Dhaka',
            'logo' => null,
            'favicon' => null,
            'admin_favicon' => null,
            'auth_bg' => null,
            'website' => 'carbliessbd.com',
            'bin' => '3485073257395735',
            'app_link' => 'carbliessbd.com',
            'bill_footer' => 'Perferendis sociis ratione nostrum nunc rerum mi aliquet? Aute, libero asperiores consectetur, veritatis necessitatibus rem saepe debitis suspendisse culpa incidunt maxime facilisi urna saepe!',
            'footer_description' => 'Bangladeshs most reliable premium car accessory & genuine spare parts store. Offering durability, performance, and luxurious aesthetic upgrades for your vehicle at unbeatable pricing.',
            'meta_title' => 'Car Bliess BD',
            'meta_description' => 'Perferendis sociis ratione nostrum nunc rerum mi aliquet? Aute, libero',
            'meta_keyword' => 'Car, Bliess, BD',

        ]);
    }
}
