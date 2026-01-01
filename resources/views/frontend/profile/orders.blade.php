{{-- resources/views/frontend/profile/orders.blade.php --}}
@extends('frontend.profile.layout')

@section('title', 'Buyurtmalarim')

@section('profile-content')
    <div class="bg-white rounded-lg shadow-md">
        {{-- Header --}}
        <div class="border-b px-6 py-4">
            <h2 class="text-2xl font-bold">Buyurtmalarim</h2>
        </div>

        {{-- Orders List --}}
        <div class="p-6">
            @if($orders->count() > 0)
                <div class="space-y-4">
                    @foreach($orders as $order)
                        <div class="border rounded-lg p-4 hover:shadow-md transition">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <h3 class="font-bold text-lg">Buyurtma #{{ $order->order_number }}</h3>
                                    <p class="text-sm text-gray-500">{{ $order->created_at->format('d.m.Y H:i') }}</p>
                                </div>
                                <div class="text-right">
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'confirmed' => 'bg-blue-100 text-blue-800',
                                            'processing' => 'bg-purple-100 text-purple-800',
                                            'shipped' => 'bg-indigo-100 text-indigo-800',
                                            'delivered' => 'bg-green-100 text-green-800',
                                            'cancelled' => 'bg-red-100 text-red-800',
                                        ];
                                        $statusNames = [
                                            'pending' => 'Kutilmoqda',
                                            'confirmed' => 'Tasdiqlangan',
                                            'processing' => 'Tayyorlanmoqda',
                                            'shipped' => 'Yo\'lda',
                                            'delivered' => 'Yetkazildi',
                                            'cancelled' => 'Bekor qilindi',
                                        ];
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-xs font-medium {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $statusNames[$order->status] ?? $order->status }}
                                </span>
                                </div>
                            </div>

                            {{-- Order Items --}}
                            <div class="flex gap-3 mb-3 overflow-x-auto">
                                @foreach($order->items->take(3) as $item)
                                    @if($item->product)
                                        <img src="{{ $item->product->primary_image }}"
                                             alt="{{ $item->product_name }}"
                                             class="w-16 h-16 object-cover rounded">
                                    @endif
                                @endforeach
                                @if($order->items->count() > 3)
                                    <div class="w-16 h-16 bg-gray-100 rounded flex items-center justify-center">
                                        <span class="text-sm text-gray-600">+{{ $order->items->count() - 3 }}</span>
                                    </div>
                                @endif
                            </div>

                            {{-- Order Total --}}
                            <div class="flex justify-between items-center">
                                <div>
                                    <span class="text-gray-600">Jami:</span>
                                    <span class="font-bold text-lg ml-2">{{ number_format($order->total) }} so'm</span>
                                </div>
                                <a href="{{ route('profile.orders.show', $order->order_number) }}"
                                   class="text-blue-600 hover:text-blue-700 font-medium">
                                    Batafsil <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-6">
                    {{ $orders->links() }}
                </div>
            @else
                {{-- Empty State --}}
                <div class="text-center py-12">
                    <i class="fas fa-shopping-bag text-gray-300 text-6xl mb-4"></i>
                    <h3 class="text-xl font-bold mb-2">Buyurtmalar yo'q</h3>
                    <p class="text-gray-500 mb-4">Siz hali hech qanday buyurtma bermagansiz</p>
                    <a href="{{ route('products.index') }}" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-shopping-cart mr-2"></i>
                        Xarid qilishni boshlash
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
