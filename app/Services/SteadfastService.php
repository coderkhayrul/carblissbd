<?php

namespace App\Services;

use App\Models\{Order, Status, SteadfastSetting, SteadfastShipment};
use Illuminate\Support\Facades\{Cache, DB, Http};
use Illuminate\Validation\ValidationException;

class SteadfastService
{
    public const API_URLS = ['https://portal.packzy.com/api/v1', 'https://portal.steadfast.com.bd/api/v1'];
    public const RETURN_STATUSES = ['partial_delivered_return_processing', 'partial_delivered_return_rider_assigned', 'partial_delivered_return_received', 'cancelled_return_processing', 'cancelled_return_rider_assigned', 'cancelled_return_received'];
    public const STATUSES = ['in_review', 'pending', 'hold', 'delivered_approval_pending', 'partial_delivered_approval_pending', 'cancelled_approval_pending', 'unknown_approval_pending', 'delivered', 'partial_delivered', 'cancelled', 'exceptional', 'unknown'];
    public const MAPPABLE_STATUSES = [...self::STATUSES, ...self::RETURN_STATUSES];

    private function ready(\App\Models\SteadfastSetting $settings): void
    {
        if (!$settings->enabled || !$settings->api_key || !$settings->secret_key) $this->fail('Save and enable Steadfast credentials first.');
        if ($settings->auth_blocked) $this->fail('Steadfast authentication is paused after a 401/403 response. Correct the credentials / account, then save the API keys again.');
        if ($settings->api_paused_until?->isFuture()) $this->fail('Steadfast rate limit reached. Requests are paused until ' . $settings->api_paused_until->timezone('Asia/Dhaka')->format('d M h:i A') . '.');
    }

    private function fail(string $message): never
    {
        throw ValidationException::withMessages(['courier' => $message]);
    }

    private function api(string $method, string $path, array $data = [], ?bool &$authenticationRejected = null): array
    {
        $settings = SteadfastSetting::configuration();
        $this->ready($settings);
        $baseUrl = $settings->base_url;
        if (!in_array($baseUrl, self::API_URLS, true)) $this->fail('Select a supported Steadfast API address in Configuration.');
        try {
            // Only provider hosts are allowed; secrets cannot follow a redirect.
            $response = Http::acceptJson()->asJson()->withHeaders(['Api-Key' => $settings->api_key, 'Secret-Key' => $settings->secret_key])
                ->connectTimeout(5)->timeout(20)->withoutRedirecting()
                ->send($method, $baseUrl . '/' . $path, $method === 'POST' ? ['json' => $data] : []);
        } catch (\Throwable $e) {
            // Never report the HTTP exception: it may include recipient data or credentials.
            $this->fail('Steadfast did not confirm the request. Use Sync Status to check the invoice before booking again.');
        }
        $body = $response->json();
        $authenticationRejected = in_array($response->status(), [401, 403], true);
        if ($authenticationRejected) {
            $settings->update(['auth_blocked' => true]);
            $this->fail('Steadfast refused authentication or account access (401/403). Automatic API requests are paused. Correct the keys / account and save the credentials again.');
        }
        if ($response->status() === 429) {
            $seconds = is_numeric($response->header('Retry-After')) ? max(60, min(86400, (int) $response->header('Retry-After'))) : 3600;
            $settings->update(['api_paused_until' => now()->addSeconds($seconds)]);
            $this->fail('Steadfast rate limit reached (429). Requests have been paused; do not repeatedly retry.');
        }
        if ($response->status() === 422) $this->fail('Steadfast rejected a booking field (422). Check recipient name, phone, address, invoice and COD. Verify the invoice in Steadfast before retrying.');
        if (!$response->successful() || !is_array($body) || (int) ($body['status'] ?? 0) !== 200) {
            $this->fail('Steadfast rejected the request or returned an unconfirmed response. Check credentials and invoice in the Steadfast panel, then Sync Status.');
        }
        return $body;
    }

    public function balance(): string
    {
        return (string) ($this->api('GET', 'get_balance')['current_balance'] ?? 'Unavailable');
    }

