<?php
// app/Http/Controllers/Frontend/CartController.php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = $this->getCartItems();

        $subtotal = $cartItems->sum(function($item) {
            $price = $item->variant ? $item->variant->price : $item->product->final_price;
            return $price * $item->quantity;
        });

        $shippingCost = 30000; // 30,000 so'm
        $discount = 0;
        $total = $subtotal + $shippingCost - $discount;

        return view('frontend.cart', compact('cartItems', 'subtotal', 'shippingCost', 'discount', 'total'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'product_variant_id' => 'nullable|exists:product_variants,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($validated['product_id']);

        // Check stock
        $stock = isset($validated['product_variant_id'])
            ? $product->variants()->find($validated['product_variant_id'])->stock
            : $product->stock;

        if ($stock < $validated['quantity']) {
            return response()->json([
                'success' => false,
                'message' => 'Omborda yetarli mahsulot yo\'q'
            ], 400);
        }

        // Check if already in cart
        $cartItem = CartItem::where('product_id', $validated['product_id'])
            ->where('product_variant_id', $validated['product_variant_id'] ?? null)
            ->where(function($query) {
                if (Auth::check()) {
                    $query->where('user_id', Auth::id());
                } else {
                    $query->where('session_id', session()->getId());
                }
            })
            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity', $validated['quantity']);
        } else {
            CartItem::create([
                'user_id' => Auth::id(),
                'session_id' => Auth::check() ? null : session()->getId(),
                'product_id' => $validated['product_id'],
                'product_variant_id' => $validated['product_variant_id'] ?? null,
                'quantity' => $validated['quantity']
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Mahsulot savatchaga qo\'shildi'
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cartItem = CartItem::where('id', $id)
            ->where(function($query) {
                if (Auth::check()) {
                    $query->where('user_id', Auth::id());
                } else {
                    $query->where('session_id', session()->getId());
                }
            })
            ->firstOrFail();

        $stock = $cartItem->variant ? $cartItem->variant->stock : $cartItem->product->stock;

        if ($stock < $validated['quantity']) {
            return response()->json([
                'success' => false,
                'message' => 'Omborda yetarli mahsulot yo\'q'
            ], 400);
        }

        $cartItem->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Savatcha yangilandi'
        ]);
    }

    public function destroy($id)
    {
        CartItem::where('id', $id)
            ->where(function($query) {
                if (Auth::check()) {
                    $query->where('user_id', Auth::id());
                } else {
                    $query->where('session_id', session()->getId());
                }
            })
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Mahsulot o\'chirildi'
        ]);
    }

    public function clear()
    {
        CartItem::where(function($query) {
            if (Auth::check()) {
                $query->where('user_id', Auth::id());
            } else {
                $query->where('session_id', session()->getId());
            }
        })->delete();

        return response()->json([
            'success' => true,
            'message' => 'Savatcha tozalandi'
        ]);
    }

    private function getCartItems()
    {
        $query = CartItem::with(['product.images', 'variant']);

        if (Auth::check()) {
            $query->where('user_id', Auth::id());
        } else {
            $query->where('session_id', session()->getId());
        }

        return $query->get();
    }
}
