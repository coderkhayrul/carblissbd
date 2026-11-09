<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessPathaoWebhook;
use App\Models\{PathaoSetting, PathaoWebhook};
use Illuminate\Http\Request;

class PathaoWebhookController extends Controller
{
    // This is the fixed public verification response from Pathao's documentation, NOT our private webhook secret.
    public const VERIFICATION_HEADER = 'X-Pathao-Merchant-Webhook-Integration-Secret';
    public const VERIFICATION_VALUE = 'f3992ecc-59da-4cbe-a049-a13da2018d51';

    public function __invoke(Request $request)
    {
        $settings = PathaoSetting::find(1);
        if (!$settings?->webhook_enabled || !$settings->webhook_secret) return response()->json(['message' => 'Pathao webhook is disabled.'], 503);
        $rawBody = $request->getContent();
        if (strlen($rawBody) > 262144) return response()->json(['message' => 'Payload too large.'], 413);
        $data = json_decode($rawBody, true);
        if (!is_array($data) || !isset($data['event']) || !is_string($data['event']) || strlen($data['event']) > 100) return response()->json(['message' => 'Invalid webhook envelope.'], 400);
        $signature = $request->header('X-PATHAO-Signature', '');
        if ($data['event'] === 'webhook_integration') {
            // Pathao may perform the integration probe before sending the configured signature.
            // This public handshake never creates an order, queues an event, or proves authentication.
            if ($signature !== '' && !hash_equals($settings->webhook_secret, $signature)) return response()->json(['message' => 'Invalid signature.'], 401);
            if ($signature !== '') $settings->update(['webhook_verified_at' => now()]);
            return $this->accepted();
        }
        // Pathao documents this header as the supplied secret itself; it is not an HMAC digest.
        if ($signature === '' || !hash_equals($settings->webhook_secret, $signature)) return response()->json(['message' => 'Invalid signature.'], 401);
        try {
            $canonical = $data;
            ksort($canonical);
            $event = PathaoWebhook::firstOrCreate(['event_key' => hash('sha256', $settings->environment . ':' . json_encode($canonical, JSON_THROW_ON_ERROR))], [
                'event' => $data['event'], 'environment' => $settings->environment, 'payload' => $data,
            ]);
            $settings->update(['webhook_verified_at' => now()]);
        } catch (\Throwable $exception) {
            return response()->json(['message' => 'Unable to persist event. Retry later.'], 503);
        }
        if ($event->wasRecentlyCreated) {
            try { ProcessPathaoWebhook::dispatch($event->id)->onConnection('database')->onQueue('default'); }
            catch (\Throwable $exception) { /* Scheduler recovers the durable event without returning a failed acknowledgement. */ }
        }
        return $this->accepted();
    }

    private function accepted()
    {
        return response()->json(['status' => 'success'], 202)->header(self::VERIFICATION_HEADER, self::VERIFICATION_VALUE);
    }
}
