<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariation extends Model
{
    protected $fillable = ['product_id', 'name', 'sku', 'purchase_price', 'sale_price', 'bangla_sale_price', 'mrp_price', 'status', 'image'];

    protected $casts = [
        'purchase_price' => 'decimal:2', 'sale_price' => 'decimal:2', 'bangla_sale_price' => 'decimal:2',
        'mrp_price' => 'decimal:2', 'status' => 'boolean',
    ];

    public function product() { return $this->belongsTo(Product::class); }
    public function variationAttributes() { return $this->hasMany(ProductVariationAttribute::class); }

    public function optionLabel(?string $productName = null): string
    {
        $label = $this->variationAttributes->map(fn ($pair) => $pair->attribute?->name)
            ->filter()->unique()->implode(' / ');
        if ($label !== '') return $label;
        $label = trim($this->name);
        if ($productName && str_starts_with($label, $productName)) {
            $label = preg_replace('/^[\s\-–—\/|:]+|[\s\-–—\/|:]+$/u', '', substr($label, strlen($productName)));
        }
        return $label !== '' ? $label : 'Variation #' . $this->id;
    }
}
