@extends('layouts.admin')

@section('title', $category->name)
@section('topbar-title', 'Categories / Detail')

@section('content')
<div class="page-header">
    <div>
        <h2>{{ $category->name }}</h2>
        <p>{{ $category->products->count() }} products in this category</p>
    </div>
    <div style="display:flex;gap:10px">
        <a href="{{ route('categories.edit', $category) }}" class="btn btn-primary">Edit Category</a>
        <a href="{{ route('categories.index') }}" class="btn btn-secondary">← Back</a>
    </div>
</div>

<div class="grid-2" style="align-items:start">
    <div style="display:flex;flex-direction:column;gap:20px">
        @if($category->image)
        <div class="card">
            <div style="height:220px;overflow:hidden;border-radius:var(--radius)">
                <img src="{{ asset('storage/'.$category->image) }}" style="width:100%;height:100%;object-fit:cover">
            </div>
        </div>
        @endif

        <div class="card">
            <div class="card-header"><span class="card-title">Info</span></div>
            <div class="card-body">
                <table style="width:100%">
                    <tr>
                        <td style="color:var(--muted);padding:10px 0;border-bottom:1px solid var(--border)">Slug</td>
                        <td style="text-align:right;padding:10px 0;border-bottom:1px solid var(--border);font-family:monospace;color:var(--accent)">{{ $category->slug }}</td>
                    </tr>
                    <tr>
                        <td style="color:var(--muted);padding:10px 0;border-bottom:1px solid var(--border)">Parent</td>
                        <td style="text-align:right;padding:10px 0;border-bottom:1px solid var(--border)">{{ $category->parent->name ?? '—' }}</td>
                    </tr>
                    <tr>
                        <td style="color:var(--muted);padding:10px 0;border-bottom:1px solid var(--border)">Status</td>
                        <td style="text-align:right;padding:10px 0;border-bottom:1px solid var(--border)">
                            <span class="badge {{ $category->is_active ? 'badge-green' : 'badge-gray' }}">{{ $category->is_active ? 'Active' : 'Inactive' }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="color:var(--muted);padding:10px 0">Sort Order</td>
                        <td style="text-align:right;padding:10px 0;color:var(--muted)">{{ $category->sort_order }}</td>
                    </tr>
                </table>

                @if($category->description)
                    <p style="margin-top:16px;color:var(--muted);font-size:0.9rem;line-height:1.6">{{ $category->description }}</p>
                @endif
            </div>
        </div>

        @if($category->children->count())
        <div class="card">
            <div class="card-header"><span class="card-title">Sub-categories</span></div>
            <div class="card-body" style="display:flex;flex-wrap:wrap;gap:8px">
                @foreach($category->children as $child)
                    <a href="{{ route('categories.show', $child) }}" class="badge badge-blue" style="text-decoration:none">{{ $child->name }}</a>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <div class="card">
        <div class="card-header">
            <span class="card-title">Products in this Category</span>
            <a href="{{ route('products.index', ['category_id' => $category->id]) }}" class="btn btn-secondary btn-sm">View all</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Stock</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($category->products->take(10) as $product)
                    <tr>
                        <td>
                            <a href="{{ route('products.show', $product) }}" style="color:var(--text);text-decoration:none;font-weight:500">{{ $product->name }}</a>
                        </td>
                        <td style="color:var(--accent)">${{ number_format($product->price,2) }}</td>
                        <td>
                            @if($product->stock == 0)
                                <span class="badge badge-red">Out</span>
                            @else
                                <span style="color:var(--muted)">{{ $product->stock }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="3" style="text-align:center;padding:24px;color:var(--muted)">No products in this category yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
