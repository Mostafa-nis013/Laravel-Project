@extends('layouts.admin')

@section('title', 'Reports')
@section('topbar-title', 'Reports')
@section('breadcrumb')
    <span class="crumb-current">Reports</span>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2>Reports</h2>
        <p>Store performance and analytics overview.</p>
    </div>
    <div class="page-header-actions">
        <form method="GET" action="{{ route('admin.reports') }}" style="display:flex;gap:8px">
            <select name="period" onchange="this.form.submit()" style="width:auto">
                <option value="7"  {{ $period == 7  ? 'selected' : '' }}>Last 7 days</option>
                <option value="30" {{ $period == 30 ? 'selected' : '' }}>Last 30 days</option>
                <option value="60" {{ $period == 60 ? 'selected' : '' }}>Last 60 days</option>
                <option value="90" {{ $period == 90 ? 'selected' : '' }}>Last 90 days</option>
            </select>
        </form>
    </div>
</div>

{{-- Summary KPIs --}}
<div class="stat-grid" style="grid-template-columns:repeat(auto-fill,minmax(170px,1fr))">
    <div class="stat-card">
        <div class="stat-label">Period Revenue</div>
        <div class="stat-value" style="color:var(--accent)">${{ number_format($summary['period_revenue'], 0) }}</div>
        <div class="stat-sub">Last {{ $period }} days</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Total Revenue</div>
        <div class="stat-value">${{ number_format($summary['total_revenue'], 0) }}</div>
        <div class="stat-sub">All time</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Period Orders</div>
        <div class="stat-value">{{ number_format($summary['period_orders']) }}</div>
        <div class="stat-sub">Last {{ $period }} days</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Avg Order Value</div>
        <div class="stat-value">${{ number_format($summary['avg_order_value'], 2) }}</div>
        <div class="stat-sub">Delivered orders</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Total Customers</div>
        <div class="stat-value">{{ number_format($summary['total_customers']) }}</div>
        <div class="stat-sub">{{ $summary['new_customers'] }} new this period</div>
    </div>
</div>

{{-- Revenue Chart --}}
<div class="card" style="margin-bottom:20px">
    <div class="card-header">
        <span class="card-title">Revenue Over Time</span>
        <div style="display:flex;gap:16px;align-items:center;font-size:0.8rem;color:var(--muted)">
            <span style="display:flex;align-items:center;gap:5px"><span style="width:10px;height:10px;border-radius:50%;background:var(--accent);display:inline-block"></span>Revenue</span>
            <span style="display:flex;align-items:center;gap:5px"><span style="width:10px;height:10px;border-radius:50%;background:var(--info);display:inline-block"></span>Orders</span>
        </div>
    </div>
    <div class="card-body" style="padding:20px 24px 16px">
        <canvas id="revenueChart" height="90"></canvas>
    </div>
</div>

