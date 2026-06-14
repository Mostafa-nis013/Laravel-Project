@extends('layouts.shop')

@section('title', $product->name)

@section('content')

{{-- Breadcrumb --}}
<div style="display:flex;align-items:center;gap:8px;font-size:0.82rem;color:var(--muted);margin-bottom:28px">
    <a href="{{ route('shop.index') }}" style="color:var(--muted);text-decoration:none">Shop</a>
    <span>›</span>
    @if($product->category)
        <a href="{{ route('shop.index', ['category' => $product->category->slug]) }}" style="color:var(--muted);text-decoration:none">{{ $product->category->name }}</a>
        <span>›</span>
    @endif
    <span style="color:var(--text)">{{ $product->name }}</span>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:48px;margin-bottom:64px;align-items:start">

    {{-- Product image --}}
    <div>
        <div style="aspect-ratio:1;background:var(--surface);border:1px solid var(--border);border-radius:12px;overflow:hidden;display:flex;align-items:center;justify-content:center;font-size:6rem;color:var(--border)">
            @if($product->image)
                <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}" style="width:100%;height:100%;object-fit:cover">
            @else
                ◈
            @endif
        </div>
    </div>

    {{-- Product info --}}
    <div>
        {{-- Category --}}
        @if($product->category)
        <div style="margin-bottom:10px">
            <a href="{{ route('shop.index', ['category' => $product->category->slug]) }}" style="font-size:0.75rem;text-transform:uppercase;letter-spacing:1.5px;color:var(--muted);text-decoration:none;font-weight:600">
                {{ $product->category->name }}
            </a>
        </div>
        @endif

        <h1 style="font-family:'DM Serif Display',serif;font-size:2.2rem;line-height:1.15;margin-bottom:16px">{{ $product->name }}</h1>

        {{-- Badges --}}
        <div style="display:flex;gap:8px;margin-bottom:20px;flex-wrap:wrap">
            @if($product->is_on_sale)
                <span class="badge badge-sale">SALE</span>
            @endif
            @if($product->is_featured)
                <span class="badge" style="background:rgba(232,200,122,0.12);color:var(--accent)">★ Featured</span>
            @endif
            @if($product->stock == 0)
                <span class="badge badge-out">Out of Stock</span>
            @elseif($product->stock <= 5)
                <span class="badge" style="background:rgba(224,160,82,0.12);color:var(--warning)">Only {{ $product->stock }} left!</span>
            @else
                <span class="badge" style="background:rgba(82,192,122,0.12);color:var(--success)">✓ In Stock</span>
            @endif
        </div>

        {{-- Price --}}
        <div style="margin-bottom:24px">
            @if($product->is_on_sale)
                <div style="font-size:1rem;color:var(--muted);text-decoration:line-through;margin-bottom:4px">${{ number_format($product->price, 2) }}</div>
                <div style="font-size:2rem;font-weight:700;color:var(--danger)">${{ number_format($product->sale_price, 2) }}</div>
                <div style="font-size:0.82rem;color:var(--success);margin-top:4px">
                    You save ${{ number_format($product->price - $product->sale_price, 2) }} ({{ round(($product->price - $product->sale_price) / $product->price * 100) }}% off)
                </div>
            @else
                <div style="font-size:2rem;font-weight:700;color:var(--accent)">${{ number_format($product->price, 2) }}</div>
            @endif
        </div>

        {{-- Description --}}
        @if($product->description)
        <p style="color:var(--muted);line-height:1.75;font-size:0.95rem;margin-bottom:28px">{{ $product->description }}</p>
        @endif

        {{-- Add to cart --}}
        @if($product->stock > 0)
            @auth
            <form method="POST" action="{{ route('cart.add') }}" style="margin-bottom:16px">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <div style="display:flex;gap:12px;align-items:center;margin-bottom:14px">
                    <div style="display:flex;align-items:center;border:1px solid var(--border);border-radius:var(--radius);overflow:hidden;background:var(--surface2)">
                        <button type="button" onclick="changeQty(-1)" style="width:40px;height:44px;background:none;border:none;color:var(--text);font-size:1.2rem;cursor:pointer">−</button>
                        <input type="number" name="quantity" id="qty-input" value="1" min="1" max="{{ $product->stock }}" style="width:52px;height:44px;text-align:center;background:none;border:none;color:var(--text);font-size:1rem;font-family:'DM Sans',sans-serif">
                        <button type="button" onclick="changeQty(1)" style="width:40px;height:44px;background:none;border:none;color:var(--text);font-size:1.2rem;cursor:pointer">+</button>
                    </div>
                    <button type="submit" class="btn btn-primary" style="flex:1;padding:12px 24px;font-size:1rem">
                        🛒 Add to Cart
                    </button>
                </div>
            </form>
            @else
            <a href="{{ route('login') }}" class="btn btn-primary btn-block" style="margin-bottom:16px;padding:13px;font-size:1rem;justify-content:center">
                Sign in to Buy
            </a>
            @endauth
        @else
            <button class="btn btn-block" disabled style="background:var(--surface2);border:1px solid var(--border);color:var(--muted);cursor:not-allowed;padding:13px;font-size:1rem;margin-bottom:16px">
                Out of Stock
            </button>
        @endif

        {{-- Meta --}}
        <div style="background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);overflow:hidden">
            <div style="display:flex;justify-content:space-between;padding:12px 16px;border-bottom:1px solid var(--border);font-size:0.85rem">
                <span style="color:var(--muted)">SKU</span>
                <span style="font-family:monospace;color:var(--text)">{{ $product->sku }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;padding:12px 16px;border-bottom:1px solid var(--border);font-size:0.85rem">
                <span style="color:var(--muted)">Availability</span>
                <span style="color:{{ $product->stock > 0 ? 'var(--success)' : 'var(--danger)' }}">{{ $product->stock > 0 ? $product->stock . ' in stock' : 'Out of stock' }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;padding:12px 16px;border-bottom:1px solid var(--border);font-size:0.85rem">
                <span style="color:var(--muted)">Free Shipping</span>
                <span style="color:var(--text)">On orders over $100</span>
            </div>
            <div style="display:flex;justify-content:space-between;padding:12px 16px;font-size:0.85rem">
                <span style="color:var(--muted)">Returns</span>
                <span style="color:var(--text)">30-day easy returns</span>
            </div>
        </div>
    </div>
</div>

{{-- Related Products --}}
@if($related->count())
<div>
    <h2 style="font-family:'DM Serif Display',serif;font-size:1.5rem;margin-bottom:20px">You might also like</h2>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:16px">
        @foreach($related as $p)
        <a href="{{ route('shop.show', $p->slug) }}" style="text-decoration:none">
            <div style="background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);overflow:hidden;transition:border-color 0.15s,transform 0.15s" onmouseover="this.style.borderColor='var(--accent)';this.style.transform='translateY(-2px)'" onmouseout="this.style.borderColor='var(--border)';this.style.transform='translateY(0)'">
                <div style="height:160px;background:var(--surface2);display:flex;align-items:center;justify-content:center;font-size:2.5rem;color:var(--border);overflow:hidden">
                    @if($p->image)
                        <img src="{{ asset('storage/'.$p->image) }}" alt="{{ $p->name }}" style="width:100%;height:100%;object-fit:cover">
                    @else
                        ◈
                    @endif
                </div>
                <div style="padding:14px 16px">
                    <div style="font-weight:600;font-size:0.9rem;color:var(--text);margin-bottom:5px">{{ $p->name }}</div>
                    <div style="font-size:0.9rem;font-weight:700;color:var(--accent)">${{ number_format($p->effective_price, 2) }}</div>
                </div>
            </div>
        </a>
        @endforeach
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
function changeQty(delta) {
    const input = document.getElementById('qty-input');
    const max   = parseInt(input.max);
    const val   = Math.min(max, Math.max(1, parseInt(input.value) + delta));
    input.value = val;
}
</script>
@endpush
