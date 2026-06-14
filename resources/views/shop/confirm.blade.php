@extends('layouts.shop')

@section('title', 'Order Confirmed!')

@section('content')

{{-- Success header --}}
<div style="text-align:center;padding:48px 20px 40px">
    <div style="width:72px;height:72px;border-radius:50%;background:rgba(82,192,122,0.15);border:2px solid rgba(82,192,122,0.4);display:inline-flex;align-items:center;justify-content:center;font-size:2rem;margin-bottom:20px">✓</div>
    <h1 style="font-family:'DM Serif Display',serif;font-size:2.4rem;margin-bottom:10px">Order Confirmed!</h1>
    <p style="color:var(--muted);font-size:1rem;max-width:480px;margin:0 auto;line-height:1.6">
        Thank you, <strong style="color:var(--text)">{{ $order->customer_name }}</strong>!
        Your order has been placed and is being processed. A confirmation will be sent to <strong style="color:var(--accent)">{{ $order->customer_email }}</strong>.
    </p>
    <div style="display:inline-flex;align-items:center;gap:8px;margin-top:20px;padding:10px 20px;background:var(--surface);border:1px solid var(--border);border-radius:50px;font-size:0.875rem">
        <span style="color:var(--muted)">Order number:</span>
        <span style="font-family:monospace;font-weight:700;color:var(--accent)">{{ $order->order_number }}</span>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 340px;gap:28px;align-items:start;max-width:960px;margin:0 auto">

    {{-- Order items --}}
    <div style="display:flex;flex-direction:column;gap:20px">

        <div style="background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);overflow:hidden">
            <div style="padding:18px 22px;border-bottom:1px solid var(--border);font-weight:600">Items Ordered</div>
            @foreach($order->items as $item)
            <div style="display:flex;align-items:center;gap:14px;padding:16px 22px;border-bottom:1px solid var(--border)">
                <div style="width:56px;height:56px;border-radius:8px;background:var(--surface2);border:1px solid var(--border);overflow:hidden;flex-shrink:0;display:flex;align-items:center;justify-content:center;color:var(--border)">
                    @if($item->product?->image)
                        <img src="{{ asset('storage/'.$item->product->image) }}" alt="{{ $item->product_name }}" style="width:100%;height:100%;object-fit:cover">
                    @else
                        ◈
                    @endif
                </div>
                <div style="flex:1">
                    <div style="font-weight:600;font-size:0.9rem">{{ $item->product_name }}</div>
                    <div style="font-size:0.78rem;color:var(--muted);margin-top:2px">SKU: {{ $item->product_sku }} · Qty: {{ $item->quantity }}</div>
                </div>
                <div style="font-weight:700;color:var(--accent)">${{ number_format($item->subtotal, 2) }}</div>
            </div>
            @endforeach
            <div style="padding:16px 22px">
                <div style="display:flex;justify-content:space-between;font-size:0.875rem;margin-bottom:6px">
                    <span style="color:var(--muted)">Subtotal</span><span>${{ number_format($order->subtotal, 2) }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:0.875rem;margin-bottom:6px">
                    <span style="color:var(--muted)">Shipping</span><span>{{ $order->shipping_fee > 0 ? '$'.number_format($order->shipping_fee,2) : 'Free' }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:0.875rem;margin-bottom:12px">
                    <span style="color:var(--muted)">Tax</span><span>${{ number_format($order->tax, 2) }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:1.05rem;font-weight:700;border-top:1px solid var(--border);padding-top:12px">
                    <span>Total</span><span style="color:var(--accent)">${{ number_format($order->total, 2) }}</span>
                </div>
            </div>
        </div>

        {{-- What's next --}}
        <div style="background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);padding:22px">
            <h3 style="font-size:0.95rem;font-weight:600;margin-bottom:16px">What happens next?</h3>
            <div style="display:flex;flex-direction:column;gap:14px">
                @foreach([
                    ['🔔','Order Confirmed','Your order is being reviewed and will be processed shortly.'],
                    ['📦','Processing','We\'re picking and packing your items.'],
                    ['🚚','Shipped','Your order will be on its way with tracking details.'],
                    ['🏠','Delivered','Estimated delivery within 3–7 business days.'],
                ] as [$icon,$title,$desc])
                <div style="display:flex;gap:12px;align-items:flex-start">
                    <div style="width:34px;height:34px;border-radius:50%;background:var(--surface2);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;font-size:0.9rem;flex-shrink:0">{{ $icon }}</div>
                    <div>
                        <div style="font-weight:600;font-size:0.875rem">{{ $title }}</div>
                        <div style="font-size:0.8rem;color:var(--muted);margin-top:2px">{{ $desc }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Delivery & actions --}}
    <div style="display:flex;flex-direction:column;gap:16px">

        <div style="background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);overflow:hidden">
            <div style="padding:16px 20px;border-bottom:1px solid var(--border);font-weight:600;font-size:0.9rem">Shipping To</div>
            <div style="padding:16px 20px;font-size:0.875rem;color:var(--muted);line-height:1.8">
                <strong style="color:var(--text)">{{ $order->customer_name }}</strong><br>
                {{ $order->shipping_address }}<br>
                {{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_zip }}<br>
                {{ $order->shipping_country }}
            </div>
        </div>

        <div style="background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);overflow:hidden">
            <div style="padding:16px 20px;border-bottom:1px solid var(--border);font-weight:600;font-size:0.9rem">Payment</div>
            <div style="padding:16px 20px">
                <div style="display:flex;justify-content:space-between;font-size:0.875rem;margin-bottom:6px">
                    <span style="color:var(--muted)">Method</span>
                    <span>{{ ucwords(str_replace('_',' ',$order->payment_method)) }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:0.875rem">
                    <span style="color:var(--muted)">Status</span>
                    <span style="color:var(--success);font-weight:600">✓ Paid</span>
                </div>
            </div>
        </div>

        <div style="display:flex;flex-direction:column;gap:10px">
            <a href="{{ route('shop.orders') }}" class="btn btn-primary btn-block" style="justify-content:center;padding:12px">
                📦 View My Orders
            </a>
            <a href="{{ route('shop.index') }}" class="btn btn-secondary btn-block" style="justify-content:center;padding:12px">
                Continue Shopping
            </a>
        </div>
    </div>
</div>
@endsection
