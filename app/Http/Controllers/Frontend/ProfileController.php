<?php
// app/Http/Controllers/Frontend/ProfileController.php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function orders()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with(['items.product'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('frontend.profile.orders', compact('orders'));
    }

    public function showOrder($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->with(['items.product.images', 'items.variant', 'shippingAddress'])
            ->firstOrFail();

        return view('frontend.profile.order-detail', compact('order'));
    }

    public function settings()
    {
        $user = Auth::user();
        return view('frontend.profile.settings', compact('user'));
    }

    public function updateSettings(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'current_password' => 'nullable|required_with:password',
            'password' => 'nullable|min:8|confirmed',
        ]);

        if (isset($validated['current_password'])) {
            if (!Hash::check($validated['current_password'], $user->password)) {
                return redirect()->back()
                    ->withErrors(['current_password' => 'Joriy parol noto\'g\'ri']);
            }

            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update(array_filter($validated, fn($value) => !is_null($value)));

        return redirect()->back()->with('success', 'Sozlamalar yangilandi');
    }
}
