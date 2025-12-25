<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // Barcha kategoriyalar
    public function index()
    {
        $categories = Category::with('children')
            ->active()
            ->parents()
            ->orderBy('order')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    // Bitta kategoriya
    public function show($slug)
    {
        $category = Category::where('slug', $slug)
            ->with(['children'])
            ->active()
            ->first();

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Kategoriya topilmadi'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $category
        ]);
    }

    // Kategoriya yaratish (Admin)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:categories,slug',
            'image' => 'nullable|string',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'is_active' => 'boolean',
            'order' => 'integer'
        ]);

        $category = Category::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Kategoriya yaratildi',
            'data' => $category
        ], 201);
    }

    // Kategoriya yangilash (Admin)
    public function update(Request $request, $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Kategoriya topilmadi'
            ], 404);
        }

        $validated = $request->validate([
            'name' => 'string|max:255',
            'slug' => 'string|unique:categories,slug,' . $id,
            'image' => 'nullable|string',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'is_active' => 'boolean',
            'order' => 'integer'
        ]);

        $category->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Kategoriya yangilandi',
            'data' => $category
        ]);
    }

    // Kategoriya o'chirish (Admin)
    public function destroy($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Kategoriya topilmadi'
            ], 404);
        }

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kategoriya o\'chirildi'
        ]);
    }
}
