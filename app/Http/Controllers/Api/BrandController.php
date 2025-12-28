<?php
// app/Http/Controllers/Api/BrandController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    // GET /api/brands
    public function index()
    {
        $brands = Brand::active()
            ->orderBy('order')
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $brands
        ]);
    }

    // GET /api/brands/{slug}
    public function show($slug)
    {
        $brand = Brand::where('slug', $slug)
            ->with(['products' => function($query) {
                $query->active()->with('images')->limit(10);
            }])
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => $brand
        ]);
    }

    // POST /api/brands
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:brands,slug',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'order' => 'integer'
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('brands', 'public');
        }

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $brand = Brand::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Brand muvaffaqiyatli yaratildi',
            'data' => $brand
        ], 201);
    }

    // PUT/PATCH /api/brands/{id}
    public function update(Request $request, $id)
    {
        $brand = Brand::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'slug' => 'nullable|string|unique:brands,slug,' . $id,
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'order' => 'integer'
        ]);

        if ($request->hasFile('logo')) {
            // Eski rasmni o'chirish
            if ($brand->logo) {
                Storage::disk('public')->delete($brand->logo);
            }
            $validated['logo'] = $request->file('logo')->store('brands', 'public');
        }

        if (isset($validated['name']) && empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $brand->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Brand muvaffaqiyatli yangilandi',
            'data' => $brand
        ]);
    }

    // DELETE /api/brands/{id}
    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);

        // Logo rasmni o'chirish
        if ($brand->logo) {
            Storage::disk('public')->delete($brand->logo);
        }

        $brand->delete();

        return response()->json([
            'success' => true,
            'message' => 'Brand muvaffaqiyatli o\'chirildi'
        ]);
    }
}
