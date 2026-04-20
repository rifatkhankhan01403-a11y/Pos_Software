<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceBilling extends Model
{
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
        'source'
    ];

    protected $casts = [
        'items' => 'array',
    ];
}
