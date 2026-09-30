@extends('layouts.app')

@section('title', 'Checkout — Pistis')

@section('content')
{{-- Checkout Banner --}}
<div class="page-dark-banner checkout-hero-banner" style="background:#000000;color:#ffffff;text-align:center;padding:48px 24px 40px;">
    <span class="banner-subtitle" style="font-family:'Inter',sans-serif;font-size:0.7rem;letter-spacing:0.25em;text-transform:uppercase;color:#a3a3a3;display:block;margin-bottom:12px;">SECURE CHECKOUT</span>
    <h1 style="color:#ffffff !important;font-family:'Cormorant Garamond',serif;font-size:clamp(1.8rem,4vw,2.8rem);font-weight:300;margin:0;letter-spacing:0.02em;">Complete Your Order</h1>
    <div class="banner-divider" style="width:40px;height:1px;background:rgba(255,255,255,0.3);margin:20px auto 0;"></div>
</div>

<div class="container" style="padding-top:48px;padding-bottom:60px;">
    <form id="checkout-form">
        @csrf
        <div class="two-col">
            <div>
                <div class="checkout-form-box" style="border:1px solid #e5e5e5;padding:32px;">
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
                <div style="background:#fafafa;border:1px solid #e5e5e5;padding:32px;">
                    <h3 style="font-family:'Cormorant Garamond',serif;font-size:1.3rem;font-weight:400;margin-bottom:24px;padding-bottom:16px;border-bottom:1px solid #e5e5e5;">Your Order</h3>
                    @foreach($cart->items as $item)
                        <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-bottom:1px solid #e5e5e5;">
                            <div>
                                <div style="font-size:0.85rem;color:#000000;">{{ $item->product->name }}</div>
                                <div style="font-size:0.7rem;color:#a3a3a3;letter-spacing:0.05em;text-transform:uppercase;">Qty: {{ $item->quantity }}</div>
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
</script>

</div>
@endsection
