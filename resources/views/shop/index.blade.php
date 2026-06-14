@extends('layouts.shop')

@section('title', $activeCategory ? $activeCategory->name : 'Shop')

@section('content')

{{-- Hero / Featured strip (only on main shop page without filters) --}}
@if(!request('category') && !request('search') && $featured->count() > 0)
<div style="margin-bottom:40px">
    <div style="display:flex;align-items:baseline;justify-content:space-between;margin-bottom:18px">
        <h2 style="font-family:'DM Serif Display',serif;font-size:1.6rem">Featured <em style="font-style:italic;color:var(--accent)">Picks</em></h2>
        <a href="{{ route('shop.index', ['sort' => 'featured']) }}" style="font-size:0.82rem;color:var(--muted);text-decoration:none">View all featured →</a>
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:16px">
        @foreach($featured as $p)
        <a href="{{ route('shop.show', $p->slug) }}" style="text-decoration:none">
            <div style="background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);overflow:hidden;transition:border-color 0.15s,transform 0.15s" onmouseover="this.style.borderColor='var(--accent)';this.style.transform='translateY(-2px)'" onmouseout="this.style.borderColor='var(--border)';this.style.transform='translateY(0)'">
                <div style="height:180px;background:var(--surface2);display:flex;align-items:center;justify-content:center;font-size:3rem;overflow:hidden">
                    @if($p->image)
                        <img src="{{ asset('storage/'.$p->image) }}" alt="{{ $p->name }}" style="width:100%;height:100%;object-fit:cover">
                    @else
                        ◈
                    @endif
                </div>
                <div style="padding:14px 16px">
                    <div style="font-weight:600;font-size:0.9rem;margin-bottom:4px;color:var(--text)">{{ $p->name }}</div>
                    <div style="font-size:0.85rem">
                        @if($p->is_on_sale)
                            <span style="color:var(--muted);text-decoration:line-through;margin-right:6px">${{ number_format($p->price,2) }}</span>
                            <span style="color:var(--danger);font-weight:600">${{ number_format($p->sale_price,2) }}</span>
                        @else
                            <span style="color:var(--accent);font-weight:600">${{ number_format($p->price,2) }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </a>
        @endforeach
    </div>
</div>
<hr style="border:none;border-top:1px solid var(--border);margin-bottom:32px">
@endif

{{-- Page title + sort bar --}}
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;flex-wrap:wrap;gap:12px">
    <div>
        <h1 style="font-family:'DM Serif Display',serif;font-size:1.5rem">
            @if($activeCategory)
                {{ $activeCategory->name }}
            @elseif(request('search'))
                Results for "<em style="color:var(--accent)">{{ request('search') }}</em>"
            @else
                All Products
            @endif
        </h1>
        <p style="font-size:0.82rem;color:var(--muted);margin-top:3px">{{ $products->total() }} {{ Str::plural('product', $products->total()) }}</p>
    </div>

    <form method="GET" action="{{ route('shop.index') }}" style="display:flex;gap:8px;align-items:center">
        @if(request('category'))<input type="hidden" name="category" value="{{ request('category') }}">@endif
        @if(request('search'))<input type="hidden" name="search" value="{{ request('search') }}">@endif
        <select name="sort" onchange="this.form.submit()" style="background:var(--surface2);border:1px solid var(--border);border-radius:var(--radius);color:var(--text);padding:8px 14px;font-size:0.82rem;font-family:'DM Sans',sans-serif;outline:none;cursor:pointer">
            <option value="newest"     {{ request('sort','newest') == 'newest'     ? 'selected' : '' }}>Newest first</option>
            <option value="featured"   {{ request('sort') == 'featured'   ? 'selected' : '' }}>Featured</option>
            <option value="price_asc"  {{ request('sort') == 'price_asc'  ? 'selected' : '' }}>Price: low → high</option>
            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: high → low</option>
            <option value="name"       {{ request('sort') == 'name'       ? 'selected' : '' }}>Name A–Z</option>
        </select>
    </form>
</div>

{{-- Product Grid --}}
@if($products->isEmpty())
<div style="text-align:center;padding:80px 20px;color:var(--muted)">
    <div style="font-size:3rem;margin-bottom:16px">◈</div>
    <h3 style="font-size:1.1rem;margin-bottom:8px;color:var(--text)">No products found</h3>
    <p style="font-size:0.875rem">Try a different search term or browse all categories.</p>
    <a href="{{ route('shop.index') }}" class="btn btn-secondary" style="margin-top:20px">Browse all products</a>
</div>
@else
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:20px;margin-bottom:40px">
    @foreach($products as $product)
    <div style="background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);overflow:hidden;display:flex;flex-direction:column;transition:border-color 0.15s,transform 0.15s" onmouseover="this.style.borderColor='var(--accent)';this.style.transform='translateY(-2px)'" onmouseout="this.style.borderColor='var(--border)';this.style.transform='translateY(0)'">

        {{-- Product image --}}
        <a href="{{ route('shop.show', $product->slug) }}" style="display:block;height:220px;background:var(--surface2);overflow:hidden;position:relative">
            @if($product->image)
                <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}" style="width:100%;height:100%;object-fit:cover;transition:transform 0.3s" onmouseover="this.style.transform='scale(1.04)'" onmouseout="this.style.transform='scale(1)'">
            @else
                <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:4rem;color:var(--border)">◈</div>
            @endif

            {{-- Badges --}}
            <div style="position:absolute;top:10px;left:10px;display:flex;flex-direction:column;gap:5px">
                @if($product->is_on_sale)
                    <span class="badge badge-sale">SALE</span>
                @endif
                @if($product->is_featured)
                    <span class="badge" style="background:rgba(232,200,122,0.15);color:var(--accent)">★ Featured</span>
                @endif
                @if($product->stock == 0)
                    <span class="badge badge-out">Out of Stock</span>
                @elseif($product->stock <= 5)
                    <span class="badge" style="background:rgba(224,160,82,0.15);color:var(--warning)">Only {{ $product->stock }} left</span>
                @endif
            </div>
        </a>

        {{-- Info --}}
        <div style="padding:16px 18px 18px;display:flex;flex-direction:column;flex:1">
            <div style="font-size:0.72rem;color:var(--muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:5px">{{ $product->category->name ?? '' }}</div>
            <a href="{{ route('shop.show', $product->slug) }}" style="font-weight:600;font-size:0.95rem;color:var(--text);text-decoration:none;margin-bottom:8px;line-height:1.3">{{ $product->name }}</a>
            <p style="font-size:0.8rem;color:var(--muted);line-height:1.5;flex:1;margin-bottom:14px">{{ Str::limit($product->description, 70) }}</p>

            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px">
                <div>
                    @if($product->is_on_sale)
                        <span style="font-size:0.82rem;color:var(--muted);text-decoration:line-through">${{ number_format($product->price,2) }}</span>
                        <span style="font-size:1.1rem;font-weight:700;color:var(--danger);margin-left:6px">${{ number_format($product->sale_price,2) }}</span>
                    @else
                        <span style="font-size:1.1rem;font-weight:700;color:var(--accent)">${{ number_format($product->price,2) }}</span>
                    @endif
                </div>
            </div>

            @if($product->stock > 0)
                @auth
                <form method="POST" action="{{ route('cart.add') }}">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" value="1">
                    <input type="hidden" name="redirect" value="back">
                    <button type="submit" class="btn btn-primary btn-block" style="padding:10px">
                        🛒 Add to Cart
                    </button>
                </form>
                @else
                <a href="{{ route('login') }}" class="btn btn-secondary btn-block" style="padding:10px;justify-content:center">
                    Sign in to Buy
                </a>
                @endauth
            @else
                <button class="btn btn-block" style="background:var(--surface2);border:1px solid var(--border);color:var(--muted);padding:10px;cursor:not-allowed">
                    Out of Stock
                </button>
            @endif
        </div>
    </div>
    @endforeach
