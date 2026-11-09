<?php

namespace App\Http\Controllers;

use App\Models\{SteadfastSetting, SteadfastWebhook};
use App\Jobs\ProcessSteadfastWebhook;
use Illuminate\Http\Request;

class SteadfastWebhookController extends Controller
{
    public function __invoke(Request $request)
    {
        $settings = SteadfastSetting::find(1);
        if (!$settings?->enabled) return response()->json(['message' => 'Webhook temporarily disabled.'], 503);
        $rawBody = $request->getContent();
        if (strlen($rawBody) > 262144) return response()->json(['message' => 'Payload too large.'], 413);
        $signature = $request->header('X-Signature', '');
        $token = $settings->webhook_token;
        if (!$request->bearerToken() || !hash_equals($token, $request->bearerToken()) || !preg_match('/^[a-fA-F0-9]{64}$/', $signature)
            || !hash_equals(hash_hmac('sha256', $rawBody, $token), strtolower($signature))) {
            return response()->json(['message' => 'Invalid webhook authentication or signature.'], 401);
        }
        $data = json_decode($rawBody, true);
        if (!is_array($data) || !isset($data['notification_type']) || !is_string($data['notification_type']) || strlen($data['notification_type']) > 100) {
            return response()->json(['message' => 'Invalid event format.'], 400);
        }
        $idempotencyKey = $request->header('Idempotency-Key');
        if ($idempotencyKey && strlen($idempotencyKey) > 255) return response()->json(['message' => 'Invalid idempotency key.'], 400);
        $bodyHash = hash('sha256', $rawBody);
        $eventKey = hash('sha256', $idempotencyKey ? 'key:' . $idempotencyKey : 'body:' . $bodyHash);
        try {
            $event = SteadfastWebhook::firstOrCreate(['event_key' => $eventKey], [
                'body_hash' => $bodyHash, 'notification_type' => $data['notification_type'], 'payload' => $data,
            ]);
            if (!hash_equals($event->body_hash, $bodyHash)) return response()->json(['message' => 'Idempotency key reused with a different payload.'], 409);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Unable to persist event. Retry later.'], 503);
        }
        if ($event->wasRecentlyCreated) {
            try {
                // Persist and enqueue only: no courier HTTP calls within the provider's five-second response window.
                ProcessSteadfastWebhook::dispatch($event->id)->onConnection('database')->onQueue('default');
            } catch (\Throwable $e) {
                // Durable inbox + scheduler recover this event even if queue insertion is interrupted.
            }
        }
        return response()->json(['status' => 'success', 'message' => 'Webhook accepted.'], 202);
    }
}
