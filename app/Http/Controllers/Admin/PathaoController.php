<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Order, Status, PathaoSetting};
use App\Services\PathaoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\{Rule, ValidationException};

class PathaoController extends Controller
{
    public function settings()
    {
        $settings = PathaoSetting::configuration();
        $statuses = Status::where('status', true)->orderBy('name')->get();
        $webhookEvents = \App\Models\PathaoWebhook::latest('id')->limit(20)->get(['id', 'event', 'status', 'attempts', 'created_at', 'processed_at', 'last_error']);
        return view('admin.settings.pathao', compact('settings', 'statuses', 'webhookEvents'));
    }

    public function save(Request $request)
    {
        $data = $request->validate([
            'enabled' => ['required', 'boolean'], 'environment' => ['required', Rule::in(array_keys(PathaoService::HOSTS))],
            'pathao_client_id' => ['nullable', 'string', 'max:255', 'regex:/^[^\r\n]+$/'],
            'pathao_client_secret' => ['nullable', 'string', 'max:1000', 'regex:/^[^\r\n]+$/'],
            'pathao_username' => ['nullable', 'email', 'max:255'], 'pathao_password' => ['nullable', 'string', 'max:1000'],
            'webhook_enabled' => ['required', 'boolean'], 'pathao_webhook_secret' => ['nullable', 'string', 'min:32', 'max:128', 'regex:/^[a-zA-Z0-9_.=\-]+$/'],
            'store_id' => ['nullable', 'integer', 'min:1'], 'delivery_type' => ['required', Rule::in([48, 12])],
            'item_type' => ['required', Rule::in([1, 2])], 'item_weight' => ['required', 'numeric', 'min:0.5', 'max:10'],
            'mapping_keys' => ['nullable', 'array', 'max:50'], 'mapping_keys.*' => ['nullable', 'string', 'max:100', 'regex:/^[a-zA-Z0-9_. -]+$/'],
            'mapping_values' => ['nullable', 'array', 'max:50'], 'mapping_values.*' => ['nullable', 'integer', Rule::exists('statuses', 'id')->where('status', true)],
        ]);
        $lock = Cache::lock('pathao-token', 90);
        if (!$lock->get()) throw ValidationException::withMessages(['pathao' => 'Authentication is running. Please save again shortly.']);
        try {
            $settings = PathaoSetting::configuration();
            $environmentChanged = $settings->environment !== $data['environment'];
            $credentialsChanged = $environmentChanged;
            foreach (['pathao_client_id' => 'client_id', 'pathao_client_secret' => 'client_secret', 'pathao_username' => 'username', 'pathao_password' => 'password'] as $input => $field) {
                if (isset($data[$input]) && $data[$input] !== '') {
                    $credentialsChanged = true;
                    $settings->$field = $data[$input];
                }
            }
            if ($data['enabled'] && (!$settings->client_id || !$settings->client_secret || !$settings->username || !$settings->password)) throw ValidationException::withMessages(['enabled' => 'Merchant login email and password, Client ID and Client Secret are required to enable Pathao.']);
            if ($environmentChanged) {
                $settings->stores = null;
                $data['store_id'] = null;
            }
            if (!empty($data['store_id']) && !collect($settings->stores ?? [])->contains(fn ($row) => (int) ($row['store_id'] ?? 0) === (int) $data['store_id'] && (int) ($row['is_active'] ?? 0) === 1)) throw ValidationException::withMessages(['store_id' => 'Load stores and select an active store from this environment.']);
            $mapping = [];
            foreach ($data['mapping_keys'] ?? [] as $index => $key) {
                $key = strtolower(trim($key ?? ''));
                if ($key !== '' && !empty($data['mapping_values'][$index])) $mapping[$key] = (int) $data['mapping_values'][$index];
            }
            $settings->fill(collect($data)->only(['enabled', 'environment', 'store_id', 'delivery_type', 'item_type', 'item_weight'])->all());
            $settings->status_mapping = $mapping;
            $settings->webhook_enabled = $data['webhook_enabled'];
            if (!empty($data['pathao_webhook_secret'])) {
                $settings->webhook_secret = $data['pathao_webhook_secret'];
                $settings->webhook_verified_at = null;
            }
            if (!$settings->webhook_secret) $settings->webhook_secret = \Illuminate\Support\Str::random(64);
            if ($credentialsChanged) $settings->fill(['access_token' => null, 'refresh_token' => null, 'token_expires_at' => null, 'auth_blocked' => false]);
            $settings->saveOrFail();
        } finally {
            $lock->release();
        }
        return back()->with('success', 'Pathao settings saved. Connect / Load Stores, select the pickup store and save again before booking.');
    }

    public function connect(PathaoService $service)
    {
        $stores = $service->stores();
        return back()->with('success', 'Pathao connected. ' . count($stores) . ' stores loaded. Select an active pickup store and save.');
    }

    public function locations(Request $request, string $type, PathaoService $service)
    {
        abort_unless(in_array($type, ['cities', 'zones', 'areas'], true), 404);
        $data = $request->validate(['parent' => [$type === 'cities' ? 'nullable' : 'required', 'integer', 'min:1']]);
        try {
            return response()->json(['data' => $service->locations($type, isset($data['parent']) ? (int) $data['parent'] : null)]);
        } catch (ValidationException $e) {
            return response()->json(['message' => $e->validator->errors()->first()], 422);
        }
    }

