<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class ProductReportService
{
    public function query(array $filters)
    {
        $skuMode = ($filters['group'] ?? 'product') === 'variation';
        $cancelled = "LOWER(TRIM(COALESCE(s.name,''))) IN ('cancelled','canceled','cancel')";
        $returned = "LOWER(TRIM(COALESCE(s.name,''))) IN ('returned','return')";
        $returnQty = "CASE WHEN $returned THEN d.quantity ELSE LEAST(d.quantity, GREATEST(0, COALESCE(d.returned_qty,0))) END";
        $netQty = "CASE WHEN $cancelled THEN 0 ELSE GREATEST(0, d.quantity - ($returnQty)) END";
        $sales = DB::table('order_details as d')->join('orders as o', 'o.id', '=', 'd.order_id')->leftJoin('statuses as s', 's.id', '=', 'o.current_status');
        if (!empty($filters['from'])) $sales->whereDate('o.date', '>=', $filters['from']);
        if (!empty($filters['to'])) $sales->whereDate('o.date', '<=', $filters['to']);
        foreach (['source'=>'o.order_source','channel'=>'o.entry_channel','order_status'=>'o.current_status'] as $key=>$column) if (!empty($filters[$key])) $sales->where($column, $filters[$key]);
        $sales->selectRaw('d.product_id')->groupBy('d.product_id');
        if ($skuMode) $sales->selectRaw('COALESCE(d.product_variation_id,0) as variation_id')->groupByRaw('COALESCE(d.product_variation_id,0)');
        $sales->selectRaw("COUNT(DISTINCT CASE WHEN NOT ($cancelled) THEN o.id END) as order_count,
            COALESCE(SUM(CASE WHEN $cancelled THEN 0 ELSE d.quantity END),0) as ordered_qty,
            COALESCE(SUM(CASE WHEN $cancelled THEN 0 ELSE ($returnQty) END),0) as returned_qty,
            COALESCE(SUM($netQty),0) as net_qty,
            COALESCE(SUM(CASE WHEN $cancelled THEN 0 ELSE d.quantity * d.sale_price END),0) as gross_sales,
            COALESCE(SUM(CASE WHEN $cancelled THEN 0 ELSE ($returnQty) * d.sale_price END),0) as return_value,
            COALESCE(SUM(($netQty) * d.sale_price),0) as net_sales,
            COALESCE(SUM(($netQty) * d.purchase_price),0) as cost,
            MAX(CASE WHEN NOT ($cancelled) THEN o.date END) as last_order_date");

        $base = DB::table('products as p')->selectRaw("p.id as product_id, 0 as variation_id, p.name, p.sku, '' as variation_name, p.category_id, p.brand_id, p.thumbnail_image as image, p.status, p.publish, p.sale_price, p.purchase_price");
        if ($skuMode) {
            $base->where(function ($query) {
                $query->where('p.is_variation', false)->orWhereNotExists(fn ($q) => $q->selectRaw('1')->from('product_variations as v')->whereColumn('v.product_id','p.id'))
                    ->orWhereExists(fn ($q) => $q->selectRaw('1')->from('order_details as historic')->whereColumn('historic.product_id','p.id')->where(fn ($base) => $base->whereNull('historic.product_variation_id')->orWhere('historic.product_variation_id',0)));
            });
            $variants = DB::table('product_variations as v')->join('products as p','p.id','=','v.product_id')
                ->selectRaw("p.id as product_id, v.id as variation_id, p.name, v.sku, v.name as variation_name, p.category_id, p.brand_id, COALESCE(NULLIF(v.image,''),p.thumbnail_image) as image, CASE WHEN p.status = 1 AND v.status = 1 THEN 1 ELSE 0 END as status, p.publish, v.sale_price, v.purchase_price");
            $base->unionAll($variants);
        }
        $query = DB::query()->fromSub($base,'catalog')->leftJoinSub($sales,'sales',function ($join) use ($skuMode) {
            $join->on('sales.product_id','=','catalog.product_id');
            if ($skuMode) $join->on('sales.variation_id','=','catalog.variation_id');
        })->leftJoin('categories as category','category.id','=','catalog.category_id')->leftJoin('brands as brand','brand.id','=','catalog.brand_id');
        foreach (['category_id'=>'catalog.category_id','brand_id'=>'catalog.brand_id'] as $key=>$column) if (!empty($filters[$key])) $query->where($column,$filters[$key]);
        if (isset($filters['active']) && $filters['active'] !== null && $filters['active'] !== '') $query->where('catalog.status',(int)$filters['active']);
        if (isset($filters['published']) && $filters['published'] !== null && $filters['published'] !== '') $query->where('catalog.publish',(int)$filters['published']);
        if (!empty($filters['search'])) $query->where(function ($q) use ($filters) { $term = '%' . $filters['search'] . '%'; $q->where('catalog.name','like',$term)->orWhere('catalog.sku','like',$term)->orWhere('catalog.variation_name','like',$term); });
        if (($filters['activity'] ?? '') === 'sold') $query->whereRaw('COALESCE(sales.net_qty,0) > 0');
        if (($filters['activity'] ?? '') === 'unsold') $query->whereRaw('COALESCE(sales.net_qty,0) = 0');
        $query->select('catalog.*')->selectRaw("COALESCE(category.name,'Uncategorized') as category_name, COALESCE(brand.name,'Unbranded') as brand_name");
        foreach (['order_count','ordered_qty','returned_qty','net_qty','gross_sales','return_value','net_sales','cost'] as $column) $query->selectRaw("COALESCE(sales.$column,0) as $column");
        return $query->selectRaw('COALESCE(sales.net_sales,0) - COALESCE(sales.cost,0) as gross_margin, sales.last_order_date');
    }

    public function sorted(array $filters)
    {
        $column = match ($filters['sort'] ?? 'sales') { 'quantity'=>'net_qty','margin'=>'gross_margin','returns'=>'returned_qty','name'=>'name',default=>'net_sales' };
        return $this->query($filters)->orderBy($column, $column === 'name' ? 'asc' : 'desc')->orderBy('catalog.product_id')->orderBy('catalog.variation_id');
    }

    public function summary(array $filters): array
    {
        $stats = DB::query()->fromSub($this->query($filters),'report')->selectRaw('COUNT(*) as row_count, COUNT(DISTINCT product_id) as product_count, COALESCE(SUM(CASE WHEN net_qty > 0 THEN 1 ELSE 0 END),0) as sold_rows, COALESCE(SUM(CASE WHEN net_qty = 0 THEN 1 ELSE 0 END),0) as unsold_rows')
            ->selectRaw('COALESCE(SUM(ordered_qty),0) as ordered_qty, COALESCE(SUM(returned_qty),0) as returned_qty, COALESCE(SUM(net_qty),0) as net_qty, COALESCE(SUM(gross_sales),0) as gross_sales, COALESCE(SUM(return_value),0) as return_value, COALESCE(SUM(net_sales),0) as net_sales, COALESCE(SUM(cost),0) as cost, COALESCE(SUM(gross_margin),0) as gross_margin')->first();
        $top = $this->query($filters)->whereRaw('COALESCE(sales.net_qty,0) > 0')->orderByDesc('net_sales')->orderBy('catalog.product_id')->limit(10)->get();
        $categories = DB::query()->fromSub($this->query($filters),'report')->selectRaw('category_name, COUNT(DISTINCT product_id) as products, SUM(net_qty) as quantity, SUM(net_sales) as sales, SUM(gross_margin) as margin')->groupBy('category_name')->orderByDesc('sales')->get();
        return compact('stats','top','categories');
    }
}
