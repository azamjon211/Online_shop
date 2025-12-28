{{-- resources/views/layouts/admin.blade.php --}}
    <!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - AlifShop</title>

    {{-- Tailwind CSS CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Alpine.js --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- Icons --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @stack('styles')
</head>
<body class="bg-gray-100">
<div class="flex h-screen">
    {{-- Sidebar --}}
    <aside class="w-64 bg-gray-800 text-white">
        <div class="p-4">
            <h1 class="text-2xl font-bold">AlifShop Admin</h1>
        </div>

        <nav class="mt-8">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center px-6 py-3 hover:bg-gray-700 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-700' : '' }}">
                <i class="fas fa-home mr-3"></i>
                Dashboard
            </a>

            <a href="{{ route('admin.categories.index') }}" class="flex items-center px-6 py-3 hover:bg-gray-700 {{ request()->routeIs('admin.categories.*') ? 'bg-gray-700' : '' }}">
                <i class="fas fa-tags mr-3"></i>
                Kategoriyalar
            </a>

            <a href="{{ route('admin.brands.index') }}" class="flex items-center px-6 py-3 hover:bg-gray-700 {{ request()->routeIs('admin.brands.*') ? 'bg-gray-700' : '' }}">
                <i class="fas fa-trademark mr-3"></i>
                Brendlar
            </a>

            <a href="{{ route('admin.products.index') }}" class="flex items-center px-6 py-3 hover:bg-gray-700 {{ request()->routeIs('admin.products.*') ? 'bg-gray-700' : '' }}">
                <i class="fas fa-box mr-3"></i>
                Mahsulotlar
            </a>

            <a href="{{ route('admin.orders.index') }}" class="flex items-center px-6 py-3 hover:bg-gray-700 {{ request()->routeIs('admin.orders.*') ? 'bg-gray-700' : '' }}">
                <i class="fas fa-shopping-cart mr-3"></i>
                Buyurtmalar
            </a>

            <a href="{{ route('admin.reviews.index') }}" class="flex items-center px-6 py-3 hover:bg-gray-700 {{ request()->routeIs('admin.reviews.*') ? 'bg-gray-700' : '' }}">
                <i class="fas fa-star mr-3"></i>
                Sharhlar
            </a>
        </nav>
    </aside>

    {{-- Main Content --}}
    <div class="flex-1 flex flex-col overflow-hidden">
        {{-- Header --}}
        <header class="bg-white shadow-md">
            <div class="flex items-center justify-between px-6 py-4">
                <h2 class="text-xl font-semibold">@yield('page-title', 'Dashboard')</h2>

                <div class="flex items-center gap-4">
                    <span>{{ auth()->user()->name }}</span>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-red-600 hover:text-red-800">
                            <i class="fas fa-sign-out-alt"></i> Chiqish
                        </button>
                    </form>
                </div>
            </div>
        </header>

        {{-- Content --}}
        <main class="flex-1 overflow-y-auto p-6">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

@stack('scripts')
</body>
</html>
