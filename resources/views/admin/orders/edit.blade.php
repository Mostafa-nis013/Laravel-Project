@extends('layouts.admin')

@section('title', 'Edit Order')
@section('topbar-title', 'Orders / Edit')

@section('content')
<div class="page-header">
    <div>
        <h2>Edit Order</h2>
        <p>Editing: <strong>{{ $order->order_number }}</strong></p>
    </div>
    <div style="display:flex;gap:10px">
        <a href="{{ route('orders.show', $order) }}" class="btn btn-secondary">View</a>
        <a href="{{ route('orders.index') }}" class="btn btn-secondary">← Back</a>
    </div>
</div>

<form method="POST" action="{{ route('orders.update', $order) }}">
    @csrf @method('PUT')
    <div class="grid-2" style="align-items:start">
        {{-- Left --}}
        <div style="display:flex;flex-direction:column;gap:20px">
            <div class="card">
                <div class="card-header"><span class="card-title">Customer Information</span></div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Full Name <span class="required">*</span></label>
                        <input type="text" name="customer_name" value="{{ old('customer_name', $order->customer_name) }}" required>
                        @error('customer_name')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="grid-2">
                        <div class="form-group">
                            <label>Email <span class="required">*</span></label>
                            <input type="email" name="customer_email" value="{{ old('customer_email', $order->customer_email) }}" required>
                            @error('customer_email')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label>Phone</label>
                            <input type="tel" name="customer_phone" value="{{ old('customer_phone', $order->customer_phone) }}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><span class="card-title">Shipping Address</span></div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Address <span class="required">*</span></label>
                        <input type="text" name="shipping_address" value="{{ old('shipping_address', $order->shipping_address) }}" required>
                        @error('shipping_address')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="grid-2">
                        <div class="form-group">
                            <label>City <span class="required">*</span></label>
                            <input type="text" name="shipping_city" value="{{ old('shipping_city', $order->shipping_city) }}" required>
                        </div>
                        <div class="form-group">
                            <label>State <span class="required">*</span></label>
                            <input type="text" name="shipping_state" value="{{ old('shipping_state', $order->shipping_state) }}" required>
                        </div>
                    </div>
                    <div class="grid-2">
                        <div class="form-group">
                            <label>ZIP <span class="required">*</span></label>
                            <input type="text" name="shipping_zip" value="{{ old('shipping_zip', $order->shipping_zip) }}" required>
                        </div>
                        <div class="form-group">
                            <label>Country <span class="required">*</span></label>
                            <input type="text" name="shipping_country" value="{{ old('shipping_country', $order->shipping_country) }}" required>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><span class="card-title">Payment & Notes</span></div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Payment Method <span class="required">*</span></label>
                        <select name="payment_method" required>
                            @foreach(['credit_card','debit_card','paypal','bank_transfer','cash_on_delivery'] as $m)
                                <option value="{{ $m }}" {{ old('payment_method', $order->payment_method) === $m ? 'selected' : '' }}>
                                    {{ ucwords(str_replace('_',' ',$m)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Notes</label>
                        <textarea name="notes" rows="3">{{ old('notes', $order->notes) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right: read-only items + summary --}}
        <div style="display:flex;flex-direction:column;gap:20px">
            <div class="card">
                <div class="card-header"><span class="card-title">Order Items</span> <span style="color:var(--muted);font-size:0.8rem">(read-only)</span></div>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Qty</th>
                                <th>Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td>{{ $item->product_name }}</td>
                                <td>×{{ $item->quantity }}</td>
                                <td>${{ number_format($item->unit_price,2) }}</td>
                                <td style="color:var(--accent)">${{ number_format($item->subtotal,2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div style="padding:16px 20px;border-top:1px solid var(--border);text-align:right">
                    <span style="font-size:0.85rem;color:var(--muted)">Total: </span>
                    <span style="font-size:1.1rem;font-weight:700;color:var(--accent)">${{ number_format($order->total,2) }}</span>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><span class="card-title">Order Status</span></div>
                <div class="card-body">
                    <div style="display:flex;align-items:center;gap:10px">
                        <span class="badge badge-{{ $order->status_badge_color }}">{{ ucfirst($order->status) }}</span>
                        <span style="color:var(--muted);font-size:0.85rem">To change status, use the "Update Status" panel on the order detail page.</span>
                    </div>
                </div>
            </div>

            <div style="display:flex;gap:12px;justify-content:flex-end">
                <a href="{{ route('orders.show', $order) }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </div>
    </div>
</form>
@endsection
