{{-- resources/views/components/product-card.blade.php --}}
@props(['product'])

<div class="bg-white rounded-lg shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden group h-full flex flex-col">
    {{-- Image --}}
    <a href="{{ route('products.show', $product->slug) }}" class="block relative overflow-hidden">
        <img src="{{ asset('storage/' . $product->primary_image) }}"
             alt="{{ $product->name }}"
             class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-300"
             onerror="this.src='{{ asset('images/no-image.png') }}'">

        {{-- Badges --}}
        <div class="absolute top-2 left-2 flex flex-col gap-2 z-10">
            @if($product->is_new)
                <span class="bg-green-500 text-white text-xs px-3 py-1 rounded-md font-medium shadow">Yangi</span>
            @endif
            @if($product->sale_price && $product->sale_price < $product->price)
                <span class="bg-red-500 text-white text-xs px-3 py-1 rounded-md font-medium shadow">
                    -{{ $product->discount_percentage }}%
                </span>
            @endif
            @if($product->stock <= 0)
                <span class="bg-gray-500 text-white text-xs px-3 py-1 rounded-md font-medium shadow">Tugagan</span>
            @endif
        </div>

        {{-- Wishlist Button --}}
        @auth
            <button onclick="toggleWishlist({{ $product->id }}, event)"
                    class="absolute top-2 right-2 w-10 h-10 bg-white rounded-full flex items-center justify-center hover:bg-red-50 transition-all shadow-md z-10 wishlist-btn-{{ $product->id }}">
                @php
                    $inWishlist = Auth::user()->wishlists()->where('product_id', $product->id)->exists();
                @endphp
                <i class="{{ $inWishlist ? 'fas' : 'far' }} fa-heart text-red-500 text-lg wishlist-icon-{{ $product->id }}"></i>
            </button>
        @else
            <a href="{{ route('login') }}"
               class="absolute top-2 right-2 w-10 h-10 bg-white rounded-full flex items-center justify-center hover:bg-red-50 transition-all shadow-md z-10">
                <i class="far fa-heart text-red-500 text-lg"></i>
            </a>
        @endauth
    </a>

    {{-- Info --}}
    <div class="p-4 flex-1 flex flex-col">
        {{-- Brand --}}
        @if($product->brand)
            <a href="{{ route('brands.show', $product->brand->slug) }}"
               class="text-xs text-gray-500 hover:text-blue-600 transition mb-1">
                {{ $product->brand->name }}
            </a>
        @endif

        {{-- Name --}}
        <a href="{{ route('products.show', $product->slug) }}"
           class="font-semibold text-base text-gray-800 mb-2 line-clamp-2 hover:text-blue-600 transition flex-1">
            {{ $product->name }}
        </a>

        {{-- Rating --}}
        <div class="flex items-center gap-2 mb-3">
            <div class="flex text-yellow-400 text-sm">
                @for($i = 1; $i <= 5; $i++)
                    @if($i <= round($product->rating))
                        <i class="fas fa-star"></i>
                    @else
                        <i class="far fa-star"></i>
                    @endif
                @endfor
            </div>
            <span class="text-xs text-gray-500">({{ $product->reviews_count }})</span>
        </div>

        {{-- Price --}}
        <div class="mb-3">
            <div class="text-2xl font-bold text-blue-600">
                {{ number_format($product->final_price) }} <span class="text-sm">so'm</span>
            </div>
            @if($product->sale_price && $product->sale_price < $product->price)
                <div class="text-sm text-gray-400 line-through">
                    {{ number_format($product->price) }} so'm
                </div>
            @endif
        </div>

        {{-- Add to Cart --}}
        <button onclick="addToCart({{ $product->id }}, event)"
                class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 active:bg-blue-800 transition flex items-center justify-center gap-2 font-medium {{ $product->stock <= 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
            {{ $product->stock <= 0 ? 'disabled' : '' }}>
            <i class="fas fa-shopping-cart"></i>
            <span>{{ $product->stock > 0 ? 'Savatga qo\'shish' : 'Tugagan' }}</span>
        </button>
    </div>
</div>

@once
    @push('scripts')
        <script>
            // Toggle wishlist
            function toggleWishlist(productId, event) {
                event.preventDefault();
                event.stopPropagation();

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
                            // Update icon
                            const icon = document.querySelector(`.wishlist-icon-${productId}`);
                            if (icon) {
                                if (data.in_wishlist) {
                                    icon.classList.remove('far');
                                    icon.classList.add('fas');
                                } else {
                                    icon.classList.remove('fas');
                                    icon.classList.add('far');
                                }
                            }

                            // Update header count
                            updateWishlistHeaderCount(data.wishlist_count);

                            // Show notification
                            showNotification(data.message, 'success');
                        } else {
                            if (data.redirect) {
                                window.location.href = data.redirect;
                            } else {
                                showNotification(data.message, 'error');
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('Xatolik yuz berdi', 'error');
                    });
            }

            // Add to cart
            function addToCart(productId, event) {
                event.preventDefault();
                event.stopPropagation();

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
                            updateCartHeaderCount(data.cart_count);
                            showNotification(data.message, 'success');
                        } else {
                            showNotification(data.message, 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('Savat funksiyasi hali tayyor emas', 'info');
                    });
            }

            // Update wishlist count in header
            function updateWishlistHeaderCount(count) {
                // Find all wishlist badges
                document.querySelectorAll('a[href*="wishlist"] span.bg-red-500').forEach(badge => {
                    if (count > 0) {
                        badge.textContent = count;
                        badge.classList.remove('hidden');
                    } else {
                        badge.classList.add('hidden');
                    }
                });
            }

            // Update cart count in header
            function updateCartHeaderCount(count) {
                document.querySelectorAll('a[href*="cart"] span.bg-blue-500').forEach(badge => {
                    if (count > 0) {
                        badge.textContent = count;
                        badge.classList.remove('hidden');
                    } else {
                        badge.classList.add('hidden');
                    }
                });
            }

            // Show notification
            function showNotification(message, type = 'info') {
                // Simple alert for now
                const emoji = type === 'success' ? '✅' : type === 'error' ? '❌' : 'ℹ️';
                alert(`${emoji} ${message}`);

                // You can replace this with a toast notification library later
            }
        </script>
    @endpush
@endonce
