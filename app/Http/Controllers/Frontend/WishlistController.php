<?php
// app/Http/Controllers/Frontend/WishlistController.php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlists = Wishlist::where('user_id', Auth::id())
            ->with(['product.images', 'product.brand', 'product.category'])
            ->latest()
            ->get();

        return view('frontend.wishlist.index', compact('wishlists'));
    }

    public function toggle(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $wishlist = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $validated['product_id'])
            ->first();

        if ($wishlist) {
            $wishlist->delete();
            return response()->json([
                'success' => true,
                'message' => 'Mahsulot sevimlilardan o\'chirildi',
                'in_wishlist' => false
            ]);
        } else {
            Wishlist::create([
                'user_id' => Auth::id(),
                'product_id' => $validated['product_id']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Mahsulot sevimlilarga qo\'shildi',
                'in_wishlist' => true
            ]);
        }
    }

    public function destroy($id)
    {
        Wishlist::where('id', $id)
            ->where('user_id', Auth::id())
            ->delete();

        return redirect()->back()->with('success', 'Mahsulot o\'chirildi');
    }
}
