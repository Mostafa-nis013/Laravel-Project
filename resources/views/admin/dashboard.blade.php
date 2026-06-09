@extends('layouts.admin')

@section('title', 'Dashboard')
@section('topbar-title', 'Dashboard')
@section('breadcrumb')
    <span class="crumb-current">Dashboard</span>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2>Dashboard</h2>
        <p>{{ now()->format('l, F j, Y') }} — Welcome back, {{ auth()->user()->name }}.</p>
    </div>
    <div style="display:flex;align-items:center;gap:8px">
        @foreach(auth()->user()->roles as $role)
        @php $cls = match($role->name) { 'admin'=>'badge-yellow','editor'=>'badge-blue','moderator'=>'badge-purple',default=>'badge-green' }; @endphp
        <span class="badge {{ $cls }}" style="padding:6px 14px">{{ $role->label }}</span>
        @endforeach
    </div>
</div>

{{-- KPI cards --}}
<div class="stat-grid">
    <div class="stat-card" style="border-left:3px solid var(--accent)">
        <div class="stat-label">Total Revenue</div>
        <div class="stat-value" style="color:var(--accent)">${{ number_format($stats['total_revenue'], 0) }}</div>
        <div class="stat-sub">From delivered orders</div>
    </div>
    <div class="stat-card" style="border-left:3px solid var(--info)">
        <div class="stat-label">Total Orders</div>
        <div class="stat-value">{{ number_format($stats['total_orders']) }}</div>
        <div class="stat-sub">
            @if($stats['pending_orders'] > 0)
                <span style="color:var(--warning)">{{ $stats['pending_orders'] }} pending action</span>
            @else
                All up to date
            @endif
        </div>
    </div>
    <div class="stat-card" style="border-left:3px solid var(--success)">
        <div class="stat-label">Active Products</div>
        <div class="stat-value">{{ $stats['active_products'] }}</div>
        <div class="stat-sub">of {{ $stats['total_products'] }} total</div>
    </div>
    <div class="stat-card" style="border-left:3px solid var(--warning)">
        <div class="stat-label">Inventory Alerts</div>
        <div class="stat-value" style="color:var(--warning)">{{ $stats['low_stock'] + $stats['out_of_stock'] }}</div>
        <div class="stat-sub">{{ $stats['out_of_stock'] }} out of stock, {{ $stats['low_stock'] }} low</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Categories</div>
        <div class="stat-value">{{ $stats['total_categories'] }}</div>
        <div class="stat-sub">Active collections</div>
    </div>
    @if(auth()->user()->isAdmin())
    <div class="stat-card" style="border-left:3px solid #a878e8">
        <div class="stat-label">Registered Users</div>
        <div class="stat-value">{{ number_format($stats['total_users']) }}</div>
        <div class="stat-sub">{{ $stats['active_users'] }} active accounts</div>
    </div>
    @endif
</div>

{{-- Revenue sparkline --}}
<div class="card" style="margin-bottom:20px">
    <div class="card-header">
        <span class="card-title">Revenue — Last 14 Days</span>
        <a href="{{ route('admin.reports') }}" class="btn btn-secondary btn-sm">Full reports →</a>
    </div>
    <div class="card-body" style="padding:16px 24px">
        <canvas id="sparkRevenue" height="55"></canvas>
    </div>
</div>

