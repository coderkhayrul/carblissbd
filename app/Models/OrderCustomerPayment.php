<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderCustomerPayment extends Model
{
    protected $guarded = [];
    protected $casts = ['amount' => 'decimal:2','paid_before'=>'decimal:2','paid_after'=>'decimal:2','due_after'=>'decimal:2','received_at'=>'datetime','superseded_at'=>'datetime'];
    public function collector() { return $this->belongsTo(User::class,'collected_by'); }
}
