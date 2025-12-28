<?php
// app/Http/Controllers/Api/WishlistController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    // GET /api/wishlist
    public function index()
    {
        $wishlist = Wishlist::where('user_id', Auth::id())
            ->with(['product.images', 'product.brand', 'product.category'])
            ->latest()
            ->get();

        $products = $wishlist->map(function($item) {
            return [
                'wishlist_id' => $item->id,
                'product' => $item->product,
                'added_at' => $item->created_at
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    // POST /api/wishlist/toggle
    public function toggle(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $wishlistItem = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $validated['product_id'])
            ->first();

        if ($wishlistItem) {
            // Mavjud bo'lsa o'chirish
            $wishlistItem->delete();
            return response()->json([
                'success' => true,
                'message' => 'Mahsulot sevimlilardan o\'chirildi',
                'in_wishlist' => false
            ]);
        } else {
            // Yo'q bo'lsa qo'shish
            $wishlistItem = Wishlist::create([
                'user_id' => Auth::id(),
                'product_id' => $validated['product_id']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Mahsulot sevimlilarga qo\'shildi',
                'in_wishlist' => true,
                'data' => $wishlistItem->load('product')
            ], 201);
        }
    }

    // POST /api/wishlist
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        // Allaqachon bor-yo'qligini tekshirish
        $exists = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $validated['product_id'])
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Mahsulot allaqachon sevimlilarda'
            ], 400);
        }

        $wishlistItem = Wishlist::create([
            'user_id' => Auth::id(),
            'product_id' => $validated['product_id']
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Mahsulot sevimlilarga qo\'shildi',
            'data' => $wishlistItem->load('product')
        ], 201);
    }

    // DELETE /api/wishlist/{id}
    public function destroy($id)
    {
        $wishlistItem = Wishlist::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $wishlistItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Mahsulot sevimlilardan o\'chirildi'
        ]);
    }

    // DELETE /api/wishlist/clear
    public function clear()
    {
        Wishlist::where('user_id', Auth::id())->delete();

        return response()->json([
            'success' => true,
            'message' => 'Barcha sevimlilar o\'chirildi'
        ]);
    }

    // GET /api/wishlist/check/{productId}
    public function check($productId)
    {
        $exists = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->exists();

        return response()->json([
            'success' => true,
            'in_wishlist' => $exists
        ]);
    }
}
