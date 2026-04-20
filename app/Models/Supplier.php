<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'name',
        'mobile',
        'email',
        'address',
        'note',
        'img_url'
    ];


    public function stockAdds()
{
    return $this->hasMany(StockAdd::class, 'supplier_id');
}
}
