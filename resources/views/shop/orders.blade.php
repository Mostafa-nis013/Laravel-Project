@extends('layouts.shop')

@section('title', 'My Orders')

@section('content')
<div style="display:flex;align-items:baseline;justify-content:space-between;margin-bottom:28px">
    <div>
        <h1 style="font-family:'DM Serif Display',serif;font-size:1.9rem;margin-bottom:4px">My Orders</h1>
        <p style="color:var(--muted);font-size:0.875rem">{{ $orders->total() }} {{ Str::plural('order', $orders->total()) }} placed</p>
    </div>
    <a href="{{ route('shop.index') }}" class="btn btn-secondary btn-sm">← Keep Shopping</a>
</div>

@if($orders->isEmpty())
<div style="text-align:center;padding:80px 20px">
    <div style="font-size:4rem;margin-bottom:16px;opacity:0.3">📦</div>
    <h2 style="font-family:'DM Serif Display',serif;font-size:1.5rem;margin-bottom:10px">No orders yet</h2>
    <p style="color:var(--muted);margin-bottom:24px">You haven't placed any orders. Browse our store and find something you love!</p>
    <a href="{{ route('shop.index') }}" class="btn btn-primary">Start Shopping</a>
</div>
@else

<div style="display:flex;flex-direction:column;gap:16px">
    @foreach($orders as $order)
    @php
        $statusColors = [
            'pending'    => ['bg'=>'rgba(232,200,122,0.1)','color'=>'var(--accent)','border'=>'rgba(232,200,122,0.3)'],
            'processing' => ['bg'=>'rgba(82,130,224,0.1)', 'color'=>'#7aabf0',      'border'=>'rgba(82,130,224,0.3)'],
            'shipped'    => ['bg'=>'rgba(150,100,220,0.1)','color'=>'#c090f0',      'border'=>'rgba(150,100,220,0.3)'],
            'delivered'  => ['bg'=>'rgba(82,192,122,0.1)', 'color'=>'var(--success)','border'=>'rgba(82,192,122,0.3)'],
            'cancelled'  => ['bg'=>'rgba(224,82,82,0.1)',  'color'=>'var(--danger)', 'border'=>'rgba(224,82,82,0.3)'],
            'refunded'   => ['bg'=>'rgba(122,120,128,0.1)','color'=>'var(--muted)', 'border'=>'rgba(122,120,128,0.3)'],
        ];
        $sc = $statusColors[$order->status] ?? $statusColors['pending'];
    @endphp
    <div style="background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);overflow:hidden">

        {{-- Order header --}}
        <div style="padding:16px 22px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
            <div style="display:flex;align-items:center;gap:20px;flex-wrap:wrap">
                <div>
                    <div style="font-size:0.72rem;color:var(--muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:2px">Order</div>
                    <div style="font-family:monospace;font-weight:700;color:var(--accent)">{{ $order->order_number }}</div>
                </div>
                <div>
                    <div style="font-size:0.72rem;color:var(--muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:2px">Placed</div>
                    <div style="font-size:0.875rem">{{ $order->created_at->format('M d, Y') }}</div>
                </div>
                <div>
                    <div style="font-size:0.72rem;color:var(--muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:2px">Total</div>
                    <div style="font-size:0.95rem;font-weight:700">${{ number_format($order->total, 2) }}</div>
                </div>
                <div>
                    <div style="font-size:0.72rem;color:var(--muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:2px">Items</div>
                    <div style="font-size:0.875rem">{{ $order->items->count() }}</div>
                </div>
            </div>
            <div style="display:flex;align-items:center;gap:10px">
                <span style="display:inline-flex;align-items:center;padding:5px 14px;border-radius:20px;font-size:0.78rem;font-weight:600;background:{{ $sc['bg'] }};color:{{ $sc['color'] }};border:1px solid {{ $sc['border'] }}">
                    {{ ucfirst($order->status) }}
                </span>
            </div>
        </div>

        {{-- Progress bar for active orders --}}
        @if(!in_array($order->status, ['cancelled','refunded']))
        @php
            $steps = ['pending','processing','shipped','delivered'];
            $currentStep = array_search($order->status, $steps);
        @endphp
        <div style="padding:14px 22px;border-bottom:1px solid var(--border);background:var(--surface2)">
            <div style="display:flex;align-items:center;justify-content:space-between">
                @foreach(['Order Placed','Processing','Shipped','Delivered'] as $si => $sLabel)
                <div style="display:flex;flex-direction:column;align-items:center;gap:5px;flex:1">
                    <div style="width:28px;height:28px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:0.72rem;font-weight:700;
                        background:{{ $si <= $currentStep ? 'var(--accent)' : 'var(--surface)' }};
                        color:{{ $si <= $currentStep ? '#0c0c0e' : 'var(--muted)' }};
                        border:2px solid {{ $si <= $currentStep ? 'var(--accent)' : 'var(--border)' }}">
                        {{ $si <= $currentStep ? '✓' : ($si + 1) }}
                    </div>
                    <span style="font-size:0.68rem;color:{{ $si <= $currentStep ? 'var(--text)' : 'var(--muted)' }};text-align:center;white-space:nowrap">{{ $sLabel }}</span>
                </div>
                @if($si < 3)
                <div style="flex:1;height:2px;background:{{ $si < $currentStep ? 'var(--accent)' : 'var(--border)' }};margin:0 4px;margin-bottom:20px;max-width:80px"></div>
                @endif
                @endforeach
            </div>
        </div>
        @endif

        {{-- Items preview --}}
        <div style="padding:16px 22px;display:flex;gap:10px;flex-wrap:wrap;align-items:center">
            @foreach($order->items->take(4) as $item)
            <div style="display:flex;align-items:center;gap:8px">
                <div style="width:44px;height:44px;border-radius:7px;background:var(--surface2);border:1px solid var(--border);overflow:hidden;display:flex;align-items:center;justify-content:center;color:var(--border);font-size:1rem">
                    @if($item->product?->image)
                        <img src="{{ asset('storage/'.$item->product->image) }}" alt="{{ $item->product_name }}" style="width:100%;height:100%;object-fit:cover">
                    @else
                        ◈
                    @endif
                </div>
                <div>
                    <div style="font-size:0.8rem;font-weight:500;max-width:120px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $item->product_name }}</div>
                    <div style="font-size:0.72rem;color:var(--muted)">×{{ $item->quantity }} · ${{ number_format($item->unit_price, 2) }}</div>
                </div>
            </div>
            @endforeach
            @if($order->items->count() > 4)
            <div style="font-size:0.8rem;color:var(--muted)">+{{ $order->items->count() - 4 }} more</div>
            @endif
        </div>
    </div>
    @endforeach
