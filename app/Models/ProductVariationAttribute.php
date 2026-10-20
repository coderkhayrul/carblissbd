<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariationAttribute extends Model
{
    protected $fillable = ['product_id', 'product_variation_id', 'attribute_type_id', 'attribute_id'];

    public function attributeType() { return $this->belongsTo(AttributeType::class); }
    public function attribute() { return $this->belongsTo(Attribute::class); }
    public function variation() { return $this->belongsTo(ProductVariation::class, 'product_variation_id'); }
}
