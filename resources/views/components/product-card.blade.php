{{-- resources/views/components/product-card.blade.php --}}
@props(['product'])

<div class="bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow overflow-hidden group">
    {{-- Image --}}
    <a href="{{ route('products.show', $product->slug) }}" class="block relative overflow-hidden">
        <img src="{{ $product->primary_image }}"
             alt="{{ $product->name }}"
             class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-300">

        {{-- Badges --}}
        <div class="absolute top-2 left-2 flex flex-col gap-2">
            @if($product->is_new)
                <span class="bg-green-500 text-white text-xs px-2 py-1 rounded">Yangi</span>
            @endif
            @if($product->sale_price && $product->sale_price < $product->price)
                <span class="bg-red-500 text-white text-xs px-2 py-1 rounded">
                    -{{ round((($product->price - $product->sale_price) / $product->price) * 100) }}%
                </span>
            @endif
        </div>

        {{-- Wishlist Button --}}
        <button onclick="toggleWishlist({{ $product->id }})"
                class="absolute top-2 right-2 w-10 h-10 bg-white rounded-full flex items-center justify-center hover:bg-red-50 transition wishlist-btn-{{ $product->id }}">
            <i class="far fa-heart text-red-500 text-lg"></i>
        </button>
    </a>

    {{-- Info --}}
    <div class="p-4">
        {{-- Brand --}}
        @if($product->brand)
            <a href="{{ route('brands.show', $product->brand->slug) }}"
               class="text-xs text-gray-500 hover:text-blue-600">
                {{ $product->brand->name }}
            </a>
        @endif

        {{-- Name --}}
        <a href="{{ route('products.show', $product->slug) }}"
           class="block font-semibold text-lg mb-2 line-clamp-2 hover:text-blue-600 transition">
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
        </div>

        {{-- Price --}}
        <div class="flex items-center justify-between mb-3">
            <div>
                <div class="text-2xl font-bold text-blue-600">
                    {{ number_format($product->final_price) }} so'm
                </div>
                @if($product->sale_price && $product->sale_price < $product->price)
                    <div class="text-sm text-gray-400 line-through">
                        {{ number_format($product->price) }} so'm
                    </div>
                @endif
            </div>
        </div>

        {{-- Add to Cart --}}
        <button onclick="addToCart({{ $product->id }})"
                class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition flex items-center justify-center gap-2">
            <i class="fas fa-shopping-cart"></i>
            <span>Savatga qo'shish</span>
        </button>
    </div>
</div>
