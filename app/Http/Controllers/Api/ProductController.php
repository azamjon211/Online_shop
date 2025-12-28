<?php
// app/Http/Controllers/Api/ProductController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    // GET /api/products
    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand', 'images'])
            ->active();

        // Filter by category
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by brand
        if ($request->has('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }

        // Filter by price range
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        // Filter by features
        if ($request->has('is_featured') && $request->is_featured) {
            $query->featured();
        }
        if ($request->has('is_new') && $request->is_new) {
            $query->new();
        }

        // In stock only
        if ($request->has('in_stock') && $request->in_stock) {
            $query->inStock();
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        $allowedSorts = ['price', 'created_at', 'views', 'rating', 'sales_count', 'name'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $perPage = $request->get('per_page', 20);
        $products = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    // GET /api/products/featured
    public function featured()
    {
        $products = Product::active()
            ->featured()
            ->with(['category', 'brand', 'images'])
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    // GET /api/products/new-arrivals
    public function newArrivals()
    {
        $products = Product::active()
            ->new()
            ->with(['category', 'brand', 'images'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    // GET /api/products/{slug}
    public function show($slug)
    {
        $product = Product::where('slug', $slug)
            ->with(['category', 'brand', 'images', 'variants', 'reviews' => function($query) {
                $query->approved()->latest()->limit(5);
            }])
            ->firstOrFail();

        // Increment views
        $product->increment('views');

        return response()->json([
            'success' => true,
            'data' => $product
        ]);
    }

    // POST /api/products
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:products,slug',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'description' => 'nullable|string',
            'specifications' => 'nullable|json',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'sku' => 'required|string|unique:products,sku',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'is_new' => 'boolean',
            'weight' => 'nullable|numeric',
            'warranty' => 'nullable|string',
            'order' => 'integer',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        DB::beginTransaction();
        try {
            // Slug yaratish
            if (empty($validated['slug'])) {
                $validated['slug'] = Str::slug($validated['name']);
            }

            // Specifications JSON parse
            if (isset($validated['specifications'])) {
                $validated['specifications'] = json_decode($validated['specifications'], true);
            }

            // Chegirma % hisoblash
            if ($validated['sale_price']) {
                $validated['discount_percent'] = (($validated['price'] - $validated['sale_price']) / $validated['price']) * 100;
            }

            $product = Product::create($validated);

            // Rasmlarni saqlash
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    $path = $image->store('products', 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image' => $path,
                        'is_primary' => $index === 0, // birinchi rasm asosiy
                        'order' => $index
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Mahsulot muvaffaqiyatli yaratildi',
                'data' => $product->load(['category', 'brand', 'images'])
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Xatolik yuz berdi: ' . $e->getMessage()
            ], 500);
        }
    }

    // PUT/PATCH /api/products/{id}
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'slug' => 'nullable|string|unique:products,slug,' . $id,
            'category_id' => 'sometimes|required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'description' => 'nullable|string',
            'specifications' => 'nullable|json',
            'price' => 'sometimes|required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock' => 'sometimes|required|integer|min:0',
            'sku' => 'sometimes|required|string|unique:products,sku,' . $id,
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'is_new' => 'boolean',
            'weight' => 'nullable|numeric',
            'warranty' => 'nullable|string',
            'order' => 'integer'
        ]);

        if (isset($validated['name']) && empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        if (isset($validated['specifications'])) {
            $validated['specifications'] = json_decode($validated['specifications'], true);
        }

        // Chegirma % qayta hisoblash
        if (isset($validated['price']) || isset($validated['sale_price'])) {
            $price = $validated['price'] ?? $product->price;
            $salePrice = $validated['sale_price'] ?? $product->sale_price;

            if ($salePrice) {
                $validated['discount_percent'] = (($price - $salePrice) / $price) * 100;
            }
        }

        $product->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Mahsulot muvaffaqiyatli yangilandi',
            'data' => $product->load(['category', 'brand', 'images'])
        ]);
    }

    // DELETE /api/products/{id}
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        DB::beginTransaction();
        try {
            // Rasmlarni o'chirish
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image->image);
            }

            $product->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Mahsulot muvaffaqiyatli o\'chirildi'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Xatolik yuz berdi: ' . $e->getMessage()
            ], 500);
        }
    }

    // POST /api/products/{id}/images
    public function uploadImages(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $uploadedImages = [];

        foreach ($request->file('images') as $image) {
            $path = $image->store('products', 'public');
            $productImage = ProductImage::create([
                'product_id' => $product->id,
                'image' => $path,
                'order' => $product->images()->count()
            ]);
            $uploadedImages[] = $productImage;
        }

        return response()->json([
            'success' => true,
            'message' => 'Rasmlar muvaffaqiyatli yuklandi',
            'data' => $uploadedImages
        ]);
    }

    // DELETE /api/products/images/{imageId}
    public function deleteImage($imageId)
    {
        $image = ProductImage::findOrFail($imageId);

        Storage::disk('public')->delete($image->image);
        $image->delete();

        return response()->json([
            'success' => true,
            'message' => 'Rasm muvaffaqiyatli o\'chirildi'
        ]);
    }
}
