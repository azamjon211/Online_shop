<?php
// app/Models/Product.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'category_id', 'brand_id', 'description',
        'specifications', 'price', 'sale_price', 'discount_percent',
        'stock', 'sku', 'is_active', 'is_featured', 'is_new',
        'views', 'rating', 'reviews_count', 'sales_count',
        'weight', 'warranty', 'order'
    ];

    protected $casts = [
        'specifications' => 'array',
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'discount_percent' => 'decimal:2',
        'rating' => 'decimal:2',
        'weight' => 'decimal:2',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'is_new' => 'boolean',
    ];

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('order');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', 1);
    }

    public function scopeNew($query)
    {
        return $query->where('is_new', 1);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    // Accessors
    public function getPrimaryImageAttribute()
    {
        $primary = $this->images()->where('is_primary', 1)->first();

        if ($primary) {
            return asset('storage/' . $primary->image);
        }

        // Agar primary rasm bo'lmasa, birinchi rasmni qaytarish
        $firstImage = $this->images()->first();
        if ($firstImage) {
            return asset('storage/' . $firstImage->image);
        }

        // Agar rasm umuman bo'lmasa, default rasm
        return asset('images/no-image.png');
    }

    public function getFinalPriceAttribute()
    {
        return $this->sale_price ?? $this->price;
    }

    public function getInStockAttribute()
    {
        return $this->stock > 0;
    }

    public function getDiscountPercentAttribute()
    {
        if ($this->sale_price && $this->price > $this->sale_price) {
            return round((($this->price - $this->sale_price) / $this->price) * 100);
        }
        return 0;
    }
}
