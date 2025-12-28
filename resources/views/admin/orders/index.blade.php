{{-- resources/views/admin/orders/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Buyurtmalar')
@section('page-title', 'Buyurtmalar')

@section('content')
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b">
            <div class="flex gap-4">
                <input type="text" placeholder="Qidirish..." class="border rounded px-4 py-2 w-64">
                <select class="border rounded px-4 py-2">
                    <option value="">Barcha holatlar</option>
                    <option value="pending">Kutilmoqda</option>
                    <option value="confirmed">Tasdiqlangan</option>
                    <option value="processing">Jarayonda</option>
                    <option value="shipped">Yuborilgan</option>
                    <option value="delivered">Yetkazilgan</option>
                    <option value="cancelled">Bekor qilingan</option>
                </select>
                <select class="border rounded px-4 py-2">
                    <option value="">To'lov holati</option>
                    <option value="pending">Kutilmoqda</option>
                    <option value="paid">To'langan</option>
                    <option value="failed">Muvaffaqiyatsiz</option>
                    <option value="refunded">Qaytarilgan</option>
                </select>
            </div>
        </div>

        <div class="p-6">
            <table class="w-full">
                <thead>
                <tr class="text-left text-gray-500 text-sm border-b">
                    <th class="pb-3">Buyurtma #</th>
                    <th class="pb-3">Mijoz</th>
                    <th class="pb-3">Mahsulotlar</th>
                    <th class="pb-3">Summa</th>
                    <th class="pb-3">To'lov</th>
                    <th class="pb-3">Holat</th>
                    <th class="pb-3">Sana</th>
                    <th class="pb-3">Amallar</th>
                </tr>
                </thead>
                <tbody>
                @foreach($orders as $order)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="py-4">
                            <a href="{{ route('admin.orders.show', $order->order_number) }}"
                               class="font-medium text-blue-600 hover:underline">
                                #{{ $order->order_number }}
                            </a>
                        </td>
                        <td class="py-4">
                            <div class="font-medium">{{ $order->user->name }}</div>
                            <div class="text-xs text-gray-500">{{ $order->user->email }}</div>
                        </td>
                        <td class="py-4">{{ $order->items->count() }} ta</td>
                        <td class="py-4 font-semibold">{{ number_format($order->total) }} so'm</td>
                        <td class="py-4">
                        <span class="px-2 py-1 text-xs rounded-full
                            @if($order->payment_status == 'paid') bg-green-100 text-green-800
                            @elseif($order->payment_status == 'failed') bg-red-100 text-red-800
                            @else bg-yellow-100 text-yellow-800
                            @endif">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                            <div class="text-xs text-gray-500 mt-1">{{ ucfirst($order->payment_method) }}</div>
                        </td>
                        <td class="py-4">
                            <select onchange="updateOrderStatus({{ $order->id }}, this.value)"
                                    class="text-xs rounded px-2 py-1 border
                                @if($order->status == 'pending') bg-yellow-50
                                @elseif($order->status == 'delivered') bg-green-50
                                @elseif($order->status == 'cancelled') bg-red-50
                                @else bg-blue-50
                                @endif">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Kutilmoqda</option>
                                <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>Tasdiqlangan</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Jarayonda</option>
                                <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Yuborilgan</option>
                                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Yetkazilgan</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Bekor qilingan</option>
                            </select>
                        </td>
                        <td class="py-4 text-sm">
                            {{ $order->created_at->format('d.m.Y H:i') }}
                        </td>
                        <td class="py-4">
                            <a href="{{ route('admin.orders.show', $order->order_number) }}"
                               class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <div class="mt-4">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function updateOrderStatus(orderId, status) {
            if (!confirm('Buyurtma holatini o\'zgartirmoqchimisiz?')) {
                location.reload();
                return;
            }

            fetch(`/admin/orders/${orderId}/status`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ status: status })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                });
        }
    </script>
@endpush
