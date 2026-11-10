<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SalesReportService;
use App\Models\{Customer, Status, User};
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SalesReportController extends Controller
{
    private function filters(Request $request): array
    {
        if (!$request->hasAny(['from', 'to', 'all_time'])) $request->merge(['from' => now('Asia/Dhaka')->startOfMonth()->toDateString(), 'to' => now('Asia/Dhaka')->toDateString()]);
        $data = $request->validate([
            'from' => ['nullable', 'date_format:Y-m-d'], 'to' => array_filter(['nullable', 'date_format:Y-m-d', $request->filled('from') ? 'after_or_equal:from' : null]),
            'all_time' => ['nullable', 'boolean'], 'source' => ['nullable', Rule::in(['Website', 'GSale', 'App'])],
            'channel' => ['nullable', Rule::in(['Checkout', 'Landing Page', 'Admin'])],
            'status_id' => ['nullable', 'integer', 'exists:statuses,id'], 'customer_id' => ['nullable', 'integer', 'exists:customers,id'],
            'staff_id' => ['nullable', 'integer', Rule::exists('users', 'id')->where('role', 'admin')],
            'payment_type' => ['nullable', Rule::in(['COD', 'Online'])], 'courier' => ['nullable', Rule::in(['Pathao', 'Steadfast'])],
            'invoice' => ['nullable', 'string', 'max:100'], 'group' => ['nullable', Rule::in(['daily', 'monthly'])],
            'per_page' => ['nullable', 'integer', Rule::in([25, 50, 100])],
        ]);
        if (!empty($data['all_time'])) { $data['from'] = null; $data['to'] = null; }
        if (empty($data['from']) && empty($data['to'])) $data['all_time'] = 1;
        return $data;
    }

    public function customers(Request $request)
    {
        $data = $request->validate(['q' => ['required', 'string', 'min:2', 'max:100']]);
        $term = trim($data['q']);
        $customers = Customer::where(function ($query) use ($term) {
            $query->where('name', 'like', '%' . $term . '%')->orWhere('mobile', 'like', '%' . $term . '%');
        })->orderBy('name')->limit(20)->get(['id', 'name', 'mobile']);
        return response()->json(['results' => $customers->map(fn ($customer) => ['id' => $customer->id, 'text' => $customer->name . ' — ' . $customer->mobile])])->header('Cache-Control', 'no-store, private');
    }

    public function index(Request $request, SalesReportService $service)
    {
        $filters = $this->filters($request);
        $report = $service->summary($filters);
        $orders = $service->rows($filters)->paginate($filters['per_page'] ?? 50)->withQueryString();
        $statuses = Status::orderBy('id')->get(['id', 'name']);
        $staff = User::where('role', 'admin')->orderBy('name')->get(['id', 'name']);
        $selectedCustomer = !empty($filters['customer_id']) ? Customer::find($filters['customer_id']) : null;
        return response()->view('admin.reports.sales', compact('filters', 'report', 'orders', 'statuses', 'staff', 'selectedCustomer'))->header('Cache-Control', 'no-store, private');
    }

    public function export(Request $request, SalesReportService $service)
    {
        $filters = $this->filters($request);
        return response()->streamDownload(function () use ($filters, $service) {
            $handle = fopen('php://output', 'w');
            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, ['Date', 'Invoice', 'Customer', 'Phone', 'Source', 'Channel', 'Status', 'Included in Sales', 'Quantity', 'Gross Products', 'Discount', 'Shipping', 'VAT', 'Order Value', 'Recorded Paid', 'Recorded Due', 'Recorded Returns', 'Payment Type', 'Courier', 'Staff'], ',', '"', '');
            $safe = static fn ($value) => preg_match('/^[\s]*[=+@-]/u', (string) $value) ? "'" . $value : $value;
            foreach ($service->rows($filters)->cursor() as $row) {
                fputcsv($handle, array_map($safe, [$row->date, $row->order_no, $row->customer_name, $row->phone, $row->order_source, $row->entry_channel, $row->status_name, $row->eligible ? 'Yes' : 'No', $row->total_quantity, $row->subtotal, $row->total_discount_amount, $row->total_shipping_cost, $row->total_vat_amount, $row->grand_total, $row->paid_amount, $row->due_amount, $row->returned_amount, $row->payment_type, $row->courier_name, $row->staff_name]), ',', '"', '');
            }
            fclose($handle);
        }, 'sales-report-' . now('Asia/Dhaka')->format('Y-m-d-His') . '.csv', ['Content-Type' => 'text/csv; charset=UTF-8', 'Cache-Control' => 'no-store, private']);
    }
}
