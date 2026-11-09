<?php

namespace App\Services;

use App\Models\{Order, Status, PathaoSetting, PathaoShipment};
use Illuminate\Support\Facades\{Cache, DB, Http};
use Illuminate\Validation\ValidationException;

class PathaoService
{
    public const HOSTS = ['production' => 'https://api-hermes.pathao.com', 'sandbox' => 'https://courier-api-sandbox.pathao.com'];

    private function fail(string $message): never
    {
        throw ValidationException::withMessages(['pathao' => $message]);
    }

    private function ready(PathaoSetting $settings): void
    {
        if (!$settings->enabled || !$settings->client_id || !$settings->client_secret || !$settings->username || !$settings->password) $this->fail('Save merchant email, password, Client ID and Client Secret, then enable Pathao in Courier Configuration.');
        if ($settings->auth_blocked) $this->fail('Pathao authentication is paused. Correct and save merchant credentials before reconnecting.');
        if ($settings->api_paused_until?->isFuture()) $this->fail('Pathao rate limit reached. Please wait before trying again.');
    }

    private function send(PathaoSetting $settings, string $method, string $path, array $data = [], ?string $token = null)
    {
        $host = self::HOSTS[$settings->environment] ?? null;
        if (!$host) $this->fail('Select a supported Pathao environment.');
        try {
            $request = Http::acceptJson()->asJson()->connectTimeout(5)->timeout(20)->withoutRedirecting();
            if ($token) $request = $request->withToken($token);
            return $request->send($method, $host . '/aladdin/api/v1/' . $path, $method === 'GET' ? ['query' => $data] : ['json' => $data]);
        } catch (\Throwable $exception) {
            $this->fail($path === 'orders' ? 'Pathao did not confirm the booking request. Check the merchant panel before retrying.' : 'Unable to reach Pathao. Check network access and try again later.');
        }
    }

    private function reject(PathaoSetting $settings, $response): never
    {
        if (in_array($response->status(), [401, 403], true)) {
            $settings->update(['auth_blocked' => true]);
            $this->fail('Pathao authentication / account access was refused (401/403). Correct and save the credentials.');
        }
        if ($response->status() === 429) {
            $seconds = is_numeric($response->header('Retry-After')) ? max(60, min(86400, (int) $response->header('Retry-After'))) : 300;
            $settings->update(['api_paused_until' => now()->addSeconds($seconds)]);
            $this->fail('Pathao rate limit reached. Requests have been paused temporarily.');
        }
        $this->fail('Pathao rejected the request (HTTP ' . $response->status() . '). Check required fields, account access and the merchant panel.');
    }

    public function token(bool $force = false): string
    {
        $lock = Cache::lock('pathao-token', 90);
        if (!$lock->get()) $this->fail('Another Pathao authentication request is running. Please wait.');
        try {
            $settings = PathaoSetting::configuration();
            $this->ready($settings);
            if (!$force && $settings->access_token && $settings->token_expires_at?->gt(now()->addMinute())) return $settings->access_token;
            $refresh = !empty($settings->refresh_token);
            $payload = ['client_id' => $settings->client_id, 'client_secret' => $settings->client_secret, 'grant_type' => $refresh ? 'refresh_token' : 'password'];
            if ($refresh) $payload['refresh_token'] = $settings->refresh_token;
            else $payload += ['username' => $settings->username, 'password' => $settings->password];
            $response = $this->send($settings, 'POST', 'issue-token', $payload);
            // One password-grant fallback when a stored refresh token has expired / been revoked.
            if ($refresh && in_array($response->status(), [400, 401, 422], true)) {
                $response = $this->send($settings, 'POST', 'issue-token', ['client_id' => $settings->client_id, 'client_secret' => $settings->client_secret, 'grant_type' => 'password', 'username' => $settings->username, 'password' => $settings->password]);
            }
            if (!$response->successful()) $this->reject($settings, $response);
            $body = $response->json();
            if (!is_array($body) || empty($body['access_token']) || empty($body['refresh_token']) || !is_numeric($body['expires_in'] ?? null) || (int) $body['expires_in'] <= 0 || strcasecmp($body['token_type'] ?? '', 'Bearer') !== 0) $this->fail('Pathao returned an invalid token response. No token was stored.');
            $settings->update(['access_token' => $body['access_token'], 'refresh_token' => $body['refresh_token'], 'token_expires_at' => now()->addSeconds((int) $body['expires_in']), 'auth_blocked' => false]);
            return $body['access_token'];
        } finally {
            $lock->release();
        }
    }

