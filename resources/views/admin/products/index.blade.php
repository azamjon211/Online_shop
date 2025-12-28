{{-- resources/views/admin/products/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Mahsulotlar')
@section('page-title', 'Mahsulotlar')

@section('content')
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b flex justify-between items-center">
            <div class="flex gap-4">
                <input type="text" placeholder="Qidirish..." class="border rounded px-4 py-2 w-64">
                <select class="border rounded px-4 py-2">
                    <option value="">Barcha kategoriyalar</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                <select class="border rounded px-4 py-2">
                    <option value="">Barcha brendlar</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                    @endforeach
                </select>
            </div>
            <a href="{{ route('admin.products.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                <i class="fas fa-plus mr-2"></i> Yangi mahsulot
            </a>
        </div>

        <div class="p-6">
            <table class="w-full">
                <thead>
                <tr class="text-left text-gray-500 text-sm border-b">
                    <th class="pb-3">ID</th>
                    <th class="pb-3">Rasm</th>
                    <th class="pb-3">Nom</th>
                    <th class="pb-3">Kategoriya</th>
                    <th class="pb-3">Brend</th>
                    <th class="pb-3">Narx</th>
                    <th class="pb-3">Ombor</th>
                    <th class="pb-3">Holat</th>
                    <th class="pb-3">Amallar</th>
                </tr>
                </thead>
                <tbody>
                @foreach($products as $product)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="py-4">{{ $product->id }}</td>
                        <td class="py-4">
                            @if($product->primary_image)
                                <img src="{{ $product->primary_image }}" alt="{{ $product->name }}" class="w-16 h-16 object-cover rounded">
                            @else
                                <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center">
                                    <i class="fas fa-image text-gray-400"></i>
                                </div>
                            @endif
                        </td>
                        <td class="py-4">
                            <div class="font-medium">{{ $product->name }}</div>
                            <div class="text-xs text-gray-500">SKU: {{ $product->sku }}</div>
                        </td>
                        <td class="py-4">{{ $product->category->name }}</td>
                        <td class="py-4">{{ $product->brand?->name ?? '-' }}</td>
                        <td class="py-4">
                            <div class="font-semibold">{{ number_format($product->final_price) }} so'm</div>
                            @if($product->sale_price)
                                <div class="text-xs text-gray-500 line-through">{{ number_format($product->price) }}</div>
                            @endif
                        </td>
                        <td class="py-4">
                        <span class="px-2 py-1 text-xs rounded {{ $product->stock > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $product->stock }}
                        </span>
                        </td>
                        <td class="py-4">
                        <span class="px-2 py-1 text-xs rounded-full {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $product->is_active ? 'Faol' : 'Nofaol' }}
                        </span>
                        </td>
                        <td class="py-4">
                            <a href="{{ route('admin.products.edit', $product->id) }}" class="text-blue-600 hover:text-blue-800 mr-3">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="inline" onsubmit="return confirm('Ishonchingiz komilmi?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <div class="mt-4">
                {{ $products->links() }}
            </div>
        </div>
    </div>
@endsection