<div class="grid-2" style="gap:20px">

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
                            <a href="{{ route('orders.show', $order) }}" style="color:var(--accent);text-decoration:none;font-weight:500;font-family:monospace;font-size:0.82rem">
                                {{ $order->order_number }}
                            </a>
                        </td>
                        <td>
                            <div style="font-weight:500;font-size:0.875rem">{{ $order->customer_name }}</div>
                        </td>
                        <td style="font-weight:600">${{ number_format($order->total, 2) }}</td>
                        <td>
                            <span class="badge badge-{{ $order->status_badge_color }}">{{ ucfirst($order->status) }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" style="color:var(--muted);text-align:center;padding:32px">No orders yet</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Top Products + recent activity side by side --}}
    <div style="display:flex;flex-direction:column;gap:20px">

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
                            <th>Sold</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topProducts as $product)
                        <tr>
                            <td>
                                <a href="{{ route('products.edit', $product) }}" style="color:var(--text);text-decoration:none;font-weight:500;font-size:0.875rem">
                                    {{ Str::limit($product->name, 22) }}
                                </a>
                            </td>
                            <td>
                                @if($product->stock == 0)
                                    <span class="badge badge-red">Out</span>
                                @elseif($product->stock <= 5)
                                    <span class="badge badge-yellow">{{ $product->stock }}</span>
                                @else
                                    <span style="color:var(--muted);font-size:0.82rem">{{ $product->stock }}</span>
                                @endif
                            </td>
                            <td style="color:var(--muted);font-size:0.82rem">{{ $product->order_items_count }}</td>
                            <td style="color:var(--accent);font-weight:600;font-size:0.82rem">${{ number_format($product->price, 2) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" style="color:var(--muted);text-align:center;padding:24px">No products yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Quick actions --}}
        <div class="card">
            <div class="card-header"><span class="card-title">Quick Actions</span></div>
            <div class="card-body" style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
                <a href="{{ route('products.create') }}" class="btn btn-secondary" style="justify-content:center;padding:12px">
                    <span>◈</span> New Product
                </a>
                <a href="{{ route('categories.create') }}" class="btn btn-secondary" style="justify-content:center;padding:12px">
                    <span>◉</span> New Category
                </a>
                <a href="{{ route('orders.create') }}" class="btn btn-secondary" style="justify-content:center;padding:12px">
                    <span>◷</span> New Order
                </a>
                @if(auth()->user()->isAdmin())
                <a href="{{ route('users.create') }}" class="btn btn-secondary" style="justify-content:center;padding:12px">
                    <span>◎</span> New User
                </a>
                @else
                <a href="{{ route('admin.reports') }}" class="btn btn-secondary" style="justify-content:center;padding:12px">
                    <span>◱</span> Reports
                </a>
                @endif
            </div>
        </div>

    </div>
</div>

{{-- Recent Activity feed --}}
<div class="card" style="margin-top:20px">
    <div class="card-header">
        <span class="card-title">Recent Activity</span>
        <a href="{{ route('admin.activity') }}" class="btn btn-secondary btn-sm">Full log →</a>
    </div>
    @if($recentActivity->isEmpty())
    <div style="padding:32px;text-align:center;color:var(--muted);font-size:0.875rem">No activity recorded yet. Actions you take will appear here.</div>
    @else
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr))">
        @foreach($recentActivity as $log)
        @php
            $iconMap = [
                'created'  => ['icon'=>'+','bg'=>'rgba(82,192,122,0.12)','color'=>'var(--success)'],
                'updated'  => ['icon'=>'✎','bg'=>'rgba(82,130,224,0.12)','color'=>'var(--info)'],
                'deleted'  => ['icon'=>'✕','bg'=>'rgba(224,82,82,0.12)','color'=>'var(--danger)'],
                'restored' => ['icon'=>'↩','bg'=>'rgba(232,200,122,0.12)','color'=>'var(--accent)'],
                'login'    => ['icon'=>'→','bg'=>'rgba(150,100,220,0.12)','color'=>'#a878e8'],
                'status'   => ['icon'=>'◷','bg'=>'rgba(82,130,224,0.12)','color'=>'var(--info)'],
            ];
            $icon = $iconMap[$log->action] ?? ['icon'=>'·','bg'=>'var(--surface2)','color'=>'var(--muted)'];
        @endphp
        <div style="display:flex;align-items:center;gap:10px;padding:12px 20px;border-bottom:1px solid var(--border)">
            <div style="width:28px;height:28px;border-radius:50%;background:{{ $icon['bg'] }};color:{{ $icon['color'] }};display:flex;align-items:center;justify-content:center;font-size:0.78rem;font-weight:700;flex-shrink:0">
                {{ $icon['icon'] }}
            </div>
            <div style="flex:1;min-width:0">
                <div style="font-size:0.82rem;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $log->description }}</div>
                <div style="font-size:0.72rem;color:var(--muted);margin-top:2px">
                    {{ $log->user->name ?? 'System' }} · {{ $log->created_at->diffForHumans() }}
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection

@push('styles')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
@endpush

@push('scripts')
<script>
const sparkData = @json($sparkRevenue);

Chart.defaults.color = '#7a7880';
Chart.defaults.borderColor = '#2a2a32';
Chart.defaults.font.family = "'DM Sans', sans-serif";

const ctx = document.getElementById('sparkRevenue').getContext('2d');
const grad = ctx.createLinearGradient(0, 0, 0, 120);
grad.addColorStop(0, 'rgba(232,200,122,0.2)');
grad.addColorStop(1, 'rgba(232,200,122,0)');

new Chart(ctx, {
    type: 'line',
    data: {
        labels: sparkData.map(d => d.date),
        datasets: [{
            data: sparkData.map(d => d.revenue),
            borderColor: '#e8c87a',
            backgroundColor: grad,
            borderWidth: 2,
            pointRadius: 0,
            pointHoverRadius: 4,
            pointHoverBackgroundColor: '#e8c87a',
            fill: true,
            tension: 0.4,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: '#1c1c21',
                borderColor: '#2a2a32',
                borderWidth: 1,
                padding: 10,
                callbacks: { label: ctx => ` $${ctx.parsed.y.toFixed(2)}` }
            }
        },
        scales: {
            x: { grid: { display: false }, ticks: { maxTicksLimit: 7 } },
            y: {
                grid: { color: 'rgba(42,42,50,0.4)' },
                ticks: { callback: v => '$' + v.toLocaleString() }
            }
        }
    }
});
</script>
@endpush
