{{-- resources/views/frontend/home.blade.php --}}
@extends('layouts.app')

@section('title', 'Bosh sahifa')

@section('content')
    {{-- Hero Section - Full Width & Height --}}
    <section class="relative bg-gradient-to-r from-blue-600 to-purple-600 text-white overflow-hidden">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-0 min-h-[600px]">

            {{-- Left: Content --}}
            <div class="flex items-center">
                <div class="container mx-auto px-4 lg:px-8 py-16 lg:py-24">
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold leading-tight mb-6">
                        O'zbekistondagi eng yirik onlayn do'kon
                    </h1>
                    <p class="text-lg md:text-xl lg:text-2xl text-blue-100 mb-8 max-w-xl">
                        50,000+ mahsulot. Tezkor yetkazib berish. Kafolat.
                    </p>
                    <a href="{{ route('products.index') }}"
                       class="inline-block bg-white text-blue-600 px-8 py-4 rounded-xl text-lg font-bold hover:bg-blue-50 transition shadow-xl transform hover:scale-105">
                        <i class="fas fa-shopping-bag mr-2"></i>
                        Xarid qilish
                    </a>
                </div>
            </div>

            {{-- Right: Full Height Image --}}
            <div class="relative min-h-[400px] lg:min-h-full">
                <img src="{{ asset('images/img.png') }}"
                     alt="Products"
                     class="absolute inset-0 w-full h-full object-cover object-center">

                {{-- Sale Badge --}}
                <div class="absolute top-8 right-8 bg-red-500 text-white w-28 h-28 rounded-full flex flex-col items-center justify-center shadow-2xl animate-bounce">
                    <div class="text-4xl font-bold">-10%</div>
                    <div class="text-xs font-semibold uppercase">Aksiya</div>
                </div>
            </div>

        </div>
    </section>

    {{-- Categories --}}
    <section class="py-12">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold mb-8">Kategoriyalar</h2>

            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @foreach($categories->take(12) as $category)
                    <a href="{{ route('products.index', ['category_id' => $category->id]) }}"
                       class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition text-center group">
                        @if($category->image)
                            <img src="{{ asset('storage/' . $category->image) }}"
                                 alt="{{ $category->name }}"
                                 class="w-20 h-20 mx-auto mb-3 object-contain group-hover:scale-110 transition">
                        @else
                            <div class="w-20 h-20 mx-auto mb-3 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-tag text-blue-600 text-2xl"></i>
                            </div>
                        @endif
                        <h3 class="font-medium group-hover:text-blue-600 transition">{{ $category->name }}</h3>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Featured Products --}}
    <section class="py-12 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-3xl font-bold">Mashhur mahsulotlar</h2>
                <a href="{{ route('products.index', ['is_featured' => 1]) }}"
                   class="text-blue-600 hover:text-blue-800">
                    Barchasini ko'rish <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                @foreach($featuredProducts as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        </div>
    </section>

    {{-- New Arrivals --}}
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-3xl font-bold">Yangi mahsulotlar</h2>
                <a href="{{ route('products.index', ['is_new' => 1]) }}"
                   class="text-blue-600 hover:text-blue-800">
                    Barchasini ko'rish <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                @foreach($newProducts as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        </div>
    </section>

    {{-- Brands --}}
    <section class="py-12 bg-gray-50">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold mb-8 text-center">Mashhur brandlar</h2>

            <div class="grid grid-cols-3 md:grid-cols-6 gap-6">
                @foreach($brands->take(12) as $brand)
                    <a href="{{ route('brands.show', $brand->slug) }}"
                       class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition flex items-center justify-center">
                        @if($brand->logo)
                            <img src="{{ asset('storage/' . $brand->logo) }}"
                                 alt="{{ $brand->name }}"
                                 class="w-full h-16 object-contain grayscale hover:grayscale-0 transition">
                        @else
                            <span class="font-semibold text-gray-600">{{ $brand->name }}</span>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Features --}}
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-shipping-fast text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="font-semibold mb-2">Tezkor yetkazib berish</h3>
                    <p class="text-sm text-gray-500">24 soat ichida yetkazib beramiz</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-shield-alt text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="font-semibold mb-2">Kafolat</h3>
                    <p class="text-sm text-gray-500">Barcha mahsulotlarga kafolat beriladi</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-undo text-yellow-600 text-2xl"></i>
                    </div>
                    <h3 class="font-semibold mb-2">14 kun qaytarish</h3>
                    <p class="text-sm text-gray-500">Mahsulotni 14 kun ichida qaytarishingiz mumkin</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-headset text-purple-600 text-2xl"></i>
                    </div>
                    <h3 class="font-semibold mb-2">24/7 Qo'llab-quvvatlash</h3>
                    <p class="text-sm text-gray-500">Har doim sizga yordam berishga tayyormiz</p>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        function addToCart(productId) {
            fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: 1
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Mahsulot savatchaga qo\'shildi!');
                        location.reload();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Savat funksiyasi hali tayyor emas');
                });
        }

        function toggleWishlist(productId) {
            @auth
            fetch('/wishlist/toggle', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    product_id: productId
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const btn = document.querySelector('.wishlist-btn-' + productId + ' i');
                        if (btn) {
                            if (data.in_wishlist) {
                                btn.classList.remove('far');
                                btn.classList.add('fas');
                            } else {
                                btn.classList.remove('fas');
                                btn.classList.add('far');
                            }
                        }
                        alert(data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
            @else
            alert('Iltimos, avval tizimga kiring!');
            window.location.href = '{{ route("login") }}';
            @endauth
        }
    </script>
@endpush

