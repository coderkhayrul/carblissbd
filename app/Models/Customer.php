<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $guarded = [];

    public function user()
    {
        $this->hasOne(User::class);
    }

    public function orders()
    {
        $this->hasMany(Order::class);
    }
}
