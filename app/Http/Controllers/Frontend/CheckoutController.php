<?php
// app/Http/Controllers/Frontend/CheckoutController.php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function index()
    {
        $cartItems = CartItem::where('user_id', Auth::id())
            ->with(['product.images', 'variant'])
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Savatcha bo\'sh');
        }

        $subtotal = $cartItems->sum(function($item) {
            $price = $item->variant ? $item->variant->price : $item->product->final_price;
            return $price * $item->quantity;
        });

        $shippingCost = 30000;
        $discount = 0;
        $total = $subtotal + $shippingCost - $discount;

        return view('frontend.checkout', compact('cartItems', 'subtotal', 'shippingCost', 'discount', 'total'));
    }
}
