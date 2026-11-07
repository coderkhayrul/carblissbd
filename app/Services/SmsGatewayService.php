<?php
namespace App\Services;

use App\Models\SMSApiSetting;
use App\Support\BangladeshPhone;
use Illuminate\Support\Facades\Http;

class SmsGatewayService
{
    public function send(SMSApiSetting $settings, string $phone, string $message): array
    {
        if (!$settings->enabled || !$settings->base_url || !$settings->api_key || !$settings->sender_id) {
            throw new \RuntimeException('SMS provider configuration is incomplete or disabled.');
        }
        $phone = BangladeshPhone::normalize($phone);
        if (!BangladeshPhone::valid($phone)) throw new \RuntimeException('Invalid customer mobile number.');
        $phone = match ($settings->phone_format) {
            'local' => substr($phone, 3), 'digits' => ltrim($phone, '+'), default => $phone,
        };
        $values = ['{phone}' => $phone, '{message}' => $message, '{api_key}' => $settings->api_key, '{api_secret}' => $settings->api_secret ?? '', '{sender_id}' => $settings->sender_id];
        $parameters = $settings->request_parameters ? json_decode($settings->request_parameters, true, 512, JSON_THROW_ON_ERROR) : config('sms.parameters');
        $headers = $settings->request_headers ? json_decode($settings->request_headers, true, 512, JSON_THROW_ON_ERROR) : [];
        array_walk_recursive($parameters, function (&$value) use ($values) { if (is_string($value)) $value = strtr($value, $values); });
        array_walk_recursive($headers, function (&$value) use ($values) { if (is_string($value)) $value = strtr($value, $values); });
        $client = Http::connectTimeout(5)->timeout(15)->withoutRedirecting()->withHeaders($headers);
        if ($settings->request_format === 'form') $client = $client->asForm();
        $response = $settings->request_method === 'GET' ? $client->get($settings->base_url, $parameters) : $client->post($settings->base_url, $parameters);
        $accepted = $response->successful();
        if ($settings->success_path) {
            $actual = data_get($response->json(), $settings->success_path);
            $actual = is_bool($actual) ? ($actual ? 'true' : 'false') : $actual;
            $accepted = $accepted && is_scalar($actual) && (string) $actual === (string) $settings->success_value;
        }
        return ['accepted' => $accepted, 'http_status' => $response->status()];
    }
}
