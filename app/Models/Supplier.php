<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\TracksUserShop;

class Supplier extends Model
{
    use HasFactory;
 use TracksUserShop;
    protected $fillable = [
        'email',
        'name',
        'mobile',
        'email',
        'address',
        'note',
        'img_url',
          'user_id',
        'shop_id'
    ];


    public function stockAdds()
{
    return $this->hasMany(StockAdd::class, 'supplier_id');
}
}
