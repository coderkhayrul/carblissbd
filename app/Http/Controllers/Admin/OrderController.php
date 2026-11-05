<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Order, Customer, Status};
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\ReturnReason;
use App\Services\AdminOrderService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    private function filteredOrders(Request $request)
    {
        $filters = $request->validate([
            'customer_id' => ['nullable', 'integer', 'exists:customers,id'],
            'order_source' => ['nullable', Rule::in(['Website', 'GSale', 'App'])],
            'payment_type' => ['nullable', Rule::in(['COD', 'Online'])],
            'courier' => ['nullable', Rule::in(array_merge(AdminOrderService::COURIERS, ['unassigned']))],
            'status_id' => ['nullable', 'integer', 'exists:statuses,id'],
            'date_from' => ['nullable', 'date_format:Y-m-d'],
            'date_to' => array_merge(['nullable', 'date_format:Y-m-d'], $request->filled('date_from') ? ['after_or_equal:date_from'] : []),
            'order_no' => ['nullable', 'string', 'max:255'],
        ]);
        $query = Order::with(['customerInfo', 'customer', 'currentStatus'])->latest('id');
        foreach (['customer_id' => 'customer_id', 'order_source' => 'order_source', 'payment_type' => 'payment_type', 'status_id' => 'current_status'] as $filter => $column) {
            if (isset($filters[$filter]) && $filters[$filter] !== '') $query->where($column, $filters[$filter]);
        }
        if (!empty($filters['courier'])) {
            if ($filters['courier'] === 'unassigned') $query->where('is_courier_assigned', false);
            else $query->whereRaw('TRIM(courier_name) = ?', [$filters['courier']])->where('is_courier_assigned', true);
        }
        if (!empty($filters['date_from'])) $query->whereDate('date', '>=', $filters['date_from']);
        if (!empty($filters['date_to'])) $query->whereDate('date', '<=', $filters['date_to']);
        if (!empty($filters['order_no'])) $query->where('order_no', 'like', '%' . trim($filters['order_no']) . '%');
        return $query;
    }

    public function index(Request $request)
    {
        $orders = $this->filteredOrders($request)->get();
        $customers = Customer::orderBy('name')->get(['id', 'name', 'mobile']);
        $statuses = Status::orderBy('id')->get(['id', 'name']);
        return view('admin.orders.index', compact('orders', 'customers', 'statuses'));
    }

    public function export(Request $request)
    {
        $query = $this->filteredOrders($request);
        return response()->streamDownload(function () use ($query) {
            $output = fopen('php://output', 'w');
            fwrite($output, "\xEF\xBB\xBF");
            fputcsv($output, ['Invoice', 'Date', 'Customer', 'Phone', 'Source', 'Quantity', 'Paid', 'Due', 'Total', 'Payment Type', 'Status', 'Courier'], ',', '"', '');
            $safe = static function ($value) {
                $value = (string) $value;
                return preg_match('/^[\s]*[=+@-]/u', $value) ? "'" . $value : $value;
            };
            foreach ($query->lazy(500) as $order) {
                fputcsv($output, array_map($safe, [
                    $order->order_no, $order->date?->format('Y-m-d'),
                    $order->customerInfo?->name ?? $order->customer?->name,
                    $order->customerInfo?->phone ?? $order->customer?->mobile,
                    $order->order_source, $order->total_quantity, $order->paid_amount, $order->due_amount,
                    $order->grand_total, $order->payment_type, $order->currentStatus?->name,
                    $order->is_courier_assigned ? trim($order->courier_name ?? '') : 'Not Assigned',
                ]), ',', '"', '');
            }
            fclose($output);
        }, 'orders-' . now('Asia/Dhaka')->format('Y-m-d-His') . '.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function show(Order $order)
    {
        $order->load(['customer', 'customerInfo', 'details', 'payments', 'statusHistory.status', 'currentStatus']);
        $complaints = DB::table('order_complaints')->where('order_id', $order->id)->latest('id')->get();
        $returns = DB::table('order_return_records')->join('return_reasons', 'return_reasons.id', '=', 'order_return_records.return_reason_id')
            ->where('order_return_records.order_id', $order->id)->select('order_return_records.*', 'return_reasons.title as reason')->latest('order_return_records.id')->get();
        return view('admin.orders.show', compact('order', 'complaints', 'returns'));
    }

    public function invoice(Order $order, string $copy)
    {
        abort_unless(array_key_exists($copy, AdminOrderService::COPIES), 404);
        $order->load(['customerInfo', 'customer', 'details', 'currentStatus']);
        $copyLabel = AdminOrderService::COPIES[$copy];
        return response()->view('admin.orders.invoice', compact('order', 'copy', 'copyLabel'))->header('Cache-Control', 'no-store, private');
    }

    public function action(Order $order, string $action)
    {
        abort_unless(in_array($action, ['status', 'return', 'complaint', 'duplicate'], true), 404);
        $order->load(['details', 'currentStatus']);
        $statuses = Status::where('status', true)->orderBy('id')->get();
        $reasons = ReturnReason::where('status', true)->orderBy('serial_no')->get();
        $requestToken = (string) Str::uuid();
        return view('admin.orders.action', compact('order', 'action', 'statuses', 'reasons', 'requestToken'));
    }

    public function performAction(Request $request, Order $order, string $action, AdminOrderService $service)
    {
        $rules = match ($action) {
            'status' => ['status_id' => ['required', 'integer', Rule::exists('statuses', 'id')->where('status', true)]],
            'complaint' => ['subject' => ['required', 'string', 'max:255'], 'message' => ['required', 'string', 'max:10000']],
            'duplicate' => ['request_token' => ['required', 'uuid'], 'confirm' => ['accepted']],
            'return' => [
                'request_token' => ['required', 'uuid'],
                'return_reason_id' => ['required', 'integer', Rule::exists('return_reasons', 'id')->where('status', true)],
                'note' => ['nullable', 'string', 'max:2000'],
                'items' => ['required', 'array', 'min:1', 'max:100'],
                'items.*.id' => ['required', 'integer', 'distinct', Rule::exists('order_details', 'id')->where('order_id', $order->id)],
                'items.*.quantity' => ['required', 'integer', 'min:0', 'max:999999'],
            ],
            default => abort(404),
        };
        $data = $request->validate($rules);
        try {
            if ($action === 'duplicate') {
                $copy = $service->duplicate($order, $data['request_token']);
                return redirect()->route('admin.orders.show', $copy)->with('success', 'Order duplicated with a new invoice number.');
            }
            match ($action) {
                'status' => $service->status($order, (int) $data['status_id']),
                'complaint' => $service->complaint($order, $data, $request->user()->id),
                'return' => $service->recordReturn($order, $data, $request->user()->id),
            };
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (\Throwable $exception) {
            report($exception);
            return back()->withInput()->with('error', 'Unable to save this order action. Please try again.');
        }
        return redirect()->route('admin.orders.show', $order)->with('success', 'Order action saved successfully.');
    }

    public function assignCourier(Request $request, Order $order, AdminOrderService $service)
    {
        $data = $request->validate(['courier' => ['required', Rule::in(AdminOrderService::COURIERS)]]);
        try {
            $service->courier($order, $data['courier']);
        } catch (\Throwable $exception) {
            report($exception);
            return back()->with('error', 'Unable to assign this courier. Please try again.');
        }
        return back()->with('success', $data['courier'] . ' assigned locally. No courier API booking was sent.');
    }
}