    public function book(Order $order): void
    {
        $lock = Cache::lock('steadfast-order-' . $order->id, 90);
        if (!$lock->get()) $this->fail('Another courier request is running. Please wait.');
        try {
            $settings = SteadfastSetting::configuration();
            $this->ready($settings);
            $shipment = DB::transaction(function () use ($order) {
                $current = Order::whereKey($order->id)->lockForUpdate()->firstOrFail();
                if ($current->pathaoShipment()->exists()) $this->fail('This order already has a Pathao booking or an unresolved request.');
                if ($existing = SteadfastShipment::where('order_id', $current->id)->first()) {
                    $this->fail($existing->booking_state === 'booked' ? 'This invoice is already booked. Use Sync Status.' : 'An earlier booking needs verification. Use Sync Status or check this invoice in Steadfast; another booking has been blocked to prevent duplicates.');
                }
                $current->load(['customerInfo', 'currentStatus']);
                if (in_array(strtolower(trim($current->currentStatus?->name ?? '')), ['cancelled', 'canceled', 'cancel', 'returned', 'delivered', 'completed'])) $this->fail('A cancelled, returned or completed order cannot be booked.');
                if ($current->is_courier_assigned && trim($current->courier_name ?? '') !== 'Steadfast') $this->fail('This order is already assigned to another courier.');
                $info = $current->customerInfo;
                $phone = preg_replace('/\D/', '', $info?->phone ?? '');
                if (str_starts_with($phone, '880')) $phone = substr($phone, 2);
                if (!$info || !trim($info->name ?? '') || !trim($info->address ?? '') || !preg_match('/^01[3-9]\d{8}$/', $phone)) $this->fail('A recipient name, address and valid Bangladesh mobile number are required. Edit the order first.');
                if (mb_strlen($current->order_no) > 100 || mb_strlen($info->name) > 100 || mb_strlen($info->address) > 490) $this->fail('Steadfast permits an invoice / recipient name up to 100 characters and an address up to 490 characters. Shorten the order information before booking to prevent truncation.');
                $cod = max(0, round((float) $current->due_amount, 2));
                $shipment = SteadfastShipment::create(['order_id' => $current->id, 'invoice' => $current->order_no, 'cod_amount' => $cod]);
                // The reservation commits before the external call; uncertain requests are never retried automatically.
                return [$shipment, ['invoice' => $current->order_no, 'recipient_name' => $info->name, 'recipient_phone' => $phone, 'recipient_address' => $info->address, 'cod_amount' => $cod, 'note' => mb_substr($info->order_note ?? '', 0, 480)]];
            });
            [$record, $payload] = $shipment;
            $authenticationRejected = false;
            try {
                $body = $this->api('POST', 'create_order', $payload, $authenticationRejected);
                $consignment = $body['consignment'] ?? [];
                if (empty($consignment['consignment_id']) || ($consignment['invoice'] ?? null) !== $record->invoice) $this->fail('Booking response could not be verified. Check the invoice in Steadfast and Sync Status.');
                $record->update(['booking_state' => 'booked', 'consignment_id' => (string) $consignment['consignment_id'], 'tracking_code' => $consignment['tracking_code'] ?? null, 'last_error' => null]);
                $this->apply($record, (string) ($consignment['status'] ?? 'in_review'), 'booking');
            } catch (\Throwable $e) {
                if ($authenticationRejected) $record->delete();
                elseif ($record->fresh()->booking_state !== 'booked') $record->update(['booking_state' => 'uncertain', 'last_error' => 'Booking not confirmed. Verify the invoice before any retry.']);
                throw $e;
            }
        } finally {
            $lock->release();
        }
    }

    public function sync(SteadfastShipment $shipment, string $source = 'manual'): void
    {
        $lock = Cache::lock('steadfast-order-' . $shipment->order_id, 90);
        if (!$lock->get()) $this->fail('Another courier request is running. Please wait.');
        try {
            $shipment->refresh();
            if ($shipment->checked_at?->gt(now()->subSeconds(60))) return;
            $shipment->update(['checked_at' => now()]);
            // Provider status reads are cached for 60s. Do not overwrite a fresh signed webhook with a cached response.
            $shipment->refresh();
            if ($shipment->webhook_received_at?->gt(now()->subSeconds(65))) return;
            $body = $this->api('GET', $shipment->consignment_id
                ? 'status_with_return_status_by_cid/' . rawurlencode($shipment->consignment_id)
                : 'status_by_invoice/' . rawurlencode($shipment->invoice));
            $status = strtolower(trim((string) ($body['delivery_status'] ?? '')));
            $returnStatus = strtolower(trim((string) ($body['return_status'] ?? '')));
            if (in_array($status, self::RETURN_STATUSES, true)) {
                $returnStatus = $status;
                $status = str_starts_with($status, 'cancelled_') ? 'cancelled' : 'partial_delivered';
            }
            if (!in_array($status, self::STATUSES, true)) $this->fail('Steadfast returned an unknown delivery status. No order status was changed.');
            $this->apply($shipment, $status, $source, null, in_array($returnStatus, self::RETURN_STATUSES, true) ? $returnStatus : null);
        } catch (\Throwable $e) {
            $shipment->update(['last_error' => 'Status sync failed. Check credentials / Steadfast panel.']);
            throw $e;
        } finally {
            $lock->release();
        }
    }

