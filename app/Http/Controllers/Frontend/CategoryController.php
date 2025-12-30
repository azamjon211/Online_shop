<?php
// app/Http/Controllers/Frontend/CategoryController.php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories
     */
    public function index()
    {
        $categories = Category::with('children')
            ->whereNull('parent_id')
            ->where('is_active', 1)
            ->withCount('products')
            ->orderBy('order')
            ->get();

        return view('frontend.categories.index', compact('categories'));
    }

    /**
     * Display the specified category with its products
     */
    public function show($slug)
    {
        $category = Category::where('slug', $slug)
            ->where('is_active', 1)
            ->with(['children', 'parent'])
            ->withCount('products')
            ->firstOrFail();

        // Get products with sorting
        $sort = request('sort', 'newest');

        $productsQuery = $category->products()
            ->where('is_active', 1)
            ->with(['images', 'brand', 'category']);

        // Apply sorting
        switch ($sort) {
            case 'price_asc':
                $productsQuery->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $productsQuery->orderBy('price', 'desc');
                break;
            case 'popular':
                $productsQuery->orderBy('sales_count', 'desc');
                break;
            case 'rating':
                $productsQuery->orderBy('rating', 'desc');
                break;
            case 'newest':
            default:
                $productsQuery->orderBy('created_at', 'desc');
                break;
        }

        $products = $productsQuery->paginate(20);

        return view('frontend.categories.show', compact('category', 'products'));
    }
}
