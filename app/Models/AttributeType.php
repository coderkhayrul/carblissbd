<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttributeType extends Model
{
    protected $fillable = ['name', 'description', 'status'];

    protected $casts = ['status' => 'boolean'];
}
