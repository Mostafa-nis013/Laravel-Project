@extends('layouts.shop')

@section('title', 'Checkout')

@section('content')

{{-- Progress bar --}}
<div style="display:flex;align-items:center;justify-content:center;gap:0;margin-bottom:40px">
    @foreach([['Cart','✓'],['Checkout','2'],['Confirmation','3']] as $i => $step)
    <div style="display:flex;align-items:center">
        <div style="display:flex;flex-direction:column;align-items:center;gap:5px">
            <div style="width:34px;height:34px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:0.82rem;font-weight:700;
                background:{{ $i == 1 ? 'var(--accent)' : ($i == 0 ? 'var(--success)' : 'var(--surface2)') }};
                color:{{ $i == 1 ? '#0c0c0e' : ($i == 0 ? '#0c0c0e' : 'var(--muted)') }};
                border:2px solid {{ $i == 1 ? 'var(--accent)' : ($i == 0 ? 'var(--success)' : 'var(--border)') }}">
                {{ $step[1] }}
            </div>
            <span style="font-size:0.75rem;font-weight:{{ $i == 1 ? '600' : '400' }};color:{{ $i == 1 ? 'var(--accent)' : 'var(--muted)' }}">{{ $step[0] }}</span>
        </div>
        @if($i < 2)
        <div style="width:80px;height:2px;background:{{ $i == 0 ? 'var(--success)' : 'var(--border)' }};margin:0 8px;margin-bottom:20px"></div>
        @endif
    </div>
    @endforeach
</div>

