<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SMSApiSetting extends Model
{
    protected $guarded = [];
    protected $hidden = ['api_key', 'api_secret'];
    protected $casts = ['enabled' => 'boolean'];
}
