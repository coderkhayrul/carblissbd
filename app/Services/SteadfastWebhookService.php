<?php

namespace App\Services;

use App\Models\{SteadfastSetting, SteadfastShipment, SteadfastWebhook};
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\{Cache, Validator};

class SteadfastWebhookService
{
    public function process(int $id): void
    {
        $lock = Cache::lock('steadfast-webhook-' . $id, 90);
        if (!$lock->get()) return;
        try {
            $event = SteadfastWebhook::find($id);
            if (!$event || $event->processed_at || $event->retry_at?->isFuture()) return;
            if (!SteadfastSetting::find(1)?->enabled) return;
            $event->increment('attempts');
            try {
                $data = $event->payload;
                // Account, payment and pickup notifications do not necessarily contain a parcel.
                $invoice = $data['invoice'] ?? null;
                $cid = $data['consignment_id'] ?? null;
                $shipment = is_string($invoice) ? SteadfastShipment::where('invoice', $invoice)->first() : null;
                if (!$shipment && (is_int($cid) || is_string($cid))) $shipment = SteadfastShipment::where('consignment_id', (string) $cid)->first();
                if (!$shipment && $event->notification_type === 'return_list_accepted') {
                    // Account-level event may have no single invoice: let the normal scheduler read each return's state.
                    SteadfastShipment::whereIn('delivery_status', ['cancelled', 'partial_delivered'])->update(['checked_at' => null]);
                    $event->update(['status' => 'processed', 'processed_at' => now(), 'last_error' => null]);
                    return;
                }
                if (!$shipment) {
                    $event->update(['status' => 'ignored', 'processed_at' => now(), 'last_error' => 'No matching managed shipment.']);
                    return;
                }
                if (($invoice && $invoice !== $shipment->invoice) || ($cid && $shipment->consignment_id && (string) $cid !== $shipment->consignment_id)) {
                    $event->update(['status' => 'ignored', 'processed_at' => now(), 'last_error' => 'Parcel identifiers do not match.']);
                    return;
                }
                if ($event->notification_type === 'delivery_status') {
                    $validator = Validator::make($data, [
                        'invoice' => ['required', 'string', 'max:100'],
                        'consignment_id' => ['required', 'integer', 'min:1'],
                        'status' => ['required', \Illuminate\Validation\Rule::in(SteadfastService::STATUSES)],
                        'updated_at' => ['required', 'date'],
                    ]);
                    if ($validator->fails()) {
                        $event->update(['status' => 'ignored', 'processed_at' => now(), 'last_error' => 'Invalid delivery event fields.']);
                        return;
                    }
                    $parcelLock = Cache::lock('steadfast-order-' . $shipment->order_id, 90);
                    if (!$parcelLock->get()) throw new \RuntimeException('Parcel busy.');
                    try {
                        // Raw-body HMAC authenticates these values. Timestamp ordering rejects stale events.
                        $providerTime = CarbonImmutable::parse($data['updated_at'], 'Asia/Dhaka')->utc();
                        app(SteadfastService::class)->apply($shipment, $data['status'], 'webhook', $providerTime);
                        // Recover the identifier when a booking response was previously lost.
                        $shipment->refresh();
                        if (!$shipment->consignment_id) $shipment->update(['consignment_id' => (string) $cid]);
                    } finally {
                        $parcelLock->release();
                    }
                } elseif (in_array($event->notification_type, ['tracking_update', 'consignment_update', 'return_list_accepted', 'cancel_request', 'return_request'], true)) {
                    // These events may omit status or have a non-delivery status. Reconcile asynchronously.
                    app(SteadfastService::class)->sync($shipment, 'webhook-reconcile');
                } else {
                    $event->update(['status' => 'ignored', 'processed_at' => now(), 'last_error' => null]);
                    return;
                }
                $event->update(['status' => 'processed', 'processed_at' => now(), 'last_error' => null, 'retry_at' => null]);
            } catch (\Throwable $exception) {
                $event->update(['status' => 'pending', 'last_error' => 'Processing deferred. Check courier configuration / shipment sync.', 'retry_at' => now()->addMinutes(min(60, 2 ** min(6, $event->attempts)))]);
                // No raw payload, credential or provider exception is logged.
            }
        } finally {
            $lock->release();
        }
    }
}
