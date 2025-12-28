{{-- resources/views/frontend/brands/show.blade.php --}}
@extends('layouts.app')

@section('title', $brand->name)

@section('content')
    <div class="container mx-auto px-4 py-8">
        {{-- Breadcrumb --}}
        <nav class="mb-6 text-sm">
            <ol class="flex items-center gap-2 text-gray-500">
                <li><a href="{{ route('home') }}" class="hover:text-blue-600">Bosh sahifa</a></li>
                <li><i class="fas fa-chevron-right text-xs"></i></li>
                <li><a href="{{ route('brands.index') }}" class="hover:text-blue-600">Brendlar</a></li>
                <li><i class="fas fa-chevron-right text-xs"></i></li>
                <li class="text-gray-900">{{ $brand->name }}</li>
            </ol>
        </nav>

        {{-- Brand Header --}}
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <div class="flex flex-col md:flex-row items-center gap-6">
                @if($brand->logo)
                    <div class="flex-shrink-0">
                        <img src="{{ asset('storage/' . $brand->logo) }}"
                             alt="{{ $brand->name }}"
                             class="w-40 h-40 object-contain">
                    </div>
                @endif
                <div class="flex-1 text-center md:text-left">
                    <h1 class="text-4xl font-bold mb-3">{{ $brand->name }}</h1>
                    @if($brand->description)
                        <p class="text-gray-600 mb-4 leading-relaxed">{{ $brand->description }}</p>
                    @endif
                    <div class="flex flex-wrap gap-4 justify-center md:justify-start">
                        <div class="bg-blue-50 px-4 py-2 rounded-lg">
                            <span class="text-2xl font-bold text-blue-600">{{ $brand->products_count }}</span>
                            <span class="text-sm text-gray-600 ml-1">ta mahsulot</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Products --}}
        @if($products->count() > 0)
            <div class="mb-6 flex justify-between items-center">
                <h2 class="text-2xl font-bold">{{ $brand->name }} mahsulotlari</h2>
                <p class="text-gray-500">{{ $products->total() }} ta mahsulot topildi</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                @foreach($products as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-8">
                {{ $products->links() }}
            </div>
        @else
            <div class="bg-white rounded-lg shadow p-12 text-center">
                <i class="fas fa-box-open text-gray-300 text-6xl mb-4"></i>
                <h3 class="text-xl font-semibold mb-2">Mahsulotlar topilmadi</h3>
                <p class="text-gray-500 mb-6">Bu brendda hozircha mahsulotlar mavjud emas</p>
                <a href="{{ route('products.index') }}"
                   class="inline-block bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                    Boshqa mahsulotlar
                </a>
            </div>
        @endif
    </div>
@endsection
