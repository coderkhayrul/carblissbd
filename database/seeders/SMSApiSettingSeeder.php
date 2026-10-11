<?php

namespace Database\Seeders;

use App\Models\SMSApiSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SMSApiSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SMSApiSetting::create([
            'base_url' => 'coderkhayrul.com',
            'sender_id' => '4538905834',
            'api_key' => '5340583058',
            'api_secret' => '3457037593845',
        ]);
    }
}
