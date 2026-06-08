@extends('layouts.admin')

@section('title', $order->order_number)
@section('topbar-title', 'Orders / Detail')

@section('content')
<div class="page-header">
    <div>
        <h2>{{ $order->order_number }}</h2>
        <p>Placed on {{ $order->created_at->format('F d, Y \a\t g:i A') }}</p>
    </div>
    <div style="display:flex;gap:10px;align-items:center">
        <span class="badge badge-{{ $order->status_badge_color }}" style="padding:8px 16px;font-size:0.85rem">{{ ucfirst($order->status) }}</span>
        <a href="{{ route('orders.edit', $order) }}" class="btn btn-secondary">Edit</a>
        <a href="{{ route('orders.index') }}" class="btn btn-secondary">← Back</a>
    </div>
</div>

<div class="grid-2" style="align-items:start">
    {{-- Left --}}
    <div style="display:flex;flex-direction:column;gap:20px">
        {{-- Items --}}
        <div class="card">
            <div class="card-header"><span class="card-title">Order Items</span></div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>SKU</th>
                            <th>Qty</th>
                            <th>Unit Price</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr>
                            <td>
                                @if($item->product)
                                    <a href="{{ route('products.show', $item->product) }}" style="color:var(--text);text-decoration:none;font-weight:500">{{ $item->product_name }}</a>
                                @else
                                    <span style="color:var(--muted)">{{ $item->product_name }} (deleted)</span>
                                @endif
                            </td>
                            <td style="font-family:monospace;color:var(--muted);font-size:0.82rem">{{ $item->product_sku }}</td>
                            <td style="color:var(--muted)">×{{ $item->quantity }}</td>
                            <td>${{ number_format($item->unit_price, 2) }}</td>
                            <td style="font-weight:600">${{ number_format($item->subtotal, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div style="padding:20px 24px;border-top:1px solid var(--border)">
                <table style="width:100%;max-width:280px;margin-left:auto">
                    <tr>
                        <td style="padding:6px 0;color:var(--muted)">Subtotal</td>
                        <td style="text-align:right;padding:6px 0">${{ number_format($order->subtotal, 2) }}</td>
                    </tr>
                    <tr>
                        <td style="padding:6px 0;color:var(--muted)">Shipping</td>
                        <td style="text-align:right;padding:6px 0">{{ $order->shipping_fee > 0 ? '$'.number_format($order->shipping_fee,2) : 'Free' }}</td>
                    </tr>
                    <tr>
                        <td style="padding:6px 0;color:var(--muted)">Tax</td>
                        <td style="text-align:right;padding:6px 0">${{ number_format($order->tax, 2) }}</td>
                    </tr>
                    <tr style="border-top:2px solid var(--border)">
                        <td style="padding:12px 0;font-weight:700;font-size:1rem">Total</td>
                        <td style="text-align:right;padding:12px 0;font-weight:700;font-size:1.1rem;color:var(--accent)">${{ number_format($order->total, 2) }}</td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- Update Status --}}
        <div class="card">
            <div class="card-header"><span class="card-title">Update Status</span></div>
            <div class="card-body">
                <form method="POST" action="{{ route('orders.updateStatus', $order) }}" style="display:flex;gap:10px">
                    @csrf @method('PATCH')
                    <select name="status" style="flex:1">
                        @foreach(\App\Models\Order::STATUSES as $key => $label)
                            <option value="{{ $key }}" {{ $order->status === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>

    {{-- Right --}}
    <div style="display:flex;flex-direction:column;gap:20px">
        {{-- Customer --}}
        <div class="card">
            <div class="card-header"><span class="card-title">Customer</span></div>
            <div class="card-body" style="display:flex;flex-direction:column;gap:12px">
                <div>
                    <div style="font-size:0.75rem;color:var(--muted);margin-bottom:2px">Name</div>
                    <div style="font-weight:500">{{ $order->customer_name }}</div>
                </div>
                <div>
                    <div style="font-size:0.75rem;color:var(--muted);margin-bottom:2px">Email</div>
                    <a href="mailto:{{ $order->customer_email }}" style="color:var(--accent);text-decoration:none">{{ $order->customer_email }}</a>
                </div>
                @if($order->customer_phone)
                <div>
                    <div style="font-size:0.75rem;color:var(--muted);margin-bottom:2px">Phone</div>
                    <div>{{ $order->customer_phone }}</div>
                </div>
                @endif
            </div>
        </div>

        {{-- Shipping --}}
        <div class="card">
            <div class="card-header"><span class="card-title">Shipping Address</span></div>
            <div class="card-body" style="color:var(--muted);line-height:1.8;font-size:0.9rem">
                {{ $order->shipping_address }}<br>
                {{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_zip }}<br>
                {{ $order->shipping_country }}
            </div>
        </div>

        {{-- Payment --}}
        <div class="card">
            <div class="card-header"><span class="card-title">Payment</span></div>
            <div class="card-body">
                <table style="width:100%">
                    <tr>
                        <td style="padding:8px 0;color:var(--muted)">Method</td>
                        <td style="text-align:right;padding:8px 0">{{ ucwords(str_replace('_', ' ', $order->payment_method)) }}</td>
                    </tr>
                    <tr>
                        <td style="padding:8px 0;color:var(--muted)">Status</td>
                        <td style="text-align:right;padding:8px 0">
                            <span class="badge {{ $order->payment_status === 'paid' ? 'badge-green' : 'badge-yellow' }}">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        @if($order->notes)
        <div class="card">
            <div class="card-header"><span class="card-title">Notes</span></div>
            <div class="card-body" style="color:var(--muted);font-size:0.9rem">{{ $order->notes }}</div>
        </div>
        @endif

        {{-- Danger --}}
        <div class="card" style="border-color:rgba(224,82,82,0.3)">
            <div class="card-header"><span class="card-title" style="color:var(--danger)">Danger Zone</span></div>
            <div class="card-body">
                <p style="font-size:0.875rem;color:var(--muted);margin-bottom:14px">Deleting this order will restore stock for all items.</p>
                <form method="POST" action="{{ route('orders.destroy', $order) }}">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger" data-confirm="Delete order {{ $order->order_number }}? Stock will be restored.">Delete Order</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('[data-confirm]').forEach(el => {
    el.addEventListener('click', e => { if (!confirm(el.dataset.confirm)) e.preventDefault(); });
});
</script>
@endpush
