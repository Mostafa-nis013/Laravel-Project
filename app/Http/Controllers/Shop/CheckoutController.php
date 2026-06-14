<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    // ── Show checkout form ───────────────────────────────────────────────────

    public function index()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('shop.cart')
                ->with('cart_error', 'Your cart is empty.');
        }

        $subtotal = collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']);
        $shipping = $subtotal >= 100 ? 0 : 9.99;
        $tax      = round($subtotal * 0.08, 2);
        $total    = $subtotal + $shipping + $tax;

        $user = auth()->user();

        return view('shop.checkout', compact('cart', 'subtotal', 'shipping', 'tax', 'total', 'user'));
    }

    // ── Process order ────────────────────────────────────────────────────────

    public function store(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('shop.cart')
                ->with('cart_error', 'Your cart is empty.');
        }

        $validated = $request->validate([
            'customer_name'    => 'required|string|max:255',
            'customer_email'   => 'required|email|max:255',
            'customer_phone'   => 'nullable|string|max:30',
            'shipping_address' => 'required|string|max:500',
            'shipping_city'    => 'required|string|max:100',
            'shipping_state'   => 'required|string|max:100',
            'shipping_zip'     => 'required|string|max:20',
            'shipping_country' => 'required|string|max:100',
            'payment_method'   => 'required|in:credit_card,debit_card,paypal,bank_transfer',
            'card_name'        => 'required_if:payment_method,credit_card,debit_card|nullable|string|max:255',
            'card_number'      => 'required_if:payment_method,credit_card,debit_card|nullable|string|max:19',
            'card_expiry'      => 'required_if:payment_method,credit_card,debit_card|nullable|string|max:7',
            'card_cvv'         => 'required_if:payment_method,credit_card,debit_card|nullable|string|max:4',
        ]);

        // Validate stock for every item
        foreach ($cart as $id => $item) {
            $product = Product::find($id);
            if (!$product || $product->stock < $item['quantity']) {
                return back()->with('cart_error', "Sorry, \"{$item['name']}\" no longer has enough stock.");
            }
        }

        // Calculate totals
        $subtotal    = round(collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']), 2);
        $shippingFee = $subtotal >= 100 ? 0 : 9.99;
        $tax         = round($subtotal * 0.08, 2);
        $total       = $subtotal + $shippingFee + $tax;

        // Create order
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
            'payment_status'   => 'paid',  // simulated payment
        ]);

        // Create items & decrement stock
        foreach ($cart as $id => $item) {
            $order->items()->create([
                'product_id'   => $item['product_id'],
                'product_name' => $item['name'],
                'product_sku'  => Product::find($id)?->sku ?? 'N/A',
                'quantity'     => $item['quantity'],
                'unit_price'   => $item['price'],
                'subtotal'     => round($item['price'] * $item['quantity'], 2),
            ]);

            Product::find($id)?->decrement('stock', $item['quantity']);
        }

        ActivityLog::log('created', "New storefront order {$order->order_number} placed by {$order->customer_name}.", $order);

        // Clear cart
        session()->forget('cart');

        return redirect()->route('shop.order.confirm', $order->order_number)
            ->with('success', 'Order placed successfully!');
    }

    // ── Order confirmation ───────────────────────────────────────────────────

    public function confirm(string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->with('items.product')
            ->firstOrFail();

        // Only the ordering customer or admins can view
        if (auth()->check() && auth()->user()->email !== $order->customer_email && !auth()->user()->isAdmin()) {
            abort(403);
        }

        return view('shop.confirm', compact('order'));
    }
}
