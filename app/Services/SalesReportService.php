<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class SalesReportService
{
    public const ELIGIBLE = "LOWER(TRIM(COALESCE(statuses.name, ''))) NOT IN ('cancelled','canceled','cancel','returned','return')";

    public function query(array $filters)
    {
        $returns = DB::table('order_return_records')->selectRaw('order_id, SUM(amount) as returned_amount, SUM(quantity) as returned_quantity')->groupBy('order_id');
        $query = DB::table('orders')->leftJoin('statuses', 'statuses.id', '=', 'orders.current_status')
            ->leftJoin('order_customer_infos as recipient', 'recipient.order_id', '=', 'orders.id')
            ->leftJoin('customers', 'customers.id', '=', 'orders.customer_id')
            ->leftJoin('users as staff', 'staff.id', '=', 'orders.created_by')
            ->leftJoinSub($returns, 'returns', 'returns.order_id', '=', 'orders.id');
        if (!empty($filters['from'])) $query->whereDate('orders.date', '>=', $filters['from']);
        if (!empty($filters['to'])) $query->whereDate('orders.date', '<=', $filters['to']);
        foreach (['source' => 'orders.order_source', 'channel' => 'orders.entry_channel', 'status_id' => 'orders.current_status', 'customer_id' => 'orders.customer_id', 'staff_id' => 'orders.created_by', 'payment_type' => 'orders.payment_type', 'courier' => 'orders.courier_name'] as $key => $column) {
            if (!empty($filters[$key])) $query->where($column, $filters[$key]);
        }
        if (!empty($filters['invoice'])) $query->where('orders.order_no', 'like', '%' . $filters['invoice'] . '%');
        return $query;
    }

    public function rows(array $filters)
    {
        return $this->query($filters)->select('orders.id', 'orders.order_no', 'orders.date', 'orders.order_source', 'orders.entry_channel', 'orders.payment_type', 'orders.courier_name', 'orders.total_quantity', 'orders.subtotal', 'orders.total_discount_amount', 'orders.total_shipping_cost', 'orders.total_vat_amount', 'orders.grand_total', 'orders.paid_amount', 'orders.due_amount')
            ->selectRaw("COALESCE(recipient.name, customers.name, 'Customer') as customer_name, COALESCE(recipient.phone, customers.mobile) as phone, statuses.name as status_name, staff.name as staff_name, COALESCE(returns.returned_amount, 0) as returned_amount, CASE WHEN " . self::ELIGIBLE . ' THEN 1 ELSE 0 END as eligible')->orderByDesc('orders.date')->orderByDesc('orders.id');
    }

    public function summary(array $filters): array
    {
        $eligible = self::ELIGIBLE;
        $columns = ['subtotal', 'total_discount_amount', 'total_shipping_cost', 'total_vat_amount', 'grand_total', 'paid_amount', 'due_amount', 'total_quantity'];
        $query = $this->query($filters)->selectRaw('COUNT(*) as order_count, COUNT(DISTINCT orders.customer_id) as customer_count')
            ->selectRaw("SUM(CASE WHEN $eligible THEN 1 ELSE 0 END) as eligible_count")
            ->selectRaw("SUM(CASE WHEN LOWER(TRIM(statuses.name)) IN ('cancelled','canceled','cancel') THEN 1 ELSE 0 END) as cancelled_count")
            ->selectRaw("SUM(CASE WHEN LOWER(TRIM(statuses.name)) IN ('returned','return') THEN 1 ELSE 0 END) as returned_count")
            ->selectRaw("SUM(CASE WHEN LOWER(TRIM(statuses.name)) IN ('delivered','completed','complete') THEN orders.grand_total ELSE 0 END) as completed_sales")
            ->selectRaw('COALESCE(SUM(returns.returned_amount), 0) as recorded_returns');
        foreach ($columns as $column) $query->selectRaw("COALESCE(SUM(CASE WHEN $eligible THEN orders.$column ELSE 0 END), 0) as $column");
        $stats = (array) $query->first();
        $stats['average_order'] = (int) $stats['eligible_count'] > 0 ? (float) $stats['grand_total'] / (int) $stats['eligible_count'] : 0;
        $group = ($filters['group'] ?? 'daily') === 'monthly' ? 'SUBSTR(orders.date, 1, 7)' : 'DATE(orders.date)';
        $periods = $this->query($filters)->selectRaw("$group as period, COUNT(*) as orders, COALESCE(SUM(CASE WHEN $eligible THEN orders.grand_total ELSE 0 END), 0) as sales, COALESCE(SUM(CASE WHEN $eligible THEN orders.paid_amount ELSE 0 END), 0) as paid, COALESCE(SUM(CASE WHEN $eligible THEN orders.due_amount ELSE 0 END), 0) as due")
            ->groupByRaw($group)->orderBy('period')->get();
        $sources = $this->query($filters)->selectRaw("orders.order_source as source, orders.entry_channel as channel, COUNT(*) as orders, COALESCE(SUM(CASE WHEN $eligible THEN orders.grand_total ELSE 0 END), 0) as sales")
            ->groupBy('orders.order_source', 'orders.entry_channel')->orderByDesc('sales')->get();
        return compact('stats', 'periods', 'sources');
    }
}
