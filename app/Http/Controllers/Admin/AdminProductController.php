<?php
// app/Http/Controllers/Admin/AdminProductController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'brand', 'images'])
            ->latest()
            ->paginate(20);

        $categories = Category::all();
        $brands = Brand::all();

        return view('admin.products.index', compact('products', 'categories', 'brands'));
    }

    public function create()
    {
        $categories = Category::with('children')
            ->whereNull('parent_id')
            ->orderBy('name')
            ->get();

        $brands = Brand::orderBy('name')->get();

        return view('admin.products.create', compact('categories', 'brands'));
    }

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
            if (empty($validated['slug'])) {
                $validated['slug'] = Str::slug($validated['name']);
            }

            if (isset($validated['specifications'])) {
                $validated['specifications'] = json_decode($validated['specifications'], true);
            }

            // Calculate discount percentage
            if (isset($validated['sale_price']) && $validated['sale_price']) {
                $validated['discount_percent'] = (($validated['price'] - $validated['sale_price']) / $validated['price']) * 100;
            }

            $product = Product::create($validated);

            // Upload images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    $path = $image->store('products', 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image' => $path,
                        'is_primary' => $index === 0,
                        'order' => $index
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('admin.products.index')
                ->with('success', 'Mahsulot yaratildi');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Xatolik: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $product = Product::with('images')->findOrFail($id);

        $categories = Category::with('children')
            ->whereNull('parent_id')
            ->orderBy('name')
            ->get();

        $brands = Brand::orderBy('name')->get();

        return view('admin.products.edit', compact('product', 'categories', 'brands'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:products,slug,' . $id,
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'description' => 'nullable|string',
            'specifications' => 'nullable|json',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'sku' => 'required|string|unique:products,sku,' . $id,
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'is_new' => 'boolean',
            'weight' => 'nullable|numeric',
            'warranty' => 'nullable|string',
            'order' => 'integer',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if (isset($validated['name']) && empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        if (isset($validated['specifications'])) {
            $validated['specifications'] = json_decode($validated['specifications'], true);
        }

        // Recalculate discount
        if (isset($validated['price']) && isset($validated['sale_price']) && $validated['sale_price']) {
            $validated['discount_percent'] = (($validated['price'] - $validated['sale_price']) / $validated['price']) * 100;
        }

        $product->update($validated);

        // Upload new images
        if ($request->hasFile('images')) {
            $currentCount = $product->images()->count();
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $path,
                    'is_primary' => $currentCount === 0 && $index === 0,
                    'order' => $currentCount + $index
                ]);
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Mahsulot yangilandi');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        DB::beginTransaction();
        try {
            // Delete images
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image->image);
            }

            $product->delete();

            DB::commit();

            return redirect()->route('admin.products.index')
                ->with('success', 'Mahsulot o\'chirildi');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Xatolik: ' . $e->getMessage());
        }
    }

    public function uploadImages(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $uploadedImages = [];
        $currentCount = $product->images()->count();

        foreach ($request->file('images') as $index => $image) {
            $path = $image->store('products', 'public');
            $productImage = ProductImage::create([
                'product_id' => $product->id,
                'image' => $path,
                'order' => $currentCount + $index
            ]);
            $uploadedImages[] = $productImage;
        }

        return response()->json([
            'success' => true,
            'message' => 'Rasmlar yuklandi',
            'data' => $uploadedImages
        ]);
    }

    public function deleteImage($imageId)
    {
        $image = ProductImage::findOrFail($imageId);

        Storage::disk('public')->delete($image->image);
        $image->delete();

        return response()->json([
            'success' => true,
            'message' => 'Rasm o\'chirildi'
        ]);
    }
}
