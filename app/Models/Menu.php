<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = ['name', 'slug', 'icon', 'image', 'parent_id', 'serial_no', 'status'];

    protected $casts = ['status' => 'boolean', 'parent_id' => 'integer', 'serial_no' => 'integer'];

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }
}
