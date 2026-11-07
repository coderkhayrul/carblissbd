<?php
namespace App\Services;

use App\Models\EmailSetting;
use Illuminate\Mail\Mailer;
use Illuminate\Support\Facades\{Crypt, Mail};

class SmtpMailerService
{
    public function build(EmailSetting $settings): Mailer
    {
        $password = $settings->mail_password ?? '';
        try { $password = Crypt::decryptString($password); } catch (\Illuminate\Contracts\Encryption\DecryptException) { /* Legacy plaintext settings. */ }
        $encryption = strtolower($settings->mail_encryption);
        return Mail::build([
            'transport' => 'smtp', 'scheme' => $encryption === 'ssl' ? 'smtps' : 'smtp',
            'host' => $settings->mail_host, 'port' => (int) $settings->mail_port,
            'username' => $settings->mail_username ?: null, 'password' => $password ?: null,
            'auto_tls' => $encryption !== 'none', 'require_tls' => $encryption === 'tls', 'timeout' => 20,
        ]);
    }
}
