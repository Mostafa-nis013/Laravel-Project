@extends('layouts.shop')

@section('title', 'Your Cart')

@section('content')
<h1 style="font-family:'DM Serif Display',serif;font-size:1.9rem;margin-bottom:28px">
    Your Cart
    @if(count($cart) > 0)
        <span style="font-family:'DM Sans',sans-serif;font-size:1rem;font-weight:400;color:var(--muted);margin-left:10px">{{ collect($cart)->sum('quantity') }} {{ Str::plural('item', collect($cart)->sum('quantity')) }}</span>
    @endif
</h1>

@if(empty($cart))
{{-- Empty state --}}
<div style="text-align:center;padding:80px 20px">
    <div style="font-size:5rem;margin-bottom:20px;opacity:0.3">🛒</div>
    <h2 style="font-family:'DM Serif Display',serif;font-size:1.6rem;margin-bottom:12px">Your cart is empty</h2>
    <p style="color:var(--muted);margin-bottom:28px">Looks like you haven't added anything yet. Browse our products and find something you love.</p>
    <a href="{{ route('shop.index') }}" class="btn btn-primary">Start Shopping</a>
</div>
@else
<div style="display:grid;grid-template-columns:1fr 360px;gap:32px;align-items:start">

    {{-- Cart items --}}
    <div>
        <div style="background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);overflow:hidden">

            {{-- Header --}}
            <div style="display:grid;grid-template-columns:1fr auto auto auto;gap:16px;padding:12px 20px;border-bottom:1px solid var(--border);font-size:0.72rem;font-weight:700;letter-spacing:1px;text-transform:uppercase;color:var(--muted)">
                <span>Product</span>
                <span style="min-width:80px;text-align:center">Price</span>
                <span style="min-width:120px;text-align:center">Quantity</span>
                <span style="min-width:80px;text-align:right">Total</span>
            </div>

            @foreach($cart as $id => $item)
            <div style="display:grid;grid-template-columns:1fr auto auto auto;gap:16px;padding:18px 20px;border-bottom:1px solid var(--border);align-items:center">

                {{-- Product info --}}
                <div style="display:flex;align-items:center;gap:14px">
                    <a href="{{ route('shop.show', $item['slug']) }}" style="display:block;width:64px;height:64px;border-radius:8px;background:var(--surface2);border:1px solid var(--border);overflow:hidden;flex-shrink:0">
                        @if($item['image'])
                            <img src="{{ asset('storage/'.$item['image']) }}" alt="{{ $item['name'] }}" style="width:100%;height:100%;object-fit:cover">
                        @else
                            <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:1.4rem;color:var(--border)">◈</div>
                        @endif
                    </a>
                    <div>
                        <a href="{{ route('shop.show', $item['slug']) }}" style="font-weight:600;font-size:0.95rem;color:var(--text);text-decoration:none">{{ $item['name'] }}</a>
                        @if($item['stock'] <= 5 && $item['stock'] > 0)
                            <div style="font-size:0.75rem;color:var(--warning);margin-top:3px">Only {{ $item['stock'] }} left in stock</div>
                        @elseif($item['stock'] == 0)
                            <div style="font-size:0.75rem;color:var(--danger);margin-top:3px">Out of stock — will be removed</div>
                        @endif
                        {{-- Remove --}}
                        <form method="POST" action="{{ route('cart.remove', $id) }}" style="display:inline">
                            @csrf @method('DELETE')
                            <button type="submit" style="background:none;border:none;color:var(--muted);font-size:0.78rem;cursor:pointer;padding:0;margin-top:4px;font-family:'DM Sans',sans-serif;display:block" onmouseover="this.style.color='var(--danger)'" onmouseout="this.style.color='var(--muted)'">
                                Remove
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Unit price --}}
                <div style="min-width:80px;text-align:center;font-weight:600;color:var(--accent)">
                    ${{ number_format($item['price'], 2) }}
                </div>

                {{-- Quantity stepper --}}
                <div style="min-width:120px;text-align:center">
                    <form method="POST" action="{{ route('cart.update', $id) }}" id="qty-form-{{ $id }}">
                        @csrf @method('PATCH')
                        <div style="display:inline-flex;align-items:center;border:1px solid var(--border);border-radius:8px;overflow:hidden;background:var(--surface2)">
                            <button type="button" onclick="stepQty('{{ $id }}', -1)" style="width:32px;height:36px;background:none;border:none;color:var(--text);font-size:1rem;cursor:pointer">−</button>
                            <input type="number" name="quantity" id="qty-{{ $id }}" value="{{ $item['quantity'] }}" min="1" max="{{ $item['stock'] ?: 1 }}"
                                style="width:40px;height:36px;text-align:center;background:none;border:none;color:var(--text);font-size:0.9rem;font-family:'DM Sans',sans-serif"
                                onchange="document.getElementById('qty-form-{{ $id }}').submit()">
                            <button type="button" onclick="stepQty('{{ $id }}', 1)" style="width:32px;height:36px;background:none;border:none;color:var(--text);font-size:1rem;cursor:pointer">+</button>
                        </div>
                    </form>
                </div>

                {{-- Line total --}}
                <div style="min-width:80px;text-align:right;font-weight:700;font-size:0.95rem">
                    ${{ number_format($item['price'] * $item['quantity'], 2) }}
                </div>
            </div>
            @endforeach

            {{-- Cart actions --}}
            <div style="padding:14px 20px;display:flex;justify-content:space-between;align-items:center">
                <a href="{{ route('shop.index') }}" style="font-size:0.85rem;color:var(--muted);text-decoration:none" onmouseover="this.style.color='var(--text)'" onmouseout="this.style.color='var(--muted)'">
                    ← Continue Shopping
                </a>
                <form method="POST" action="{{ route('cart.clear') }}">
                    @csrf @method('DELETE')
                    <button type="submit" style="background:none;border:none;color:var(--muted);font-size:0.82rem;cursor:pointer;font-family:'DM Sans',sans-serif" onmouseover="this.style.color='var(--danger)'" onmouseout="this.style.color='var(--muted)'"
                        onclick="return confirm('Clear your entire cart?')">
                        🗑 Clear cart
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Order summary --}}
    <div style="position:sticky;top:90px">
        <div style="background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);overflow:hidden">
            <div style="padding:18px 20px;border-bottom:1px solid var(--border)">
                <span style="font-size:1rem;font-weight:600">Order Summary</span>
            </div>
            <div style="padding:20px">
                <div style="display:flex;justify-content:space-between;margin-bottom:12px;font-size:0.9rem">
                    <span style="color:var(--muted)">Subtotal</span>
                    <span>${{ number_format($subtotal, 2) }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;margin-bottom:12px;font-size:0.9rem">
                    <span style="color:var(--muted)">Shipping</span>
                    <span>
                        @if($shipping == 0)
                            <span style="color:var(--success);font-weight:600">Free</span>
                        @else
                            ${{ number_format($shipping, 2) }}
                        @endif
                    </span>
                </div>
                @if($shipping > 0)
                <div style="background:rgba(232,200,122,0.07);border:1px solid rgba(232,200,122,0.2);border-radius:8px;padding:10px 12px;margin-bottom:12px;font-size:0.78rem;color:var(--muted)">
                    Add <strong style="color:var(--accent)">${{ number_format(100 - $subtotal, 2) }}</strong> more for free shipping!
                </div>
                @endif
                <div style="display:flex;justify-content:space-between;margin-bottom:16px;font-size:0.9rem">
                    <span style="color:var(--muted)">Tax (8%)</span>
                    <span>${{ number_format($tax, 2) }}</span>
                </div>
                <div style="border-top:1px solid var(--border);padding-top:16px;display:flex;justify-content:space-between;margin-bottom:20px">
                    <span style="font-size:1.05rem;font-weight:700">Total</span>
                    <span style="font-size:1.2rem;font-weight:700;color:var(--accent)">${{ number_format($total, 2) }}</span>
                </div>
                <a href="{{ route('shop.checkout') }}" class="btn btn-primary btn-block" style="padding:14px;font-size:1rem;justify-content:center">
                    Proceed to Checkout →
                </a>
                <div style="display:flex;justify-content:center;gap:12px;margin-top:16px;font-size:0.75rem;color:var(--muted)">
                    <span>🔒 Secure checkout</span>
                    <span>🚚 Fast delivery</span>
                    <span>↩ Easy returns</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
function stepQty(id, delta) {
    const input = document.getElementById('qty-' + id);
    const max   = parseInt(input.max) || 99;
    const newVal = Math.min(max, Math.max(1, parseInt(input.value) + delta));
    input.value = newVal;
    document.getElementById('qty-form-' + id).submit();
}
</script>
@endpush
