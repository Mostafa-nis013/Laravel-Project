@extends('layouts.admin')

@section('title', 'Dashboard')
@section('topbar-title', 'Dashboard')

@section('content')
<div class="page-header">
    <div>
        <h2>Good morning ☀</h2>
        <p>Here's what's happening in your store today.</p>
    </div>
    {{-- Current user's role badge --}}
    <div style="display:flex;align-items:center;gap:8px">
        @foreach(auth()->user()->roles as $role)
            @php
                $cls = match($role->name) {
                    'admin'     => 'badge-yellow',
                    'editor'    => 'badge-blue',
                    'moderator' => 'badge-purple',
                    default     => 'badge-green',
                };
            @endphp
            <span class="badge {{ $cls }}" style="padding:6px 14px;font-size:0.82rem">{{ $role->label }}</span>
        @endforeach
    </div>
</div>

{{-- Stats Grid --}}
<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-label">Total Products</div>
        <div class="stat-value">{{ number_format($stats['total_products']) }}</div>
        <div class="stat-sub">{{ $stats['active_products'] }} active</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Low Stock</div>
        <div class="stat-value" style="color:var(--warning)">{{ $stats['low_stock'] }}</div>
        <div class="stat-sub">{{ $stats['out_of_stock'] }} out of stock</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Categories</div>
        <div class="stat-value">{{ $stats['total_categories'] }}</div>
        <div class="stat-sub">Active collections</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Total Orders</div>
        <div class="stat-value">{{ number_format($stats['total_orders']) }}</div>
        <div class="stat-sub">{{ $stats['pending_orders'] }} pending</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Revenue</div>
        <div class="stat-value" style="color:var(--accent)">${{ number_format($stats['total_revenue'], 2) }}</div>
        <div class="stat-sub">From delivered orders</div>
    </div>
    @if(auth()->user()->isAdmin())
    <div class="stat-card">
        <div class="stat-label">Users</div>
        <div class="stat-value">{{ number_format($stats['total_users']) }}</div>
        <div class="stat-sub">{{ $stats['active_users'] }} active</div>
    </div>
    @endif
</div>

<div class="grid-2" style="gap:24px">
    {{-- Recent Orders --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">Recent Orders</span>
            <a href="{{ route('orders.index') }}" class="btn btn-secondary btn-sm">View all</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders as $order)
                    <tr>
                        <td>
                            <a href="{{ route('orders.show', $order) }}" style="color:var(--accent);text-decoration:none;font-weight:500;">
                                {{ $order->order_number }}
                            </a>
                        </td>
                        <td>{{ $order->customer_name }}</td>
                        <td>${{ number_format($order->total, 2) }}</td>
                        <td>
                            <span class="badge badge-{{ $order->status_badge_color }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" style="color:var(--muted);text-align:center;padding:28px">No orders yet</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Top Products --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">Top Products</span>
            <a href="{{ route('products.index') }}" class="btn btn-secondary btn-sm">View all</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Stock</th>
                        <th>Orders</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topProducts as $product)
                    <tr>
                        <td>
                            <a href="{{ route('products.edit', $product) }}" style="color:var(--text);text-decoration:none;font-weight:500;">
                                {{ $product->name }}
                            </a>
                        </td>
                        <td>
                            @if($product->stock == 0)
                                <span class="badge badge-red">Out</span>
                            @elseif($product->stock <= 5)
                                <span class="badge badge-yellow">{{ $product->stock }}</span>
                            @else
                                <span style="color:var(--muted)">{{ $product->stock }}</span>
                            @endif
                        </td>
                        <td style="color:var(--muted)">{{ $product->order_items_count }}</td>
                        <td style="color:var(--accent)">${{ number_format($product->price, 2) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" style="color:var(--muted);text-align:center;padding:28px">No products yet</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
