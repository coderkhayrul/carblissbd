<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'name', 'code', 'amount', 'use_type', 'discount_type', 'start_date', 'end_date', 'is_highlighted', 'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2', 'start_date' => 'date', 'end_date' => 'date',
        'is_highlighted' => 'boolean', 'status' => 'boolean',
    ];
}
