<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $fillable = [
        'name',
        'tagline',
        'slug',
        'image',
        'short_description',
        'button_title',
        'button_icon',
        'button_url',
        'orderBy',
        'button_status',
        'status',
    ];
}
