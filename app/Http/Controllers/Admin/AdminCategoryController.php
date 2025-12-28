<?php
// app/Http/Controllers/Admin/AdminCategoryController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminCategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with(['parent', 'children'])
            ->withCount('products')
            ->orderBy('order')
            ->paginate(20);

        return view('admin.categories', compact('categories'));
    }

    public function create()
    {
        $categories = Category::whereNull('parent_id')
            ->orderBy('name')
            ->get();

        return view('admin.categories-create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:categories,slug',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'is_active' => 'boolean',
            'order' => 'integer'
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('categories', 'public');
        }

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        Category::create($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategoriya yaratildi');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);

        if (request()->expectsJson()) {
            return response()->json($category);
        }

        $categories = Category::whereNull('parent_id')
            ->where('id', '!=', $id)
            ->orderBy('name')
            ->get();

        return view('admin.categories-edit', compact('category', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:categories,slug,' . $id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'is_active' => 'boolean',
            'order' => 'integer'
        ]);

        // Prevent self-parenting
        if (isset($validated['parent_id']) && $validated['parent_id'] == $id) {
            return redirect()->back()
                ->withErrors(['parent_id' => 'Kategoriya o\'z-o\'ziga parent bo\'la olmaydi']);
        }

        if ($request->hasFile('image')) {
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $validated['image'] = $request->file('image')->store('categories', 'public');
        }

        if (isset($validated['name']) && empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $category->update($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategoriya yangilandi');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategoriya o\'chirildi');
    }
}
