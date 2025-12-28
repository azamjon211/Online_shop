{{-- resources/views/admin/products/edit.blade.php --}}
@extends('layouts.admin')

@section('title', 'Mahsulotni tahrirlash')
@section('page-title', 'Mahsulotni tahrirlash')

@section('content')
    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-3 gap-6">
            {{-- Main Info --}}
            <div class="col-span-2 space-y-6">
                {{-- Basic Information --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold mb-4">Asosiy ma'lumotlar</h3>

                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-2">Mahsulot nomi *</label>
                        <input type="text" name="name" value="{{ old('name', $product->name) }}" required
                               class="w-full border rounded px-3 py-2">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-2">Slug</label>
                        <input type="text" name="slug" value="{{ old('slug', $product->slug) }}"
                               class="w-full border rounded px-3 py-2">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-2">Tavsif</label>
                        <textarea name="description" rows="5"
                                  class="w-full border rounded px-3 py-2">{{ old('description', $product->description) }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-2">SKU *</label>
                        <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" required
                               class="w-full border rounded px-3 py-2">
                    </div>
                </div>

                {{-- Specifications --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold mb-4">Texnik xususiyatlar</h3>

                    <div id="specifications">
                        @if($product->specifications)
                            @foreach($product->specifications as $key => $value)
                                <div class="flex gap-4 mb-3">
                                    <input type="text" value="{{ $key }}" placeholder="Kalit"
                                           class="flex-1 border rounded px-3 py-2 spec-key">
                                    <input type="text" value="{{ $value }}" placeholder="Qiymat"
                                           class="flex-1 border rounded px-3 py-2 spec-value">
                                    <button type="button" onclick="removeSpec(this)"
                                            class="px-3 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <button type="button" onclick="addSpec()"
                            class="mt-2 text-blue-600 hover:text-blue-800">
                        <i class="fas fa-plus mr-2"></i> Xususiyat qo'shish
                    </button>

                    <input type="hidden" name="specifications" id="specificationsInput">
                </div>

                {{-- Current Images --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold mb-4">Mavjud rasmlar</h3>

                    <div class="grid grid-cols-4 gap-4">
                        @foreach($product->images as $image)
                            <div class="relative group">
                                <img src="{{ asset('storage/' . $image->image) }}"
                                     class="w-full h-32 object-cover rounded">
                                @if($image->is_primary)
                                    <span class="absolute top-1 left-1 bg-blue-500 text-white text-xs px-2 py-1 rounded">
                                Asosiy
                            </span>
                                @endif
                                <button type="button" onclick="deleteImage({{ $image->id }})"
                                        class="absolute top-1 right-1 bg-red-500 text-white p-2 rounded opacity-0 group-hover:opacity-100 transition">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- New Images --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold mb-4">Yangi rasmlar qo'shish</h3>

                    <div class="mb-4">
                        <input type="file" name="images[]" multiple accept="image/*"
                               class="w-full border rounded px-3 py-2"
                               onchange="previewImages(event)">
                    </div>

                    <div id="imagePreview" class="grid grid-cols-4 gap-4"></div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Pricing --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold mb-4">Narx</h3>

                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-2">Asosiy narx *</label>
                        <input type="number" name="price" value="{{ old('price', $product->price) }}" required
                               step="0.01" class="w-full border rounded px-3 py-2">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-2">Chegirmali narx</label>
                        <input type="number" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}"
                               step="0.01" class="w-full border rounded px-3 py-2">
                    </div>
                </div>

                {{-- Organization --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold mb-4">Guruhlar</h3>

                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-2">Kategoriya *</label>
                        <select name="category_id" required class="w-full border rounded px-3 py-2">
                            <option value="">Tanlang</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                                @foreach($category->children as $child)
                                    <option value="{{ $child->id }}"
                                        {{ old('category_id', $product->category_id) == $child->id ? 'selected' : '' }}>
                                        &nbsp;&nbsp;— {{ $child->name }}
                                    </option>
                                @endforeach
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-2">Brend</label>
                        <select name="brand_id" class="w-full border rounded px-3 py-2">
                            <option value="">Tanlang</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}"
                                    {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Inventory --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold mb-4">Ombor</h3>

                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-2">Miqdor *</label>
                        <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" required
                               class="w-full border rounded px-3 py-2">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-2">Og'irligi (kg)</label>
                        <input type="number" name="weight" value="{{ old('weight', $product->weight) }}"
                               step="0.01" class="w-full border rounded px-3 py-2">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-2">Kafolat</label>
                        <input type="text" name="warranty" value="{{ old('warranty', $product->warranty) }}"
                               class="w-full border rounded px-3 py-2">
                    </div>
                </div>

                {{-- Status --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold mb-4">Holat</h3>

                    <div class="space-y-3">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_active" value="1"
                                   {{ old('is_active', $product->is_active) ? 'checked' : '' }} class="mr-2">
                            <span class="text-sm">Faol</span>
                        </label>

                        <label class="flex items-center">
                            <input type="checkbox" name="is_featured" value="1"
                                   {{ old('is_featured', $product->is_featured) ? 'checked' : '' }} class="mr-2">
                            <span class="text-sm">Mashhur mahsulot</span>
                        </label>

                        <label class="flex items-center">
                            <input type="checkbox" name="is_new" value="1"
                                   {{ old('is_new', $product->is_new) ? 'checked' : '' }} class="mr-2">
                            <span class="text-sm">Yangi mahsulot</span>
                        </label>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        <i class="fas fa-save mr-2"></i> Yangilash
                    </button>
                    <a href="{{ route('admin.products.index') }}"
                       class="block w-full text-center mt-3 border px-4 py-2 rounded hover:bg-gray-50">
                        Bekor qilish
                    </a>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        // Specifications (xuddi create sahifadagidek)
        function addSpec() {
            const container = document.getElementById('specifications');
            const div = document.createElement('div');
            div.className = 'flex gap-4 mb-3';
            div.innerHTML = `
        <input type="text" placeholder="Kalit" class="flex-1 border rounded px-3 py-2 spec-key">
        <input type="text" placeholder="Qiymat" class="flex-1 border rounded px-3 py-2 spec-value">
        <button type="button" onclick="removeSpec(this)" class="px-3 py-2 bg-red-500 text-white rounded hover:bg-red-600">
            <i class="fas fa-trash"></i>
        </button>
    `;
            container.appendChild(div);
        }

        function removeSpec(button) {
            button.parentElement.remove();
        }

        document.querySelector('form').addEventListener('submit', function(e) {
            const specs = {};
            document.querySelectorAll('#specifications > div').forEach(div => {
                const key = div.querySelector('.spec-key').value;
                const value = div.querySelector('.spec-value').value;
                if (key && value) {
                    specs[key] = value;
                }
            });
            document.getElementById('specificationsInput').value = JSON.stringify(specs);
        });

        function previewImages(event) {
            const preview = document.getElementById('imagePreview');
            preview.innerHTML = '';

            const files = event.target.files;
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const reader = new FileReader();

                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'relative';
                    div.innerHTML = `<img src="${e.target.result}" class="w-full h-32 object-cover rounded">`;
                    preview.appendChild(div);
                };

                reader.readAsDataURL(file);
            }
        }

        function deleteImage(imageId) {
            if (!confirm('Rasmni o\'chirmoqchimisiz?')) return;

            fetch(`/admin/products/images/${imageId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                });
        }
    </script>
@endpush
