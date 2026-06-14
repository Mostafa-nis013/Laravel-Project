<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    // ── Product listing ──────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $query = Product::with('category')->active();

        // Filter by category slug
        if ($request->filled('category')) {
            $category = Category::where('slug', $request->category)->firstOrFail();
            $query->where('category_id', $category->id);
        }

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%");
            });
        }

        // Sort
        match ($request->get('sort', 'newest')) {
            'price_asc'  => $query->orderByRaw('COALESCE(sale_price, price) ASC'),
            'price_desc' => $query->orderByRaw('COALESCE(sale_price, price) DESC'),
            'name'       => $query->orderBy('name'),
            'featured'   => $query->orderBy('is_featured', 'desc')->orderBy('created_at', 'desc'),
            default      => $query->orderBy('created_at', 'desc'),
        };

        $products  = $query->paginate(12)->withQueryString();
        $categories = Category::active()->withCount(['products' => fn($q) => $q->active()])->get();
        $featured   = Product::active()->featured()->inStock()->limit(4)->get();

        $activeCategory = $request->filled('category')
            ? Category::where('slug', $request->category)->first()
            : null;

        return view('shop.index', compact('products', 'categories', 'featured', 'activeCategory'));
    }

    // ── Single product ───────────────────────────────────────────────────────

    public function show(string $slug)
    {
        $product = Product::with('category')
            ->where('slug', $slug)
            ->active()
            ->firstOrFail();

        $related = Product::active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->inStock()
            ->limit(4)
            ->get();

        return view('shop.show', compact('product', 'related'));
    }

    // ── Customer orders ──────────────────────────────────────────────────────

    public function orders()
    {
        $orders = Order::where('customer_email', auth()->user()->email)
            ->with('items')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('shop.orders', compact('orders'));
    }
}
