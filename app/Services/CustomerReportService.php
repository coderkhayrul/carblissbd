<?php

namespace App\Services;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;

class CustomerReportService
{
    public function query(array $filters)
    {
        $eligible = SalesReportService::ELIGIBLE;
        $returns = DB::table('order_return_records')->selectRaw('order_id, SUM(amount) as amount')->groupBy('order_id');
        $period = DB::table('orders')->leftJoin('statuses','statuses.id','=','orders.current_status')->leftJoinSub($returns,'returns','returns.order_id','=','orders.id');
        if (!empty($filters['from'])) $period->whereDate('orders.date','>=',$filters['from']);
        if (!empty($filters['to'])) $period->whereDate('orders.date','<=',$filters['to']);
        foreach (['source'=>'orders.order_source','channel'=>'orders.entry_channel','order_status'=>'orders.current_status'] as $key=>$column) if (!empty($filters[$key])) $period->where($column,$filters[$key]);
        $period->selectRaw("orders.customer_id, COUNT(*) as order_count, SUM(CASE WHEN $eligible THEN 1 ELSE 0 END) as sales_orders,
            COALESCE(SUM(CASE WHEN $eligible THEN orders.grand_total ELSE 0 END),0) as sales,
            COALESCE(SUM(CASE WHEN $eligible THEN orders.paid_amount ELSE 0 END),0) as paid,
            COALESCE(SUM(CASE WHEN $eligible THEN orders.due_amount ELSE 0 END),0) as due,
            SUM(CASE WHEN LOWER(TRIM(statuses.name)) IN ('cancelled','canceled','cancel') THEN 1 ELSE 0 END) as cancelled_orders,
            SUM(CASE WHEN LOWER(TRIM(statuses.name)) IN ('returned','return') THEN 1 ELSE 0 END) as returned_orders,
            SUM(CASE WHEN LOWER(TRIM(statuses.name)) IN ('delivered','completed','complete') THEN 1 ELSE 0 END) as completed_orders,
            COALESCE(SUM(returns.amount),0) as return_value, MAX(orders.date) as last_period_order")->groupBy('orders.customer_id');
        $lifetime = DB::table('orders')->leftJoin('statuses','statuses.id','=','orders.current_status')
            ->selectRaw("orders.customer_id, SUM(CASE WHEN $eligible THEN 1 ELSE 0 END) as lifetime_orders,
                COALESCE(SUM(CASE WHEN $eligible THEN orders.grand_total ELSE 0 END),0) as lifetime_sales,
                MIN(CASE WHEN $eligible THEN orders.date END) as first_purchase,
                MAX(CASE WHEN $eligible THEN orders.date END) as last_purchase")->groupBy('orders.customer_id');
        $query = DB::table('customers as c')->leftJoinSub($period,'period','period.customer_id','=','c.id')->leftJoinSub($lifetime,'lifetime','lifetime.customer_id','=','c.id');
        if (isset($filters['active']) && $filters['active'] !== '' && $filters['active'] !== null) $query->where('c.status',(int)$filters['active']);
        if (!empty($filters['search'])) $query->where(function ($q) use ($filters) { $term='%'.$filters['search'].'%';$q->where('c.name','like',$term)->orWhere('c.mobile','like',$term)->orWhere('c.email','like',$term); });
        switch ($filters['segment'] ?? '') {
            case 'buyers': $query->whereRaw('COALESCE(period.sales_orders,0) > 0'); break;
            case 'repeat': $query->whereRaw('COALESCE(period.sales_orders,0) > 0 AND COALESCE(lifetime.lifetime_orders,0) >= 2'); break;
            case 'single': $query->whereRaw('COALESCE(period.sales_orders,0) > 0 AND COALESCE(lifetime.lifetime_orders,0) = 1'); break;
            case 'no_orders': $query->whereRaw('COALESCE(period.order_count,0) = 0'); break;
            case 'outstanding': $query->whereRaw('COALESCE(period.due,0) > 0'); break;
            case 'dormant': $query->where(function ($q) { $q->whereNull('lifetime.last_purchase')->orWhere('lifetime.last_purchase','<',now('Asia/Dhaka')->subDays(90)->toDateString()); }); break;
        }
        $query->select('c.id','c.name','c.mobile','c.email','c.status','c.created_at');
        foreach (['order_count','sales_orders','sales','paid','due','cancelled_orders','returned_orders','completed_orders','return_value'] as $column) $query->selectRaw("COALESCE(period.$column,0) as $column");
        return $query->selectRaw('COALESCE(lifetime.lifetime_orders,0) as lifetime_orders, COALESCE(lifetime.lifetime_sales,0) as lifetime_sales, lifetime.first_purchase, lifetime.last_purchase, period.last_period_order, CASE WHEN COALESCE(period.sales_orders,0) > 0 THEN period.sales / period.sales_orders ELSE 0 END as average_order');
    }

    public function sorted(array $filters)
    {
        $column = match ($filters['sort'] ?? 'sales') { 'orders'=>'sales_orders','due'=>'due','lifetime'=>'lifetime_sales','recent'=>'last_purchase','name'=>'name',default=>'sales' };
        return $this->query($filters)->orderBy($column,$column === 'name' ? 'asc' : 'desc')->orderBy('c.id');
    }

    public function summary(array $filters): array
    {
        $stats = DB::query()->fromSub($this->query($filters),'report')->selectRaw('COUNT(*) as customers,
            COALESCE(SUM(CASE WHEN sales_orders > 0 THEN 1 ELSE 0 END),0) as buyers,
            COALESCE(SUM(CASE WHEN sales_orders > 0 AND lifetime_orders >= 2 THEN 1 ELSE 0 END),0) as repeat_buyers,
            COALESCE(SUM(CASE WHEN order_count = 0 THEN 1 ELSE 0 END),0) as without_orders,
            COALESCE(SUM(order_count),0) as orders, COALESCE(SUM(sales_orders),0) as sales_orders,
            COALESCE(SUM(sales),0) as sales, COALESCE(SUM(paid),0) as paid, COALESCE(SUM(due),0) as due,
            COALESCE(SUM(cancelled_orders),0) as cancelled_orders, COALESCE(SUM(returned_orders),0) as returned_orders,
            COALESCE(SUM(return_value),0) as return_value')->first();
        $new = DB::query()->fromSub($this->query($filters),'report');
        if (!empty($filters['from'])) $new->where('created_at','>=',CarbonImmutable::parse($filters['from'],'Asia/Dhaka')->startOfDay()->setTimezone(config('app.timezone'))->toDateTimeString());
        if (!empty($filters['to'])) $new->where('created_at','<=',CarbonImmutable::parse($filters['to'],'Asia/Dhaka')->endOfDay()->setTimezone(config('app.timezone'))->toDateTimeString());
        $stats->new_customers = $new->count();
        $stats->average_order = $stats->sales_orders > 0 ? $stats->sales / $stats->sales_orders : 0;
        $stats->repeat_rate = $stats->buyers > 0 ? $stats->repeat_buyers / $stats->buyers * 100 : 0;
        $top = $this->query($filters)->whereRaw('COALESCE(period.sales_orders,0) > 0')->orderByDesc('sales')->orderBy('c.id')->limit(10)->get();
        return compact('stats','top');
    }
}
