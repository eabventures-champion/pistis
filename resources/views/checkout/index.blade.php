@extends('layouts.app')

@section('title', 'Checkout — Pistis')

@push('styles')
<style>
/* ─── Checkout Responsive Luxury Styles ────────────────────────────── */
.checkout-hero-banner {
    background: #000000;
    color: #ffffff;
    text-align: center;
    padding: 44px 24px 38px;
}
.checkout-container {
    padding-top: 40px;
    padding-bottom: 60px;
}
.checkout-form-box {
    border: 1px solid #e5e5e5;
    padding: 32px;
    background: #ffffff;
}
.checkout-summary-box {
    background: #fafafa;
    border: 1px solid #e5e5e5;
    padding: 32px;
}

/* Mobile Order Summary Bar (Top dropdown on mobile) */
.mobile-order-summary-bar {
    display: none;
}

/* ─── Mobile Viewport Rules (<= 768px) ─────────────────────────────── */
@media (max-width: 768px) {
    .checkout-hero-banner {
        padding: 28px 16px 22px !important;
    }
    .checkout-container {
        padding-top: 18px !important;
        padding-bottom: 44px !important;
    }
    .checkout-form-box {
        padding: 20px 16px !important;
        margin-bottom: 18px !important;
    }
    .checkout-summary-box {
        padding: 20px 16px !important;
    }
    
    /* Input touch optimization (16px prevents iOS Safari auto-zoom) */
    .checkout-form-box .form-control {
        font-size: 16px !important;
        height: 46px !important;
        padding: 10px 14px !important;
    }
    .checkout-form-box textarea.form-control {
        height: auto !important;
    }
    .form-row {
        gap: 12px !important;
    }
    .form-group {
        margin-bottom: 14px !important;
    }

    /* Mobile Order Summary Dropdown */
    .mobile-order-summary-bar {
        display: block !important;
        background: #f7f7f7;
        border: 1px solid #e5e5e5;
        margin-bottom: 18px;
    }
    .mobile-order-summary-btn {
        width: 100%;
        padding: 13px 16px;
        background: none;
        border: none;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        font-family: 'Inter', sans-serif;
    }
    .summary-btn-label {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.76rem;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        font-weight: 600;
        color: #171717;
    }
    .summary-btn-total {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.25rem;
        font-weight: 500;
        color: #000000;
    }
    .mobile-order-summary-drawer {
        display: none;
        border-top: 1px solid #e5e5e5;
        padding: 16px;
        background: #ffffff;
    }
    .mobile-order-summary-drawer.is-open {
        display: block;
    }
    .mobile-order-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 0;
        border-bottom: 1px solid #f0f0f0;
    }
    .mobile-item-img {
        width: 44px;
        height: 56px;
        background: #fafafa;
        position: relative;
        flex-shrink: 0;
        border: 1px solid #e5e5e5;
    }
    .mobile-item-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .mobile-item-qty-badge {
        position: absolute;
        top: -6px;
        right: -6px;
        width: 18px;
        height: 18px;
        background: #000000;
        color: #ffffff;
        border-radius: 50%;
        font-size: 0.65rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .mobile-item-details {
        flex: 1;
        min-width: 0;
    }
    .mobile-item-name {
        font-family: 'Cormorant Garamond', serif;
        font-size: 0.95rem;
        color: #000000;
        line-height: 1.2;
    }
    .mobile-item-meta {
        font-size: 0.68rem;
        color: #737373;
        text-transform: uppercase;
        margin-top: 2px;
    }
    .mobile-item-price {
        font-family: 'Cormorant Garamond', serif;
        font-size: 0.95rem;
        color: #000000;
        text-align: right;
    }
    .mobile-breakdown-row {
        display: flex;
        justify-content: space-between;
        font-size: 0.82rem;
        color: #525252;
        padding: 6px 0;
    }
    .mobile-total-row {
        margin-top: 8px;
        padding-top: 10px;
        border-top: 1px solid #e5e5e5;
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.25rem;
        color: #000000;
    }
    .mobile-total-amt {
        font-weight: 600;
    }
}
</style>
@endpush

