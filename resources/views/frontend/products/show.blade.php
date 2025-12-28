{{-- resources/views/frontend/products/show.blade.php --}}
@extends('layouts.app')

@section('title', $product->name)

@section('content')
    <div class="container mx-auto px-4 py-8">
        {{-- Breadcrumb --}}
        <nav class="mb-6 text-sm">
            <ol class="flex items-center gap-2 text-gray-500">
                <li><a href="{{ route('home') }}" class="hover:text-blue-600">Bosh sahifa</a></li>
                <li><i class="fas fa-chevron-right text-xs"></i></li>
                <li><a href="{{ route('products.index') }}" class="hover:text-blue-600">Mahsulotlar</a></li>
                <li><i class="fas fa-chevron-right text-xs"></i></li>
                <li><a href="{{ route('products.index', ['category_id' => $product->category_id]) }}" class="hover:text-blue-600">{{ $product->category->name }}</a></li>
                <li><i class="fas fa-chevron-right text-xs"></i></li>
                <li class="text-gray-900">{{ $product->name }}</li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Left: Images --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow p-6">
                    {{-- Main Image --}}
                    <div class="mb-4">
                        <img id="mainImage"
                             src="{{ $product->images->first()?->image ? asset('storage/' . $product->images->first()->image) : $product->primary_image }}"
                             alt="{{ $product->name }}"
                             class="w-full h-96 object-contain rounded">
                    </div>

                    {{-- Thumbnails --}}
                    @if($product->images->count() > 1)
                        <div class="grid grid-cols-5 gap-2">
                            @foreach($product->images as $image)
                                <img src="{{ asset('storage/' . $image->image) }}"
                                     alt="{{ $product->name }}"
                                     onclick="changeMainImage('{{ asset('storage/' . $image->image) }}')"
                                     class="w-full h-20 object-cover rounded border-2 cursor-pointer hover:border-blue-600">
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Product Info Tabs --}}
                <div class="bg-white rounded-lg shadow mt-6" x-data="{ tab: 'description' }">
                    <div class="border-b flex">
                        <button @click="tab = 'description'"
                                :class="tab === 'description' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-500'"
                                class="px-6 py-3 font-semibold">
                            Tavsif
                        </button>
                        <button @click="tab = 'specs'"
                                :class="tab === 'specs' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-500'"
                                class="px-6 py-3 font-semibold">
                            Xususiyatlar
                        </button>
                        <button @click="tab = 'reviews'"
                                :class="tab === 'reviews' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-500'"
                                class="px-6 py-3 font-semibold">
                            Sharhlar ({{ $product->reviews_count }})
                        </button>
                    </div>

                    <div class="p-6">
                        {{-- Description --}}
                        <div x-show="tab === 'description'" class="prose max-w-none">
                            {!! nl2br(e($product->description)) !!}
                        </div>

                        {{-- Specifications --}}
                        <div x-show="tab === 'specs'">
                            @if($product->specifications)
                                <table class="w-full">
                                    @foreach($product->specifications as $key => $value)
                                        <tr class="border-b">
                                            <td class="py-3 font-semibold w-1/3">{{ $key }}</td>
                                            <td class="py-3">{{ $value }}</td>
                                        </tr>
                                    @endforeach
                                </table>
                            @else
                                <p class="text-gray-500">Texnik xususiyatlar mavjud emas</p>
                            @endif
                        </div>

                        {{-- Reviews --}}
                        <div x-show="tab === 'reviews'">
                            @if($product->reviews->count() > 0)
                                <div class="space-y-4">
                                    @foreach($product->reviews()->approved()->latest()->get() as $review)
                                        <div class="border-b pb-4">
                                            <div class="flex items-center gap-2 mb-2">
                                                <div class="flex text-yellow-400">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= $review->rating)
                                                            <i class="fas fa-star"></i>
                                                        @else
                                                            <i class="far fa-star"></i>
                                                        @endif
                                                    @endfor
                                                </div>
                                                <span class="font-semibold">{{ $review->user->name }}</span>
                                                <span class="text-sm text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                                            </div>
                                            <p class="text-gray-700">{{ $review->comment }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500">Hali sharhlar yo'q</p>
                            @endif

                            {{-- Add Review --}}
                            @auth
                                <div class="mt-6 pt-6 border-t">
                                    <h4 class="font-semibold mb-4">Sharh qoldiring</h4>
                                    <form action="{{ route('reviews.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                                        <div class="mb-4">
                                            <label class="block mb-2">Reyting</label>
                                            <div class="flex gap-2">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <input type="radio" name="rating" value="{{ $i }}" id="rating{{ $i }}" class="hidden peer/rating{{ $i }}" required>
                                                    <label for="rating{{ $i }}" class="text-2xl cursor-pointer text-gray-300 peer-checked/rating{{ $i }}:text-yellow-400">
                                                        <i class="fas fa-star"></i>
                                                    </label>
                                                @endfor
                                            </div>
                                        </div>

                                        <div class="mb-4">
                                            <label class="block mb-2">Sharh</label>
                                            <textarea name="comment" rows="4" class="w-full border rounded px-3 py-2" required></textarea>
                                        </div>

                                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                                            Yuborish
                                        </button>
                                    </form>
                                </div>
                            @else
                                <div class="mt-6 pt-6 border-t text-center">
                                    <p class="text-gray-500 mb-4">Sharh qoldirish uchun tizimga kiring</p>
                                    <a href="{{ route('login') }}" class="inline-block bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                                        Kirish
                                    </a>
                                </div>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right: Purchase --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow p-6 sticky top-24">
                    {{-- Brand --}}
                    @if($product->brand)
                        <a href="{{ route('brands.show', $product->brand->slug) }}"
                           class="text-sm text-gray-500 hover:text-blue-600 mb-2 inline-block">
                            {{ $product->brand->name }}
                        </a>
                    @endif

                    {{-- Name --}}
                    <h1 class="text-2xl font-bold mb-4">{{ $product->name }}</h1>

                    {{-- Rating --}}
                    <div class="flex items-center gap-2 mb-4">
                        <div class="flex text-yellow-400">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= round($product->rating))
                                    <i class="fas fa-star"></i>
                                @else
                                    <i class="far fa-star"></i>
                                @endif
                            @endfor
                        </div>
                        <span class="text-sm text-gray-500">({{ $product->reviews_count }} sharh)</span>
                    </div>

                    {{-- Price --}}
                    <div class="mb-6">
                        <div class="text-3xl font-bold text-blue-600 mb-1">
                            {{ number_format($product->final_price) }} so'm
                        </div>
                        @if($product->sale_price && $product->sale_price < $product->price)
                            <div class="flex items-center gap-2">
                                <span class="text-lg text-gray-400 line-through">{{ number_format($product->price) }} so'm</span>
                                <span class="bg-red-500 text-white text-sm px-2 py-1 rounded">
                                -{{ round((($product->price - $product->sale_price) / $product->price) * 100) }}%
                            </span>
                            </div>
                        @endif
                    </div>

                    {{-- Variants --}}
                    @if($product->variants->count() > 0)
                        <div class="mb-6">
                            <label class="block font-semibold mb-2">Variant tanlang:</label>
                            <select id="variantSelect" class="w-full border rounded px-3 py-2">
                                <option value="">Asosiy variant</option>
                                @foreach($product->variants as $variant)
                                    <option value="{{ $variant->id }}" data-price="{{ $variant->price }}" data-stock="{{ $variant->stock }}">
                                        {{ $variant->name }} - {{ number_format($variant->price) }} so'm
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    {{-- Stock Status --}}
                    <div class="mb-6">
                        @if($product->stock > 0)
                            <span class="flex items-center text-green-600">
                            <i class="fas fa-check-circle mr-2"></i>
                            Omborda mavjud ({{ $product->stock }} dona)
                        </span>
                        @else
                            <span class="flex items-center text-red-600">
                            <i class="fas fa-times-circle mr-2"></i>
                            Omborda yo'q
                        </span>
                        @endif
                    </div>

                    {{-- Quantity --}}
                    <div class="mb-6">
                        <label class="block font-semibold mb-2">Miqdor:</label>
                        <div class="flex items-center gap-4">
                            <button onclick="decreaseQty()" class="w-10 h-10 border rounded hover:bg-gray-100">
                                <i class="fas fa-minus"></i>
                            </button>
                            <input type="number" id="quantity" value="1" min="1" max="{{ $product->stock }}"
                                   class="w-20 text-center border rounded px-3 py-2">
                            <button onclick="increaseQty()" class="w-10 h-10 border rounded hover:bg-gray-100">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="space-y-3 mb-6">
                        <button onclick="addToCart({{ $product->id }})"
                                class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 font-semibold flex items-center justify-center gap-2"
                            {{ $product->stock <= 0 ? 'disabled' : '' }}>
                            <i class="fas fa-shopping-cart"></i>
                            Savatga qo'shish
                        </button>

                        <button onclick="buyNow({{ $product->id }})"
                                class="w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 font-semibold"
                            {{ $product->stock <= 0 ? 'disabled' : '' }}>
                            Tezkor xarid
                        </button>

                        <button onclick="toggleWishlist({{ $product->id }})"
                                class="w-full border-2 border-red-500 text-red-500 py-3 rounded-lg hover:bg-red-50 font-semibold flex items-center justify-center gap-2">
                            <i class="far fa-heart"></i>
                            Sevimlilar
                        </button>
                    </div>

                    {{-- Additional Info --}}
                    <div class="border-t pt-6 space-y-3 text-sm">
                        <div class="flex items-center gap-2 text-gray-600">
                            <i class="fas fa-shipping-fast text-blue-600"></i>
                            <span>Tezkor yetkazib berish</span>
                        </div>
                        @if($product->warranty)
                            <div class="flex items-center gap-2 text-gray-600">
                                <i class="fas fa-shield-alt text-green-600"></i>
                                <span>Kafolat: {{ $product->warranty }}</span>
                            </div>
                        @endif
                        <div class="flex items-center gap-2 text-gray-600">
                            <i class="fas fa-undo text-yellow-600"></i>
                            <span>14 kun qaytarish kafolati</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Similar Products --}}
        @if($similarProducts->count() > 0)
            <section class="mt-12">
                <h2 class="text-2xl font-bold mb-6">O'xshash mahsulotlar</h2>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
                    @foreach($similarProducts as $similar)
                        <x-product-card :product="$similar" />
                    @endforeach
                </div>
            </section>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        function changeMainImage(src) {
            document.getElementById('mainImage').src = src;
        }

        function increaseQty() {
            const input = document.getElementById('quantity');
            const max = parseInt(input.max);
            if (parseInt(input.value) < max) {
                input.value = parseInt(input.value) + 1;
            }
        }

        function decreaseQty() {
            const input = document.getElementById('quantity');
            if (parseInt(input.value) > 1) {
                input.value = parseInt(input.value) - 1;
            }
        }

        function addToCart(productId) {
            const quantity = document.getElementById('quantity').value;
            const variantSelect = document.getElementById('variantSelect');
            const variantId = variantSelect ? variantSelect.value : null;

            fetch('/cart', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    product_id: productId,
                    product_variant_id: variantId || null,
                    quantity: parseInt(quantity)
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Mahsulot savatchaga qo\'shildi!');
                        location.reload();
                    }
                });
        }

        function buyNow(productId) {
            addToCart(productId);
            setTimeout(() => {
                window.location.href = '{{ route("cart") }}';
            }, 500);
        }

        function toggleWishlist(productId) {
            @auth
            fetch('/wishlist/toggle', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ product_id: productId })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        location.reload();
                    }
                });
            @else
            alert('Iltimos, avval tizimga kiring!');
            window.location.href = '{{ route("login") }}';
            @endauth
        }

        // Variant selection
        @if($product->variants->count() > 0)
        document.getElementById('variantSelect').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.value) {
                const price = selectedOption.dataset.price;
                const stock = selectedOption.dataset.stock;
                // Update price and stock display here
            }
        });
        @endif
    </script>
@endpush
