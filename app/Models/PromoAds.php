<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromoAds extends Model
{
    protected $table = 'promo_ads';

    protected $fillable = ['title', 'sub_title', 'image', 'button_text', 'button_url', 'overlay_color', 'orderBy', 'status'];

    protected $casts = ['status' => 'boolean', 'orderBy' => 'integer'];
}
