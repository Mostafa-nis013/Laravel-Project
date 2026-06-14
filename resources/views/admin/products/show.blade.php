@extends('layouts.admin')

@section('title', $product->name)
@section('topbar-title', 'Products / Detail')
@section('breadcrumb')
    <span style="color:var(--muted)">Catalog</span>
    <span class="crumb-sep">›</span>
    <span class="crumb-current">Products / Detail</span>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2>{{ $product->name }}</h2>
        <p>SKU: <span style="font-family:monospace;color:var(--accent)">{{ $product->sku }}</span></p>
    </div>
    <div style="display:flex;gap:10px">
        <a href="{{ route('products.edit', $product) }}" class="btn btn-primary">Edit Product</a>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">← Back</a>
    </div>
</div>

<div class="grid-2" style="align-items:start">
    <div style="display:flex;flex-direction:column;gap:20px">
        {{-- Image --}}
        <div class="card">
            <div style="height:280px;background:var(--surface2);border-radius:var(--radius);overflow:hidden;display:flex;align-items:center;justify-content:center;color:var(--muted);font-size:3rem">
                @if($product->image)
                    <img src="{{ asset('storage/'.$product->image) }}" style="width:100%;height:100%;object-fit:cover">
                @else
                    ◈
                @endif
            </div>
        </div>

        {{-- Description --}}
        <div class="card">
            <div class="card-header"><span class="card-title">Description</span></div>
            <div class="card-body" style="color:var(--muted);line-height:1.7;font-size:0.9rem">
                {{ $product->description ?? 'No description provided.' }}
            </div>
        </div>
    </div>

    <div style="display:flex;flex-direction:column;gap:20px">
        {{-- Details --}}
        <div class="card">
            <div class="card-header"><span class="card-title">Details</span></div>
            <div class="card-body">
                <table style="width:100%">
                    <tbody>
                        <tr>
                            <td style="color:var(--muted);padding:10px 0;font-size:0.875rem;border-bottom:1px solid var(--border)">Category</td>
                            <td style="text-align:right;padding:10px 0;border-bottom:1px solid var(--border)">{{ $product->category->name ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td style="color:var(--muted);padding:10px 0;font-size:0.875rem;border-bottom:1px solid var(--border)">Price</td>
                            <td style="text-align:right;padding:10px 0;border-bottom:1px solid var(--border);color:var(--accent)">${{ number_format($product->price,2) }}</td>
                        </tr>
                        @if($product->is_on_sale)
                        <tr>
                            <td style="color:var(--muted);padding:10px 0;font-size:0.875rem;border-bottom:1px solid var(--border)">Sale Price</td>
                            <td style="text-align:right;padding:10px 0;border-bottom:1px solid var(--border);color:var(--success)">${{ number_format($product->sale_price,2) }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td style="color:var(--muted);padding:10px 0;font-size:0.875rem;border-bottom:1px solid var(--border)">Stock</td>
                            <td style="text-align:right;padding:10px 0;border-bottom:1px solid var(--border)">
                                @if($product->stock == 0)
                                    <span class="badge badge-red">Out of stock</span>
                                @elseif($product->stock <= 5)
                                    <span class="badge badge-yellow">{{ $product->stock }} remaining</span>
                                @else
                                    {{ $product->stock }} units
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td style="color:var(--muted);padding:10px 0;font-size:0.875rem;border-bottom:1px solid var(--border)">Status</td>
                            <td style="text-align:right;padding:10px 0;border-bottom:1px solid var(--border)">
                                <span class="badge {{ $product->is_active ? 'badge-green' : 'badge-gray' }}">
                                    {{ $product->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td style="color:var(--muted);padding:10px 0;font-size:0.875rem;border-bottom:1px solid var(--border)">Featured</td>
                            <td style="text-align:right;padding:10px 0;border-bottom:1px solid var(--border)">
                                <span class="badge {{ $product->is_featured ? 'badge-yellow' : 'badge-gray' }}">
                                    {{ $product->is_featured ? 'Yes' : 'No' }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td style="color:var(--muted);padding:10px 0;font-size:0.875rem">Created</td>
                            <td style="text-align:right;padding:10px 0;color:var(--muted);font-size:0.875rem">{{ $product->created_at->format('M d, Y') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Order history --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title">Order History</span>
                <span class="badge badge-blue">{{ $product->orderItems->count() }} orders</span>
            </div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Qty</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($product->orderItems->take(6) as $item)
                        <tr>
                            <td>
                                <a href="{{ route('orders.show', $item->order) }}" style="color:var(--accent);text-decoration:none">
                                    {{ $item->order->order_number }}
                                </a>
                            </td>
                            <td style="color:var(--muted)">×{{ $item->quantity }}</td>
                            <td style="color:var(--text)">${{ number_format($item->subtotal, 2) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" style="text-align:center;padding:20px;color:var(--muted)">No orders yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
