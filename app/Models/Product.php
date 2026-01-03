<?php
// app/Models/Product.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'brand_id',
        'name',
        'slug',
        'description',
        'specifications',
        'price',
        'sale_price',
        'discount_percent',
        'stock',
        'sku',
        'warranty',
        'weight',
        'is_active',
        'is_featured',
        'is_new',
        'rating',
        'reviews_count',
        'sales_count',
        'views',
        'order',
    ];

    protected $casts = [
        'specifications' => 'array',
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'discount_percent' => 'decimal:2',
        'rating' => 'decimal:2',
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

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Accessors

    /**
     * Get primary image path
     * Database: images/products/file.jpg
     * Output: products/file.jpg (for use with storage/)
     */
    public function getPrimaryImageAttribute()
    {
        // Avval is_primary = 1 bo'lgan rasmni qidirish
        $primaryImage = $this->images()->where('is_primary', 1)->first();

        if ($primaryImage) {
            // images/products/file.jpg -> products/file.jpg
            return str_replace('images/', '', $primaryImage->image);
        }

        // Agar primary yo'q bo'lsa, birinchi rasmni olish
        $firstImage = $this->images()->first();

        if ($firstImage) {
            return str_replace('images/', '', $firstImage->image);
        }

        // Agar umuman rasm yo'q bo'lsa, default rasm
        return 'no-image.png';
    }

    /**
     * Get primary image full URL
     */
    public function getPrimaryImageUrlAttribute()
    {
        return asset('storage/' . $this->primary_image);
    }

    /**
     * Get final price (sale price if exists, otherwise regular price)
     */
    public function getFinalPriceAttribute()
    {
        return $this->sale_price ?? $this->price;
    }

    /**
     * Get discount amount
     */
    public function getDiscountAmountAttribute()
    {
        if ($this->sale_price && $this->sale_price < $this->price) {
            return $this->price - $this->sale_price;
        }
        return 0;
    }

    /**
     * Get discount percentage
     */
    public function getDiscountPercentageAttribute()
    {
        if ($this->sale_price && $this->sale_price < $this->price) {
            return round((($this->price - $this->sale_price) / $this->price) * 100);
        }
        return 0;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeNew($query)
    {
        return $query->where('is_new', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    public function scopePopular($query)
    {
        return $query->orderBy('sales_count', 'desc');
    }

    public function scopeTopRated($query)
    {
        return $query->orderBy('rating', 'desc');
    }

    // Methods
    public function incrementViews()
    {
        $this->increment('views');
    }

    public function updateRating()
    {
        $this->rating = $this->reviews()->approved()->avg('rating') ?? 0;
        $this->reviews_count = $this->reviews()->approved()->count();
        $this->save();
    }
}
