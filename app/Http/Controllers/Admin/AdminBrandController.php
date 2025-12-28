<?php
// app/Http/Controllers/Admin/AdminBrandController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminBrandController extends Controller
{
    public function index()
    {
        $brands = Brand::withCount('products')
            ->orderBy('order')
            ->paginate(20);

        return view('admin.brands', compact('brands'));
    }

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
            'message' => 'Brand yaratildi',
            'data' => $brand
        ], 201);
    }

    public function edit($id)
    {
        $brand = Brand::findOrFail($id);
        return response()->json($brand);
    }

    public function update(Request $request, $id)
    {
        $brand = Brand::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:brands,slug,' . $id,
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'order' => 'integer'
        ]);

        if ($request->hasFile('logo')) {
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
            'message' => 'Brand yangilandi',
            'data' => $brand
        ]);
    }

    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);

        if ($brand->logo) {
            Storage::disk('public')->delete($brand->logo);
        }

        $brand->delete();

        return redirect()->route('admin.brands.index')
            ->with('success', 'Brand o\'chirildi');
    }
}
