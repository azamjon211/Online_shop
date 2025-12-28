{{-- resources/views/frontend/checkout.blade.php --}}
@extends('layouts.app')

@section('title', 'Buyurtma rasmiylashtirish')

@section('content')
    <div class="container mx-auto px-4 py-8">
        {{-- Breadcrumb --}}
        <nav class="mb-6 text-sm">
            <ol class="flex items-center gap-2 text-gray-500">
                <li><a href="{{ route('home') }}" class="hover:text-blue-600">Bosh sahifa</a></li>
                <li><i class="fas fa-chevron-right text-xs"></i></li>
                <li><a href="{{ route('cart') }}" class="hover:text-blue-600">Savatcha</a></li>
                <li><i class="fas fa-chevron-right text-xs"></i></li>
                <li class="text-gray-900">Buyurtma</li>
            </ol>
        </nav>

        <h1 class="text-3xl font-bold mb-8">Buyurtma rasmiylashtirish</h1>

        <form action="{{ route('orders.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Left: Forms --}}
                <div class="lg:col-span-2 space-y-6">
                    {{-- Shipping Address --}}
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-xl font-bold mb-6">Yetkazib berish manzili</h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium mb-2">To'liq ism *</label>
                                <input type="text"
                                       name="shipping_address[full_name]"
                                       value="{{ old('shipping_address.full_name', Auth::user()->name) }}"
                                       required
                                       class="w-full border rounded px-3 py-2 @error('shipping_address.full_name') border-red-500 @enderror">
                                @error('shipping_address.full_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-2">Telefon *</label>
                                <input type="tel"
                                       name="shipping_address[phone]"
                                       value="{{ old('shipping_address.phone', Auth::user()->phone) }}"
                                       placeholder="+998 90 123 45 67"
                                       required
                                       class="w-full border rounded px-3 py-2 @error('shipping_address.phone') border-red-500 @enderror">
                                @error('shipping_address.phone')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-2">Viloyat *</label>
                                <select name="shipping_address[region]"
                                        required
                                        class="w-full border rounded px-3 py-2 @error('shipping_address.region') border-red-500 @enderror">
                                    <option value="">Tanlang</option>
                                    <option value="Toshkent shahri" {{ old('shipping_address.region') == 'Toshkent shahri' ? 'selected' : '' }}>Toshkent shahri</option>
                                    <option value="Toshkent viloyati" {{ old('shipping_address.region') == 'Toshkent viloyati' ? 'selected' : '' }}>Toshkent viloyati</option>
                                    <option value="Andijon" {{ old('shipping_address.region') == 'Andijon' ? 'selected' : '' }}>Andijon</option>
                                    <option value="Buxoro" {{ old('shipping_address.region') == 'Buxoro' ? 'selected' : '' }}>Buxoro</option>
                                    <option value="Farg'ona" {{ old('shipping_address.region') == "Farg'ona" ? 'selected' : '' }}>Farg'ona</option>
                                    <option value="Jizzax" {{ old('shipping_address.region') == 'Jizzax' ? 'selected' : '' }}>Jizzax</option>
                                    <option value="Xorazm" {{ old('shipping_address.region') == 'Xorazm' ? 'selected' : '' }}>Xorazm</option>
                                    <option value="Namangan" {{ old('shipping_address.region') == 'Namangan' ? 'selected' : '' }}>Namangan</option>
                                    <option value="Navoiy" {{ old('shipping_address.region') == 'Navoiy' ? 'selected' : '' }}>Navoiy</option>
                                    <option value="Qashqadaryo" {{ old('shipping_address.region') == 'Qashqadaryo' ? 'selected' : '' }}>Qashqadaryo</option>
                                    <option value="Qoraqalpog'iston" {{ old('shipping_address.region') == "Qoraqalpog'iston" ? 'selected' : '' }}>Qoraqalpog'iston</option>
                                    <option value="Samarqand" {{ old('shipping_address.region') == 'Samarqand' ? 'selected' : '' }}>Samarqand</option>
                                    <option value="Sirdaryo" {{ old('shipping_address.region') == 'Sirdaryo' ? 'selected' : '' }}>Sirdaryo</option>
                                    <option value="Surxondaryo" {{ old('shipping_address.region') == 'Surxondaryo' ? 'selected' : '' }}>Surxondaryo</option>
                                </select>
                                @error('shipping_address.region')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-2">Tuman *</label>
                                <input type="text"
                                       name="shipping_address[district]"
                                       value="{{ old('shipping_address.district') }}"
                                       required
                                       class="w-full border rounded px-3 py-2 @error('shipping_address.district') border-red-500 @enderror">
                                @error('shipping_address.district')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium mb-2">Manzil *</label>
                                <textarea name="shipping_address[address]"
                                          rows="3"
                                          required
                                          placeholder="Ko'cha, uy raqami, kvartira..."
                                          class="w-full border rounded px-3 py-2 @error('shipping_address.address') border-red-500 @enderror">{{ old('shipping_address.address') }}</textarea>
                                @error('shipping_address.address')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-2">Pochta indeksi</label>
                                <input type="text"
                                       name="shipping_address[postal_code]"
                                       value="{{ old('shipping_address.postal_code') }}"
                                       class="w-full border rounded px-3 py-2">
                            </div>
                        </div>
                    </div>

                    {{-- Payment Method --}}
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-xl font-bold mb-6">To'lov usuli</h2>

                        <div class="space-y-3">
                            <label class="flex items-center p-4 border rounded cursor-pointer hover:bg-gray-50 has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50">
                                <input type="radio" name="payment_method" value="cash" checked class="mr-3">
                                <div class="flex-1">
                                    <div class="font-semibold">Naqd pul</div>
                                    <div class="text-sm text-gray-500">Yetkazib berish vaqtida to'lash</div>
                                </div>
                                <i class="fas fa-money-bill-wave text-2xl text-green-600"></i>
                            </label>

                            <label class="flex items-center p-4 border rounded cursor-pointer hover:bg-gray-50 has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50">
                                <input type="radio" name="payment_method" value="card" class="mr-3">
                                <div class="flex-1">
                                    <div class="font-semibold">Plastik karta</div>
                                    <div class="text-sm text-gray-500">Uzcard, Humo</div>
                                </div>
                                <div class="flex gap-2">
                                    <img src="/images/payment/uzcard.png" alt="Uzcard" class="h-6">
                                    <img src="/images/payment/humo.png" alt="Humo" class="h-6">
                                </div>
                            </label>

                            <label class="flex items-center p-4 border rounded cursor-pointer hover:bg-gray-50 has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50">
                                <input type="radio" name="payment_method" value="payme" class="mr-3">
                                <div class="flex-1">
                                    <div class="font-semibold">Payme</div>
                                    <div class="text-sm text-gray-500">Mobil to'lov tizimi</div>
                                </div>
                                <img src="/images/payment/payme.png" alt="Payme" class="h-8">
                            </label>

                            <label class="flex items-center p-4 border rounded cursor-pointer hover:bg-gray-50 has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50">
                                <input type="radio" name="payment_method" value="click" class="mr-3">
                                <div class="flex-1">
                                    <div class="font-semibold">Click</div>
                                    <div class="text-sm text-gray-500">Mobil to'lov tizimi</div>
                                </div>
                                <img src="/images/payment/click.png" alt="Click" class="h-8">
                            </label>

                            <label class="flex items-center p-4 border rounded cursor-pointer hover:bg-gray-50 has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50">
                                <input type="radio" name="payment_method" value="uzum" class="mr-3">
                                <div class="flex-1">
                                    <div class="font-semibold">Uzum</div>
                                    <div class="text-sm text-gray-500">Bo'lib to'lash</div>
                                </div>
                                <img src="/images/payment/uzum.png" alt="Uzum" class="h-8">
                            </label>
                        </div>
                    </div>

                    {{-- Order Notes --}}
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-xl font-bold mb-6">Qo'shimcha ma'lumot</h2>
                        <label class="block text-sm font-medium mb-2">Izohlar (ixtiyoriy)</label>
                        <textarea name="notes"
                                  rows="4"
                                  placeholder="Buyurtma haqida qo'shimcha ma'lumot..."
                                  class="w-full border rounded px-3 py-2">{{ old('notes') }}</textarea>
                    </div>
                </div>

                {{-- Right: Order Summary --}}
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow p-6 sticky top-24">
                        <h2 class="text-xl font-bold mb-6">Buyurtma xulosasi</h2>

                        {{-- Cart Items --}}
                        <div class="space-y-3 mb-6 max-h-64 overflow-y-auto">
                            @foreach($cartItems as $item)
                                <div class="flex gap-3 pb-3 border-b">
                                    <img src="{{ $item->product->primary_image }}"
                                         alt="{{ $item->product->name }}"
                                         class="w-16 h-16 object-cover rounded">
                                    <div class="flex-1">
                                        <p class="text-sm font-medium line-clamp-2">{{ $item->product->name }}</p>
                                        @if($item->variant)
                                            <p class="text-xs text-gray-500">{{ $item->variant->name }}</p>
                                        @endif
                                        <p class="text-xs text-gray-500">{{ $item->quantity }} x {{ number_format($item->variant ? $item->variant->price : $item->product->final_price) }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Summary --}}
                        <div class="space-y-3 mb-6 pb-6 border-b">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Oraliq summa:</span>
                                <span class="font-semibold">{{ number_format($subtotal) }} so'm</span>
                            </div>

                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Yetkazib berish:</span>
                                <span class="font-semibold">{{ number_format($shippingCost) }} so'm</span>
                            </div>

                            @if($discount > 0)
                                <div class="flex justify-between text-sm text-green-600">
                                    <span>Chegirma:</span>
                                    <span class="font-semibold">-{{ number_format($discount) }} so'm</span>
                                </div>
                            @endif
                        </div>

                        {{-- Total --}}
                        <div class="mb-6">
                            <div class="flex justify-between text-xl">
                                <span class="font-bold">Jami:</span>
                                <span class="font-bold text-blue-600">{{ number_format($total) }} so'm</span>
                            </div>
                        </div>

                        {{-- Terms --}}
                        <div class="mb-6">
                            <label class="flex items-start gap-2 cursor-pointer">
                                <input type="checkbox" required class="mt-1">
                                <span class="text-sm text-gray-600">
                                Men <a href="#" class="text-blue-600 hover:underline">foydalanish shartlari</a> va
                                <a href="#" class="text-blue-600 hover:underline">maxfiylik siyosati</a> bilan tanishdim va roziman
                            </span>
                            </label>
                        </div>

                        {{-- Submit --}}
                        <button type="submit"
                                class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 font-semibold">
                            Buyurtma berish
                        </button>

                        <a href="{{ route('cart') }}"
                           class="block w-full text-center mt-3 py-2 text-gray-600 hover:text-gray-800">
                            <i class="fas fa-arrow-left mr-1"></i> Savatchaga qaytish
                        </a>

                        {{-- Safe Checkout --}}
                        <div class="mt-6 pt-6 border-t">
                            <div class="flex items-center justify-center gap-2 text-sm text-gray-500">
                                <i class="fas fa-lock"></i>
                                <span>Xavfsiz buyurtma</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