</div>

{{-- Pagination --}}
@if($orders->hasPages())
<div style="display:flex;justify-content:center;gap:6px;margin-top:28px">
    @if($orders->onFirstPage())
        <span style="padding:8px 14px;border-radius:8px;background:var(--surface2);border:1px solid var(--border);color:var(--muted);font-size:0.85rem">←</span>
    @else
        <a href="{{ $orders->previousPageUrl() }}" style="padding:8px 14px;border-radius:8px;background:var(--surface2);border:1px solid var(--border);color:var(--muted);font-size:0.85rem;text-decoration:none">←</a>
    @endif
    @foreach($orders->getUrlRange(max(1,$orders->currentPage()-2), min($orders->lastPage(),$orders->currentPage()+2)) as $page => $url)
        <a href="{{ $url }}" style="padding:8px 14px;border-radius:8px;background:{{ $page == $orders->currentPage() ? 'var(--accent)' : 'var(--surface2)' }};border:1px solid {{ $page == $orders->currentPage() ? 'var(--accent)' : 'var(--border)' }};color:{{ $page == $orders->currentPage() ? '#0c0c0e' : 'var(--muted)' }};font-size:0.85rem;text-decoration:none;font-weight:{{ $page == $orders->currentPage() ? '700' : '400' }}">{{ $page }}</a>
    @endforeach
    @if($orders->hasMorePages())
        <a href="{{ $orders->nextPageUrl() }}" style="padding:8px 14px;border-radius:8px;background:var(--surface2);border:1px solid var(--border);color:var(--muted);font-size:0.85rem;text-decoration:none">→</a>
    @else
        <span style="padding:8px 14px;border-radius:8px;background:var(--surface2);border:1px solid var(--border);color:var(--muted);font-size:0.85rem">→</span>
    @endif
</div>
@endif
@endif
@endsection
