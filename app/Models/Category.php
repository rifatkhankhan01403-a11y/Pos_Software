<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\TracksUserShop;
class Category extends Model
{

 use TracksUserShop;
protected $fillable = ['name', 'user_id', 'user_id',
        'shop_id'];

    public function subCategories()
    {
        return $this->hasMany(SubCategory::class, 'category_id');
    }
}
