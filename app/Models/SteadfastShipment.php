<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SteadfastShipment extends Model
{
    protected $guarded = [];
    protected $casts = ['synced_at' => 'datetime', 'checked_at' => 'datetime', 'cod_amount' => 'decimal:2', 'provider_updated_at' => 'datetime', 'webhook_received_at' => 'datetime'];
    public function order() { return $this->belongsTo(Order::class); }
    public function events() { return $this->hasMany(SteadfastEvent::class, 'shipment_id'); }
}