    public function apply(SteadfastShipment $shipment, string $deliveryStatus, string $source, ?\Carbon\CarbonInterface $providerTime = null, ?string $returnStatus = null): void
    {
        DB::transaction(function () use ($shipment, $deliveryStatus, $source, $providerTime, $returnStatus) {
            $order = Order::whereKey($shipment->order_id)->lockForUpdate()->firstOrFail();
            $shipment = SteadfastShipment::whereKey($shipment->id)->lockForUpdate()->firstOrFail();
            if ($providerTime && $shipment->provider_updated_at && $providerTime->lte($shipment->provider_updated_at)) return;
            // A repeated cancellation event must not erase an already confirmed return-received state.
            $returnStatus = $returnStatus ?: ($shipment->delivery_status === $deliveryStatus && in_array($deliveryStatus, ['cancelled', 'partial_delivered'], true) ? $shipment->return_status : null);
            $changed = $shipment->delivery_status !== $deliveryStatus || $shipment->return_status !== $returnStatus;
            $mapping = SteadfastSetting::configuration()->status_mapping ?? [];
            $mappingKey = $returnStatus && !empty($mapping[$returnStatus]) ? $returnStatus : $deliveryStatus;
            $mapped = !empty($mapping[$mappingKey]) ? Status::whereKey($mapping[$mappingKey])->where('status', true)->first() : null;
            $shipment->fill(['booking_state' => 'booked', 'delivery_status' => $deliveryStatus, 'synced_at' => now(), 'checked_at' => now(), 'last_error' => null]);
            $shipment->return_status = $returnStatus;
            if ($providerTime) $shipment->provider_updated_at = $providerTime;
            if ($source === 'webhook') $shipment->webhook_received_at = now();
            $shipment->saveOrFail();
            $order->fill(['courier_name' => 'Steadfast', 'is_courier_assigned' => true, 'courier_status' => $returnStatus ?: $deliveryStatus]);
            // Unmapped / approval-pending statuses never falsely complete or return an order.
            if ($mapped && (int) $order->current_status !== (int) $mapped->id) {
                $reason = in_array(strtolower(trim($mapped->name)), ['cancelled', 'canceled', 'cancel']) ? ($order->cancel_reason ?: 'Cancelled by Steadfast courier.') : null;
                $order->current_status = $mapped->id;
                $order->cancel_reason = $reason;
                $order->statusHistory()->create(['status_id' => $mapped->id, 'cancel_reason' => $reason]);
            }
            $order->saveOrFail();
            if ($changed) $shipment->events()->create(['source' => $source, 'delivery_status' => $returnStatus ?: $deliveryStatus, 'mapped_status_id' => $mapped?->id]);
        });
    }

    public function releaseUnconfirmed(Order $order): void
    {
        $lock = Cache::lock('steadfast-order-' . $order->id, 90);
        if (!$lock->get()) $this->fail('Another courier request is running. Please wait.');
        try {
            DB::transaction(function () use ($order) {
                Order::whereKey($order->id)->lockForUpdate()->firstOrFail();
                $shipment = SteadfastShipment::where('order_id', $order->id)->lockForUpdate()->firstOrFail();
                if ($shipment->booking_state === 'booked' || $shipment->consignment_id || $shipment->delivery_status || $shipment->created_at->gt(now()->subMinutes(2))) {
                    $this->fail('A confirmed or recently submitted booking cannot be cleared.');
                }
                // Only an explicit admin confirmation in the merchant panel permits a new attempt.
                $shipment->delete();
            });
        } finally {
            $lock->release();
        }
    }
}
