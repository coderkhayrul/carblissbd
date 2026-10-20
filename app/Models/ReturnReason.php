<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnReason extends Model
{
    protected $fillable = ['title', 'description', 'serial_no', 'status'];

    protected $casts = ['status' => 'boolean', 'serial_no' => 'integer'];
}
