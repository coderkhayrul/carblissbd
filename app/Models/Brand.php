<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $fillable = [
        'name', 'slug', 'title', 'logo', 'banner_image', 'serial',
        'status', 'is_highlight', 'is_top_ten', 'meta_title', 'meta_description', 'meta_tags',
    ];

    protected $casts = [
        'status' => 'boolean', 'is_highlight' => 'boolean', 'is_top_ten' => 'boolean', 'serial' => 'integer',
    ];
}
