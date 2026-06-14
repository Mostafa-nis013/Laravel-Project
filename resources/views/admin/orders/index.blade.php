@extends('layouts.admin')

@section('title', 'Orders')
@section('topbar-title', 'Orders')
@section('breadcrumb')
    <span style="color:var(--muted)">Sales</span>
    <span class="crumb-sep">›</span>
    <span class="crumb-current">Orders</span>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2>Orders</h2>
        <p>{{ $orders->total() }} total orders</p>
    </div>
    <a href="{{ route('orders.create') }}" class="btn btn-primary">+ New Order</a>
</div>

{{-- Status tabs --}}
<div style="display:flex;gap:8px;margin-bottom:20px;flex-wrap:wrap">
    <a href="{{ route('orders.index') }}" class="badge {{ !request('status') ? 'badge-yellow' : 'badge-gray' }}" style="text-decoration:none;padding:7px 14px;font-size:0.82rem">
        All ({{ $orders->total() }})
    </a>
    @foreach(\App\Models\Order::STATUSES as $key => $label)
    <a href="{{ route('orders.index', ['status' => $key]) }}" class="badge {{ request('status') == $key ? 'badge-yellow' : 'badge-gray' }}" style="text-decoration:none;padding:7px 14px;font-size:0.82rem">
        {{ $label }} ({{ $statusCounts[$key] ?? 0 }})
    </a>
    @endforeach
</div>

<form method="GET" action="{{ route('orders.index') }}" style="margin-bottom:20px">
    @if(request('status'))<input type="hidden" name="status" value="{{ request('status') }}">@endif
    <div class="filters-bar" style="margin-bottom:0">
        <input type="text" name="search" placeholder="Search by order #, name, or email…" value="{{ request('search') }}">
        <button type="submit" class="btn btn-secondary">Search</button>
        @if(request('search'))
            <a href="{{ route('orders.index', array_filter(['status' => request('status')])) }}" class="btn btn-secondary">Clear</a>
        @endif
    </div>
</form>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Customer</th>
                    <th>Items</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td>
                        <a href="{{ route('orders.show', $order) }}" style="color:var(--accent);text-decoration:none;font-weight:600;font-family:monospace">
                            {{ $order->order_number }}
                        </a>
                    </td>
                    <td>
                        <div style="font-weight:500">{{ $order->customer_name }}</div>
                        <div style="font-size:0.78rem;color:var(--muted)">{{ $order->customer_email }}</div>
                    </td>
                    <td style="color:var(--muted)">{{ $order->items->count() }} item(s)</td>
                    <td style="font-weight:600">${{ number_format($order->total, 2) }}</td>
                    <td>
                        <span class="badge badge-{{ $order->status_badge_color }}">{{ ucfirst($order->status) }}</span>
                    </td>
                    <td style="color:var(--muted);font-size:0.85rem">{{ $order->created_at->format('M d, Y') }}</td>
                    <td>
                        <div style="display:flex;gap:6px">
                            <a href="{{ route('orders.show', $order) }}" class="btn btn-secondary btn-sm">View</a>
                            <a href="{{ route('orders.edit', $order) }}" class="btn btn-secondary btn-sm">Edit</a>
                            <form method="POST" action="{{ route('orders.destroy', $order) }}">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" data-confirm="Delete order {{ $order->order_number }}? Stock will be restored.">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;padding:40px;color:var(--muted)">
                        No orders found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($orders->hasPages())
    <div class="pagination">
        @if($orders->onFirstPage())
            <span class="page-link" style="opacity:0.3">←</span>
        @else
            <a href="{{ $orders->previousPageUrl() }}" class="page-link">←</a>
        @endif
        @foreach($orders->getUrlRange(max(1,$orders->currentPage()-2), min($orders->lastPage(),$orders->currentPage()+2)) as $page => $url)
            <a href="{{ $url }}" class="page-link {{ $page == $orders->currentPage() ? 'active' : '' }}">{{ $page }}</a>
        @endforeach
        @if($orders->hasMorePages())
            <a href="{{ $orders->nextPageUrl() }}" class="page-link">→</a>
        @else
            <span class="page-link" style="opacity:0.3">→</span>
        @endif
        <span style="margin-left:auto;color:var(--muted);font-size:0.85rem">
            {{ $orders->firstItem() }}–{{ $orders->lastItem() }} of {{ $orders->total() }}
        </span>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('[data-confirm]').forEach(el => {
    el.addEventListener('click', e => { if (!confirm(el.dataset.confirm)) e.preventDefault(); });
});
</script>
@endpush
