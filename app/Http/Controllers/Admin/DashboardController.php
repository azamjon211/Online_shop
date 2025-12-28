<?php
// app/Http/Controllers/Admin/DashboardController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $totalUsers = User::where('role', 'customer')->count();
        $totalSales = Order::where('status', 'delivered')->sum('total');

        // Recent orders
        $recentOrders = Order::with('user')
            ->latest()
            ->limit(10)
            ->get();

        // Monthly sales chart data
        $monthlySales = Order::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('SUM(total) as total')
        )
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->get();

        // Top selling products
        $topProducts = Product::orderBy('sales_count', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalOrders',
            'totalUsers',
            'totalSales',
            'recentOrders',
            'monthlySales',
            'topProducts'
        ));
    }
}
