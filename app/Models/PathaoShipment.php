<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PathaoShipment extends Model
{
    protected $guarded = [];
    protected $hidden = ['payload'];
    protected $casts = ['payload' => 'encrypted:array', 'synced_at' => 'datetime', 'delivery_fee' => 'decimal:2', 'collected_amount' => 'decimal:2', 'provider_updated_at' => 'datetime', 'webhook_received_at' => 'datetime', 'metadata_updated_at' => 'datetime', 'settlement_updated_at' => 'datetime'];
    public function order() { return $this->belongsTo(Order::class); }
    public function events() { return $this->hasMany(PathaoShipmentEvent::class, 'shipment_id'); }
}
