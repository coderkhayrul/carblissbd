<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PopupAds extends Model
{
    protected $table = 'popup_ads';

    protected $fillable = ['title', 'url', 'description', 'image', 'is_video', 'status'];

    protected $casts = ['is_video' => 'boolean', 'status' => 'boolean'];
}
