<?php
// app/Http/Controllers/Frontend/BrandController.php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Brand;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::where('is_active', 1)
            ->withCount('products')
            ->orderBy('order')
            ->paginate(24);

        return view('frontend.brands.index', compact('brands'));
    }

    public function show($slug)
    {
        $brand = Brand::where('slug', $slug)
            ->where('is_active', 1)
            ->withCount('products')
            ->firstOrFail();

        $products = $brand->products()
            ->where('is_active', 1)
            ->with(['images', 'category'])
            ->paginate(20);

        return view('frontend.brands.show', compact('brand', 'products'));
    }
}