@section('content')
{{-- Checkout Banner --}}
<div class="page-dark-banner checkout-hero-banner">
    <span class="banner-subtitle" style="font-family:'Inter',sans-serif;font-size:0.7rem;letter-spacing:0.25em;text-transform:uppercase;color:#a3a3a3;display:block;margin-bottom:10px;">SECURE CHECKOUT</span>
    <h1 style="color:#ffffff !important;font-family:'Cormorant Garamond',serif;font-size:clamp(1.8rem,4vw,2.8rem);font-weight:300;margin:0;letter-spacing:0.02em;">Complete Your Order</h1>
    <div class="banner-divider" style="width:40px;height:1px;background:rgba(255,255,255,0.3);margin:16px auto 0;"></div>
</div>

<div class="container checkout-container">
    <form id="checkout-form">
        @csrf

        {{-- Mobile Compact Order Summary Accordion --}}
        <div class="mobile-order-summary-bar">
            <button type="button" class="mobile-order-summary-btn" onclick="toggleCheckoutMobileSummary()" aria-expanded="false" id="mobile-summary-toggle-btn">
                <span class="summary-btn-label">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                    </svg>
                    <span id="mobile-summary-text">Show order summary</span>
                    <svg id="mobile-summary-caret" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="transition:transform 0.2s;"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </span>
                <span class="summary-btn-total">{{ $currency_symbol }}{{ number_format($totals['total'], 2) }}</span>
            </button>
            <div id="mobile-order-summary-drawer" class="mobile-order-summary-drawer">
                @foreach($cart->items as $item)
                    <div class="mobile-order-item">
                        <div class="mobile-item-img">
                            @php $cImg = $item->product ? $item->product->getPrimaryImageForColor($item->color) : null; @endphp
                            @if($cImg)
                                <img src="{{ \App\Models\Product::formatImageUrl($cImg) }}" alt="{{ $item->product->name }}" onerror="this.onerror=null; this.src='{{ asset('images/placeholder.jpg') }}';">
                            @else
                                <div style="font-size:0.6rem;color:#a3a3a3;text-align:center;padding-top:16px;">PIECE</div>
                            @endif
                            <span class="mobile-item-qty-badge">{{ $item->quantity }}</span>
                        </div>
                        <div class="mobile-item-details">
                            <div class="mobile-item-name">{{ $item->product->name }}</div>
                            @if($item->color || $item->size)
                                <div class="mobile-item-meta">
                                    {{ $item->color ?? '' }} {{ ($item->color && $item->size) ? '·' : '' }} {{ $item->size ?? '' }}
                                </div>
                            @endif
                        </div>
                        <div class="mobile-item-price">{{ $currency_symbol }}{{ number_format($item->subtotal, 2) }}</div>
                    </div>
                @endforeach
                <div class="mobile-breakdown-row" style="margin-top:10px;">
                    <span>Subtotal</span>
                    <span>{{ $currency_symbol }}{{ number_format($totals['subtotal'], 2) }}</span>
                </div>
                <div class="mobile-breakdown-row">
                    <span>Shipping</span>
                    <span>{{ $totals['shipping'] > 0 ? $currency_symbol . number_format($totals['shipping'], 2) : 'Complimentary' }}</span>
                </div>
                <div class="mobile-breakdown-row mobile-total-row">
                    <span>Total</span>
                    <span class="mobile-total-amt">{{ $currency_symbol }}{{ number_format($totals['total'], 2) }}</span>
                </div>
            </div>
        </div>

        <div class="two-col">
            <div>
                <div class="checkout-form-box">
                    @auth('customer')
                        <div style="background:#f7f7f7;border:1px solid #e5e5e5;padding:14px 18px;margin-bottom:24px;display:flex;justify-content:space-between;align-items:center;">
                            <div>
                                <span style="font-size:0.65rem;letter-spacing:0.15em;text-transform:uppercase;color:#737373;display:block;">EXPRESS CHECKOUT</span>
                                <span style="font-size:0.85rem;color:#000000;">Logged in as <strong>{{ $customer->full_name }}</strong> ({{ $customer->email }})</span>
                            </div>
                            <form action="{{ route('customer.logout') }}" method="POST" style="margin:0;">
                                @csrf
                                <button type="submit" style="background:none;border:none;color:#737373;font-size:0.7rem;letter-spacing:0.08em;text-transform:uppercase;text-decoration:underline;cursor:pointer;">Switch Account</button>
                            </form>
                        </div>
                    @endauth

                    <h3 style="font-family:'Cormorant Garamond',serif;font-size:1.3rem;font-weight:400;margin-bottom:24px;padding-bottom:16px;border-bottom:1px solid #e5e5e5;">Shipping Information</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" style="font-size:0.7rem;letter-spacing:0.1em;text-transform:uppercase;color:#737373;">First Name *</label>
                            <input type="text" name="first_name" id="first_name" class="form-control" style="border-radius:0;border-color:#e5e5e5;" value="{{ old('first_name', $customer->first_name ?? $savedShipping['first_name'] ?? '') }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label" style="font-size:0.7rem;letter-spacing:0.1em;text-transform:uppercase;color:#737373;">Last Name *</label>
                            <input type="text" name="last_name" id="last_name" class="form-control" style="border-radius:0;border-color:#e5e5e5;" value="{{ old('last_name', $customer->last_name ?? $savedShipping['last_name'] ?? '') }}" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" style="font-size:0.7rem;letter-spacing:0.1em;text-transform:uppercase;color:#737373;">Email *</label>
                            <input type="email" name="email" id="email" class="form-control" style="border-radius:0;border-color:#e5e5e5;" value="{{ old('email', $customer->email ?? $savedShipping['email'] ?? '') }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label" style="font-size:0.7rem;letter-spacing:0.1em;text-transform:uppercase;color:#737373;">Phone *</label>
                            <input type="text" name="phone" id="phone" class="form-control" style="border-radius:0;border-color:#e5e5e5;" value="{{ old('phone', $customer->phone ?? $savedShipping['phone'] ?? '') }}" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label" style="font-size:0.7rem;letter-spacing:0.1em;text-transform:uppercase;color:#737373;">Address *</label>
                        <input type="text" name="address" id="address" class="form-control" style="border-radius:0;border-color:#e5e5e5;" value="{{ old('address', $customer->address ?? $savedShipping['address'] ?? '') }}" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" style="font-size:0.7rem;letter-spacing:0.1em;text-transform:uppercase;color:#737373;">City *</label>
                            <input type="text" name="city" id="city" class="form-control" style="border-radius:0;border-color:#e5e5e5;" value="{{ old('city', $customer->city ?? $savedShipping['city'] ?? '') }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label" style="font-size:0.7rem;letter-spacing:0.1em;text-transform:uppercase;color:#737373;">State *</label>
                            <input type="text" name="state" id="state" class="form-control" style="border-radius:0;border-color:#e5e5e5;" value="{{ old('state', $customer->state ?? $savedShipping['state'] ?? '') }}" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label" style="font-size:0.7rem;letter-spacing:0.1em;text-transform:uppercase;color:#737373;">Country *</label>
                        <input type="text" name="country" id="country" class="form-control" style="border-radius:0;border-color:#e5e5e5;" value="{{ old('country', $customer->country ?? $savedShipping['country'] ?? 'United States') }}" required>
                    </div>
                    <div class="form-group" style="margin-top:14px;margin-bottom:18px;">
                        <label style="display:flex;align-items:center;gap:10px;cursor:pointer;">
                            <input type="checkbox" id="save-info-checkbox" checked style="accent-color:#000000;width:15px;height:15px;">
                            <span style="font-size:0.78rem;color:#525252;">Save my shipping details for a faster checkout next time</span>
                        </label>
                    </div>
                    <div class="form-group">
                        <label class="form-label" style="font-size:0.7rem;letter-spacing:0.1em;text-transform:uppercase;color:#737373;">Order Notes</label>
                        <textarea name="notes" id="notes" class="form-control" rows="3" style="border-radius:0;border-color:#e5e5e5;" placeholder="Special instructions...">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>

            <div>
                <div class="checkout-summary-box">
                    <h3 style="font-family:'Cormorant Garamond',serif;font-size:1.3rem;font-weight:400;margin-bottom:24px;padding-bottom:16px;border-bottom:1px solid #e5e5e5;">Your Order</h3>
                    @foreach($cart->items as $item)
                        <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-bottom:1px solid #e5e5e5;">
                            <div>
                                <div style="font-size:0.85rem;color:#000000;">{{ $item->product->name }}</div>
                                <div style="font-size:0.7rem;color:#a3a3a3;letter-spacing:0.05em;text-transform:uppercase;">
                                    Qty: {{ $item->quantity }}
                                    @if($item->color)
                                        &nbsp;·&nbsp; Color: {{ $item->color }}
                                    @endif
                                    @if($item->size)
                                        &nbsp;·&nbsp; Size: {{ $item->size }}
                                    @endif
                                </div>
                            </div>
                            <div style="font-family:'Cormorant Garamond',serif;font-size:1rem;color:#000000;">{{ $currency_symbol }}{{ number_format($item->subtotal, 2) }}</div>
                        </div>
                    @endforeach

                    <div style="display:flex;justify-content:space-between;margin-top:16px;font-size:0.85rem;color:#525252;padding:6px 0;">
                        <span>Subtotal</span>
                        <span>{{ $currency_symbol }}{{ number_format($totals['subtotal'], 2) }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;font-size:0.85rem;color:#525252;padding:6px 0;">
                        <span>Shipping</span>
                        <span>{{ $totals['shipping'] > 0 ? $currency_symbol . number_format($totals['shipping'], 2) : 'Complimentary' }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding-top:16px;margin-top:12px;border-top:1px solid #e5e5e5;font-family:'Cormorant Garamond',serif;font-size:1.3rem;color:#000000;">
                        <span>Total</span>
                        <span>{{ $currency_symbol }}{{ number_format($totals['total'], 2) }}</span>
                    </div>

                    <div id="payment-errors" class="alert alert-danger mt-3" style="display:none;background:#dc2626;color:#ffffff;border:none;border-radius:0;font-size:0.8rem;letter-spacing:0.05em;"></div>

                    <div id="paypal-button-container" class="mt-4"></div>

                    <div style="text-align:center;margin:16px 0 14px;position:relative;">
                        <span style="background:#fafafa;padding:0 12px;font-size:0.7rem;color:#a3a3a3;text-transform:uppercase;letter-spacing:0.1em;position:relative;z-index:1;">or</span>
                        <div style="position:absolute;top:50%;left:0;right:0;height:1px;background:#e5e5e5;z-index:0;"></div>
                    </div>

                    <button type="button" id="sandbox-direct-btn" class="btn btn-secondary w-100" style="padding:13px;font-size:0.75rem;letter-spacing:0.12em;text-transform:uppercase;border:1px solid #171717;background:#ffffff;color:#171717;cursor:pointer;font-weight:600;">
                        Complete Sandbox Order (Direct)
                    </button>

                    <p style="font-size:0.65rem;color:#a3a3a3;text-align:center;margin-top:16px;letter-spacing:0.1em;text-transform:uppercase;">
                        SECURED BY PAYPAL · SANDBOX ENVIRONMENT
                    </p>
                </div>
            </div>
        </div>
    </form>
</div>

{{-- PayPal SDK --}}
<script src="https://www.paypal.com/sdk/js?client-id={{ $paypalClientId }}&currency={{ $currencyCode }}&components=buttons"></script>

<script>
    let localOrderId = null;

    if (typeof paypal !== 'undefined') {
        paypal.Buttons({
            style: {
                layout: 'vertical',
                color:  'gold',
                shape:  'rect',
                label:  'paypal'
            },

            onClick: function(data, actions) {
                const form = document.getElementById('checkout-form');
                if (!form.reportValidity()) {
                    return actions.reject();
                }
                return actions.resolve();
            },

            createOrder: async function(data, actions) {
                try {
                    const formData = new FormData(document.getElementById('checkout-form'));
                    const response = await fetch("{{ route('checkout.process') }}", {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    });

                    const orderData = await response.json();

                    if (!orderData.success) {
                        throw new Error(orderData.message || 'Could not create order');
                    }

                    localOrderId = orderData.order_id;

                    return actions.order.create({
                        purchase_units: [{
                            amount: {
                                currency_code: "{{ $currencyCode }}",
                                value: "{{ number_format($totals['total'], 2, '.', '') }}"
                            }
                        }]
                    });
                } catch (err) {
                    console.error(err);
                    const errorBox = document.getElementById('payment-errors');
                    errorBox.textContent = err.message || 'Payment initialization failed.';
                    errorBox.style.display = 'block';
                }
            },

            onApprove: async function(data, actions) {
                try {
                    return actions.order.capture().then(async function(details) {
                        const response = await fetch(`/payment/paypal/capture/${localOrderId}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({
                                paypal_order_id: details.id || data.orderID
                            })
                        });

                        const captureData = await response.json();

                        if (captureData.success) {
                            window.location.href = `/checkout/success/${localOrderId}`;
                        } else {
                            throw new Error(captureData.error || 'Payment capture failed');
                        }
                    });
                } catch (err) {
                    console.error(err);
                    alert('Payment captured but failed to finalize. Please contact support.');
                }
            },

            onCancel: function(data) {
                // User cancelled
            },

            onError: function(err) {
                console.error(err);
                const errorBox = document.getElementById('payment-errors');
                errorBox.textContent = 'A payment error occurred. Please try again.';
                errorBox.style.display = 'block';
            }
        }).render('#paypal-button-container');
    }

    // Direct Sandbox Order button
    const directBtn = document.getElementById('sandbox-direct-btn');
    if (directBtn) {
        directBtn.addEventListener('click', async function() {
            const form = document.getElementById('checkout-form');
            if (!form.reportValidity()) {
                return;
            }

            directBtn.disabled = true;
            directBtn.textContent = 'Processing Order...';

            try {
                const formData = new FormData(form);
                const response = await fetch("{{ route('checkout.process') }}", {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                const orderData = await response.json();

                if (!orderData.success) {
                    throw new Error(orderData.message || 'Could not create order');
                }

                const captureResponse = await fetch(`/payment/paypal/capture/${orderData.order_id}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        paypal_order_id: 'SANDBOX-' + orderData.order_id
                    })
                });

                const captureData = await captureResponse.json();

                if (captureData.success) {
                    window.location.href = `/checkout/success/${orderData.order_id}`;
                } else {
                    throw new Error(captureData.error || 'Failed to complete order');
                }
            } catch (err) {
                console.error(err);
                const errorBox = document.getElementById('payment-errors');
                errorBox.textContent = err.message || 'An error occurred during checkout.';
                errorBox.style.display = 'block';
                directBtn.disabled = false;
                directBtn.textContent = 'Complete Sandbox Order (Direct)';
            }
        });
    }

    // Client-side LocalStorage Auto-fill (Shopify Shop Pay style)
    const storageKey = 'pistis_express_shipping';
    const fields = ['first_name', 'last_name', 'email', 'phone', 'address', 'city', 'state', 'country'];
    const saveCheckbox = document.getElementById('save-info-checkbox');

    try {
        const saved = JSON.parse(localStorage.getItem(storageKey) || '{}');
        fields.forEach(field => {
            const input = document.getElementById(field);
            if (input && !input.value.trim() && saved[field]) {
                input.value = saved[field];
            }
        });
    } catch (e) {}

    function persistShippingDetails() {
        if (saveCheckbox && !saveCheckbox.checked) {
            localStorage.removeItem(storageKey);
            return;
        }
        const data = {};
        fields.forEach(field => {
            const input = document.getElementById(field);
            if (input) data[field] = input.value;
        });
        try {
            localStorage.setItem(storageKey, JSON.stringify(data));
        } catch (e) {}
    }

    fields.forEach(field => {
        const input = document.getElementById(field);
        if (input) {
            input.addEventListener('change', persistShippingDetails);
        }
    });

    if (saveCheckbox) {
        saveCheckbox.addEventListener('change', persistShippingDetails);
    }

    window.toggleCheckoutMobileSummary = function() {
        const drawer = document.getElementById('mobile-order-summary-drawer');
        const text = document.getElementById('mobile-summary-text');
        const caret = document.getElementById('mobile-summary-caret');
        const btn = document.getElementById('mobile-summary-toggle-btn');
        if (!drawer) return;

        const isOpen = drawer.classList.contains('is-open');
        if (isOpen) {
            drawer.classList.remove('is-open');
            if (text) text.textContent = 'Show order summary';
            if (caret) caret.style.transform = 'rotate(0deg)';
            if (btn) btn.setAttribute('aria-expanded', 'false');
        } else {
            drawer.classList.add('is-open');
            if (text) text.textContent = 'Hide order summary';
            if (caret) caret.style.transform = 'rotate(180deg)';
            if (btn) btn.setAttribute('aria-expanded', 'true');
        }
    };
</script>
@endsection