    public function request(string $method, string $path, array $data = [], ?bool &$definitelyRejected = null): array
    {
        $settings = PathaoSetting::configuration();
        $this->ready($settings);
        $token = $this->token();
        $response = $this->send($settings, $method, $path, $data, $token);
        // A 401 explicitly rejects this request; refresh once before retrying it.
        if ($response->status() === 401) {
            $token = $this->token(true);
            $response = $this->send($settings, $method, $path, $data, $token);
        }
        $definitelyRejected = in_array($response->status(), [400, 401, 403, 422, 429], true);
        if (!$response->successful()) $this->reject($settings, $response);
        $body = $response->json();
        if (!is_array($body) || ($body['type'] ?? '') !== 'success' || (int) ($body['code'] ?? 0) !== 200) $this->fail('Pathao did not confirm success. Check the merchant panel before trying again.');
        return $body;
    }

    public function locations(string $type, ?int $parent = null): array
    {
        $settings = PathaoSetting::configuration();
        $path = match ($type) {
            'cities' => 'city-list', 'zones' => 'cities/' . (int) $parent . '/zone-list', 'areas' => 'zones/' . (int) $parent . '/area-list',
            default => throw new \InvalidArgumentException('Invalid location type.'),
        };
        return Cache::remember('pathao-locations-' . $settings->environment . '-' . $type . '-' . ($parent ?? 0), 3600, fn () => $this->request('GET', $path)['data']['data'] ?? []);
    }

    public function stores(): array
    {
        $rows = [];
        for ($page = 1; $page <= 20; $page++) {
            $body = $this->request('GET', 'stores', ['page' => $page]);
            $data = $body['data'] ?? [];
            $rows = array_merge($rows, $data['data'] ?? []);
            if ($page >= (int) ($data['last_page'] ?? 1)) break;
        }
        // Keep just the fields needed for an admin store picker, not full API responses.
        $stores = array_map(fn ($row) => array_intersect_key($row, array_flip(['store_id', 'store_name', 'store_address', 'is_active', 'city_id', 'zone_id'])), $rows);
        PathaoSetting::configuration()->update(['stores' => $stores]);
        return $stores;
    }

    public function createStore(array $data): void { $this->request('POST', 'stores', $data); }
    public function price(array $data): array { return $this->request('POST', 'merchant/price-plan', $data)['data'] ?? []; }

    public function validateLocation(array $data, bool $required = false): void
    {
        $city = $data['recipient_city'] ?? null;
        $zone = $data['recipient_zone'] ?? null;
        $area = $data['recipient_area'] ?? null;
        if (($required && (!$city || !$zone)) || ($zone && !$city) || ($area && !$zone)) $this->fail('Select city, then its zone, then its area.');
        if ($city && !collect($this->locations('cities'))->contains(fn ($row) => (int) $row['city_id'] === (int) $city)) $this->fail('Invalid Pathao city.');
        if ($zone && !collect($this->locations('zones', (int) $city))->contains(fn ($row) => (int) $row['zone_id'] === (int) $zone)) $this->fail('The zone does not belong to the selected city.');
        if ($area && !collect($this->locations('areas', (int) $zone))->contains(fn ($row) => (int) $row['area_id'] === (int) $area)) $this->fail('The area does not belong to the selected zone.');
    }

