{{-- resources/views/frontend/products/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Mahsulotlar')

@section('content')
    <div class="container mx-auto px-4 py-8">
        {{-- Breadcrumb --}}
        <nav class="mb-6 text-sm">
            <ol class="flex items-center gap-2 text-gray-500">
                <li><a href="{{ route('home') }}" class="hover:text-blue-600">Bosh sahifa</a></li>
                <li><i class="fas fa-chevron-right text-xs"></i></li>
                <li class="text-gray-900">Mahsulotlar</li>
            </ol>
        </nav>

        <div class="grid grid-cols-4 gap-6">
            {{-- Filters Sidebar --}}
            <aside class="col-span-1">
                <div class="bg-white rounded-lg shadow p-6 sticky top-24">
                    <h3 class="font-bold text-lg mb-4">Filtrlar</h3>

                    <form action="{{ route('products.index') }}" method="GET">
                        {{-- Categories --}}
                        <div class="mb-6">
                            <h4 class="font-semibold mb-3">Kategoriyalar</h4>
                            <div class="space-y-2 max-h-64 overflow-y-auto">
                                @foreach($categories as $category)
                                    <label class="flex items-center cursor-pointer hover:text-blue-600">
                                        <input type="radio"
                                               name="category_id"
                                               value="{{ $category->id }}"
                                               {{ request('category_id') == $category->id ? 'checked' : '' }}
                                               onchange="this.form.submit()"
                                               class="mr-2">
                                        <span class="text-sm">{{ $category->name }}</span>
                                    </label>

                                    @if($category->children->count() > 0)
                                        <div class="ml-4 mt-1 space-y-1">
                                            @foreach($category->children as $child)
                                                <label class="flex items-center cursor-pointer hover:text-blue-600">
                                                    <input type="radio"
                                                           name="category_id"
                                                           value="{{ $child->id }}"
                                                           {{ request('category_id') == $child->id ? 'checked' : '' }}
                                                           onchange="this.form.submit()"
                                                           class="mr-2">
                                                    <span class="text-sm">{{ $child->name }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        {{-- Brands --}}
                        <div class="mb-6 border-t pt-6">
                            <h4 class="font-semibold mb-3">Brandlar</h4>
                            <div class="space-y-2 max-h-64 overflow-y-auto">
                                @foreach($brands as $brand)
                                    <label class="flex items-center cursor-pointer hover:text-blue-600">
                                        <input type="checkbox"
                                               name="brand_id[]"
                                               value="{{ $brand->id }}"
                                               {{ in_array($brand->id, request('brand_id', [])) ? 'checked' : '' }}
                                               onchange="this.form.submit()"
                                               class="mr-2">
                                        <span class="text-sm">{{ $brand->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Price Range --}}
                        <div class="mb-6 border-t pt-6">
                            <h4 class="font-semibold mb-3">Narx oralig'i</h4>
                            <div class="flex gap-2 mb-2">
                                <input type="number"
                                       name="min_price"
                                       value="{{ request('min_price') }}"
                                       placeholder="Dan"
                                       class="w-full border rounded px-3 py-2 text-sm">
                                <input type="number"
                                       name="max_price"
                                       value="{{ request('max_price') }}"
                                       placeholder="Gacha"
                                       class="w-full border rounded px-3 py-2 text-sm">
                            </div>
                            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded text-sm hover:bg-blue-700">
                                Qo'llash
                            </button>
                        </div>

                        {{-- Additional Filters --}}
                        <div class="border-t pt-6">
                            <h4 class="font-semibold mb-3">Qo'shimcha</h4>
                            <div class="space-y-2">
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox"
                                           name="in_stock"
                                           value="1"
                                           {{ request('in_stock') ? 'checked' : '' }}
                                           onchange="this.form.submit()"
                                           class="mr-2">
                                    <span class="text-sm">Faqat omborda bor</span>
                                </label>

                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox"
                                           name="is_featured"
                                           value="1"
                                           {{ request('is_featured') ? 'checked' : '' }}
                                           onchange="this.form.submit()"
                                           class="mr-2">
                                    <span class="text-sm">Aksiya</span>
                                </label>

                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox"
                                           name="is_new"
                                           value="1"
                                           {{ request('is_new') ? 'checked' : '' }}
                                           onchange="this.form.submit()"
                                           class="mr-2">
                                    <span class="text-sm">Yangi mahsulotlar</span>
                                </label>
                            </div>
                        </div>

                        {{-- Clear Filters --}}
                        @if(request()->hasAny(['category_id', 'brand_id', 'min_price', 'max_price', 'in_stock', 'is_featured', 'is_new']))
                            <div class="mt-4">
                                <a href="{{ route('products.index') }}"
                                   class="block text-center text-red-600 hover:text-red-800 text-sm">
                                    <i class="fas fa-times mr-1"></i> Filtrlarni tozalash
                                </a>
                            </div>
                        @endif
                    </form>
                </div>
            </aside>

            {{-- Products Grid --}}
            <main class="col-span-3">
                {{-- Header --}}
                <div class="bg-white rounded-lg shadow p-4 mb-6 flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold">Mahsulotlar</h1>
                        <p class="text-gray-500 text-sm">{{ $products->total() }} ta mahsulot topildi</p>
                    </div>

                    {{-- Sort --}}
                    <form action="{{ route('products.index') }}" method="GET" class="flex gap-2">
                        @foreach(request()->except(['sort_by', 'sort_order']) as $key => $value)
                            @if(is_array($value))
                                @foreach($value as $item)
                                    <input type="hidden" name="{{ $key }}[]" value="{{ $item }}">
                                @endforeach
                            @else
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endif
                        @endforeach

                        <select name="sort_by" onchange="this.form.submit()"
                                class="border rounded px-4 py-2 text-sm">
                            <option value="">Saralash</option>
                            <option value="price" {{ request('sort_by') == 'price' ? 'selected' : '' }}>Narx bo'yicha</option>
                            <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Yangiligi bo'yicha</option>
                            <option value="rating" {{ request('sort_by') == 'rating' ? 'selected' : '' }}>Reytingi bo'yicha</option>
                            <option value="sales_count" {{ request('sort_by') == 'sales_count' ? 'selected' : '' }}>Mashhurlik bo'yicha</option>
                        </select>

                        <select name="sort_order" onchange="this.form.submit()"
                                class="border rounded px-4 py-2 text-sm">
                            <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Arzondan qimmmatga</option>
                            <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Qimmatdan arzonga</option>
                        </select>
                    </form>
                </div>

                {{-- Products --}}
                @if($products->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($products as $product)
                            <x-product-card :product="$product" />
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-8">
                        {{ $products->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="bg-white rounded-lg shadow p-12 text-center">
                        <i class="fas fa-search text-gray-300 text-6xl mb-4"></i>
                        <h3 class="text-xl font-semibold mb-2">Mahsulotlar topilmadi</h3>
                        <p class="text-gray-500 mb-4">Qidiruv mezonlaringizni o'zgartiring yoki filtrlarni tozalang</p>
                        <a href="{{ route('products.index') }}"
                           class="inline-block bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                            Barcha mahsulotlar
                        </a>
                    </div>
                @endif
            </main>
        </div>
    </div>
@endsection
