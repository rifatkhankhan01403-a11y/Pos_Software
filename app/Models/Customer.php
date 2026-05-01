<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

use App\Traits\TracksUserShop;

class Customer extends Model
{
    use TracksUserShop;

    protected $fillable = [
        'name',
        'email',
        'mobile',
        'user_id',
        'shop_id'
    ];
}
