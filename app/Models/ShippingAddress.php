<?php
// app/Models/ShippingAddress.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingAddress extends Model
{
    protected $fillable = [
        'order_id',
        'full_name',
        'phone',
        'region',
        'district',
        'address',
        'postal_code'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
