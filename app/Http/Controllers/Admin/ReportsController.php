<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', '30'); // days

        // ── Revenue over time (daily for last N days) ─────────────────────────
        $revenueByDay = Order::whereIn('status', ['delivered', 'shipped', 'processing'])
            ->where('created_at', '>=', now()->subDays((int) $period))
            ->selectRaw('DATE(created_at) as date, SUM(total) as revenue, COUNT(*) as orders')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        // Fill gaps with zeros
        $revenueSeries = [];
        for ($i = (int)$period - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $label = now()->subDays($i)->format('M d');
            $revenueSeries[] = [
                'date'    => $label,
                'revenue' => (float) ($revenueByDay[$date]->revenue ?? 0),
                'orders'  => (int)   ($revenueByDay[$date]->orders  ?? 0),
            ];
        }

        // ── Revenue by status ──────────────────────────────────────────────────
        $revenueByStatus = Order::selectRaw('status, COUNT(*) as count, SUM(total) as revenue')
            ->groupBy('status')
            ->get();

        // ── Top-selling products ───────────────────────────────────────────────
        $topProducts = Product::withCount('orderItems')
            ->withSum('orderItems', 'subtotal')
            ->orderBy('order_items_count', 'desc')
            ->limit(10)
            ->get();

        // ── Revenue by category ────────────────────────────────────────────────
        $revenueByCategory = Category::withCount('products')
            ->get()
            ->map(function ($cat) {
                $revenue = DB::table('order_items')
                    ->join('products', 'order_items.product_id', '=', 'products.id')
                    ->where('products.category_id', $cat->id)
                    ->sum('order_items.subtotal');
                return ['name' => $cat->name, 'revenue' => (float) $revenue, 'products' => $cat->products_count];
            })
            ->sortByDesc('revenue')
            ->values();

        // ── Summary stats ─────────────────────────────────────────────────────
        $summary = [
            'total_revenue'   => Order::whereIn('status', ['delivered', 'shipped', 'processing'])->sum('total'),
            'period_revenue'  => Order::whereIn('status', ['delivered', 'shipped', 'processing'])
                ->where('created_at', '>=', now()->subDays((int)$period))->sum('total'),
            'total_orders'    => Order::count(),
            'period_orders'   => Order::where('created_at', '>=', now()->subDays((int)$period))->count(),
            'avg_order_value' => Order::whereIn('status', ['delivered'])->avg('total') ?? 0,
            'total_customers' => User::count(),
            'new_customers'   => User::where('created_at', '>=', now()->subDays((int)$period))->count(),
        ];

        // ── Orders by day of week ──────────────────────────────────────────────
        $ordersByDow = Order::selectRaw('DAYOFWEEK(created_at) as dow, COUNT(*) as count')
            ->groupBy('dow')
            ->pluck('count', 'dow');

        $dowLabels  = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
        $dowSeries  = [];
        for ($d = 1; $d <= 7; $d++) {
            $dowSeries[] = ['day' => $dowLabels[$d - 1], 'count' => (int)($ordersByDow[$d] ?? 0)];
        }

        return view('admin.reports.index', compact(
            'revenueSeries', 'revenueByStatus', 'topProducts',
            'revenueByCategory', 'summary', 'dowSeries', 'period'
        ));
    }
}
