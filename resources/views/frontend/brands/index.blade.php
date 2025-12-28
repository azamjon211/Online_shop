{{-- resources/views/frontend/brands/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Brendlar')

@section('content')
    <div class="container mx-auto px-4 py-8">
        {{-- Breadcrumb --}}
        <nav class="mb-6 text-sm">
            <ol class="flex items-center gap-2 text-gray-500">
                <li><a href="{{ route('home') }}" class="hover:text-blue-600">Bosh sahifa</a></li>
                <li><i class="fas fa-chevron-right text-xs"></i></li>
                <li class="text-gray-900">Brendlar</li>
            </ol>
        </nav>

        <h1 class="text-3xl font-bold mb-8">Barcha brendlar</h1>

        @if($brands->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-6">
                @foreach($brands as $brand)
                    <a href="{{ route('brands.show', $brand->slug) }}"
                       class="bg-white rounded-lg shadow hover:shadow-xl transition-all p-6 text-center group">
                        @if($brand->logo)
                            <img src="{{ asset('storage/' . $brand->logo) }}"
                                 alt="{{ $brand->name }}"
                                 class="w-full h-24 object-contain mb-4 grayscale group-hover:grayscale-0 transition">
                        @else
                            <div class="w-full h-24 flex items-center justify-center mb-4">
                                <i class="fas fa-trademark text-gray-300 text-5xl group-hover:text-blue-500 transition"></i>
                            </div>
                        @endif
                        <h3 class="font-semibold text-lg group-hover:text-blue-600 transition">{{ $brand->name }}</h3>
                        <p class="text-sm text-gray-500 mt-1">{{ $brand->products_count }} mahsulot</p>
                    </a>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-8">
                {{ $brands->links() }}
            </div>
        @else
            <div class="bg-white rounded-lg shadow p-12 text-center">
                <i class="fas fa-trademark text-gray-300 text-6xl mb-4"></i>
                <h3 class="text-xl font-semibold mb-2">Brendlar topilmadi</h3>
                <p class="text-gray-500">Hozircha brendlar mavjud emas</p>
            </div>
        @endif
    </div>
@endsection
