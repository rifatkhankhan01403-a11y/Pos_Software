<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['name','email','mobile','user_id'];


    public function invoiceBillings()
{
    return $this->hasMany(InvoiceBilling::class, 'customer_id');
}
}
