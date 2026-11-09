<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Order, Status, SteadfastSetting};
use App\Services\{AdminOrderService, SteadfastService};
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class SteadfastController extends Controller
{
    public function settings()
    {
        $settings = SteadfastSetting::configuration();
        $statuses = Status::where('status', true)->orderBy('name')->get();
        $mapping = $settings->status_mapping;
        if ($mapping === null) {
            $defaults = ['in_review' => 'Processing', 'pending' => 'Shipped', 'delivered' => 'Delivered', 'cancelled' => 'Cancelled'];
            $mapping = [];
            foreach ($defaults as $provider => $name) $mapping[$provider] = $statuses->first(fn ($status) => strcasecmp($status->name, $name) === 0)?->id;
        }
        $webhookEvents = \App\Models\SteadfastWebhook::latest('id')->limit(15)->get(['id', 'notification_type', 'status', 'attempts', 'created_at', 'processed_at', 'last_error']);
        return view('admin.settings.steadfast', compact('settings', 'statuses', 'mapping', 'webhookEvents'));
    }

    public function save(Request $request)
    {
        $data = $request->validate([
            'enabled' => ['required', 'boolean'],
            'base_url' => ['required', Rule::in(SteadfastService::API_URLS)],
            'steadfast_api_key' => ['nullable', 'string', 'max:1000', 'regex:/^[^\r\n]+$/'],
            'steadfast_secret_key' => ['nullable', 'string', 'max:1000', 'regex:/^[^\r\n]+$/'],
            'steadfast_webhook_token' => ['nullable', 'string', 'min:32', 'max:128', 'regex:/^[a-zA-Z0-9_.=\-]+$/'],
            'mapping' => ['required', 'array:' . implode(',', SteadfastService::MAPPABLE_STATUSES)],
            'mapping.*' => ['nullable', 'integer', Rule::exists('statuses', 'id')->where('status', true)],
        ]);
        $settings = SteadfastSetting::configuration();
        foreach (['steadfast_api_key' => 'api_key', 'steadfast_secret_key' => 'secret_key', 'steadfast_webhook_token' => 'webhook_token'] as $input => $field) {
            if (!empty($data[$input])) $settings->$field = $data[$input];
        }
        if ($data['enabled'] && (!$settings->api_key || !$settings->secret_key)) throw ValidationException::withMessages(['enabled' => 'API Key and Secret Key are required before enabling Steadfast.']);
        $settings->enabled = $data['enabled'];
        $settings->base_url = $data['base_url'];
        if (!empty($data['steadfast_api_key']) || !empty($data['steadfast_secret_key'])) $settings->auth_blocked = false;
        $settings->status_mapping = $data['mapping'];
        $settings->saveOrFail();
        return back()->with('success', 'Steadfast settings saved. Add the callback URL and bearer token to your Steadfast merchant panel.');
    }

    public function connection(SteadfastService $service)
    {
        return back()->with('success', 'Steadfast connected. Current balance: BDT ' . $service->balance());
    }

    public function sync(Order $order, SteadfastService $service)
    {
        $service->sync($order->steadfastShipment()->firstOrFail());
        return back()->with('success', 'Steadfast status synced.');
    }

    public function cancel(Request $request, Order $order, AdminOrderService $service)
    {
        $data = $request->validate(['cancel_reason' => ['required', 'string', 'max:2000', 'regex:/\S/u']]);
        $status = Status::where('status', true)->whereIn(\Illuminate\Support\Facades\DB::raw('LOWER(TRIM(name))'), ['cancelled', 'canceled', 'cancel'])->first();
        if (!$status) throw ValidationException::withMessages(['cancel_reason' => 'Create or enable the Cancelled order status first.']);
        $service->status($order, $status->id, $data['cancel_reason']);
        return back()->with('success', 'Order cancelled locally. If booked, cancel the parcel in the Steadfast merchant panel too. Sync Status will reflect the provider status.');
    }

    public function release(Request $request, Order $order, SteadfastService $service)
    {
        $request->validate(['confirmed_absent' => ['accepted']]);
        $service->releaseUnconfirmed($order);
        return back()->with('success', 'Unconfirmed reservation cleared. Correct the order or configuration, then Assign Steadfast to retry. No parcel was cancelled or booked by this action.');
    }
}