<form method="POST" action="{{ route('shop.checkout.store') }}" id="checkout-form">
    @csrf
    <div style="display:grid;grid-template-columns:1fr 360px;gap:32px;align-items:start">

        {{-- Left: forms --}}
        <div style="display:flex;flex-direction:column;gap:20px">

            {{-- Contact --}}
            <div style="background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);overflow:hidden">
                <div style="padding:18px 22px;border-bottom:1px solid var(--border);font-size:1rem;font-weight:600">
                    1 · Contact Information
                </div>
                <div style="padding:22px;display:grid;grid-template-columns:1fr 1fr;gap:16px">
                    <div style="grid-column:1/-1">
                        <label style="display:block;font-size:0.82rem;font-weight:500;color:#b0acb8;margin-bottom:6px">Full Name <span style="color:var(--accent)">*</span></label>
                        <input type="text" name="customer_name" value="{{ old('customer_name', $user->name) }}" required
                            style="width:100%;background:var(--surface2);border:1px solid var(--border);border-radius:var(--radius);color:var(--text);padding:10px 14px;font-size:0.9rem;font-family:'DM Sans',sans-serif;outline:none"
                            onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border)'">
                        @error('customer_name')<div style="color:var(--danger);font-size:0.78rem;margin-top:4px">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label style="display:block;font-size:0.82rem;font-weight:500;color:#b0acb8;margin-bottom:6px">Email <span style="color:var(--accent)">*</span></label>
                        <input type="email" name="customer_email" value="{{ old('customer_email', $user->email) }}" required
                            style="width:100%;background:var(--surface2);border:1px solid var(--border);border-radius:var(--radius);color:var(--text);padding:10px 14px;font-size:0.9rem;font-family:'DM Sans',sans-serif;outline:none"
                            onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border)'">
                        @error('customer_email')<div style="color:var(--danger);font-size:0.78rem;margin-top:4px">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label style="display:block;font-size:0.82rem;font-weight:500;color:#b0acb8;margin-bottom:6px">Phone</label>
                        <input type="tel" name="customer_phone" value="{{ old('customer_phone') }}" placeholder="+1 (555) 000-0000"
                            style="width:100%;background:var(--surface2);border:1px solid var(--border);border-radius:var(--radius);color:var(--text);padding:10px 14px;font-size:0.9rem;font-family:'DM Sans',sans-serif;outline:none"
                            onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border)'">
                    </div>
                </div>
            </div>

            {{-- Shipping address --}}
            <div style="background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);overflow:hidden">
                <div style="padding:18px 22px;border-bottom:1px solid var(--border);font-size:1rem;font-weight:600">
                    2 · Shipping Address
                </div>
                <div style="padding:22px;display:grid;grid-template-columns:1fr 1fr;gap:16px">
                    <div style="grid-column:1/-1">
                        <label style="display:block;font-size:0.82rem;font-weight:500;color:#b0acb8;margin-bottom:6px">Street Address <span style="color:var(--accent)">*</span></label>
                        <input type="text" name="shipping_address" value="{{ old('shipping_address') }}" required placeholder="123 Main Street"
                            style="width:100%;background:var(--surface2);border:1px solid var(--border);border-radius:var(--radius);color:var(--text);padding:10px 14px;font-size:0.9rem;font-family:'DM Sans',sans-serif;outline:none"
                            onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border)'">
                        @error('shipping_address')<div style="color:var(--danger);font-size:0.78rem;margin-top:4px">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label style="display:block;font-size:0.82rem;font-weight:500;color:#b0acb8;margin-bottom:6px">City <span style="color:var(--accent)">*</span></label>
                        <input type="text" name="shipping_city" value="{{ old('shipping_city') }}" required
                            style="width:100%;background:var(--surface2);border:1px solid var(--border);border-radius:var(--radius);color:var(--text);padding:10px 14px;font-size:0.9rem;font-family:'DM Sans',sans-serif;outline:none"
                            onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border)'">
                        @error('shipping_city')<div style="color:var(--danger);font-size:0.78rem;margin-top:4px">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label style="display:block;font-size:0.82rem;font-weight:500;color:#b0acb8;margin-bottom:6px">State <span style="color:var(--accent)">*</span></label>
                        <input type="text" name="shipping_state" value="{{ old('shipping_state') }}" required
                            style="width:100%;background:var(--surface2);border:1px solid var(--border);border-radius:var(--radius);color:var(--text);padding:10px 14px;font-size:0.9rem;font-family:'DM Sans',sans-serif;outline:none"
                            onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border)'">
                        @error('shipping_state')<div style="color:var(--danger);font-size:0.78rem;margin-top:4px">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label style="display:block;font-size:0.82rem;font-weight:500;color:#b0acb8;margin-bottom:6px">ZIP Code <span style="color:var(--accent)">*</span></label>
                        <input type="text" name="shipping_zip" value="{{ old('shipping_zip') }}" required
                            style="width:100%;background:var(--surface2);border:1px solid var(--border);border-radius:var(--radius);color:var(--text);padding:10px 14px;font-size:0.9rem;font-family:'DM Sans',sans-serif;outline:none"
                            onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border)'">
                        @error('shipping_zip')<div style="color:var(--danger);font-size:0.78rem;margin-top:4px">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label style="display:block;font-size:0.82rem;font-weight:500;color:#b0acb8;margin-bottom:6px">Country <span style="color:var(--accent)">*</span></label>
                        <input type="text" name="shipping_country" value="{{ old('shipping_country', 'US') }}" required
                            style="width:100%;background:var(--surface2);border:1px solid var(--border);border-radius:var(--radius);color:var(--text);padding:10px 14px;font-size:0.9rem;font-family:'DM Sans',sans-serif;outline:none"
                            onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border)'">
                        @error('shipping_country')<div style="color:var(--danger);font-size:0.78rem;margin-top:4px">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            {{-- Payment --}}
            <div style="background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);overflow:hidden">
                <div style="padding:18px 22px;border-bottom:1px solid var(--border);font-size:1rem;font-weight:600">
                    3 · Payment Method
                </div>
                <div style="padding:22px">
                    {{-- Method selector --}}
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:22px">
                        @foreach([
                            ['credit_card', '💳', 'Credit Card'],
                            ['debit_card',  '🏦', 'Debit Card'],
                            ['paypal',      '🅿', 'PayPal'],
                            ['bank_transfer','🔁', 'Bank Transfer'],
                        ] as [$val, $icon, $label])
                        <label style="display:flex;align-items:center;gap:10px;padding:12px 14px;border:2px solid var(--border);border-radius:var(--radius);cursor:pointer;transition:border-color 0.15s" id="pm-label-{{ $val }}">
                            <input type="radio" name="payment_method" value="{{ $val }}" {{ old('payment_method', 'credit_card') == $val ? 'checked' : '' }}
                                style="accent-color:var(--accent);width:16px;height:16px"
                                onchange="toggleCardFields(this.value)">
                            <span style="font-size:1.1rem">{{ $icon }}</span>
                            <span style="font-size:0.875rem;font-weight:500">{{ $label }}</span>
                        </label>
                        @endforeach
                    </div>

                    {{-- Card fields --}}
                    <div id="card-fields">
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                            <div style="grid-column:1/-1">
                                <label style="display:block;font-size:0.82rem;font-weight:500;color:#b0acb8;margin-bottom:6px">Name on Card <span style="color:var(--accent)">*</span></label>
                                <input type="text" name="card_name" value="{{ old('card_name') }}" placeholder="Jane Smith"
                                    style="width:100%;background:var(--surface2);border:1px solid var(--border);border-radius:var(--radius);color:var(--text);padding:10px 14px;font-size:0.9rem;font-family:'DM Sans',sans-serif;outline:none"
                                    onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border)'">
                                @error('card_name')<div style="color:var(--danger);font-size:0.78rem;margin-top:4px">{{ $message }}</div>@enderror
                            </div>
                            <div style="grid-column:1/-1">
                                <label style="display:block;font-size:0.82rem;font-weight:500;color:#b0acb8;margin-bottom:6px">Card Number <span style="color:var(--accent)">*</span></label>
                                <input type="text" name="card_number" value="{{ old('card_number') }}" placeholder="4242 4242 4242 4242" maxlength="19" id="card-number"
                                    style="width:100%;background:var(--surface2);border:1px solid var(--border);border-radius:var(--radius);color:var(--text);padding:10px 14px;font-size:0.9rem;font-family:'DM Sans',sans-serif;outline:none;letter-spacing:1px"
                                    onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border)'">
                                @error('card_number')<div style="color:var(--danger);font-size:0.78rem;margin-top:4px">{{ $message }}</div>@enderror
                            </div>
                            <div>
                                <label style="display:block;font-size:0.82rem;font-weight:500;color:#b0acb8;margin-bottom:6px">Expiry <span style="color:var(--accent)">*</span></label>
                                <input type="text" name="card_expiry" value="{{ old('card_expiry') }}" placeholder="MM/YY" maxlength="5" id="card-expiry"
                                    style="width:100%;background:var(--surface2);border:1px solid var(--border);border-radius:var(--radius);color:var(--text);padding:10px 14px;font-size:0.9rem;font-family:'DM Sans',sans-serif;outline:none"
                                    onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border)'">
                                @error('card_expiry')<div style="color:var(--danger);font-size:0.78rem;margin-top:4px">{{ $message }}</div>@enderror
                            </div>
                            <div>
                                <label style="display:block;font-size:0.82rem;font-weight:500;color:#b0acb8;margin-bottom:6px">CVV <span style="color:var(--accent)">*</span></label>
                                <input type="text" name="card_cvv" value="{{ old('card_cvv') }}" placeholder="123" maxlength="4"
                                    style="width:100%;background:var(--surface2);border:1px solid var(--border);border-radius:var(--radius);color:var(--text);padding:10px 14px;font-size:0.9rem;font-family:'DM Sans',sans-serif;outline:none"
                                    onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border)'">
                                @error('card_cvv')<div style="color:var(--danger);font-size:0.78rem;margin-top:4px">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div style="background:rgba(82,192,122,0.07);border:1px solid rgba(82,192,122,0.2);border-radius:8px;padding:10px 14px;margin-top:14px;font-size:0.78rem;color:var(--muted)">
                            🔒 This is a demo — no real payment is processed. Use any test card number.
                        </div>
                    </div>

                    <div id="alt-payment" style="display:none;padding:20px;text-align:center;color:var(--muted);font-size:0.875rem">
                        You will be redirected to complete payment after placing your order.
                    </div>
                </div>
            </div>
        </div>

        {{-- Right: order summary --}}
        <div style="position:sticky;top:90px">
            <div style="background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);overflow:hidden">
                <div style="padding:18px 20px;border-bottom:1px solid var(--border);font-size:1rem;font-weight:600">Order Summary</div>
                <div style="padding:20px">
                    {{-- Items --}}
                    @foreach($cart as $item)
                    <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px">
                        <div style="position:relative">
                            <div style="width:48px;height:48px;border-radius:8px;background:var(--surface2);border:1px solid var(--border);overflow:hidden;display:flex;align-items:center;justify-content:center;color:var(--border)">
                                @if($item['image'])
                                    <img src="{{ asset('storage/'.$item['image']) }}" alt="{{ $item['name'] }}" style="width:100%;height:100%;object-fit:cover">
                                @else
                                    ◈
                                @endif
                            </div>
                            <div style="position:absolute;top:-6px;right:-6px;width:18px;height:18px;border-radius:50%;background:var(--muted);color:#0c0c0e;font-size:0.65rem;font-weight:700;display:flex;align-items:center;justify-content:center">{{ $item['quantity'] }}</div>
                        </div>
                        <div style="flex:1;min-width:0">
                            <div style="font-size:0.85rem;font-weight:500;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $item['name'] }}</div>
                        </div>
                        <div style="font-weight:600;font-size:0.875rem;white-space:nowrap">${{ number_format($item['price'] * $item['quantity'], 2) }}</div>
                    </div>
                    @endforeach

                    <div style="border-top:1px solid var(--border);padding-top:14px;margin-top:6px">
                        <div style="display:flex;justify-content:space-between;margin-bottom:8px;font-size:0.875rem">
                            <span style="color:var(--muted)">Subtotal</span>
                            <span>${{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div style="display:flex;justify-content:space-between;margin-bottom:8px;font-size:0.875rem">
                            <span style="color:var(--muted)">Shipping</span>
                            <span>{{ $shipping == 0 ? 'Free' : '$'.number_format($shipping,2) }}</span>
                        </div>
                        <div style="display:flex;justify-content:space-between;margin-bottom:16px;font-size:0.875rem">
                            <span style="color:var(--muted)">Tax (8%)</span>
                            <span>${{ number_format($tax, 2) }}</span>
                        </div>
                        <div style="display:flex;justify-content:space-between;font-size:1.05rem;font-weight:700;margin-bottom:20px">
                            <span>Total</span>
                            <span style="color:var(--accent)">${{ number_format($total, 2) }}</span>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block" style="padding:14px;font-size:1rem;justify-content:center">
                            🔒 Place Order
                        </button>
                        <div style="text-align:center;margin-top:12px;font-size:0.75rem;color:var(--muted)">
                            By placing your order you agree to our terms of service.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
// Format card number with spaces
document.getElementById('card-number')?.addEventListener('input', function() {
    let v = this.value.replace(/\D/g, '').slice(0, 16);
    this.value = v.replace(/(.{4})/g, '$1 ').trim();
});

// Format expiry MM/YY
document.getElementById('card-expiry')?.addEventListener('input', function() {
    let v = this.value.replace(/\D/g, '').slice(0, 4);
    if (v.length >= 2) v = v.slice(0,2) + '/' + v.slice(2);
    this.value = v;
});

// Payment method toggle
function toggleCardFields(method) {
    const cardFields = document.getElementById('card-fields');
    const altPayment = document.getElementById('alt-payment');
    const isCard     = method === 'credit_card' || method === 'debit_card';
    cardFields.style.display = isCard ? 'block' : 'none';
    altPayment.style.display = isCard ? 'none'  : 'block';

    // Highlight selected method label
    document.querySelectorAll('[id^="pm-label-"]').forEach(el => {
        el.style.borderColor = 'var(--border)';
    });
    const lbl = document.getElementById('pm-label-' + method);
    if (lbl) lbl.style.borderColor = 'var(--accent)';
}

// Init on load
document.addEventListener('DOMContentLoaded', () => {
    const checked = document.querySelector('input[name="payment_method"]:checked');
    if (checked) toggleCardFields(checked.value);
});
</script>
@endpush
