<?php
// app/Http/Controllers/Admin/AdminOrderController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'items'])
            ->latest()
            ->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    public function show($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->with(['user', 'items.product.images', 'items.variant', 'shippingAddress'])
            ->firstOrFail();

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)->firstOrFail();

        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled',
            'payment_status' => 'nullable|in:pending,paid,failed,refunded'
        ]);

        $order->update($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Holat yangilandi'
            ]);
        }

        return redirect()->back()->with('success', 'Holat yangilandi');
    }
}
