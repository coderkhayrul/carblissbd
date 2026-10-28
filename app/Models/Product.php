<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id', 'brand_id', 'name', 'slug', 'sku', 'bangla_name', 'purchase_price', 'sale_price',
        'bangla_sale_price', 'mrp_price', 'thumbnail_image', 'size_chart_image', 'video_link', 'is_variation',
        'is_refundable', 'is_new_arrival', 'is_offer', 'barcode', 'status', 'publish', 'features', 'bangla_features',
        'short_description', 'bangla_short_description', 'description', 'bangla_description',
        'landing_page_title', 'landing_page_feature', 'landing_short_description', 'landing_long_description',
        'landing_youtube_link', 'meta_title', 'meta_description', 'meta_keywords',
    ];

    protected $casts = [
        'purchase_price' => 'decimal:2', 'sale_price' => 'decimal:2', 'bangla_sale_price' => 'decimal:2',
        'mrp_price' => 'decimal:2', 'is_variation' => 'boolean', 'is_refundable' => 'boolean',
        'is_new_arrival' => 'boolean', 'is_offer' => 'boolean', 'status' => 'boolean', 'publish' => 'boolean',
    ];

    public function category() { return $this->belongsTo(Category::class); }
    public function brand() { return $this->belongsTo(Brand::class); }
    public function tags() { return $this->belongsToMany(Tag::class, 'product_tags')->withTimestamps(); }
    public function variations() { return $this->hasMany(ProductVariation::class); }
}
