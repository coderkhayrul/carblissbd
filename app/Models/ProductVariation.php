<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariation extends Model
{
    protected $fillable = ['product_id', 'name', 'sku', 'purchase_price', 'sale_price', 'bangla_sale_price', 'mrp_price', 'status'];

    protected $casts = [
        'purchase_price' => 'decimal:2', 'sale_price' => 'decimal:2', 'bangla_sale_price' => 'decimal:2',
        'mrp_price' => 'decimal:2', 'status' => 'boolean',
    ];

    public function product() { return $this->belongsTo(Product::class); }
    public function variationAttributes() { return $this->hasMany(ProductVariationAttribute::class); }
}
