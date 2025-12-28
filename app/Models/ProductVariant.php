<?php
// app/Models/ProductVariant.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'name',
        'sku',
        'price',
        'stock',
        'attributes',
        'image',
        'is_active'
    ];

    protected $casts = [
        'attributes' => 'array',
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getInStockAttribute()
    {
        return $this->stock > 0;
    }

    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }
}
