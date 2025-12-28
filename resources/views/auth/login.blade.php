{{-- resources/views/auth/login.blade.php --}}
@extends('layouts.app')

@section('title', 'Kirish')

@section('content')
    <div class="container mx-auto px-4 py-16">
        <div class="max-w-md mx-auto bg-white rounded-lg shadow p-8">
            <h1 class="text-2xl font-bold mb-6 text-center">Tizimga kirish</h1>

            <form action="{{ route('login') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Email</label>
                    <input type="email"
                           name="email"
                           value="{{ old('email') }}"
                           required
                           class="w-full border rounded px-3 py-2 @error('email') border-red-500 @enderror">
                    @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium mb-2">Parol</label>
                    <input type="password"
                           name="password"
                           required
                           class="w-full border rounded px-3 py-2 @error('password') border-red-500 @enderror">
                    @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="mr-2">
                        <span class="text-sm">Eslab qolish</span>
                    </label>
                </div>

                <button type="submit"
                        class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
                    Kirish
                </button>
            </form>

            <p class="text-center mt-6 text-sm">
                Akkauntingiz yo'qmi?
                <a href="{{ route('register') }}" class="text-blue-600 hover:underline">Ro'yxatdan o'tish</a>
            </p>
        </div>
    </div>
@endsection