<div class="grid-2" style="gap:20px;margin-bottom:20px">

    {{-- Top Products table --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">Top Selling Products</span>
            <a href="{{ route('products.index') }}" class="btn btn-secondary btn-sm">View all</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th>Orders</th>
                        <th>Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topProducts as $i => $product)
                    <tr>
                        <td style="color:var(--muted);font-size:0.8rem;font-weight:700">{{ $i + 1 }}</td>
                        <td>
                            <a href="{{ route('products.show', $product) }}" style="color:var(--text);text-decoration:none;font-weight:500">{{ $product->name }}</a>
                        </td>
                        <td><span class="badge badge-blue">{{ $product->order_items_count }}</span></td>
                        <td style="color:var(--accent);font-weight:600">${{ number_format($product->order_items_sum_subtotal ?? 0, 2) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" style="text-align:center;padding:28px;color:var(--muted)">No data yet</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Orders by Status --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">Orders by Status</span>
        </div>
        <div class="card-body">
            <canvas id="statusChart" height="180"></canvas>
            <div style="margin-top:16px;display:flex;flex-direction:column;gap:8px">
                @foreach($revenueByStatus as $row)
                @php
                    $statusColors = [
                        'pending'    => 'var(--accent)',
                        'processing' => 'var(--info)',
                        'shipped'    => '#a878e8',
                        'delivered'  => 'var(--success)',
                        'cancelled'  => 'var(--danger)',
                        'refunded'   => 'var(--muted)',
                    ];
                    $color = $statusColors[$row->status] ?? 'var(--muted)';
                @endphp
                <div style="display:flex;align-items:center;gap:10px">
                    <div style="width:10px;height:10px;border-radius:50%;background:{{ $color }};flex-shrink:0"></div>
                    <span style="font-size:0.85rem;flex:1;color:var(--text)">{{ ucfirst($row->status) }}</span>
                    <span class="badge badge-gray">{{ $row->count }}</span>
                    <span style="font-size:0.82rem;color:var(--accent);font-weight:600;min-width:70px;text-align:right">${{ number_format($row->revenue, 0) }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<div class="grid-2" style="gap:20px">
    {{-- Revenue by Category --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">Revenue by Category</span>
        </div>
        <div class="card-body">
            @php $maxRev = $revenueByCategory->max('revenue') ?: 1; @endphp
            <div style="display:flex;flex-direction:column;gap:14px">
                @foreach($revenueByCategory as $cat)
                <div>
                    <div style="display:flex;justify-content:space-between;margin-bottom:5px;font-size:0.85rem">
                        <span style="font-weight:500">{{ $cat['name'] }}</span>
                        <span style="color:var(--accent);font-weight:600">${{ number_format($cat['revenue'], 0) }}</span>
                    </div>
                    <div style="height:6px;background:var(--surface2);border-radius:4px;overflow:hidden">
                        <div style="height:100%;background:linear-gradient(90deg,var(--accent),var(--accent-dim));border-radius:4px;width:{{ $maxRev > 0 ? ($cat['revenue'] / $maxRev * 100) : 0 }}%;transition:width 0.6s ease"></div>
                    </div>
                    <div style="font-size:0.72rem;color:var(--muted);margin-top:3px">{{ $cat['products'] }} products</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Orders by Day of Week --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">Orders by Day of Week</span>
        </div>
        <div class="card-body">
            <canvas id="dowChart" height="200"></canvas>
        </div>
    </div>
</div>
@endsection

@push('styles')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
@endpush

@push('scripts')
<script>
const revSeries = @json($revenueSeries);
const dowSeries = @json($dowSeries);
const statusData = @json($revenueByStatus);

// ── Shared chart defaults ─────────────────────────────────────────────────────
Chart.defaults.color = '#7a7880';
Chart.defaults.borderColor = '#2a2a32';
Chart.defaults.font.family = "'DM Sans', sans-serif";

// ── Revenue line chart ────────────────────────────────────────────────────────
const revenueCtx = document.getElementById('revenueChart').getContext('2d');

const gradientRev = revenueCtx.createLinearGradient(0, 0, 0, 200);
gradientRev.addColorStop(0, 'rgba(232,200,122,0.18)');
gradientRev.addColorStop(1, 'rgba(232,200,122,0)');

new Chart(revenueCtx, {
    type: 'line',
    data: {
        labels: revSeries.map(d => d.date),
        datasets: [
            {
                label: 'Revenue ($)',
                data: revSeries.map(d => d.revenue),
                borderColor: '#e8c87a',
                backgroundColor: gradientRev,
                borderWidth: 2,
                pointRadius: 0,
                pointHoverRadius: 5,
                pointHoverBackgroundColor: '#e8c87a',
                fill: true,
                tension: 0.4,
                yAxisID: 'y',
            },
            {
                label: 'Orders',
                data: revSeries.map(d => d.orders),
                borderColor: '#5282e0',
                backgroundColor: 'transparent',
                borderWidth: 2,
                pointRadius: 0,
                pointHoverRadius: 5,
                pointHoverBackgroundColor: '#5282e0',
                borderDash: [4, 3],
                tension: 0.4,
                yAxisID: 'y1',
            }
        ]
    },
    options: {
        responsive: true,
        interaction: { mode: 'index', intersect: false },
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: '#1c1c21',
                borderColor: '#2a2a32',
                borderWidth: 1,
                padding: 12,
                callbacks: {
                    label: ctx => ctx.datasetIndex === 0
                        ? ` $${ctx.parsed.y.toFixed(2)}`
                        : ` ${ctx.parsed.y} orders`
                }
            }
        },
        scales: {
            x: { grid: { color: 'rgba(42,42,50,0.5)' }, ticks: { maxTicksLimit: 8 } },
            y: {
                position: 'left',
                grid: { color: 'rgba(42,42,50,0.5)' },
                ticks: { callback: v => '$' + v.toLocaleString() }
            },
            y1: {
                position: 'right',
                grid: { drawOnChartArea: false },
                ticks: { callback: v => v + ' ord' }
            }
        }
    }
});

// ── Status doughnut chart ─────────────────────────────────────────────────────
const statusCtx = document.getElementById('statusChart').getContext('2d');
const statusColors = {
    pending:'#e8c87a', processing:'#5282e0', shipped:'#a878e8',
    delivered:'#52c07a', cancelled:'#e05252', refunded:'#7a7880'
};

new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: statusData.map(d => d.status.charAt(0).toUpperCase() + d.status.slice(1)),
        datasets: [{
            data: statusData.map(d => d.count),
            backgroundColor: statusData.map(d => statusColors[d.status] || '#7a7880'),
            borderWidth: 2,
            borderColor: '#141417',
            hoverOffset: 6,
        }]
    },
    options: {
        cutout: '65%',
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: '#1c1c21',
                borderColor: '#2a2a32',
                borderWidth: 1,
                padding: 10,
            }
        }
    }
});

// ── Day-of-week bar chart ─────────────────────────────────────────────────────
const dowCtx = document.getElementById('dowChart').getContext('2d');

const gradientDow = dowCtx.createLinearGradient(0, 0, 0, 200);
gradientDow.addColorStop(0, 'rgba(82,130,224,0.7)');
gradientDow.addColorStop(1, 'rgba(82,130,224,0.2)');

new Chart(dowCtx, {
    type: 'bar',
    data: {
        labels: dowSeries.map(d => d.day),
        datasets: [{
            label: 'Orders',
            data: dowSeries.map(d => d.count),
            backgroundColor: gradientDow,
            borderColor: '#5282e0',
            borderWidth: 1,
            borderRadius: 5,
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
            }
        },
        scales: {
            x: { grid: { display: false } },
            y: {
                grid: { color: 'rgba(42,42,50,0.5)' },
                ticks: { stepSize: 1, precision: 0 }
            }
        }
    }
});
</script>
@endpush
