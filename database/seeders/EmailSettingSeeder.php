<?php

namespace Database\Seeders;

use App\Models\EmailSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmailSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EmailSetting::firstOrCreate(['id' => 1], [
            'enabled' => false,
            'sender_name' => 'Car Bliss BD',
            'sender_email' => 'carbliessbd@gmail.com',
            'mail_mailer' => 'smtp',
            'mail_host' => '',
            'mail_port' => '587',
            'mail_username' => '',
            'mail_password' => '',
            'mail_encryption' => 'TLS',
        ]);
    }
}
