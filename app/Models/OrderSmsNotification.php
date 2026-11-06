<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderSmsNotification extends Model
{
    protected $guarded = [];
    protected $casts = ['submitted_at' => 'datetime'];
    public function order() { return $this->belongsTo(Order::class); }
}
