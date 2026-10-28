<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Order, Customer, Status};
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    private function filteredOrders(Request $request)
    {
        $filters = $request->validate([
            'customer_id' => ['nullable', 'integer', 'exists:customers,id'],
            'order_source' => ['nullable', Rule::in(['Website', 'GSale', 'App'])],
            'payment_type' => ['nullable', Rule::in(['COD', 'Online'])],
            'courier' => ['nullable', Rule::in(['Pathao', 'Steadfast', 'RedX', 'unassigned'])],
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
        return view('admin.orders.show', compact('order'));
    }
}
