<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PathaoSetting extends Model
{
    protected $guarded = [];
    protected $hidden = ['client_id', 'client_secret', 'username', 'password', 'access_token', 'refresh_token', 'webhook_secret'];
    protected $casts = [
        'enabled' => 'boolean', 'auth_blocked' => 'boolean', 'client_id' => 'encrypted', 'client_secret' => 'encrypted',
        'username' => 'encrypted', 'password' => 'encrypted', 'access_token' => 'encrypted', 'refresh_token' => 'encrypted',
        'token_expires_at' => 'datetime', 'api_paused_until' => 'datetime', 'stores' => 'array', 'status_mapping' => 'array',
        'webhook_enabled' => 'boolean', 'webhook_secret' => 'encrypted', 'webhook_verified_at' => 'datetime',
    ];
    public static function configuration(): self { return static::firstOrCreate(['id' => 1], ['webhook_secret' => \Illuminate\Support\Str::random(64)]); }
}
