{{-- resources/views/frontend/profile/orders.blade.php --}}
@extends('layouts.app')

@section('title', 'Buyurtmalarim')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            {{-- Sidebar --}}
            @include('frontend.profile.sidebar')

            {{-- Main Content --}}
            <div class="lg:col-span-3">
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6 border-b">
                        <h1 class="text-2xl font-bold">Buyurtmalarim</h1>
                    </div>

                    @if($orders->count() > 0)
                        <div class="divide-y">
                            @foreach($orders as $order)
                                <div class="p-6 hover:bg-gray-50">
                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <a href="{{ route('profile.orders.show', $order->order_number) }}"
                                               class="text-lg font-semibold text-blue-600 hover:underline">
                                                Buyurtma #{{ $order->order_number }}
                                            </a>
                                            <p class="text-sm text-gray-500">{{ $order->created_at->format('d.m.Y H:i') }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-xl font-bold">{{ number_format($order->total) }} so'm</p>
                                            <span class="inline-block px-3 py-1 text-xs rounded-full mt-1
                                            @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($order->status == 'delivered') bg-green-100 text-green-800
                                            @elseif($order->status == 'cancelled') bg-red-100 text-red-800
                                            @else bg-blue-100 text-blue-800
                                            @endif">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                        </div>
                                    </div>

                                    {{-- Order Items Preview --}}
                                    <div class="grid grid-cols-4 gap-2 mb-4">
                                        @foreach($order->items->take(4) as $item)
                                            <img src="{{ $item->product->primary_image }}"
                                                 alt="{{ $item->product_name }}"
                                                 class="w-full h-20 object-cover rounded">
                                        @endforeach
                                    </div>

                                    <div class="flex gap-2">
                                        <a href="{{ route('profile.orders.show', $order->order_number) }}"
                                           class="px-4 py-2 border rounded hover:bg-gray-50">
                                            Tafsilotlar
                                        </a>
                                        @if(in_array($order->status, ['pending', 'confirmed']))
                                            <form action="{{ route('orders.cancel', $order->order_number) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Buyurtmani bekor qilmoqchimisiz?')">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="px-4 py-2 text-red-600 border border-red-600 rounded hover:bg-red-50">
                                                    Bekor qilish
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Pagination --}}
                        <div class="p-6">
                            {{ $orders->links() }}
                        </div>
                    @else
                        <div class="p-12 text-center">
                            <i class="fas fa-shopping-bag text-gray-300 text-6xl mb-4"></i>
                            <h3 class="text-xl font-semibold mb-2">Buyurtmalar yo'q</h3>
                            <p class="text-gray-500 mb-6">Siz hali hech qanday buyurtma bermagansiz</p>
                            <a href="{{ route('products.index') }}"
                               class="inline-block bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                                Xarid qilish
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
