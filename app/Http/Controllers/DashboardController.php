<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_products'   => Product::count(),
            'active_products'  => Product::active()->count(),
            'low_stock'        => Product::where('stock', '<=', 5)->where('stock', '>', 0)->count(),
            'out_of_stock'     => Product::where('stock', 0)->count(),
            'total_categories' => Category::count(),
            'total_orders'     => Order::count(),
            'pending_orders'   => Order::where('status', Order::STATUS_PENDING)->count(),
            'total_revenue'    => Order::where('status', Order::STATUS_DELIVERED)->sum('total'),
            'total_users'      => \App\Models\User::count(),
            'active_users'     => \App\Models\User::where('is_active', true)->count(),
        ];

        $recentOrders = Order::with('items')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $topProducts = Product::withCount('orderItems')
            ->orderBy('order_items_count', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'topProducts'));
    }
}
