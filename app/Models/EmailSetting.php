<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailSetting extends Model
{
    protected $guarded = [];
    protected $hidden = ['mail_password'];
    protected $casts = ['enabled' => 'boolean'];
}
