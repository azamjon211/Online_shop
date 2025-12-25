<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'image',
        'description',
        'parent_id',
        'is_active',
        'order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Slug avtomatik yaratish
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    // Ota kategoriya
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // Bolalar kategoriyalar
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    // Mahsulotlar (keyinroq qo'shamiz)
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // Faqat faol kategoriyalar
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Faqat asosiy kategoriyalar
    public function scopeParents($query)
    {
        return $query->whereNull('parent_id');
    }
}
