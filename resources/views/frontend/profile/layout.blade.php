{{-- resources/views/frontend/profile/layout.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            {{-- Sidebar --}}
            <div class="md:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6">
                    {{-- User Info --}}
                    <div class="text-center mb-6">
                        <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-user text-blue-600 text-3xl"></i>
                        </div>
                        <h3 class="font-bold text-lg">{{ Auth::user()->name }}</h3>
                        <p class="text-sm text-gray-500">{{ Auth::user()->email }}</p>
                    </div>

                    {{-- Navigation --}}
                    <nav class="space-y-2">
                        <a href="{{ route('profile.orders') }}"
                           class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('profile.orders*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-50' }}">
                            <i class="fas fa-shopping-bag"></i>
                            <span class="font-medium">Buyurtmalarim</span>
                        </a>

                        <a href="{{ route('wishlist') }}"
                           class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('wishlist') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-50' }}">
                            <i class="fas fa-heart"></i>
                            <span class="font-medium">Sevimlilar</span>
                        </a>

                        <a href="{{ route('profile.settings') }}"
                           class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('profile.settings') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-50' }}">
                            <i class="fas fa-cog"></i>
                            <span class="font-medium">Sozlamalar</span>
                        </a>

                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit"
                                    class="w-full flex items-center gap-3 px-4 py-3 rounded-lg text-red-600 hover:bg-red-50 transition">
                                <i class="fas fa-sign-out-alt"></i>
                                <span class="font-medium">Chiqish</span>
                            </button>
                        </form>
                    </nav>
                </div>
            </div>

            {{-- Main Content --}}
            <div class="md:col-span-3">
                @yield('profile-content')
            </div>
        </div>
    </div>
@endsection
