<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanySetting extends Model
{
    protected $guarded = [];
    protected static function booted(): void
    {
        $forgetLayout = fn () => \Illuminate\Support\Facades\Cache::store(config('cache.frontend_store', 'file'))->forget('frontend.layout.v1');
        static::saved($forgetLayout);
        static::deleted($forgetLayout);
    }
}
