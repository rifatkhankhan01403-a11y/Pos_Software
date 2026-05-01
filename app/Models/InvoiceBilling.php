<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\TracksUserShop;
class InvoiceBilling extends Model
{

use TracksUserShop;
protected $table = 'invoice_billings'; // IMPORTANT

    protected $fillable = [
        'customer_id',
        'customer_name',
        'customer_mobile',
        'items',
        'subtotal',
        'discount',
        'vat',
        'total',
        'paid',
        'due',
        'invoice_date',
        'due_date',
        'profit',
        'source',
        'note',
        'courier',
           'user_id',
        'shop_id'
    ];

    protected $casts = [
        'items' => 'array',
    ];
}