    public function book(Order $order, array $data): void
    {
        $lock = Cache::lock('courier-booking-order-' . $order->id, 90);
        if (!$lock->get()) $this->fail('Another booking request is running.');
        try {
            $settings = PathaoSetting::configuration();
            $this->ready($settings);
            // Resolve authentication and lookup validation before reserving a booking.
            $this->token();
            $this->validateLocation($data);
            if (!collect($settings->stores ?? [])->contains(fn ($store) => (int) ($store['store_id'] ?? 0) === (int) $data['store_id'] && (int) ($store['is_active'] ?? 0) === 1)) $this->fail('Load your stores and select an active Pathao store first.');
            [$shipment, $payload] = DB::transaction(function () use ($order, $data, $settings) {
                $current = Order::whereKey($order->id)->lockForUpdate()->firstOrFail();
                if ($current->pathaoShipment()->exists() || $current->steadfastShipment()->exists()) $this->fail('This order already has a booking or an unresolved courier request.');
                if ($current->is_courier_assigned && trim($current->courier_name ?? '') !== 'Pathao') $this->fail('This order is assigned to another courier.');
                $current->load(['customerInfo', 'currentStatus', 'details']);
                if (in_array(strtolower(trim($current->currentStatus?->name ?? '')), ['cancelled', 'canceled', 'cancel', 'returned', 'delivered', 'completed'], true)) $this->fail('A completed, cancelled or returned order cannot be booked.');
                $info = $current->customerInfo;
                $phone = preg_replace('/\D/', '', $info?->phone ?? '');
                if (str_starts_with($phone, '880')) $phone = substr($phone, 2);
                if (!$info || !preg_match('/^01[3-9]\d{8}$/', $phone) || mb_strlen(trim($info->name ?? '')) < 3 || mb_strlen($info->name) > 100 || mb_strlen(trim($info->address ?? '')) < 10 || mb_strlen($info->address) > 220 || mb_strlen($current->order_no) > 100) $this->fail('Pathao requires a 3–100 character recipient name, 11 digit mobile and a 10–220 character address. Edit the order first.');
                $due = max(0, (float) $current->due_amount);
                if (abs($due - round($due)) > 0.00001) $this->fail('Pathao amount_to_collect must be a whole taka amount. Adjust the outstanding order total before booking.');
                $payload = ['store_id' => (int) $data['store_id'], 'merchant_order_id' => $current->order_no, 'recipient_name' => $info->name, 'recipient_phone' => $phone, 'recipient_address' => $info->address, 'delivery_type' => (int) $data['delivery_type'], 'item_type' => (int) $data['item_type'], 'item_quantity' => (int) $data['item_quantity'], 'item_weight' => (float) $data['item_weight'], 'amount_to_collect' => (int) round($due)];
                foreach (['recipient_city', 'recipient_zone', 'recipient_area'] as $field) if (!empty($data[$field])) $payload[$field] = (int) $data[$field];
                if (!empty($data['special_instruction'])) $payload['special_instruction'] = $data['special_instruction'];
                $payload['item_description'] = mb_substr($current->details->map(fn ($item) => $item->product_name . ($item->variation_name ? ' / ' . $item->variation_name : '') . ' x' . $item->quantity)->implode('; '), 0, 1000);
                $shipment = PathaoShipment::create(['order_id' => $current->id, 'merchant_order_id' => $current->order_no, 'environment' => $settings->environment, 'store_id' => $payload['store_id'], 'amount_to_collect' => $payload['amount_to_collect'], 'payload' => $payload]);
                return [$shipment, $payload];
            });
            $rejected = false;
            try {
                $body = $this->request('POST', 'orders', $payload, $rejected);
                $result = $body['data'] ?? [];
                if (empty($result['consignment_id']) || ($result['merchant_order_id'] ?? null) !== $shipment->merchant_order_id) $this->fail('Pathao booking response could not be verified. Check this invoice in the merchant panel.');
                $shipment->update(['consignment_id' => (string) $result['consignment_id'], 'booking_state' => 'booked', 'delivery_fee' => is_numeric($result['delivery_fee'] ?? null) ? $result['delivery_fee'] : null]);
                $this->apply($shipment, (string) ($result['order_status'] ?? 'Pending'), 'booking');
            } catch (\Throwable $e) {
                if ($rejected && !$shipment->fresh()->consignment_id) $shipment->delete();
                else $shipment->update(['booking_state' => $shipment->fresh()->consignment_id ? 'booked' : 'uncertain', 'last_error' => 'Booking response was not confirmed. Verify the invoice in Pathao before retrying.']);
                throw $e;
            }
        } finally {
            $lock->release();
        }
    }

