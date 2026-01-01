{{-- resources/views/frontend/profile/settings.blade.php --}}
@extends('frontend.profile.layout')

@section('title', 'Sozlamalar')

@section('profile-content')
    <div class="bg-white rounded-lg shadow-md">
        {{-- Header --}}
        <div class="border-b px-6 py-4">
            <h2 class="text-2xl font-bold">Sozlamalar</h2>
        </div>

        {{-- Settings Form --}}
        <div class="p-6">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('profile.settings.update') }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Personal Info --}}
                <div class="mb-8">
                    <h3 class="text-lg font-bold mb-4 flex items-center gap-2">
                        <i class="fas fa-user text-blue-600"></i>
                        Shaxsiy ma'lumotlar
                    </h3>

                    <div class="space-y-4">
                        {{-- Name --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Ism <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   name="name"
                                   value="{{ old('name', $user->name) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none @error('name') border-red-500 @enderror">
                            @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email"
                                   name="email"
                                   value="{{ old('email', $user->email) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none @error('email') border-red-500 @enderror">
                            @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Phone --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Telefon
                            </label>
                            <input type="text"
                                   name="phone"
                                   value="{{ old('phone', $user->phone) }}"
                                   placeholder="+998 90 123 45 67"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none @error('phone') border-red-500 @enderror">
                            @error('phone')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Password Change --}}
                <div class="mb-8 border-t pt-6">
                    <h3 class="text-lg font-bold mb-4 flex items-center gap-2">
                        <i class="fas fa-lock text-blue-600"></i>
                        Parolni o'zgartirish
                    </h3>

                    <div class="space-y-4">
                        {{-- Current Password --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Joriy parol
                            </label>
                            <input type="password"
                                   name="current_password"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none @error('current_password') border-red-500 @enderror">
                            @error('current_password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- New Password --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Yangi parol
                            </label>
                            <input type="password"
                                   name="new_password"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none @error('new_password') border-red-500 @enderror">
                            @error('new_password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Kamida 8 ta belgi</p>
                        </div>

                        {{-- Confirm Password --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Parolni tasdiqlang
                            </label>
                            <input type="password"
                                   name="new_password_confirmation"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none">
                        </div>
                    </div>
                </div>

                {{-- Submit Button --}}
                <div class="flex gap-4">
                    <button type="submit"
                            class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition font-medium">
                        <i class="fas fa-save mr-2"></i>
                        Saqlash
                    </button>
                    <a href="{{ route('home') }}"
                       class="bg-gray-200 text-gray-700 px-8 py-3 rounded-lg hover:bg-gray-300 transition font-medium">
                        Bekor qilish
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
