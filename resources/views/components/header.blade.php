{{-- resources/views/components/header.blade.php --}}
<header class="bg-white shadow-md sticky top-0 z-50">
    {{-- Top Bar --}}
    <div class="bg-gray-100 border-b border-gray-200">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-2 text-sm text-gray-600">
                <div class="flex items-center gap-4">
                    <a href="tel:+998901234567" class="hover:text-blue-600 transition">
                        <i class="fas fa-phone mr-1"></i> +998 90 123 45 67
                    </a>
                    <span class="text-gray-400">|</span>
                    <span><i class="fas fa-map-marker-alt mr-1"></i> Toshkent, O'zbekiston</span>
                </div>

                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ route('profile.orders') }}" class="hover:text-blue-600 transition">
                            <i class="fas fa-user mr-1"></i> {{ Auth::user()->name }}
                        </a>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="hover:text-red-600 transition">
                                <i class="fas fa-sign-out-alt mr-1"></i> Chiqish
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="hover:text-blue-600 transition">
                            <i class="fas fa-sign-in-alt mr-1"></i> Kirish
                        </a>
                        <a href="{{ route('register') }}" class="hover:text-blue-600 transition">
                            <i class="fas fa-user-plus mr-1"></i> Ro'yxatdan o'tish
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    {{-- Main Header --}}
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between py-4 gap-6">
            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center flex-shrink-0">
                <div class="text-3xl font-bold text-blue-600">
                    <i class="fas fa-shopping-bag mr-2"></i>
                    <span>AlifShop</span>
                </div>
            </a>

            {{-- Search --}}
            <div class="flex-1 max-w-2xl">
                <form action="{{ route('products.index') }}" method="GET" class="relative">
                    <input type="text"
                           name="search"
                           placeholder="Mahsulotlarni qidirish..."
                           value="{{ request('search') }}"
                           class="w-full px-4 py-3 pr-12 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none transition">
                    <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-blue-600 transition">
                        <i class="fas fa-search text-xl"></i>
                    </button>
                </form>
            </div>

            {{-- Icons --}}
            <div class="flex items-center gap-6">
                {{-- Wishlist --}}
                <a href="{{ route('wishlist') }}" class="relative group">
                    <div class="flex flex-col items-center">
                        <i class="far fa-heart text-2xl group-hover:text-red-600 transition"></i>
                        @auth
                            @php
                                $wishlistCount = Auth::user()->wishlists()->count();
                            @endphp
                            @if($wishlistCount > 0)
                                <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold">
                                    {{ $wishlistCount }}
                                </span>
                            @endif
                        @endauth
                        <span class="text-xs text-gray-600 mt-1">Saralanganlar</span>
                    </div>
                </a>

                {{-- Cart --}}
                <a href="{{ route('cart') }}" class="relative group">
                    <div class="flex flex-col items-center">
                        <i class="fas fa-shopping-cart text-2xl group-hover:text-blue-600 transition"></i>
                        @php
                            $cartCount = 0;
                            if (Auth::check()) {
                                $cartCount = Auth::user()->cartItems()->count();
                            } else {
                                $cartCount = \App\Models\CartItem::where('session_id', session()->getId())->count();
                            }
                        @endphp
                        @if($cartCount > 0)
                            <span class="absolute -top-2 -right-2 bg-blue-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold">
                                {{ $cartCount }}
                            </span>
                        @endif
                        <span class="text-xs text-gray-600 mt-1">Savat</span>
                    </div>
                </a>

                {{-- Profile --}}
                @auth
                    <a href="{{ route('profile.orders') }}" class="group">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-user text-2xl group-hover:text-blue-600 transition"></i>
                            <span class="text-xs text-gray-600 mt-1">Profil</span>
                        </div>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="group">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-user text-2xl group-hover:text-blue-600 transition"></i>
                            <span class="text-xs text-gray-600 mt-1">Kirish</span>
                        </div>
                    </a>
                @endauth
            </div>
        </div>
    </div>

    {{-- Navigation --}}
    <div class="border-t border-gray-200 bg-white">
        <div class="container mx-auto px-4">
            <nav class="flex items-center gap-8 py-3">
                <div class="relative group">
                    <button class="flex items-center gap-2 bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-bars"></i>
                        <span class="font-medium">Kategoriyalar</span>
                        <i class="fas fa-chevron-down text-sm"></i>
                    </button>

                    {{-- Mega Menu --}}
                    @if(isset($categories) && $categories->count() > 0)
                        <div class="absolute left-0 top-full mt-2 w-64 bg-white shadow-xl rounded-lg overflow-hidden hidden group-hover:block z-50">
                            @foreach($categories as $category)
                                <div class="relative group/sub">
                                    <a href="{{ route('categories.show', $category->slug) }}"
                                       class="flex items-center justify-between px-4 py-3 hover:bg-gray-100 transition border-b border-gray-100">
                                        <span class="font-medium text-gray-700">{{ $category->name }}</span>
                                        @if($category->children && $category->children->count() > 0)
                                            <i class="fas fa-chevron-right text-xs text-gray-400"></i>
                                        @endif
                                    </a>

                                    {{-- Subcategories --}}
                                    @if($category->children && $category->children->count() > 0)
                                        <div class="absolute left-full top-0 ml-1 w-64 bg-white shadow-xl rounded-lg overflow-hidden hidden group-hover/sub:block">
                                            @foreach($category->children as $child)
                                                <a href="{{ route('categories.show', $child->slug) }}"
                                                   class="block px-4 py-3 hover:bg-gray-100 transition border-b border-gray-100">
                                                    <span class="text-gray-700">{{ $child->name }}</span>
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <a href="{{ route('brands.index') }}" class="text-gray-700 hover:text-blue-600 font-medium transition">Brendlar</a>
                <a href="{{ route('products.index', ['is_new' => 1]) }}" class="text-gray-700 hover:text-blue-600 font-medium transition">Yangi mahsulotlar</a>
                <a href="{{ route('products.index', ['is_featured' => 1]) }}" class="text-red-600 hover:text-red-700 font-medium transition">
                    <i class="fas fa-fire mr-1"></i> Aksiya
                </a>
            </nav>
        </div>
    </div>
</header>
