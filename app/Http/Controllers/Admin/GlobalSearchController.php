<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;

class GlobalSearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $q = trim($request->get('q', ''));

        if (strlen($q) < 2) {
            return response()->json(['results' => []]);
        }

        $results = [];

        // Products
        $products = Product::with('category')
            ->where(function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                      ->orWhere('sku', 'like', "%{$q}%");
            })
            ->limit(5)
            ->get();

        foreach ($products as $p) {
            $results[] = [
                'type'     => 'product',
                'icon'     => '◈',
                'label'    => $p->name,
                'sublabel' => $p->sku . ' · ' . ($p->category->name ?? 'No category'),
                'meta'     => '$' . number_format($p->effective_price, 2),
                'url'      => route('products.show', $p),
                'badge'    => $p->stock == 0 ? 'Out of stock' : null,
            ];
        }

        // Orders
        $orders = Order::where(function ($query) use ($q) {
                $query->where('order_number', 'like', "%{$q}%")
                      ->orWhere('customer_name', 'like', "%{$q}%")
                      ->orWhere('customer_email', 'like', "%{$q}%");
            })
            ->limit(4)
            ->get();

        foreach ($orders as $o) {
            $results[] = [
                'type'     => 'order',
                'icon'     => '◷',
                'label'    => $o->order_number,
                'sublabel' => $o->customer_name . ' · ' . $o->customer_email,
                'meta'     => '$' . number_format($o->total, 2),
                'url'      => route('orders.show', $o),
                'badge'    => ucfirst($o->status),
            ];
        }

        // Users (admin only)
        if (auth()->user()->isAdmin()) {
            $users = User::where(function ($query) use ($q) {
                    $query->where('name', 'like', "%{$q}%")
                          ->orWhere('email', 'like', "%{$q}%");
                })
                ->with('roles')
                ->limit(3)
                ->get();

            foreach ($users as $u) {
                $results[] = [
                    'type'     => 'user',
                    'icon'     => '◎',
                    'label'    => $u->name,
                    'sublabel' => $u->email,
                    'meta'     => $u->role_label,
                    'url'      => route('users.show', $u),
                    'badge'    => null,
                ];
            }
        }

        // Categories
        $categories = Category::where('name', 'like', "%{$q}%")->limit(3)->get();
        foreach ($categories as $c) {
            $results[] = [
                'type'     => 'category',
                'icon'     => '◉',
                'label'    => $c->name,
                'sublabel' => 'Category',
                'meta'     => $c->products()->count() . ' products',
                'url'      => route('categories.show', $c),
                'badge'    => null,
            ];
        }

        return response()->json([
            'results' => $results,
            'query'   => $q,
        ]);
    }
}
