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
        $orders = Auth::user()->orders()
            ->with('items.product')
            ->latest()
            ->paginate(10);
        return view('frontend.profile.orders', compact('orders'));
    }

    public function showOrder($orderNumber)
    {
        $order = Auth()->user()->orders()
            ->with('order_number', $orderNumber)
            ->with(['items.product', 'shippingaddress'])
            ->FirstOrFail();

        return view('frontend.profile.order-show', compact('order'));
    }



    /**
     * Show user wishlist
     */
    public function wishlist()
    {
        return redirect()->route('wishlist');
    }

    /**
     * Show settings page
     */
    public function settings()
    {
        $user = Auth::user();
        return view('frontend.profile.setting', compact('user'));
    }

    /**
     * Update user settings
     */
    public function updateSettings(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        // Update basic info
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'] ?? null;

        // Update password if provided
        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Joriy parol noto\'g\'ri']);
            }

            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        return back()->with('success', 'Ma\'lumotlar muvaffaqiyatli yangilandi!');
    }
}
