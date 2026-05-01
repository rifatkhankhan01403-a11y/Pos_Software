<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Traits\TracksUserShop;

class InvoiceProduct extends Model
{

 use TracksUserShop;
    protected $fillable = ['invoice_id', 'product_id','user_id', 'qty', 'sale_price',  'user_id',
        'shop_id'];

    function product():BelongsTo{
        return $this->belongsTo(Product::class);
    }
}
