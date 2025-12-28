{{-- resources/views/frontend/cart.blade.php --}}
@extends('layouts.app')

@section('title', 'Savatcha')

@section('content')
    <div class="container mx-auto px-4 py-8">
        {{-- Breadcrumb --}}
        <nav class="mb-6 text-sm">
            <ol class="flex items-center gap-2 text-gray-500">
                <li><a href="{{ route('home') }}" class="hover:text-blue-600">Bosh sahifa</a></li>
                <li><i class="fas fa-chevron-right text-xs"></i></li>
                <li class="text-gray-900">Savatcha</li>
            </ol>
        </nav>

        <h1 class="text-3xl font-bold mb-8">Savatcha</h1>

        @if($cartItems->count() > 0)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Cart Items --}}
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow">
                        @foreach($cartItems as $item)
                            <div class="p-6 border-b last:border-b-0 hover:bg-gray-50">
                                <div class="flex gap-4">
                                    {{-- Image --}}
                                    <a href="{{ route('products.show', $item->product->slug) }}" class="flex-shrink-0">
                                        <img src="{{ $item->product->primary_image }}"
                                             alt="{{ $item->product->name }}"
                                             class="w-32 h-32 object-cover rounded">
                                    </a>

                                    {{-- Info --}}
                                    <div class="flex-1">
                                        <a href="{{ route('products.show', $item->product->slug) }}"
                                           class="font-semibold text-lg hover:text-blue-600 mb-2 block">
                                            {{ $item->product->name }}
                                        </a>

                                        @if($item->variant)
                                            <p class="text-sm text-gray-500 mb-2">
                                                Variant: {{ $item->variant->name }}
                                            </p>
                                        @endif

                                        {{-- Price --}}
                                        <div class="mb-3">
                                        <span class="text-2xl font-bold text-blue-600">
                                            {{ number_format($item->variant ? $item->variant->price : $item->product->final_price) }} so'm
                                        </span>
                                            @if($item->product->sale_price && $item->product->sale_price < $item->product->price && !$item->variant)
                                                <span class="text-sm text-gray-400 line-through ml-2">
                                                {{ number_format($item->product->price) }} so'm
                                            </span>
                                            @endif
                                        </div>

                                        {{-- Actions --}}
                                        <div class="flex items-center gap-4">
                                            {{-- Quantity --}}
                                            <div class="flex items-center border rounded">
                                                <button onclick="updateCartQuantity({{ $item->id }}, {{ $item->quantity - 1 }})"
                                                        class="px-3 py-2 hover:bg-gray-100"
                                                    {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                                <input type="number"
                                                       value="{{ $item->quantity }}"
                                                       min="1"
                                                       max="{{ $item->variant ? $item->variant->stock : $item->product->stock }}"
                                                       class="w-16 text-center border-x py-2"
                                                       onchange="updateCartQuantity({{ $item->id }}, this.value)">
                                                <button onclick="updateCartQuantity({{ $item->id }}, {{ $item->quantity + 1 }})"
                                                        class="px-3 py-2 hover:bg-gray-100"
                                                    {{ $item->quantity >= ($item->variant ? $item->variant->stock : $item->product->stock) ? 'disabled' : '' }}>
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>

                                            {{-- Remove --}}
                                            <button onclick="removeFromCart({{ $item->id }})"
                                                    class="text-red-500 hover:text-red-700 flex items-center gap-1">
                                                <i class="fas fa-trash"></i>
                                                <span>O'chirish</span>
                                            </button>

                                            {{-- Wishlist --}}
                                            <button onclick="moveToWishlist({{ $item->id }}, {{ $item->product->id }})"
                                                    class="text-blue-500 hover:text-blue-700 flex items-center gap-1">
                                                <i class="far fa-heart"></i>
                                                <span>Sevimlilar</span>
                                            </button>
                                        </div>
                                    </div>

                                    {{-- Subtotal --}}
                                    <div class="text-right">
                                        <p class="text-sm text-gray-500 mb-1">Jami:</p>
                                        <p class="text-xl font-bold">
                                            {{ number_format(($item->variant ? $item->variant->price : $item->product->final_price) * $item->quantity) }} so'm
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Continue Shopping --}}
                    <div class="mt-6">
                        <a href="{{ route('products.index') }}"
                           class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800">
                            <i class="fas fa-arrow-left"></i>
                            <span>Xaridni davom ettirish</span>
                        </a>
                    </div>
                </div>

                {{-- Order Summary --}}
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow p-6 sticky top-24">
                        <h2 class="text-xl font-bold mb-6">Buyurtma xulosasi</h2>

                        {{-- Summary --}}
                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Oraliq summa:</span>
                                <span class="font-semibold">{{ number_format($subtotal) }} so'm</span>
                            </div>

                            <div class="flex justify-between">
                                <span class="text-gray-600">Yetkazib berish:</span>
                                <span class="font-semibold">{{ number_format($shippingCost) }} so'm</span>
                            </div>

                            @if($discount > 0)
                                <div class="flex justify-between text-green-600">
                                    <span>Chegirma:</span>
                                    <span class="font-semibold">-{{ number_format($discount) }} so'm</span>
                                </div>
                            @endif

                            <div class="border-t pt-3">
                                <div class="flex justify-between text-xl">
                                    <span class="font-bold">Jami:</span>
                                    <span class="font-bold text-blue-600">{{ number_format($total) }} so'm</span>
                                </div>
                            </div>
                        </div>

                        {{-- Promo Code --}}
                        <div class="mb-6">
                            <label class="block text-sm font-medium mb-2">Promo kod</label>
                            <div class="flex gap-2">
                                <input type="text"
                                       id="promoCode"
                                       placeholder="Kodni kiriting"
                                       class="flex-1 border rounded px-3 py-2">
                                <button onclick="applyPromoCode()"
                                        class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                                    Qo'llash
                                </button>
                            </div>
                        </div>

                        {{-- Checkout Button --}}
                        <a href="{{ route('checkout') }}"
                           class="block w-full bg-blue-600 text-white text-center py-3 rounded-lg hover:bg-blue-700 font-semibold">
                            Buyurtma berish
                        </a>

                        {{-- Clear Cart --}}
                        <button onclick="clearCart()"
                                class="w-full mt-3 text-red-600 hover:text-red-800 text-sm">
                            <i class="fas fa-trash mr-1"></i> Savatchani tozalash
                        </button>

                        {{-- Safe Checkout --}}
                        <div class="mt-6 pt-6 border-t">
                            <div class="flex items-center justify-center gap-2 text-sm text-gray-500">
                                <i class="fas fa-lock"></i>
                                <span>Xavfsiz to'lov</span>
                            </div>
                            <div class="flex justify-center gap-2 mt-3">
                                <img src="/images/payment/uzcard.png" alt="Uzcard" class="h-8">
                                <img src="/images/payment/humo.png" alt="Humo" class="h-8">
                                <img src="/images/payment/payme.png" alt="Payme" class="h-8">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            {{-- Empty Cart --}}
            <div class="bg-white rounded-lg shadow p-12 text-center">
                <i class="fas fa-shopping-cart text-gray-300 text-8xl mb-6"></i>
                <h2 class="text-2xl font-bold mb-4">Savatchangiz bo'sh</h2>
                <p class="text-gray-500 mb-8">Mahsulotlarni ko'rish va xarid qilish uchun katalogga o'ting</p>
                <a href="{{ route('products.index') }}"
                   class="inline-block bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 font-semibold">
                    Xarid qilish
                </a>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        function updateCartQuantity(itemId, quantity) {
            if (quantity < 1) return;

            fetch(`/cart/${itemId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ quantity: parseInt(quantity) })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert(data.message || 'Xatolik yuz berdi');
                    }
                });
        }

        function removeFromCart(itemId) {
            if (!confirm('Mahsulotni savatchadan o\'chirmoqchimisiz?')) return;

            fetch(`/cart/${itemId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                });
        }

        function clearCart() {
            if (!confirm('Savatchani tozalamoqchimisiz?')) return;

            fetch('/cart/clear/all', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                });
        }

        function moveToWishlist(cartItemId, productId) {
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
                        removeFromCart(cartItemId);
                    }
                });
        }

        function applyPromoCode() {
            const code = document.getElementById('promoCode').value;
            if (!code) {
                alert('Promo kodni kiriting');
                return;
            }

            // Promo kod logikasi
            alert('Promo kod funksiyasi hali ishlab chiqilmoqda');
        }
    </script>
@endpush
