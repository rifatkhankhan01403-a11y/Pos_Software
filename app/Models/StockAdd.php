<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\TracksUserShop;
class StockAdd extends Model
{
    use HasFactory;
 use TracksUserShop;
    protected $table = 'stock_adds';

    protected $fillable = [
        'invoice_no',
        'supplier_id',
        'supplier_name',
        'supplier_phone',
        'supplier_address',
        'purchase_date',
        'items',
        'due_plan',
        'total_qty',
        'total_cost',
        'paid_amount',
        'due_amount',
        'note',
        'source',
          'user_id',
        'shop_id'
    ];

    protected $casts = [
        'items' => 'array',
        'due_plan' => 'array',
    ];
}
