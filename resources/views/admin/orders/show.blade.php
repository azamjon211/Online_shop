{{-- resources/views/admin/orders/show.blade.php --}}
@extends('layouts.admin')

@section('title', 'Buyurtma tafsilotlari')
@section('page-title', 'Buyurtma #' . $order->order_number)

@section('content')
    <div class="grid grid-cols-3 gap-6">
        {{-- Order Items --}}
        <div class="col-span-2 space-y-6">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Buyurtma mahsulotlari</h3>

                <table class="w-full">
                    <thead>
                    <tr class="text-left text-gray-500 text-sm border-b">
                        <th class="pb-3">Mahsulot</th>
                        <th class="pb-3">Narx</th>
                        <th class="pb-3">Miqdor</th>
                        <th class="pb-3">Jami</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($order->items as $item)
                        <tr class="border-b">
                            <td class="py-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $item->product->primary_image }}"
                                         alt="{{ $item->product_name }}"
                                         class="w-16 h-16 object-cover rounded">
                                    <div>
                                        <div class="font-medium">{{ $item->product_name }}</div>
                                        @if($item->variant)
                                            <div class="text-sm text-gray-500">{{ $item->variant->name }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="py-4">{{ number_format($item->price) }} so'm</td>
                            <td class="py-4">{{ $item->quantity }}</td>
                            <td class="py-4 font-semibold">{{ number_format($item->subtotal) }} so'm</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Shipping Address --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Yetkazib berish manzili</h3>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">To'liq ism</p>
                        <p class="font-medium">{{ $order->shippingAddress->full_name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Telefon</p>
                        <p class="font-medium">{{ $order->shippingAddress->phone }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Viloyat</p>
                        <p class="font-medium">{{ $order->shippingAddress->region }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Tuman</p>
                        <p class="font-medium">{{ $order->shippingAddress->district }}</p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-sm text-gray-500">Manzil</p>
                        <p class="font-medium">{{ $order->shippingAddress->address }}</p>
                    </div>
                </div>
            </div>

            @if($order->notes)
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold mb-4">Izohlar</h3>
                    <p class="text-gray-700">{{ $order->notes }}</p>
                </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Order Summary --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Buyurtma ma'lumotlari</h3>

                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Oraliq summa:</span>
                        <span class="font-medium">{{ number_format($order->subtotal) }} so'm</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Yetkazib berish:</span>
                        <span class="font-medium">{{ number_format($order->shipping_cost) }} so'm</span>
                    </div>
                    @if($order->discount > 0)
                        <div class="flex justify-between text-green-600">
                            <span>Chegirma:</span>
                            <span class="font-medium">-{{ number_format($order->discount) }} so'm</span>
                        </div>
                    @endif
                    <div class="flex justify-between pt-3 border-t text-lg">
                        <span class="font-semibold">Jami:</span>
                        <span class="font-bold text-blue-600">{{ number_format($order->total) }} so'm</span>
                    </div>
                </div>
            </div>

            {{-- Status --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Holat</h3>

                <form action="{{ route('admin.orders.update-status', $order->order_number) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-2">Buyurtma holati</label>
                        <select name="status" class="w-full border rounded px-3 py-2">
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Kutilmoqda</option>
                            <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>Tasdiqlangan</option>
                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Jarayonda</option>
                            <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Yuborilgan</option>
                            <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Yetkazilgan</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Bekor qilingan</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-2">To'lov holati</label>
                        <select name="payment_status" class="w-full border rounded px-3 py-2">
                            <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>Kutilmoqda</option>
                            <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>To'langan</option>
                            <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>Muvaffaqiyatsiz</option>
                            <option value="refunded" {{ $order->payment_status == 'refunded' ? 'selected' : '' }}>Qaytarilgan</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Yangilash
                    </button>
                </form>
            </div>

            {{-- Customer Info --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Mijoz</h3>

                <div class="space-y-2 text-sm">
                    <p class="font-medium">{{ $order->user->name }}</p>
                    <p class="text-gray-500">{{ $order->user->email }}</p>
                    @if($order->user->phone)
                        <p class="text-gray-500">{{ $order->user->phone }}</p>
                    @endif
                </div>
            </div>

            {{-- Payment Method --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">To'lov usuli</h3>
                <p class="text-sm font-medium">{{ ucfirst($order->payment_method) }}</p>
            </div>

            {{-- Order Date --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Sana</h3>
                <p class="text-sm">{{ $order->created_at->format('d.m.Y H:i') }}</p>
            </div>
        </div>
    </div>
@endsection
