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
        EmailSetting::create([
            'sender_name' => 'Car Bliss BD',
            'sender_email' => 'carbliessbd@gmail.com',
            'mail_mailer' => 'smtp',
            'mail_host' => 'sandbox.smtp.mailtrap.io',
            'mail_port' => '2525',
            'mail_username' => 'cf4d60d0af666d',
            'mail_password' => '7cb09057e4fcb7',
            'mail_encryption' => 'TLS',
        ]);
    }
}
