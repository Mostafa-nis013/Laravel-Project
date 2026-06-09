<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\ActivityLog;

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
            ->limit(6)
            ->get();

        $topProducts = Product::withCount('orderItems')
            ->orderBy('order_items_count', 'desc')
            ->limit(6)
            ->get();

        // 14-day revenue sparkline
        $rawRevenue = Order::whereIn('status', ['delivered', 'shipped', 'processing'])
            ->where('created_at', '>=', now()->subDays(13))
            ->selectRaw('DATE(created_at) as date, SUM(total) as revenue')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $sparkRevenue = [];
        for ($i = 13; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $sparkRevenue[] = [
                'date'    => now()->subDays($i)->format('M d'),
                'revenue' => (float) ($rawRevenue[$date]->revenue ?? 0),
            ];
        }

        // Recent activity feed
        $recentActivity = ActivityLog::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        return view('admin.dashboard', compact(
            'stats', 'recentOrders', 'topProducts', 'sparkRevenue', 'recentActivity'
        ));
    }
}
