<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'parent_id', 'name', 'slug', 'image', 'icon', 'banner_image', 'description',
        'is_highlight', 'show_on_menu', 'show_on_side_menu', 'is_tab', 'menu_serial',
        'tab_serial', 'status', 'is_top_ten', 'meta_title', 'meta_description', 'meta_tags',
    ];

    protected $casts = [
        'status' => 'boolean', 'is_highlight' => 'boolean', 'show_on_menu' => 'boolean',
        'show_on_side_menu' => 'boolean', 'is_tab' => 'boolean', 'is_top_ten' => 'boolean',
        'parent_id' => 'integer', 'menu_serial' => 'integer', 'tab_serial' => 'integer',
    ];

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }
}
