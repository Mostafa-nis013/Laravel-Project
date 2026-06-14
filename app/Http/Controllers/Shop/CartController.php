<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // ── View cart ────────────────────────────────────────────────────────────

    public function index()
    {
        $cart     = $this->getCart();
        $subtotal = $this->subtotal($cart);
        $shipping = $subtotal >= 100 ? 0 : ($subtotal > 0 ? 9.99 : 0);
        $tax      = round($subtotal * 0.08, 2);
        $total    = $subtotal + $shipping + $tax;

        return view('shop.cart', compact('cart', 'subtotal', 'shipping', 'tax', 'total'));
    }

    // ── Add to cart ──────────────────────────────────────────────────────────

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1|max:99',
        ]);

        $product  = Product::findOrFail($request->product_id);

        if (!$product->is_active) {
            return back()->with('cart_error', 'This product is not available.');
        }

        if ($product->stock < 1) {
            return back()->with('cart_error', '"{$product->name}" is out of stock.');
        }

        $cart = session()->get('cart', []);
        $id   = (string) $product->id;
        $qty  = (int) $request->quantity;

        if (isset($cart[$id])) {
            $newQty = $cart[$id]['quantity'] + $qty;
            if ($newQty > $product->stock) {
                return back()->with('cart_error', "Only {$product->stock} units of \"{$product->name}\" available.");
            }
            $cart[$id]['quantity'] = $newQty;
        } else {
            if ($qty > $product->stock) {
                return back()->with('cart_error', "Only {$product->stock} units available.");
            }
            $cart[$id] = [
                'product_id' => $product->id,
                'name'       => $product->name,
                'slug'       => $product->slug,
                'price'      => (float) $product->effective_price,
                'image'      => $product->image,
                'quantity'   => $qty,
                'stock'      => $product->stock,
            ];
        }

        session()->put('cart', $cart);

        $redirect = $request->get('redirect', 'cart');

        if ($redirect === 'back') {
            return back()->with('cart_success', "\"{$product->name}\" added to your cart.");
        }

        return redirect()->route('shop.cart')
            ->with('cart_success', "\"{$product->name}\" added to your cart.");
    }

    // ── Update quantity ──────────────────────────────────────────────────────

    public function update(Request $request, string $id)
    {
        $request->validate(['quantity' => 'required|integer|min:1|max:99']);

        $cart    = session()->get('cart', []);
        $product = Product::find($id);

        if (!isset($cart[$id])) {
            return back()->with('cart_error', 'Item not found in cart.');
        }

        $qty = (int) $request->quantity;

        if ($product && $qty > $product->stock) {
            return back()->with('cart_error', "Only {$product->stock} units available.");
        }

        $cart[$id]['quantity'] = $qty;
        session()->put('cart', $cart);

        return back()->with('cart_success', 'Cart updated.');
    }

    // ── Remove item ──────────────────────────────────────────────────────────

    public function remove(string $id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $name = $cart[$id]['name'];
            unset($cart[$id]);
            session()->put('cart', $cart);
            return back()->with('cart_success', "\"{$name}\" removed from cart.");
        }

        return back();
    }

    // ── Clear cart ───────────────────────────────────────────────────────────

    public function clear()
    {
        session()->forget('cart');
        return back()->with('cart_success', 'Cart cleared.');
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    private function getCart(): array
    {
        $cart = session()->get('cart', []);

        // Refresh prices & stock from DB on every cart view
        $ids      = array_keys($cart);
        $products = Product::whereIn('id', $ids)->get()->keyBy('id');

        foreach ($cart as $id => &$item) {
            if (!isset($products[$id])) {
                unset($cart[$id]);
                continue;
            }
            $p              = $products[$id];
            $item['price']  = (float) $p->effective_price;
            $item['stock']  = $p->stock;
            $item['name']   = $p->name;
            $item['image']  = $p->image;

            // Cap quantity to available stock
            if ($item['quantity'] > $p->stock) {
                $item['quantity'] = max(1, $p->stock);
            }
        }
        unset($item);

        session()->put('cart', $cart);
        return $cart;
    }

    private function subtotal(array $cart): float
    {
        return round(collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']), 2);
    }
}
