{{-- resources/views/frontend/wishlist/index.blade.php --}}
    <!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Saralanganlar - AlifShop</title>

    {{-- Tailwind CSS CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-gray-100">

{{-- Header (Simplified) --}}
<header class="bg-white shadow-md py-4 mb-6">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center">
            <a href="{{ route('home') }}" class="text-2xl font-bold text-blue-600">
                <i class="fas fa-shopping-bag mr-2"></i>
                AlifShop
            </a>
            <div class="flex items-center gap-4">
                <span class="text-gray-700">{{ Auth::user()->name }}</span>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button class="text-red-600 hover:text-red-700">
                        <i class="fas fa-sign-out-alt"></i> Chiqish
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>

{{-- Main Content --}}
<div class="container mx-auto px-4 py-8">

    {{-- Page Header --}}
    <div class="bg-white rounded-xl shadow-md p-6 mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-4xl font-bold text-gray-800 mb-2">
                    <i class="far fa-heart text-red-500 mr-3"></i>
                    Saralanganlar
                </h1>
                <p class="text-gray-600">Sizning sevimli mahsulotlaringiz</p>
            </div>
            @if($wishlists->count() > 0)
                <span class="bg-blue-500 text-white px-6 py-3 rounded-lg text-lg font-bold">
                        {{ $wishlists->count() }} ta mahsulot
                    </span>
            @endif
        </div>
    </div>

    {{-- Products Grid --}}
    @if($wishlists->count() > 0)
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
            @foreach($wishlists as $wishlist)
                @php $product = $wishlist->product; @endphp

                @if($product)
                    <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-2xl transition-all duration-300"
                         id="wishlist-item-{{ $product->id }}">

                        {{-- Image --}}
                        <div class="relative">
                            <a href="{{ route('products.show', $product->slug) }}">
                                <img src="{{ $product->primary_image }}"
                                     alt="{{ $product->name }}"
                                     class="w-full h-56 object-cover">
                            </a>

                            {{-- Remove Button --}}
                            <button onclick="removeFromWishlist({{ $product->id }})"
                                    class="absolute top-3 right-3 w-10 h-10 bg-white rounded-full shadow-lg flex items-center justify-center hover:bg-red-50 transition">
                                <i class="fas fa-trash-alt text-red-500"></i>
                            </button>
                        </div>

                        {{-- Info --}}
                        <div class="p-4">
                            @if($product->brand)
                                <p class="text-xs text-gray-500 mb-1">{{ $product->brand->name }}</p>
                            @endif

                            <h3 class="font-bold text-sm text-gray-800 mb-3 h-10 overflow-hidden">
                                {{ $product->name }}
                            </h3>

                            <div class="text-2xl font-bold text-blue-600 mb-3">
                                {{ number_format($product->final_price) }} so'm
                            </div>

                            @if($product->stock > 0)
                                <button onclick="addToCart({{ $product->id }})"
                                        class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition font-medium">
                                    <i class="fas fa-shopping-cart mr-2"></i>
                                    Savatga
                                </button>
                            @else
                                <button class="w-full bg-gray-300 text-gray-600 py-3 rounded-lg cursor-not-allowed" disabled>
                                    Tugagan
                                </button>
                            @endif
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    @else
        {{-- Empty State --}}
        <div class="bg-white rounded-xl shadow-md p-16 text-center">
            <div class="w-32 h-32 bg-gray-100 rounded-full mx-auto mb-6 flex items-center justify-center">
                <i class="far fa-heart text-gray-400 text-6xl"></i>
            </div>
            <h2 class="text-3xl font-bold text-gray-800 mb-3">Saralanganlar bo'sh</h2>
            <p class="text-gray-600 mb-6 text-lg">Mahsulotlarni sevimlilar ro'yxatiga qo'shing</p>
            <a href="{{ route('products.index') }}"
               class="inline-block bg-blue-600 text-white px-8 py-4 rounded-lg hover:bg-blue-700 transition text-lg font-medium">
                <i class="fas fa-shopping-bag mr-2"></i>
                Xarid qilish
            </a>
        </div>
    @endif
</div>

{{-- JavaScript --}}
<script>
    function removeFromWishlist(productId) {
        if (!confirm('O\'chirmoqchimisiz?')) return;

        fetch(`/wishlist/${productId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const item = document.getElementById(`wishlist-item-${productId}`);
                    item.style.opacity = '0';
                    item.style.transform = 'scale(0.8)';
                    setTimeout(() => {
                        item.remove();
                        if (document.querySelectorAll('[id^="wishlist-item-"]').length === 0) {
                            location.reload();
                        }
                    }, 300);
                    alert('✅ ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('❌ Xatolik yuz berdi');
            });
    }

    function addToCart(productId) {
        alert('Savat funksiyasi hali tayyor emas');
    }
</script>

</body>
</html>
