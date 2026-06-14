@extends('layouts.admin')

@section('title', 'Products')
@section('topbar-title', 'Products')
@section('breadcrumb')
    <span style="color:var(--muted)">Catalog</span>
    <span class="crumb-sep">›</span>
    <span class="crumb-current">Products</span>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2>Products</h2>
        <p>{{ $products->total() }} total products in your catalog</p>
    </div>
    <a href="{{ route('products.create') }}" class="btn btn-primary">+ Add Product</a>
</div>

{{-- Filters --}}
<form method="GET" action="{{ route('products.index') }}">
    <div class="filters-bar">
        <input type="text" name="search" placeholder="Search by name or SKU…" value="{{ request('search') }}">
        <select name="category_id">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
        <select name="status">
            <option value="">All Status</option>
            <option value="active"   {{ request('status') == 'active'   ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            <option value="deleted"  {{ request('status') == 'deleted'  ? 'selected' : '' }}>Deleted</option>
        </select>
        <button type="submit" class="btn btn-secondary">Filter</button>
        @if(request()->hasAny(['search','category_id','status']))
            <a href="{{ route('products.index') }}" class="btn btn-secondary">Clear</a>
        @endif
    </div>
</form>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>SKU</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr style="{{ $product->deleted_at ? 'opacity:0.5' : '' }}">
                    <td>
                        <div style="display:flex;align-items:center;gap:12px">
                            <div class="product-thumb">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                                @else
                                    ◈
                                @endif
                            </div>
                            <div>
                                <div style="font-weight:500">{{ $product->name }}</div>
                                @if($product->is_featured)
                                    <span class="badge badge-yellow" style="font-size:0.65rem;padding:2px 7px">Featured</span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td style="color:var(--muted);font-family:monospace">{{ $product->sku }}</td>
                    <td style="color:var(--muted)">{{ $product->category->name ?? '—' }}</td>
                    <td>
                        @if($product->is_on_sale)
                            <span style="color:var(--muted);text-decoration:line-through;font-size:0.8rem">${{ number_format($product->price,2) }}</span>
                            <span style="color:var(--success);font-weight:600"> ${{ number_format($product->sale_price,2) }}</span>
                        @else
                            <span style="color:var(--accent)">${{ number_format($product->price,2) }}</span>
                        @endif
                    </td>
                    <td>
                        @if($product->stock == 0)
                            <span class="badge badge-red">Out of stock</span>
                        @elseif($product->stock <= 5)
                            <span class="badge badge-yellow">{{ $product->stock }} left</span>
                        @else
                            <span style="color:var(--text)">{{ $product->stock }}</span>
                        @endif
                    </td>
                    <td>
                        @if($product->deleted_at)
                            <span class="badge badge-red">Deleted</span>
                        @elseif($product->is_active)
                            <span class="badge badge-green">Active</span>
                        @else
                            <span class="badge badge-gray">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <div style="display:flex;gap:6px">
                            @if($product->deleted_at)
                                <form method="POST" action="{{ route('products.restore', $product->id) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">Restore</button>
                                </form>
                            @else
                                <a href="{{ route('products.show', $product) }}" class="btn btn-secondary btn-sm">View</a>
                                <a href="{{ route('products.edit', $product) }}" class="btn btn-secondary btn-sm">Edit</a>
                                <form method="POST" action="{{ route('products.destroy', $product) }}">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" data-confirm="Delete this product?">Delete</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;padding:40px;color:var(--muted)">
                        No products found. <a href="{{ route('products.create') }}" style="color:var(--accent)">Add one?</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($products->hasPages())
    <div class="pagination">
        @if($products->onFirstPage())
            <span class="page-link" style="opacity:0.3">←</span>
        @else
            <a href="{{ $products->previousPageUrl() }}" class="page-link">←</a>
        @endif

        @foreach($products->getUrlRange(max(1,$products->currentPage()-2), min($products->lastPage(),$products->currentPage()+2)) as $page => $url)
            <a href="{{ $url }}" class="page-link {{ $page == $products->currentPage() ? 'active' : '' }}">{{ $page }}</a>
        @endforeach

        @if($products->hasMorePages())
            <a href="{{ $products->nextPageUrl() }}" class="page-link">→</a>
        @else
            <span class="page-link" style="opacity:0.3">→</span>
        @endif

        <span style="margin-left:auto;color:var(--muted);font-size:0.85rem">
            Showing {{ $products->firstItem() }}–{{ $products->lastItem() }} of {{ $products->total() }}
        </span>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('[data-confirm]').forEach(el => {
    el.addEventListener('click', e => {
        if (!confirm(el.dataset.confirm)) e.preventDefault();
    });
});
</script>
@endpush
