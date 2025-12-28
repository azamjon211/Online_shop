<?php
// app/Http/Controllers/Frontend/ProductController.php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand', 'images'])->active();

        // Category filter
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Brand filter
        if ($request->has('brand_id')) {
            $brandIds = is_array($request->brand_id) ? $request->brand_id : [$request->brand_id];
            $query->whereIn('brand_id', $brandIds);
        }

        // Price range
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

        // Features
        if ($request->has('in_stock')) {
            $query->inStock();
        }
        if ($request->has('is_featured')) {
            $query->featured();
        }
        if ($request->has('is_new')) {
            $query->new();
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        $allowedSorts = ['price', 'created_at', 'rating', 'sales_count', 'name'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $products = $query->paginate(24);

        $categories = Category::with('children')
            ->whereNull('parent_id')
            ->active()
            ->get();

        $brands = Brand::active()->get();

        return view('frontend.products.index', compact('products', 'categories', 'brands'));
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)
            ->active()
            ->with(['category', 'brand', 'images', 'variants', 'reviews' => function($query) {
                $query->approved()->latest();
            }])
            ->firstOrFail();

        // Increment views
        $product->increment('views');

        // Similar products
        $similarProducts = Product::active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with(['images', 'brand'])
            ->limit(5)
            ->get();

        return view('frontend.products.show', compact('product', 'similarProducts'));
    }
}
