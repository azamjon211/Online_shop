{{-- resources/views/frontend/profile/sidebar.blade.php --}}
<div class="lg:col-span-1">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center gap-3 pb-6 border-b">
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                <i class="fas fa-user text-blue-600 text-2xl"></i>
            </div>
            <div>
                <h3 class="font-semibold">{{ Auth::user()->name }}</h3>
                <p class="text-sm text-gray-500">{{ Auth::user()->email }}</p>
            </div>
        </div>

        <nav class="mt-6 space-y-2">
            <a href="{{ route('profile.orders') }}"
               class="flex items-center gap-3 px-4 py-3 rounded hover:bg-gray-100 {{ request()->routeIs('profile.orders*') ? 'bg-blue-50 text-blue-600' : '' }}">
                <i class="fas fa-shopping-bag w-5"></i>
                <span>Buyurtmalarim</span>
            </a>

            <a href="{{ route('wishlist') }}"
               class="flex items-center gap-3 px-4 py-3 rounded hover:bg-gray-100 {{ request()->routeIs('wishlist') ? 'bg-blue-50 text-blue-600' : '' }}">
                <i class="fas fa-heart w-5"></i>
                <span>Sevimlilar</span>
            </a>

            <a href="{{ route('profile.settings') }}"
               class="flex items-center gap-3 px-4 py-3 rounded hover:bg-gray-100 {{ request()->routeIs('profile.settings') ? 'bg-blue-50 text-blue-600' : '' }}">
                <i class="fas fa-cog w-5"></i>
                <span>Sozlamalar</span>
            </a>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded hover:bg-gray-100 text-red-600">
                    <i class="fas fa-sign-out-alt w-5"></i>
                    <span>Chiqish</span>
                </button>
            </form>
        </nav>
    </div>
</div>
