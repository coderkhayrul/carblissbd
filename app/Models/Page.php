<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = [
        'name', 'group_name', 'bangla_name', 'slug', 'banner_image', 'image',
        'orderBy', 'content', 'status', 'show_in_quick_links',
        'meta_title', 'meta_description', 'meta_tags',
    ];
}
