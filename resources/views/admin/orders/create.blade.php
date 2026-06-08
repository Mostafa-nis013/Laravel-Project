@extends('layouts.admin')

@section('title', 'New Order')
@section('topbar-title', 'Orders / New')

@section('content')
<div class="page-header">
    <div>
        <h2>Create Order</h2>
        <p>Manually create a new order.</p>
    </div>
    <a href="{{ route('orders.index') }}" class="btn btn-secondary">← Back</a>
</div>

<form method="POST" action="{{ route('orders.store') }}" id="order-form">
    @csrf
    <div class="grid-2" style="align-items:start">
        {{-- Left: Customer + Shipping --}}
        <div style="display:flex;flex-direction:column;gap:20px">
            <div class="card">
                <div class="card-header"><span class="card-title">Customer Information</span></div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Full Name <span class="required">*</span></label>
                        <input type="text" name="customer_name" value="{{ old('customer_name') }}" required>
                        @error('customer_name')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="grid-2">
                        <div class="form-group">
                            <label>Email <span class="required">*</span></label>
                            <input type="email" name="customer_email" value="{{ old('customer_email') }}" required>
                            @error('customer_email')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label>Phone</label>
                            <input type="tel" name="customer_phone" value="{{ old('customer_phone') }}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><span class="card-title">Shipping Address</span></div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Address <span class="required">*</span></label>
                        <input type="text" name="shipping_address" value="{{ old('shipping_address') }}" required placeholder="123 Main Street">
                        @error('shipping_address')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="grid-2">
                        <div class="form-group">
                            <label>City <span class="required">*</span></label>
                            <input type="text" name="shipping_city" value="{{ old('shipping_city') }}" required>
                            @error('shipping_city')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label>State <span class="required">*</span></label>
                            <input type="text" name="shipping_state" value="{{ old('shipping_state') }}" required>
                            @error('shipping_state')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="grid-2">
                        <div class="form-group">
                            <label>ZIP Code <span class="required">*</span></label>
                            <input type="text" name="shipping_zip" value="{{ old('shipping_zip') }}" required>
                            @error('shipping_zip')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label>Country <span class="required">*</span></label>
                            <input type="text" name="shipping_country" value="{{ old('shipping_country', 'US') }}" required>
                            @error('shipping_country')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><span class="card-title">Payment</span></div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Payment Method <span class="required">*</span></label>
                        <select name="payment_method" required>
                            <option value="credit_card">Credit Card</option>
                            <option value="debit_card">Debit Card</option>
                            <option value="paypal">PayPal</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="cash_on_delivery">Cash on Delivery</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Notes</label>
                        <textarea name="notes" rows="2" placeholder="Any special instructions…">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right: Items + Summary --}}
        <div style="display:flex;flex-direction:column;gap:20px">
            <div class="card">
                <div class="card-header">
                    <span class="card-title">Order Items</span>
                    <button type="button" class="btn btn-secondary btn-sm" id="add-item">+ Add Item</button>
                </div>
                <div class="card-body" id="items-container">
                    <div class="order-item" style="display:flex;gap:10px;margin-bottom:12px;align-items:flex-end" data-index="0">
                        <div style="flex:2">
                            <label>Product <span class="required">*</span></label>
                            <select name="items[0][product_id]" class="product-select" required>
                                <option value="">Select product…</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" data-price="{{ $product->effective_price }}" data-stock="{{ $product->stock }}">
                                        {{ $product->name }} — ${{ number_format($product->effective_price, 2) }} ({{ $product->stock }} in stock)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div style="flex:1">
                            <label>Qty <span class="required">*</span></label>
                            <input type="number" name="items[0][quantity]" class="item-qty" value="1" min="1" required>
                        </div>
                        <div style="flex:1">
                            <label>Subtotal</label>
                            <input type="text" class="item-subtotal" value="$0.00" readonly style="color:var(--accent);background:var(--bg)">
                        </div>
                        <button type="button" class="btn btn-danger btn-icon remove-item" title="Remove" style="margin-bottom:0">✕</button>
                    </div>
                </div>

                @error('items')<div style="padding:0 24px 16px;color:var(--danger);font-size:0.85rem">{{ $message }}</div>@enderror
            </div>

            {{-- Order Summary --}}
            <div class="card">
                <div class="card-header"><span class="card-title">Order Summary</span></div>
                <div class="card-body">
                    <table style="width:100%">
                        <tr>
                            <td style="padding:8px 0;color:var(--muted)">Subtotal</td>
                            <td id="summary-subtotal" style="text-align:right;padding:8px 0">$0.00</td>
                        </tr>
                        <tr>
                            <td style="padding:8px 0;color:var(--muted)">Shipping</td>
                            <td id="summary-shipping" style="text-align:right;padding:8px 0">$9.99</td>
                        </tr>
                        <tr>
                            <td style="padding:8px 0;color:var(--muted)">Tax (8%)</td>
                            <td id="summary-tax" style="text-align:right;padding:8px 0">$0.00</td>
                        </tr>
                        <tr style="border-top:1px solid var(--border)">
                            <td style="padding:12px 0;font-weight:600;font-size:1.05rem">Total</td>
                            <td id="summary-total" style="text-align:right;padding:12px 0;font-weight:700;font-size:1.1rem;color:var(--accent)">$9.99</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div style="display:flex;gap:12px;justify-content:flex-end">
                <a href="{{ route('orders.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Create Order</button>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
