<?php

namespace App\Services;

use App\Models\{Order, Status, PathaoSetting, PathaoShipment, PathaoWebhook};
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\{Cache, DB, Validator};

class PathaoWebhookService
{
    public const EVENTS = [
        'order.created' => 'Order Created', 'order.updated' => 'Order Updated', 'order.pickup-requested' => 'Pickup Requested',
        'order.assigned-for-pickup' => 'Assigned For Pickup', 'order.picked' => 'Pickup', 'order.pickup-failed' => 'Pickup Failed',
        'order.pickup-cancelled' => 'Pickup Cancelled', 'order.at-the-sorting-hub' => 'At the Sorting Hub', 'order.in-transit' => 'In Transit',
        'order.received-at-last-mile-hub' => 'Received at Last Mile Hub', 'order.assigned-for-delivery' => 'Assigned for Delivery',
        'order.delivered' => 'Delivered', 'order.partial-delivery' => 'Partial Delivery', 'order.returned' => 'Return',
        'order.delivery-failed' => 'Delivery Failed', 'order.on-hold' => 'On Hold', 'order.paid' => 'Payment Invoice',
        'order.paid-return' => 'Paid Return', 'order.exchanged' => 'Exchange', 'store.created' => 'Store Created',
        'store.updated' => 'Store Updated', 'order.return-id-created' => 'Return ID Created', 'order.return-in-transit' => 'Return In Transit',
        'order.returned-to-merchant' => 'Returned To Merchant',
    ];

    // These events contain parcel metadata or settlement information, not a new delivery status.
    public const INFORMATION_EVENTS = ['order.updated', 'order.paid', 'order.return-id-created'];

    public static function defaultMappings(): array
    {
        $names = [
            'pending' => 'Processing', 'order.created' => 'Processing', 'order.pickup-requested' => 'Processing', 'order.assigned-for-pickup' => 'Processing',
            'order.picked' => 'Shipped', 'order.at-the-sorting-hub' => 'Shipped', 'order.in-transit' => 'Shipped',
            'order.received-at-last-mile-hub' => 'Shipped', 'order.assigned-for-delivery' => 'Shipped', 'order.delivered' => 'Delivered',
            'order.returned' => 'Returned', 'order.returned-to-merchant' => 'Returned',
        ];
        $statuses = Status::where('status', true)->get();
        $mapping = [];
        foreach ($names as $key => $name) {
            if ($status = $statuses->first(fn ($row) => strcasecmp(trim($row->name), $name) === 0)) $mapping[$key] = $status->id;
        }
        return $mapping;
    }

    public function process(int $id): void
    {
        $lock = Cache::lock('pathao-webhook-' . $id, 90);
        if (!$lock->get()) return;
        try {
            $event = PathaoWebhook::find($id);
            if (!$event || $event->processed_at || $event->retry_at?->isFuture()) return;
            $settings = PathaoSetting::configuration();
            if (!$settings->webhook_enabled) return;
            if ($event->environment !== $settings->environment) { $this->ignore($event, 'Environment changed.'); return; }
            $event->increment('attempts');
            try {
                if (!isset(self::EVENTS[$event->event])) { $this->ignore($event, 'Unsupported event acknowledged.'); return; }
                if (str_starts_with($event->event, 'store.')) {
                    // Store events do not contain all store fields; fetch current authoritative picker data in background.
                    app(PathaoService::class)->stores();
                    $this->done($event);
                    return;
                }
                $data = $event->payload;
                $validator = Validator::make($data, [
                    'consignment_id' => ['required', 'string', 'max:100'], 'merchant_order_id' => ['nullable', 'string', 'max:100'],
                    'store_id' => ['required', 'integer', 'min:1'], 'timestamp' => ['nullable', 'date'], 'updated_at' => ['nullable', 'date'],
                    'delivery_fee' => ['nullable', 'numeric', 'min:0', 'max:9999999999'], 'collected_amount' => ['nullable', 'numeric', 'min:0', 'max:9999999999'],
                    'return_consignment_id' => ['nullable', 'string', 'max:100'], 'return_type' => ['nullable', 'string', 'max:100'],
                    'invoice_id' => ['nullable', 'string', 'max:255'], 'reason' => ['nullable', 'string', 'max:2000'],
                ]);
                if ($validator->fails() || (empty($data['timestamp']) && empty($data['updated_at']))) { $this->ignore($event, 'Invalid event fields or missing timestamp.'); return; }
                // Prefer ISO timestamp with its explicit offset over the redundant local updated_at string.
                $time = CarbonImmutable::parse($data['timestamp'] ?? $data['updated_at'], 'Asia/Dhaka')->utc();
                $shipment = PathaoShipment::where('environment', $event->environment)->where(function ($query) use ($data) {
                    $query->where('consignment_id', $data['consignment_id']);
                    if (!empty($data['merchant_order_id'])) $query->orWhere('merchant_order_id', $data['merchant_order_id']);
                })->first();
                if (!$shipment) { $this->ignore($event, 'No matching managed shipment.'); return; }
                if (($shipment->consignment_id && $shipment->consignment_id !== $data['consignment_id']) || (!empty($data['merchant_order_id']) && $shipment->merchant_order_id !== $data['merchant_order_id']) || (int) $shipment->store_id !== (int) $data['store_id']) { $this->ignore($event, 'Parcel, invoice or store identifiers do not match.'); return; }
                $parcelLock = Cache::lock('courier-booking-order-' . $shipment->order_id, 90);
                if (!$parcelLock->get()) throw new \RuntimeException('Parcel busy.');
                try { $this->apply($shipment, $event, $data, $time); }
                finally { $parcelLock->release(); }
                $this->done($event);
            } catch (\Throwable $exception) {
                $event->update(['status' => 'pending', 'last_error' => 'Processing deferred. Check Pathao configuration / parcel details.', 'retry_at' => now()->addMinutes(min(60, 2 ** min(6, $event->attempts)))]);
                // Never log credentials, signature headers or raw provider payloads.
            }
        } finally { $lock->release(); }
    }

