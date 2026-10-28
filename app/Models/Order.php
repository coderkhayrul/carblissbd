<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [];
    protected $casts = ['date' => 'date', 'grand_total' => 'decimal:2', 'subtotal' => 'decimal:2', 'shipping_cost' => 'decimal:2'];
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function details() { return $this->hasMany(OrderDetail::class); }
    public function customerInfo() { return $this->hasOne(OrderCustomerInfo::class); }
    public function payments() { return $this->hasMany(OrderCustomerPayment::class); }
    public function statusHistory() { return $this->hasMany(OrderStatus::class); }
    public function currentStatus() { return $this->belongsTo(Status::class, 'current_status'); }
}
