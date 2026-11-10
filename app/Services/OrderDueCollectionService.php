<?php

namespace App\Services;

use App\Models\{Order, OrderCustomerPayment};
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderDueCollectionService
{
    public const METHODS=['Cash','Bkash','Nagad','Rocket','Bank Transfer'];
    private function cents($value): int
    {
        $text=(string)$value;$negative=str_starts_with($text,'-');
        $parts=explode('.',ltrim($text,'-'),2);
        $cents=(int)$parts[0]*100+(int)str_pad(substr($parts[1] ?? '',0,2),2,'0');
        return $negative ? -$cents : $cents;
    }
    private function fail(string $message): never { throw ValidationException::withMessages(['amount'=>$message]); }

    public function collect(Order $order,array $data,int $userId): OrderCustomerPayment
    {
        $amount=$this->cents($data['amount']);
        $reference=trim($data['transaction_id'] ?? '') ?: null;
        $received=CarbonImmutable::createFromFormat('Y-m-d\TH:i',$data['received_at'],'Asia/Dhaka')->startOfMinute();
        $fingerprint=hash('sha256',json_encode([$order->id,$userId,$amount,$data['payment_method'],$reference,$data['received_at'],trim($data['note'] ?? '')],JSON_THROW_ON_ERROR));
        try {
            return DB::transaction(function () use ($order,$data,$userId,$amount,$reference,$received,$fingerprint) {
                $current=Order::whereKey($order->id)->lockForUpdate()->firstOrFail();
                if ($existing=OrderCustomerPayment::where('collection_token',$data['collection_token'])->first()) {
                    if ((int)$existing->order_id !== (int)$current->id || !hash_equals($existing->collection_fingerprint ?? '',$fingerprint)) $this->fail('This collection token was already used for a different payment. Reload the page.');
                    return $existing;
                }
                $current->load('currentStatus');
                if (!in_array(strtolower(trim($current->currentStatus?->name ?? '')),['delivered','completed','complete'],true)) $this->fail('Due collection is available after the order is Delivered / Completed.');
                if ($current->details()->where('returned_qty','>',0)->exists() || DB::table('order_return_records')->where('order_id',$current->id)->exists()) $this->fail('This order has returned products. Reconcile its refund and outstanding balance before collecting payment.');
                $total=$this->cents($current->grand_total);$paid=$this->cents($current->paid_amount);$due=$this->cents($current->due_amount);
                if ($paid<0 || $due<0 || $total-$paid !== $due) $this->fail('Order balances are inconsistent. Review the payment records before collecting.');
                $completed=$this->cents($current->payments()->where('payment_status','Completed')->sum('amount'));
                if ($completed !== $paid) $this->fail('Completed receipts do not match the saved paid balance. Review existing receipts before collecting.');
                if ($amount<=0 || $amount>$due) $this->fail('Collection must be greater than zero and cannot exceed the current outstanding amount.');
                if ($received->greaterThan(CarbonImmutable::now('Asia/Dhaka')->addMinute())) $this->fail('Received time cannot be in the future.');
                if ($reference && $current->payments()->where('payment_method',$data['payment_method'])->where('payment_status','Completed')->where(function ($q) use ($reference) { $q->where('transaction_id',$reference)->orWhere('payment_tnx_no',$reference)->orWhere('collection_reference',$reference); })->exists()) $this->fail('This payment reference has already been recorded for this order and payment method.');
                $payment=$current->payments()->create([
                    'customer_id'=>$current->customer_id,'payment_method'=>$data['payment_method'],'payment_status'=>'Completed','amount'=>$amount/100,
                    'transaction_id'=>$reference,'payment_tnx_no'=>$reference,'collection_reference'=>$reference,'record_type'=>'due_collection',
                    'collection_token'=>$data['collection_token'],'collection_fingerprint'=>$fingerprint,'collected_by'=>$userId,
                    'received_at'=>$received->setTimezone(config('app.timezone')),'paid_before'=>$paid/100,'paid_after'=>($paid+$amount)/100,'due_after'=>($due-$amount)/100,'note'=>trim($data['note'] ?? '') ?: null,
                ]);
                // Preserve pending history; replace its outstanding snapshot rather than declaring unreceived money paid.
                $current->payments()->where('payment_status','Pending')->whereNull('superseded_at')->update(['superseded_at'=>now()]);
                if ($due>$amount) $current->payments()->create(['customer_id'=>$current->customer_id,'payment_method'=>$current->payment_type==='COD'?'Cash':$data['payment_method'],'payment_status'=>'Pending','amount'=>($due-$amount)/100,'record_type'=>'balance','note'=>'Outstanding after receipt PAY-'.str_pad((string)$payment->id,8,'0',STR_PAD_LEFT)]);
                $current->update(['paid_amount'=>($paid+$amount)/100,'due_amount'=>($due-$amount)/100]);
                return $payment;
            },3);
        } catch (\Illuminate\Database\UniqueConstraintViolationException $exception) {
            $this->fail('This payment request or transaction reference has already been recorded. Reload and check receipt history before retrying.');
        }
    }
}
