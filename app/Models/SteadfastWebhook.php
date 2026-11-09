<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SteadfastWebhook extends Model
{
    protected $guarded = [];
    protected $hidden = ['payload'];
    protected $casts = ['payload' => 'encrypted:array', 'processed_at' => 'datetime', 'retry_at' => 'datetime'];
}
