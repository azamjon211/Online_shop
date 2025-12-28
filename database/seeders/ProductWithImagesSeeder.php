<?php
// database/seeders/ProductWithImagesSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Category;
use App\Models\Brand;

class ProductWithImagesSeeder extends Seeder
{
    public function run(): void
    {
        // Categories va Brands
        $smartphones = Category::where('slug', 'smartfonlar')->first();
        $apple = Brand::where('slug', 'apple')->first();
        $samsung = Brand::where('slug', 'samsung')->first();

        if (!$smartphones || !$apple || !$samsung) {
            $this->command->error('❌ Avval CategorySeeder va BrandSeeder ishga tushiring!');
            return;
        }

        // 1. iPhone 17 Pro Max (rasmlar: iphone-17-pro-max.jpg, iphone-17-pro-max-2.jpg, iphone-17-pro-max-3.jpg)
        $this->command->info('📱 iPhone 17 Pro Max yaratilmoqda...');

        $iphone = Product::create([
            'name' => 'Apple iPhone 17 Pro Max 256GB',
            'slug' => 'apple-iphone-17-pro-max-256gb',
            'category_id' => $smartphones->id,
            'brand_id' => $apple->id,
            'description' => 'iPhone 17 Pro Max - titanium korpus, A18 Pro chip, 48MP kamera, USB-C port. Eng kuchli iPhone.',
            'price' => 18000000,
            'stock' => 15,
            'sku' => 'APL-IP17PM-256',
            'is_active' => true,
            'is_featured' => true,
            'is_new' => true,
            'warranty' => '12 oy',
            'specifications' => [
                'Ekran' => '6.7" Super Retina XDR',
                'Protsessor' => 'A18 Pro',
                'RAM' => '8 GB',
                'Xotira' => '256 GB',
                'Kamera' => '48 MP + 12 MP + 12 MP',
                'Old kamera' => '12 MP',
                'Batareya' => '4422 mAh',
                'OS' => 'iOS 18',
                'Og\'irligi' => '221g',
                'Ranglar' => 'Natural Titanium, Blue Titanium, White Titanium, Black Titanium'
            ]
        ]);

        // iPhone rasmlari - RASMLARINGIZNING ANIQ NOMLARI
        ProductImage::create([
            'product_id' => $iphone->id,
            'image' => 'products/iphone-17-pro-max.jpg',  // ← Sizning rasm nomingiz
            'is_primary' => true,
            'order' => 1
        ]);

        ProductImage::create([
            'product_id' => $iphone->id,
            'image' => 'products/iphone-17-pro-max-2.jpg',  // ← Sizning rasm nomingiz
            'is_primary' => false,
            'order' => 2
        ]);

        ProductImage::create([
            'product_id' => $iphone->id,
            'image' => 'products/iphone-17-pro-max-3.jpg',  // ← Sizning rasm nomingiz
            'is_primary' => false,
            'order' => 3
        ]);

        // 2. Samsung S22 Ultra (rasmlar: samsung-s22-ultra.jpg, samsung-s22-ultra-2.jpg, samsung-s22-ultra-3.jpg)
        $this->command->info('📱 Samsung Galaxy S22 Ultra yaratilmoqda...');

        $samsung22 = Product::create([
            'name' => 'Samsung Galaxy S22 Ultra 12/256GB',
            'slug' => 'samsung-galaxy-s22-ultra-12-256gb',
            'category_id' => $smartphones->id,
            'brand_id' => $samsung->id,
            'description' => 'Samsung Galaxy S22 Ultra - 108MP kamera, 5000mAh batareya, Snapdragon 8 Gen 1, S Pen bilan.',
            'price' => 12500000,
            'sale_price' => 11900000,
            'stock' => 25,
            'sku' => 'SAM-S22U-256',
            'is_active' => true,
            'is_featured' => true,
            'is_new' => false,
            'warranty' => '12 oy',
            'specifications' => [
                'Ekran' => '6.8" Dynamic AMOLED 2X',
                'Protsessor' => 'Snapdragon 8 Gen 1',
                'RAM' => '12 GB',
                'Xotira' => '256 GB',
                'Kamera' => '108 MP + 10 MP + 10 MP + 12 MP',
                'Old kamera' => '40 MP',
                'Batareya' => '5000 mAh',
                'OS' => 'Android 14, One UI 6',
                'Og\'irligi' => '228g',
                'Qo\'shimcha' => 'S Pen, Gorilla Glass Victus+'
            ]
        ]);

        // Samsung rasmlari - RASMLARINGIZNING ANIQ NOMLARI
        ProductImage::create([
            'product_id' => $samsung22->id,
            'image' => 'products/samsung-s22-ultra.jpg',  // ← Sizning rasm nomingiz
            'is_primary' => true,
            'order' => 1
        ]);

        ProductImage::create([
            'product_id' => $samsung22->id,
            'image' => 'products/samsung-s22-ultra-2.jpg',  // ← Sizning rasm nomingiz
            'is_primary' => false,
            'order' => 2
        ]);

        ProductImage::create([
            'product_id' => $samsung22->id,
            'image' => 'products/samsung-s22-ultra-3.jpg',  // ← Sizning rasm nomingiz
            'is_primary' => false,
            'order' => 3
        ]);

        $this->command->info('✅ 2 ta mahsulot va 6 ta rasm muvaffaqiyatli yaratildi!');
        $this->command->info('📊 iPhone 17 Pro Max: 3 ta rasm');
        $this->command->info('📊 Samsung S22 Ultra: 3 ta rasm');
    }
}
