<?php
// app/Http/Controllers/Api/CartController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // GET /api/cart
    public function index(Request $request)
    {
        $cartItems = $this->getCartItems($request);

        $cart = $cartItems->map(function($item) {
            $product = $item->product;
            $variant = $item->variant;

            $price = $variant ? $variant->price : $product->final_price;

            return [
                'id' => $item->id,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'product_slug' => $product->slug,
                'product_image' => $product->primary_image,
                'variant_id' => $variant ? $variant->id : null,
                'variant_name' => $variant ? $variant->name : null,
                'price' => $price,
                'quantity' => $item->quantity,
                'subtotal' => $price * $item->quantity,
                'stock' => $variant ? $variant->stock : $product->stock,
                'in_stock' => $variant ? $variant->in_stock : $product->in_stock
            ];
        });

        $total = $cart->sum('subtotal');

        return response()->json([
            'success' => true,
            'data' => [
                'items' => $cart,
                'total' => $total,
                'items_count' => $cart->count()
            ]
        ]);
    }

    // POST /api/cart
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'product_variant_id' => 'nullable|exists:product_variants,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($validated['product_id']);

        // Stock tekshirish
        if (isset($validated['product_variant_id'])) {
            $variant = ProductVariant::findOrFail($validated['product_variant_id']);
            if ($variant->stock < $validated['quantity']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Omborda yetarli mahsulot yo\'q'
                ], 400);
            }
        } else {
            if ($product->stock < $validated['quantity']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Omborda yetarli mahsulot yo\'q'
                ], 400);
            }
        }

        // Savatchada bor-yo'qligini tekshirish
        $cartItem = CartItem::where('user_id', Auth::id())
            ->orWhere('session_id', session()->getId())
            ->where('product_id', $validated['product_id'])
            ->where('product_variant_id', $validated['product_variant_id'] ?? null)
            ->first();

        if ($cartItem) {
            // Mavjud bo'lsa miqdorni oshirish
            $cartItem->quantity += $validated['quantity'];
            $cartItem->save();
        } else {
            // Yangi qo'shish
            $cartItem = CartItem::create([
                'user_id' => Auth::id(),
                'session_id' => Auth::check() ? null : session()->getId(),
                'product_id' => $validated['product_id'],
                'product_variant_id' => $validated['product_variant_id'] ?? null,
                'quantity' => $validated['quantity']
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Mahsulot savatchaga qo\'shildi',
            'data' => $cartItem->load(['product', 'variant'])
        ], 201);
    }

    // PUT /api/cart/{id}
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cartItem = CartItem::where('id', $id)
            ->where(function($query) {
                $query->where('user_id', Auth::id())
                    ->orWhere('session_id', session()->getId());
            })
            ->firstOrFail();

        // Stock tekshirish
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
            'message' => 'Savatcha yangilandi',
            'data' => $cartItem->load(['product', 'variant'])
        ]);
    }

    // DELETE /api/cart/{id}
    public function destroy($id)
    {
        $cartItem = CartItem::where('id', $id)
            ->where(function($query) {
                $query->where('user_id', Auth::id())
                    ->orWhere('session_id', session()->getId());
            })
            ->firstOrFail();

        $cartItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Mahsulot savatchadan o\'chirildi'
        ]);
    }

    // DELETE /api/cart/clear
    public function clear(Request $request)
    {
        CartItem::where('user_id', Auth::id())
            ->orWhere('session_id', session()->getId())
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Savatcha tozalandi'
        ]);
    }

    // Helper method
    private function getCartItems($request)
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
