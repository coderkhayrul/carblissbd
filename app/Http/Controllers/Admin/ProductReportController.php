<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Category, Brand, Status};
use App\Services\ProductReportService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductReportController extends Controller
{
    private function filters(Request $request): array
    {
        if (!$request->hasAny(['from','to','all_time'])) $request->merge(['from'=>now('Asia/Dhaka')->startOfMonth()->toDateString(),'to'=>now('Asia/Dhaka')->toDateString()]);
        $data = $request->validate([
            'from'=>['nullable','date_format:Y-m-d'],'to'=>array_filter(['nullable','date_format:Y-m-d',$request->filled('from')?'after_or_equal:from':null]),'all_time'=>['nullable','boolean'],
            'category_id'=>['nullable','integer','exists:categories,id'],'brand_id'=>['nullable','integer','exists:brands,id'],'order_status'=>['nullable','integer','exists:statuses,id'],
            'active'=>['nullable','boolean'],'published'=>['nullable','boolean'],'search'=>['nullable','string','max:100'],
            'source'=>['nullable',Rule::in(['Website','GSale','App'])],'channel'=>['nullable',Rule::in(['Checkout','Landing Page','Admin'])],
            'activity'=>['nullable',Rule::in(['sold','unsold'])],'group'=>['nullable',Rule::in(['product','variation'])],
            'sort'=>['nullable',Rule::in(['sales','quantity','margin','returns','name'])],'per_page'=>['nullable','integer',Rule::in([25,50,100])],
        ]);
        if (!empty($data['all_time'])) { $data['from']=null; $data['to']=null; }
        if (empty($data['from']) && empty($data['to'])) $data['all_time']=1;
        return $data;
    }

    public function index(Request $request, ProductReportService $service)
    {
        $filters=$this->filters($request);
        $report=$service->summary($filters);
        $rows=$service->sorted($filters)->paginate($filters['per_page'] ?? 50)->withQueryString();
        $categories=Category::serialOrder()->get(['id','name']);
        $brands=Brand::orderBy('name')->get(['id','name']);
        $statuses=Status::orderBy('id')->get(['id','name']);
        return response()->view('admin.reports.products',compact('filters','report','rows','categories','brands','statuses'))->header('Cache-Control','no-store, private');
    }

    public function export(Request $request, ProductReportService $service)
    {
        $filters=$this->filters($request);
        return response()->streamDownload(function () use ($filters,$service) {
            $handle=fopen('php://output','w');fwrite($handle,"\xEF\xBB\xBF");
            fputcsv($handle,['Product','Variation','SKU','Category','Brand','Active','Published','Current Sale Price','Current Purchase Price','Booked Orders','Booked Units','Returned Units','Retained Units','Gross Line Sales','Return Value','Retained Line Sales','Retained Cost','Estimated Gross Margin','Last Order Date'],',','"','');
            $safe=static fn ($value)=>preg_match('/^[\s]*[=+@-]/u',(string)$value)?"'".$value:$value;
            foreach ($service->sorted($filters)->cursor() as $row) fputcsv($handle,array_map($safe,[$row->name,$row->variation_name,$row->sku,$row->category_name,$row->brand_name,$row->status?'Yes':'No',$row->publish?'Yes':'No',$row->sale_price,$row->purchase_price,$row->order_count,$row->ordered_qty,$row->returned_qty,$row->net_qty,$row->gross_sales,$row->return_value,$row->net_sales,$row->cost,$row->gross_margin,$row->last_order_date]),',','"','');
            fclose($handle);
        },'product-report-'.now('Asia/Dhaka')->format('Y-m-d-His').'.csv',['Content-Type'=>'text/csv; charset=UTF-8','Cache-Control'=>'no-store, private']);
    }
}
