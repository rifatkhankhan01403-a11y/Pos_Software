<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $fillable = [
        'firstName',
        'shop_name',
        'role',
        'email',
        'mobile',
        'password',
        'otp',
        'shop_id',        // ✅ ADD THIS
        'login_token'     // ✅ ADD THIS
    ];

    protected $attributes = [
        'otp' => '0'
    ];
}
