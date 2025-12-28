<?php
// app/Http/Controllers/Frontend/OrderController.php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ShippingAddress;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
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
            $cartItems = CartItem::where('user_id', Auth::id())
                ->with(['product', 'variant'])
                ->get();

            if ($cartItems->isEmpty()) {
                return redirect()->route('cart')->with('error', 'Savatcha bo\'sh');
            }

            // Calculate totals
            $subtotal = 0;
            foreach ($cartItems as $item) {
                $price = $item->variant ? $item->variant->price : $item->product->final_price;
                $subtotal += $price * $item->quantity;

                // Check stock
                $stock = $item->variant ? $item->variant->stock : $item->product->stock;
                if ($stock < $item->quantity) {
                    DB::rollBack();
                    return redirect()->route('cart')
                        ->with('error', 'Mahsulot: ' . $item->product->name . ' - omborda yetarli emas');
                }
            }

            $shippingCost = 30000;
            $tax = 0;
            $discount = 0;
            $total = $subtotal + $shippingCost + $tax - $discount;

            // Create order
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

            // Create order items
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

                // Update stock
                if ($item->variant) {
                    $item->variant->decrement('stock', $item->quantity);
                } else {
                    $item->product->decrement('stock', $item->quantity);
                }

                $item->product->increment('sales_count', $item->quantity);
            }

            // Create shipping address
            ShippingAddress::create([
                'order_id' => $order->id,
                'full_name' => $validated['shipping_address']['full_name'],
                'phone' => $validated['shipping_address']['phone'],
                'region' => $validated['shipping_address']['region'],
                'district' => $validated['shipping_address']['district'],
                'address' => $validated['shipping_address']['address'],
                'postal_code' => $validated['shipping_address']['postal_code'] ?? null
            ]);

            // Clear cart
            CartItem::where('user_id', Auth::id())->delete();

            DB::commit();

            return redirect()->route('profile.orders.show', $order->order_number)
                ->with('success', 'Buyurtma muvaffaqiyatli yaratildi!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('cart')
                ->with('error', 'Xatolik yuz berdi: ' . $e->getMessage());
        }
    }

    public function cancel($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if (!in_array($order->status, ['pending', 'confirmed'])) {
            return redirect()->back()
                ->with('error', 'Bu buyurtmani bekor qilish mumkin emas');
        }

        DB::beginTransaction();
        try {
            // Restore stock
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

            return redirect()->back()->with('success', 'Buyurtma bekor qilindi');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Xatolik yuz berdi');
        }
    }
}