</div>

{{-- Pagination --}}
@if($products->hasPages())
<div style="display:flex;justify-content:center;gap:6px;margin-bottom:20px">
    @if($products->onFirstPage())
        <span style="padding:8px 14px;border-radius:8px;background:var(--surface2);border:1px solid var(--border);color:var(--muted);font-size:0.85rem">←</span>
    @else
        <a href="{{ $products->previousPageUrl() }}" style="padding:8px 14px;border-radius:8px;background:var(--surface2);border:1px solid var(--border);color:var(--muted);font-size:0.85rem;text-decoration:none">←</a>
    @endif

    @foreach($products->getUrlRange(max(1,$products->currentPage()-2), min($products->lastPage(),$products->currentPage()+2)) as $page => $url)
        <a href="{{ $url }}" style="padding:8px 14px;border-radius:8px;background:{{ $page == $products->currentPage() ? 'var(--accent)' : 'var(--surface2)' }};border:1px solid {{ $page == $products->currentPage() ? 'var(--accent)' : 'var(--border)' }};color:{{ $page == $products->currentPage() ? '#0c0c0e' : 'var(--muted)' }};font-size:0.85rem;text-decoration:none;font-weight:{{ $page == $products->currentPage() ? '700' : '400' }}">{{ $page }}</a>
    @endforeach

    @if($products->hasMorePages())
        <a href="{{ $products->nextPageUrl() }}" style="padding:8px 14px;border-radius:8px;background:var(--surface2);border:1px solid var(--border);color:var(--muted);font-size:0.85rem;text-decoration:none">→</a>
    @else
        <span style="padding:8px 14px;border-radius:8px;background:var(--surface2);border:1px solid var(--border);color:var(--muted);font-size:0.85rem">→</span>
    @endif
</div>
@endif
@endif
@endsection
