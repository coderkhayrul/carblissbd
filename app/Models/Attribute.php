<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    protected $fillable = ['attribute_type_id', 'name', 'slug', 'color_code', 'description', 'status'];

    protected $casts = ['status' => 'boolean', 'attribute_type_id' => 'integer'];

    public function attributeType()
    {
        return $this->belongsTo(AttributeType::class);
    }
}
