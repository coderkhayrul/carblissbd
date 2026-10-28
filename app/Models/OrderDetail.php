<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $guarded = [];
    protected $casts = [
        'attributes' => 'array', 'quantity' => 'integer', 'returned_qty' => 'integer', 'exist_in_return' => 'boolean',
        'purchase_price' => 'decimal:2', 'sale_price' => 'decimal:2', 'mrp_price' => 'decimal:2',
        'total_cost' => 'decimal:2', 'total_amount' => 'decimal:2', 'unit_price' => 'decimal:2', 'line_total' => 'decimal:2',
    ];
}