    private function apply(PathaoShipment $original, PathaoWebhook $event, array $data, CarbonImmutable $time): void
    {
        DB::transaction(function () use ($original, $event, $data, $time) {
            $order = Order::whereKey($original->order_id)->lockForUpdate()->firstOrFail();
            $shipment = PathaoShipment::whereKey($original->id)->lockForUpdate()->firstOrFail();
            $storedEvent = PathaoWebhook::whereKey($event->id)->lockForUpdate()->firstOrFail();
            if ($storedEvent->processed_at) return;
            $settings = PathaoSetting::configuration();
            $informationOnly = in_array($event->event, self::INFORMATION_EVENTS, true);
            $stale = $shipment->provider_updated_at && $time->lt($shipment->provider_updated_at);
            $shipment->webhook_received_at = now();
            // Metadata and settlement have their own clocks: a late payment event must never regress delivery status.
            if (!$shipment->metadata_updated_at || $time->gte($shipment->metadata_updated_at)) {
                foreach (['delivery_fee', 'return_consignment_id', 'return_type', 'reason'] as $field) {
                    if (isset($data[$field])) $shipment->{$field === 'reason' ? 'courier_reason' : $field} = $data[$field];
                }
                $shipment->metadata_updated_at = $time;
            }
            if (!$shipment->settlement_updated_at || $time->gte($shipment->settlement_updated_at)) {
                foreach (['collected_amount', 'invoice_id'] as $field) if (isset($data[$field])) $shipment->$field = $data[$field];
                if (isset($data['collected_amount']) || isset($data['invoice_id'])) $shipment->settlement_updated_at = $time;
            }
            if (!$shipment->consignment_id) $shipment->consignment_id = $data['consignment_id'];
            $shipment->booking_state = 'booked';
            $mapped = null;
            if (!$informationOnly && !$stale && $this->canAdvanceAtSameTime($shipment, $event->event, $time)) {
                $shipment->provider_updated_at = $time;
                $shipment->last_webhook_event = $event->event;
                $shipment->order_status = substr($event->event, strlen('order.'));
                $order->fill(['courier_name' => 'Pathao', 'is_courier_assigned' => true, 'courier_status' => $shipment->order_status]);
                $mapping = $settings->status_mapping ?? [];
                $statusId = $mapping[$event->event] ?? $mapping[strtolower($shipment->order_status)] ?? null;
                // A returned exchange/partial remainder is not a full order return.
                if ($event->event === 'order.returned-to-merchant' && in_array($data['return_type'] ?? $shipment->return_type, ['partial-delivery', 'exchange'], true)) $statusId = null;
                $mapped = $statusId ? Status::whereKey($statusId)->where('status', true)->first() : null;
                if ($mapped && (int) $order->current_status !== (int) $mapped->id) {
                    $reason = in_array(strtolower(trim($mapped->name)), ['cancelled', 'canceled', 'cancel'], true) ? ($data['reason'] ?? $order->cancel_reason ?? 'Cancelled by Pathao courier.') : null;
                    $order->current_status = $mapped->id;
                    $order->cancel_reason = $reason;
                    $order->statusHistory()->create(['status_id' => $mapped->id, 'cancel_reason' => $reason]);
                }
                $order->saveOrFail();
            }
            $shipment->synced_at = now();
            $shipment->last_error = null;
            $shipment->saveOrFail();
            $shipment->events()->create(['source' => $stale && !$informationOnly ? 'webhook-stale' : 'webhook', 'order_status' => $event->event, 'mapped_status_id' => $mapped?->id]);
            // Commit status/history and the inbox acknowledgement together, so a worker crash cannot duplicate history.
            $this->done($storedEvent);
        });
    }

    private function canAdvanceAtSameTime(PathaoShipment $shipment, string $event, CarbonImmutable $time): bool
    {
        if (!$shipment->provider_updated_at || !$time->equalTo($shipment->provider_updated_at)) return true;
        // Some documented return events share an identical timestamp. Permit forward return steps at that second.
        $rank = ['order.created' => 1, 'order.pickup-requested' => 2, 'order.assigned-for-pickup' => 3, 'order.picked' => 4,
            'order.at-the-sorting-hub' => 5, 'order.in-transit' => 6, 'order.received-at-last-mile-hub' => 7, 'order.assigned-for-delivery' => 8,
            'order.delivered' => 9, 'order.partial-delivery' => 9, 'order.returned' => 10, 'order.paid-return' => 10, 'order.exchanged' => 10,
            'order.return-in-transit' => 11, 'order.returned-to-merchant' => 12];
        return ($rank[$event] ?? 0) > ($rank[$shipment->last_webhook_event ?? ''] ?? 0);
    }

    private function done(PathaoWebhook $event): void { $event->update(['status' => 'processed', 'processed_at' => now(), 'retry_at' => null, 'last_error' => null]); }
    private function ignore(PathaoWebhook $event, string $reason): void { $event->update(['status' => 'ignored', 'processed_at' => now(), 'last_error' => $reason]); }
}