    public function sync(PathaoShipment $shipment): void
    {
        $lock = Cache::lock('courier-booking-order-' . $shipment->order_id, 90);
        if (!$lock->get()) $this->fail('Another courier request is running.');
        try {
            $shipment->refresh();
            if (!$shipment->consignment_id) $this->fail('Enter the consignment ID from the Pathao panel to recover this unconfirmed booking.');
            if ($shipment->environment !== PathaoSetting::configuration()->environment) $this->fail('Select the same Pathao environment used to book this shipment.');
            $body = $this->request('GET', 'orders/' . rawurlencode($shipment->consignment_id) . '/info');
            $result = $body['data'] ?? [];
            if ((string) ($result['consignment_id'] ?? '') !== $shipment->consignment_id || ($result['merchant_order_id'] ?? null) !== $shipment->merchant_order_id) $this->fail('Pathao returned different shipment identifiers. No order status was changed.');
            $status = $result['order_status_slug'] ?? $result['order_status'] ?? null;
            if (!is_string($status) || !trim($status) || mb_strlen($status) > 100) $this->fail('Invalid Pathao status response.');
            $providerTime = !empty($result['updated_at']) ? \Carbon\CarbonImmutable::parse($result['updated_at'], 'Asia/Dhaka')->utc() : null;
            // Do not overwrite a signed webhook status with an older / undated provider snapshot.
            if ($shipment->provider_updated_at && (!$providerTime || $providerTime->lte($shipment->provider_updated_at))) return;
            $this->apply($shipment, $status, 'manual', $providerTime);
        } finally {
            $lock->release();
        }
    }

    public function recover(PathaoShipment $shipment, string $consignmentId): void
    {
        $lock = Cache::lock('courier-booking-order-' . $shipment->order_id, 90);
        if (!$lock->get()) $this->fail('Another courier request is running.');
        try {
            $shipment->refresh();
            if ($shipment->booking_state === 'booked' || $shipment->consignment_id) $this->fail('This booking is already confirmed. Use Sync Status.');
            if ($shipment->environment !== PathaoSetting::configuration()->environment) $this->fail('Select the same Pathao environment as the booking.');
            $body = $this->request('GET', 'orders/' . rawurlencode($consignmentId) . '/info');
            $result = $body['data'] ?? [];
            if (($result['merchant_order_id'] ?? null) !== $shipment->merchant_order_id || (string) ($result['consignment_id'] ?? '') !== $consignmentId) $this->fail('This consignment does not match the order invoice.');
            $status = $result['order_status_slug'] ?? $result['order_status'] ?? null;
            if (!is_string($status) || !trim($status) || mb_strlen($status) > 100) $this->fail('Invalid Pathao status response.');
            DB::transaction(function () use ($shipment, $consignmentId, $status) {
                Order::whereKey($shipment->order_id)->lockForUpdate()->firstOrFail();
                $current = PathaoShipment::whereKey($shipment->id)->lockForUpdate()->firstOrFail();
                if ($current->consignment_id) $this->fail('This booking has already been recovered.');
                $current->update(['consignment_id' => $consignmentId, 'booking_state' => 'booked']);
                $this->apply($current, $status, 'recovery');
            });
        } finally { $lock->release(); }
    }

    private function apply(PathaoShipment $shipment, string $providerStatus, string $source, ?\Carbon\CarbonInterface $providerTime = null): void
    {
        DB::transaction(function () use ($shipment, $providerStatus, $source, $providerTime) {
            $order = Order::whereKey($shipment->order_id)->lockForUpdate()->firstOrFail();
            $shipment = PathaoShipment::whereKey($shipment->id)->lockForUpdate()->firstOrFail();
            $changed = $shipment->order_status !== $providerStatus;
            $key = strtolower(trim($providerStatus));
            $mapping = PathaoSetting::configuration()->status_mapping ?? [];
            $status = !empty($mapping[$key]) ? Status::whereKey($mapping[$key])->where('status', true)->first() : null;
            $shipment->update(['booking_state' => 'booked', 'order_status' => $providerStatus, 'synced_at' => now(), 'last_error' => null]);
            if ($providerTime) $shipment->update(['provider_updated_at' => $providerTime, 'last_webhook_event' => null]);
            $order->fill(['courier_name' => 'Pathao', 'is_courier_assigned' => true, 'courier_status' => $providerStatus]);
            if ($status && (int) $order->current_status !== (int) $status->id) {
                $reason = in_array(strtolower(trim($status->name)), ['cancelled', 'canceled', 'cancel'], true) ? ($order->cancel_reason ?: 'Cancelled by Pathao courier.') : null;
                $order->current_status = $status->id;
                $order->cancel_reason = $reason;
                $order->statusHistory()->create(['status_id' => $status->id, 'cancel_reason' => $reason]);
            }
            $order->saveOrFail();
            if ($changed) $shipment->events()->create(['source' => $source, 'order_status' => $providerStatus, 'mapped_status_id' => $status?->id]);
        });
    }
}
