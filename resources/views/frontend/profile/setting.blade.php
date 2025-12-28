{{-- resources/views/frontend/profile/settings.blade.php --}}
@extends('layouts.app')

@section('title', 'Sozlamalar')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            {{-- Sidebar --}}
            @include('frontend.profile.sidebar')

            {{-- Main Content --}}
            <div class="lg:col-span-3">
                <div class="bg-white rounded-lg shadow p-6">
                    <h1 class="text-2xl font-bold mb-6">Profil sozlamalari</h1>

                    <form action="{{ route('profile.settings.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium mb-2">Ism *</label>
                                <input type="text"
                                       name="name"
                                       value="{{ old('name', $user->name) }}"
                                       required
                                       class="w-full border rounded px-3 py-2 @error('name') border-red-500 @enderror">
                                @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-2">Email *</label>
                                <input type="email"
                                       name="email"
                                       value="{{ old('email', $user->email) }}"
                                       required
                                       class="w-full border rounded px-3 py-2 @error('email') border-red-500 @enderror">
                                @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-2">Telefon</label>
                                <input type="tel"
                                       name="phone"
                                       value="{{ old('phone', $user->phone) }}"
                                       placeholder="+998 90 123 45 67"
                                       class="w-full border rounded px-3 py-2">
                            </div>
                        </div>

                        <div class="border-t mt-8 pt-8">
                            <h3 class="text-lg font-semibold mb-4">Parolni o'zgartirish</h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium mb-2">Joriy parol</label>
                                    <input type="password"
                                           name="current_password"
                                           class="w-full border rounded px-3 py-2 @error('current_password') border-red-500 @enderror">
                                    @error('current_password')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium mb-2">Yangi parol</label>
                                    <input type="password"
                                           name="password"
                                           class="w-full border rounded px-3 py-2 @error('password') border-red-500 @enderror">
                                    @error('password')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium mb-2">Parolni tasdiqlash</label>
                                    <input type="password"
                                           name="password_confirmation"
                                           class="w-full border rounded px-3 py-2">
                                </div>
                            </div>
                        </div>

                        <div class="mt-8">
                            <button type="submit"
                                    class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                                Saqlash
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
