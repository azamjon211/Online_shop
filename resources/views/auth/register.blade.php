{{-- resources/views/auth/register.blade.php --}}
@extends('layouts.app')

@section('title', 'Ro\'yxatdan o\'tish')

@section('content')
    <div class="container mx-auto px-4 py-16">
        <div class="max-w-md mx-auto bg-white rounded-lg shadow p-8">
            <h1 class="text-2xl font-bold mb-6 text-center">Ro'yxatdan o'tish</h1>

            <form action="{{ route('register') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Ism *</label>
                    <input type="text"
                           name="name"
                           value="{{ old('name') }}"
                           required
                           class="w-full border rounded px-3 py-2 @error('name') border-red-500 @enderror">
                    @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Email *</label>
                    <input type="email"
                           name="email"
                           value="{{ old('email') }}"
                           required
                           class="w-full border rounded px-3 py-2 @error('email') border-red-500 @enderror">
                    @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Telefon</label>
                    <input type="tel"
                           name="phone"
                           value="{{ old('phone') }}"
                           placeholder="+998 90 123 45 67"
                           class="w-full border rounded px-3 py-2">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Parol *</label>
                    <input type="password"
                           name="password"
                           required
                           class="w-full border rounded px-3 py-2 @error('password') border-red-500 @enderror">
                    @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium mb-2">Parolni tasdiqlash *</label>
                    <input type="password"
                           name="password_confirmation"
                           required
                           class="w-full border rounded px-3 py-2">
                </div>

                <button type="submit"
                        class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
                    Ro'yxatdan o'tish
                </button>
            </form>

            <p class="text-center mt-6 text-sm">
                Akkauntingiz bormi?
                <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Kirish</a>
            </p>
        </div>
    </div>
@endsection
