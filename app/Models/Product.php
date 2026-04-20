<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

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
    'img_url'
];
public function category()
{
    return $this->belongsTo(Category::class);
}
}
