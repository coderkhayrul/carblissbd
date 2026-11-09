<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SteadfastSetting extends Model
{
    protected $guarded = [];
    protected $hidden = ['api_key', 'secret_key', 'webhook_token'];
    protected $casts = ['enabled' => 'boolean', 'api_key' => 'encrypted', 'secret_key' => 'encrypted', 'webhook_token' => 'encrypted', 'status_mapping' => 'array', 'auth_blocked' => 'boolean', 'api_paused_until' => 'datetime'];

    public static function configuration(): self
    {
        return static::firstOrCreate(['id' => 1], ['webhook_token' => Str::random(64)]);
    }
}