const products = @json($products->map(fn($p) => ['id' => $p->id, 'name' => $p->name, 'price' => $p->effective_price, 'stock' => $p->stock]));
let itemIndex = 1;

function buildSelect(idx) {
    let opts = `<option value="">Select product…</option>`;
    products.forEach(p => {
        opts += `<option value="${p.id}" data-price="${p.price}" data-stock="${p.stock}">${p.name} — $${p.price.toFixed(2)} (${p.stock} in stock)</option>`;
    });
    return `
    <div class="order-item" style="display:flex;gap:10px;margin-bottom:12px;align-items:flex-end" data-index="${idx}">
        <div style="flex:2">
            <label>Product <span style="color:var(--accent)">*</span></label>
            <select name="items[${idx}][product_id]" class="product-select" required>${opts}</select>
        </div>
        <div style="flex:1">
            <label>Qty <span style="color:var(--accent)">*</span></label>
            <input type="number" name="items[${idx}][quantity]" class="item-qty" value="1" min="1" required>
        </div>
        <div style="flex:1">
            <label>Subtotal</label>
            <input type="text" class="item-subtotal" value="$0.00" readonly style="color:var(--accent);background:var(--bg)">
        </div>
        <button type="button" class="btn btn-danger btn-icon remove-item" title="Remove">✕</button>
    </div>`;
}

document.getElementById('add-item').addEventListener('click', () => {
    document.getElementById('items-container').insertAdjacentHTML('beforeend', buildSelect(itemIndex++));
    bindItemEvents();
    updateSummary();
});

function bindItemEvents() {
    document.querySelectorAll('.order-item').forEach(row => {
        row.querySelector('.product-select').onchange = () => updateRow(row);
        row.querySelector('.item-qty').oninput = () => updateRow(row);
        row.querySelector('.remove-item').onclick = () => { row.remove(); updateSummary(); };
    });
}

function updateRow(row) {
    const sel = row.querySelector('.product-select');
    const qty = parseFloat(row.querySelector('.item-qty').value) || 0;
    const price = parseFloat(sel.selectedOptions[0]?.dataset.price || 0);
    const sub = price * qty;
    row.querySelector('.item-subtotal').value = '$' + sub.toFixed(2);
    updateSummary();
}

function updateSummary() {
    let subtotal = 0;
    document.querySelectorAll('.order-item').forEach(row => {
        const sel = row.querySelector('.product-select');
        const qty = parseFloat(row.querySelector('.item-qty').value) || 0;
        const price = parseFloat(sel.selectedOptions[0]?.dataset.price || 0);
        subtotal += price * qty;
    });
    const shipping = subtotal >= 100 ? 0 : 9.99;
    const tax = subtotal * 0.08;
    const total = subtotal + shipping + tax;

    document.getElementById('summary-subtotal').textContent = '$' + subtotal.toFixed(2);
    document.getElementById('summary-shipping').textContent = shipping === 0 ? 'Free' : '$' + shipping.toFixed(2);
    document.getElementById('summary-tax').textContent = '$' + tax.toFixed(2);
    document.getElementById('summary-total').textContent = '$' + total.toFixed(2);
}

bindItemEvents();
</script>
@endpush
