<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\TracksUserShop;
class SubCategory extends Model
{

 use TracksUserShop;
    protected $fillable = [
        'name',
        'category_id',
          'user_id',
           'user_id',
        'shop_id'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