    public function createStore(Request $request, PathaoService $service)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:50'], 'contact_name' => ['required', 'string', 'min:3', 'max:50'],
            'contact_number' => ['required', 'regex:/^01[3-9][0-9]{8}$/'],
            'secondary_contact' => ['nullable', 'regex:/^01[3-9][0-9]{8}$/'], 'otp_number' => ['nullable', 'regex:/^01[3-9][0-9]{8}$/'],
            'address' => ['required', 'string', 'min:15', 'max:120'],
            'recipient_city' => ['required', 'integer', 'min:1'], 'recipient_zone' => ['required', 'integer', 'min:1'], 'recipient_area' => ['required', 'integer', 'min:1'],
        ]);
        $service->validateLocation($data, true);
        $payload = collect($data)->except(['recipient_city', 'recipient_zone', 'recipient_area'])->filter(fn ($value) => $value !== null && $value !== '')->all();
        $payload += ['city_id' => (int) $data['recipient_city'], 'zone_id' => (int) $data['recipient_zone'], 'area_id' => (int) $data['recipient_area']];
        $service->createStore($payload);
        return back()->with('success', 'Pathao store creation accepted. Approval may take one hour. Load Stores after approval to select it.');
    }

    public function booking(Order $order)
    {
        if (!$order->canAssignCourier('Pathao')) return redirect()->route('admin.orders.show', $order)->with('error', 'A courier is already assigned or a booking is pending. Use the existing courier details.');
        $settings = PathaoSetting::configuration();
        $order->load(['customerInfo', 'pathaoShipment']);
        return view('admin.orders.pathao-booking', compact('order', 'settings'));
    }

    private function parcelData(Request $request, bool $quote = false): array
    {
        return $request->validate([
            'store_id' => ['required', 'integer', 'min:1'], 'delivery_type' => ['required', Rule::in([12, 48])], 'item_type' => ['required', Rule::in([1, 2])],
            'item_weight' => ['required', 'numeric', 'min:0.5', 'max:10'], 'item_quantity' => [$quote ? 'nullable' : 'required', 'integer', 'min:1', 'max:99999'],
            'recipient_city' => [$quote ? 'required' : 'nullable', 'integer', 'min:1'], 'recipient_zone' => [$quote ? 'required' : 'nullable', 'integer', 'min:1'],
            'recipient_area' => ['nullable', 'integer', 'min:1'], 'special_instruction' => ['nullable', 'string', 'max:1000'],
        ]);
    }

    public function book(Request $request, Order $order, PathaoService $service)
    {
        $service->book($order, $this->parcelData($request));
        return redirect()->route('admin.orders.show', $order)->with('success', 'Pathao parcel booked successfully.');
    }

    public function price(Request $request, Order $order, PathaoService $service)
    {
        try {
            $data = $this->parcelData($request, true);
            $service->validateLocation($data, true);
            $payload = collect($data)->only(['store_id', 'delivery_type', 'item_type', 'item_weight', 'recipient_city', 'recipient_zone'])->map(fn ($value, $key) => $key === 'item_weight' ? (float) $value : (int) $value)->all();
            $result = $service->price($payload);
            return response()->json(['data' => collect($result)->only(['price', 'discount', 'promo_discount', 'final_price', 'cod_percentage', 'additional_charge'])->all()]);
        } catch (ValidationException $e) {
            return response()->json(['message' => $e->validator->errors()->first()], 422);
        }
    }

    public function sync(Order $order, PathaoService $service)
    {
        $service->sync($order->pathaoShipment()->firstOrFail());
        return back()->with('success', 'Pathao order status synced.');
    }

    public function recover(Request $request, Order $order, PathaoService $service)
    {
        $data = $request->validate(['consignment_id' => ['required', 'string', 'max:100', 'regex:/^[a-zA-Z0-9_-]+$/']]);
        $service->recover($order->pathaoShipment()->firstOrFail(), $data['consignment_id']);
        return back()->with('success', 'Pathao consignment recovered and synced.');
    }

    public function release(Request $request, Order $order)
    {
        $request->validate(['confirmed_absent' => ['accepted']]);
        $lock = Cache::lock('courier-booking-order-' . $order->id, 90);
        if (!$lock->get()) throw ValidationException::withMessages(['pathao' => 'Another request is running.']);
        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($order) {
                Order::whereKey($order->id)->lockForUpdate()->firstOrFail();
                $shipment = $order->pathaoShipment()->lockForUpdate()->firstOrFail();
                if ($shipment->consignment_id || $shipment->booking_state === 'booked' || $shipment->created_at->gt(now()->subMinutes(2))) throw ValidationException::withMessages(['pathao' => 'A confirmed or recently submitted booking cannot be cleared.']);
                $shipment->delete();
            });
        } finally { $lock->release(); }
        return back()->with('success', 'Unconfirmed reservation cleared. No Pathao parcel was cancelled or created.');
    }
}
