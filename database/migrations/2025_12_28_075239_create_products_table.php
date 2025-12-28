<?php
// database/migrations/xxxx_create_products_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();

            // Foreign keys
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->foreignId('brand_id')->nullable()->constrained('brands')->onDelete('set null');

            // Ma'lumotlar
            $table->text('description')->nullable();
            $table->json('specifications')->nullable();

            // Narxlar
            $table->decimal('price', 10, 2);
            $table->decimal('sale_price', 10, 2)->nullable();
            $table->decimal('discount_percent', 5, 2)->default(0);

            // Ombor
            $table->integer('stock')->default(0);
            $table->string('sku')->unique();

            // Holatlar
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_new')->default(false);

            // Statistika
            $table->integer('views')->default(0);
            $table->decimal('rating', 3, 2)->default(0);
            $table->integer('reviews_count')->default(0);
            $table->integer('sales_count')->default(0);

            // Yetkazib berish
            $table->decimal('weight', 8, 2)->nullable();
            $table->string('warranty')->nullable();

            $table->integer('order')->default(0);
            $table->timestamps();

            $table->index('category_id');
            $table->index('brand_id');
            $table->index('is_active');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
