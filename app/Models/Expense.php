<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\TracksUserShop;

class Expense extends Model
{

 use TracksUserShop;
    protected $fillable=[
        'date',
        'category',
        'amount',
        'note',
          'user_id',
        'shop_id'
    ];
}
