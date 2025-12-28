{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        {{-- Statistika kartochkalari --}}
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-full">
                    <i class="fas fa-box text-blue-600 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-gray-500 text-sm">Mahsulotlar</p>
                    <p class="text-2xl font-bold">{{ $totalProducts }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-full">
                    <i class="fas fa-shopping-cart text-green-600 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-gray-500 text-sm">Buyurtmalar</p>
                    <p class="text-2xl font-bold">{{ $totalOrders }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-full">
                    <i class="fas fa-users text-yellow-600 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-gray-500 text-sm">Foydalanuvchilar</p>
                    <p class="text-2xl font-bold">{{ $totalUsers }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-red-100 rounded-full">
                    <i class="fas fa-dollar-sign text-red-600 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-gray-500 text-sm">Umumiy sotuvlar</p>
                    <p class="text-2xl font-bold">{{ number_format($totalSales) }} so'm</p>
                </div>
            </div>
        </div>
    </div>

    {{-- So'nggi buyurtmalar --}}
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b">
            <h3 class="text-lg font-semibold">So'nggi buyurtmalar</h3>
        </div>
        <div class="p-6">
            <table class="w-full">
                <thead>
                <tr class="text-left text-gray-500 text-sm">
                    <th class="pb-3">Buyurtma #</th>
                    <th class="pb-3">Mijoz</th>
                    <th class="pb-3">Summa</th>
                    <th class="pb-3">Holat</th>
                    <th class="pb-3">Sana</th>
                </tr>
                </thead>
                <tbody>
                @foreach($recentOrders as $order)
                    <tr class="border-t">
                        <td class="py-3">#{{ $order->order_number }}</td>
                        <td class="py-3">{{ $order->user->name }}</td>
                        <td class="py-3">{{ number_format($order->total) }} so'm</td>
                        <td class="py-3">
                        <span class="px-2 py-1 text-xs rounded-full
                            @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                            @elseif($order->status == 'delivered') bg-green-100 text-green-800
                            @else bg-blue-100 text-blue-800
                            @endif">
                            {{ $order->status }}
                        </span>
                        </td>
                        <td class="py-3">{{ $order->created_at->format('d.m.Y') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
