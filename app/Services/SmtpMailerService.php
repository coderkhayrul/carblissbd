<?php
namespace App\Services;

use App\Models\EmailSetting;
use Illuminate\Mail\Mailer;
use Illuminate\Support\Facades\{Crypt, Mail};

class SmtpMailerService
{
    public function failureMessage(\Throwable $exception): string
    {
        // Classify privately: SMTP exceptions can contain credentials and protocol transcripts.
        $details = '';
        do {
            $details .= ' ' . strtolower($exception->getMessage());
        } while ($exception = $exception->getPrevious());
        if (preg_match('/certificate|peer verification|self.signed|crypto|ssl operation/', $details)) {
            return 'TLS / SSL connection failed. Check the SMTP encryption, port and server certificate.';
        }
        if (preg_match('/authenticate|authentication|\b535\b|\b534\b|5\.7\.8|credentials/', $details)) {
            return 'SMTP authentication failed. Check the username and password / provider app password, and whether SMTP access is enabled.';
        }
        if (preg_match('/getaddrinfo|name resolution|no such host|php_network_getaddresses/', $details)) {
            return 'SMTP hostname could not be resolved. Check SMTP Host and the server DNS connection.';
        }
        if (preg_match('/timed out|timeout|connection refused|could not connect|connection could not be established|network is unreachable/', $details)) {
            return 'Unable to connect to the SMTP server. Check the host, port, encryption and hosting firewall / outbound SMTP restrictions.';
        }
        if (preg_match('/\b550\b|\b551\b|\b553\b|\b554\b|relay|sender.*reject|recipient.*reject/', $details)) {
            return 'SMTP provider rejected the sender, recipient or message. Verify Sender Email, recipient address and provider sending permissions.';
        }
        if (preg_match('/\b421\b|\b450\b|\b451\b|\b452\b|rate limit|quota/', $details)) {
            return 'SMTP provider is temporarily unavailable or its sending limit was reached. Check provider limits and try again later.';
        }
        return 'Email could not be sent. Check saved SMTP settings and provider availability. No sensitive SMTP response is displayed.';
    }

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
