<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Status;
use App\Services\CustomerReportService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CustomerReportController extends Controller
{
    private function filters(Request $request): array
    {
        if (!$request->hasAny(['from','to','all_time'])) $request->merge(['from'=>now('Asia/Dhaka')->startOfMonth()->toDateString(),'to'=>now('Asia/Dhaka')->toDateString()]);
        $data=$request->validate([
            'from'=>['nullable','date_format:Y-m-d'],'to'=>array_filter(['nullable','date_format:Y-m-d',$request->filled('from')?'after_or_equal:from':null]),'all_time'=>['nullable','boolean'],
            'active'=>['nullable','boolean'],'search'=>['nullable','string','max:100'],
            'source'=>['nullable',Rule::in(['Website','GSale','App'])],'channel'=>['nullable',Rule::in(['Checkout','Landing Page','Admin'])],
            'order_status'=>['nullable','integer','exists:statuses,id'],'segment'=>['nullable',Rule::in(['buyers','repeat','single','no_orders','outstanding','dormant'])],
            'sort'=>['nullable',Rule::in(['sales','orders','due','lifetime','recent','name'])],'per_page'=>['nullable','integer',Rule::in([25,50,100])],
        ]);
        if (!empty($data['all_time'])) { $data['from']=null;$data['to']=null; }
        if (empty($data['from']) && empty($data['to'])) $data['all_time']=1;
        return $data;
    }
    public function index(Request $request,CustomerReportService $service)
    {
        $filters=$this->filters($request);$report=$service->summary($filters);
        $rows=$service->sorted($filters)->paginate($filters['per_page'] ?? 50)->withQueryString();
        $statuses=Status::orderBy('id')->get(['id','name']);
        return response()->view('admin.reports.customers',compact('filters','report','rows','statuses'))->header('Cache-Control','no-store, private');
    }
    public function export(Request $request,CustomerReportService $service)
    {
        $filters=$this->filters($request);
        return response()->streamDownload(function () use ($filters,$service) {
            $handle=fopen('php://output','w');fwrite($handle,"\xEF\xBB\xBF");
            fputcsv($handle,['Customer ID','Name','Mobile','Email','Active','Registered','Period Orders','Period Sales Orders','Period Sales','Recorded Paid','Recorded Due','Average Sales Order','Cancelled Orders','Returned Orders','Completed Orders','Recorded Return Value','Lifetime Sales Orders','Lifetime Sales','First Purchase','Last Purchase'],',','"','');
            $safe=static fn ($value)=>preg_match('/^[\s]*[=+@-]/u',(string)$value)?"'".$value:$value;
            foreach ($service->sorted($filters)->cursor() as $row) fputcsv($handle,array_map($safe,[$row->id,$row->name,$row->mobile,$row->email,$row->status?'Yes':'No',$row->created_at,$row->order_count,$row->sales_orders,$row->sales,$row->paid,$row->due,$row->average_order,$row->cancelled_orders,$row->returned_orders,$row->completed_orders,$row->return_value,$row->lifetime_orders,$row->lifetime_sales,$row->first_purchase,$row->last_purchase]),',','"','');
            fclose($handle);
        },'customer-report-'.now('Asia/Dhaka')->format('Y-m-d-His').'.csv',['Content-Type'=>'text/csv; charset=UTF-8','Cache-Control'=>'no-store, private']);
    }
}
