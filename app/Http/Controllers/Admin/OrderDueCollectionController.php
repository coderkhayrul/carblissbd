<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Order,OrderCustomerPayment};
use App\Services\OrderDueCollectionService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class OrderDueCollectionController extends Controller
{
    public function create(Order $order)
    {
        $order->load(['customerInfo','customer','currentStatus','payments.collector']);
        $returnBlocked=$order->details()->where('returned_qty','>',0)->exists() || \Illuminate\Support\Facades\DB::table('order_return_records')->where('order_id',$order->id)->exists();
        $token=(string)Str::uuid();
        return response()->view('admin.orders.due-collection',compact('order','token','returnBlocked'))->header('Cache-Control','no-store, private');
    }
    public function store(Request $request,Order $order,OrderDueCollectionService $service)
    {
        $data=$request->validate([
            'amount'=>['required','regex:/^\d{1,8}(?:\.\d{1,2})?$/'],
            'payment_method'=>['required',Rule::in(OrderDueCollectionService::METHODS)],
            'transaction_id'=>['nullable','string','max:191','required_unless:payment_method,Cash'],
            'received_at'=>['required','date_format:Y-m-d\TH:i'],'note'=>['nullable','string','max:2000'],'collection_token'=>['required','uuid'],
            'confirmed_received'=>['accepted'],
        ]);
        $receipt=$service->collect($order,$data,(int)$request->user()->id);
        return redirect()->route('admin.orders.due-collection',$order)->with('success','Payment received. Receipt PAY-'.str_pad((string)$receipt->id,8,'0',STR_PAD_LEFT).'. Outstanding: BDT '.number_format((float)$receipt->due_after,2));
    }
    public function receipt(Order $order,OrderCustomerPayment $payment)
    {
        abort_unless((int)$payment->order_id===(int)$order->id && $payment->record_type==='due_collection',404);
        $order->load(['customerInfo','customer']);$payment->load('collector');
        return response()->view('admin.orders.collection-receipt',['order'=>$order,'payment'=>$payment,'company'=>app('company.branding')])->header('Cache-Control','no-store, private');
    }
}
