<?php
// app/Http/Controllers/Frontend/HomeController.php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::with('children')
            ->whereNull('parent_id')
            ->active()
            ->orderBy('order')
            ->get();

        $featuredProducts = Product::active()
            ->featured()
            ->with(['images', 'brand', 'category'])
            ->limit(10)
            ->get();

        $newProducts = Product::active()
            ->new()
            ->with(['images', 'brand', 'category'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $brands = Brand::active()
            ->orderBy('order')
            ->get();

        return view('frontend.home', compact(
            'categories',
            'featuredProducts',
            'newProducts',
            'brands'
        ));
    }
}
