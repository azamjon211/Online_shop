<?php
// app/Http/Controllers/Api/OrderController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ShippingAddress;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // GET /api/orders
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with(['items.product', 'shippingAddress'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }

    // GET /api/orders/{orderNumber}
    public function show($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->with(['items.product.images', 'items.variant', 'shippingAddress'])
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => $order
        ]);
    }

    // POST /api/orders
    public function store(Request $request)
    {
        $validated = $request->validate([
            'payment_method' => 'required|in:cash,card,payme,click,uzum',
            'notes' => 'nullable|string',
            'shipping_address' => 'required|array',
            'shipping_address.full_name' => 'required|string',
            'shipping_address.phone' => 'required|string',
            'shipping_address.region' => 'required|string',
            'shipping_address.district' => 'required|string',
            'shipping_address.address' => 'required|string',
            'shipping_address.postal_code' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Savatchadagi mahsulotlarni olish
            $cartItems = CartItem::where('user_id', Auth::id())
                ->with(['product', 'variant'])
                ->get();

            if ($cartItems->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Savatcha bo\'sh'
                ], 400);
            }

            // Narxlarni hisoblash
            $subtotal = 0;
            foreach ($cartItems as $item) {
                $price = $item->variant ? $item->variant->price : $item->product->final_price;
                $subtotal += $price * $item->quantity;

                // Stock tekshirish
                $stock = $item->variant ? $item->variant->stock : $item->product->stock;
                if ($stock < $item->quantity) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Mahsulot: ' . $item->product->name . ' - omborda yetarli emas'
                    ], 400);
                }
            }

            $shippingCost = 30000; // 30,000 so'm
            $tax = 0;
            $discount = 0;
            $total = $subtotal + $shippingCost + $tax - $discount;

            // Buyurtma yaratish
            $order = Order::create([
                'user_id' => Auth::id(),
                'subtotal' => $subtotal,
                'tax' => $tax,
                'shipping_cost' => $shippingCost,
                'discount' => $discount,
                'total' => $total,
                'payment_method' => $validated['payment_method'],
                'notes' => $validated['notes'] ?? null,
                'status' => 'pending',
                'payment_status' => 'pending'
            ]);

            // Buyurtma mahsulotlarini yaratish
            foreach ($cartItems as $item) {
                $price = $item->variant ? $item->variant->price : $item->product->final_price;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'product_variant_id' => $item->product_variant_id,
                    'product_name' => $item->product->name,
                    'price' => $price,
                    'quantity' => $item->quantity,
                    'subtotal' => $price * $item->quantity
                ]);

                // Stockni kamaytirish
                if ($item->variant) {
                    $item->variant->decrement('stock', $item->quantity);
                } else {
                    $item->product->decrement('stock', $item->quantity);
                }

                // Sotish statistikasini oshirish
                $item->product->increment('sales_count', $item->quantity);
            }

            // Yetkazib berish manzilini saqlash
            ShippingAddress::create([
                'order_id' => $order->id,
                'full_name' => $validated['shipping_address']['full_name'],
                'phone' => $validated['shipping_address']['phone'],
                'region' => $validated['shipping_address']['region'],
                'district' => $validated['shipping_address']['district'],
                'address' => $validated['shipping_address']['address'],
                'postal_code' => $validated['shipping_address']['postal_code'] ?? null
            ]);

            // Savatchani tozalash
            CartItem::where('user_id', Auth::id())->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Buyurtma muvaffaqiyatli yaratildi',
                'data' => $order->load(['items.product', 'shippingAddress'])
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Xatolik yuz berdi: ' . $e->getMessage()
            ], 500);
        }
    }

    // PUT /api/orders/{orderNumber}/cancel
    public function cancel($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if (!in_array($order->status, ['pending', 'confirmed'])) {
            return response()->json([
                'success' => false,
                'message' => 'Bu buyurtmani bekor qilish mumkin emas'
            ], 400);
        }

        DB::beginTransaction();
        try {
            // Stockni qaytarish
            foreach ($order->items as $item) {
                if ($item->variant) {
                    $item->variant->increment('stock', $item->quantity);
                } else {
                    $item->product->increment('stock', $item->quantity);
                }
                $item->product->decrement('sales_count', $item->quantity);
            }

            $order->update(['status' => 'cancelled']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Buyurtma bekor qilindi',
                'data' => $order
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Xatolik yuz berdi: ' . $e->getMessage()
            ], 500);
        }
    }

    // PUT /api/orders/{orderNumber}/status (Admin uchun)
    public function updateStatus(Request $request, $orderNumber)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled',
            'payment_status' => 'nullable|in:pending,paid,failed,refunded'
        ]);

        $order = Order::where('order_number', $orderNumber)->firstOrFail();
        $order->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Buyurtma holati yangilandi',
            'data' => $order
        ]);
    }
}
