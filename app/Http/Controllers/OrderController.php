<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('items');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('order_number', 'like', "%{$request->search}%")
                  ->orWhere('customer_name', 'like', "%{$request->search}%")
                  ->orWhere('customer_email', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $query->orderBy('created_at', 'desc');
        $orders = $query->paginate(15)->withQueryString();

        $statusCounts = Order::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        return view('admin.orders.index', compact('orders', 'statusCounts'));
    }

    public function create()
    {
        $products = Product::active()->inStock()->with('category')->get();
        return view('admin.orders.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name'     => 'required|string|max:255',
            'customer_email'    => 'required|email|max:255',
            'customer_phone'    => 'nullable|string|max:30',
            'shipping_address'  => 'required|string',
            'shipping_city'     => 'required|string|max:100',
            'shipping_state'    => 'required|string|max:100',
            'shipping_zip'      => 'required|string|max:20',
            'shipping_country'  => 'required|string|max:100',
            'payment_method'    => 'required|string|max:50',
            'notes'             => 'nullable|string',
            'items'             => 'required|array|min:1',
            'items.*.product_id'=> 'required|exists:products,id',
            'items.*.quantity'  => 'required|integer|min:1',
        ]);

        // Calculate totals
        $subtotal = 0;
        $orderItems = [];

        foreach ($request->items as $item) {
            $product = Product::findOrFail($item['product_id']);
            $unitPrice = $product->effective_price;
            $itemSubtotal = $unitPrice * $item['quantity'];
            $subtotal += $itemSubtotal;

            $orderItems[] = [
                'product_id'   => $product->id,
                'product_name' => $product->name,
                'product_sku'  => $product->sku,
                'quantity'     => $item['quantity'],
                'unit_price'   => $unitPrice,
                'subtotal'     => $itemSubtotal,
            ];
        }

        $shippingFee = $subtotal >= 100 ? 0 : 9.99;
        $tax         = round($subtotal * 0.08, 2);
        $total       = $subtotal + $shippingFee + $tax;

        $order = Order::create([
            'customer_name'    => $validated['customer_name'],
            'customer_email'   => $validated['customer_email'],
            'customer_phone'   => $validated['customer_phone'] ?? null,
            'shipping_address' => $validated['shipping_address'],
            'shipping_city'    => $validated['shipping_city'],
            'shipping_state'   => $validated['shipping_state'],
            'shipping_zip'     => $validated['shipping_zip'],
            'shipping_country' => $validated['shipping_country'],
            'subtotal'         => $subtotal,
            'shipping_fee'     => $shippingFee,
            'tax'              => $tax,
            'total'            => $total,
            'status'           => Order::STATUS_PENDING,
            'payment_method'   => $validated['payment_method'],
            'payment_status'   => 'pending',
            'notes'            => $validated['notes'] ?? null,
        ]);

        foreach ($orderItems as $item) {
            $order->items()->create($item);
            // Decrement stock
            Product::find($item['product_id'])->decrement('stock', $item['quantity']);
        }

        return redirect()
            ->route('orders.show', $order)
            ->with('success', "Order {$order->order_number} created successfully.");
    }

    public function show(Order $order)
    {
        $order->load('items.product');
        return view('admin.orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $order->load('items.product');
        $products = Product::active()->with('category')->get();
        return view('admin.orders.edit', compact('order', 'products'));
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'customer_name'    => 'required|string|max:255',
            'customer_email'   => 'required|email|max:255',
            'customer_phone'   => 'nullable|string|max:30',
            'shipping_address' => 'required|string',
            'shipping_city'    => 'required|string|max:100',
            'shipping_state'   => 'required|string|max:100',
            'shipping_zip'     => 'required|string|max:20',
            'shipping_country' => 'required|string|max:100',
            'payment_method'   => 'required|string|max:50',
            'notes'            => 'nullable|string',
        ]);

        $order->update($validated);

        return redirect()
            ->route('orders.show', $order)
            ->with('success', "Order {$order->order_number} updated successfully.");
    }

    public function destroy(Order $order)
    {
        // Restore stock
        foreach ($order->items as $item) {
            if ($item->product) {
                $item->product->increment('stock', $item->quantity);
            }
        }

        $orderNumber = $order->order_number;
        $order->items()->delete();
        $order->delete();

        return redirect()
            ->route('orders.index')
            ->with('success', "Order {$orderNumber} has been deleted.");
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:' . implode(',', array_keys(Order::STATUSES)),
        ]);

        $order->update(['status' => $request->status]);

        return redirect()
            ->back()
            ->with('success', "Order status updated to " . Order::STATUSES[$request->status] . ".");
    }
}
