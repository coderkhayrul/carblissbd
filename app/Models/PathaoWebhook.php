<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PathaoWebhook extends Model
{
    protected $guarded = [];
    protected $hidden = ['payload'];
    protected $casts = ['payload' => 'encrypted:array', 'retry_at' => 'datetime', 'processed_at' => 'datetime'];
}
