<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\TracksUserShop;
class Product extends Model
{
 use TracksUserShop;
  protected $fillable = [
    'user_id',
    'category_id',
    'subcategory_id',
    'name',
   // 'unit',
    'quantity',
    'buy_price',
    'sell_price',
    'note',
    'img_url',
     'user_id',
        'shop_id'
];
public function category()
{
    return $this->belongsTo(Category::class);
}
}
