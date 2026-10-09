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
                    <div>
                        <span>Shipping</span>
                        <span id="mobile-shipping-service-label" style="font-size:0.65rem;color:#a3a3a3;display:block;">{{ $selectedRate['name'] ?? 'Australia Post' }}</span>
                    </div>
                    <span id="mobile-summary-shipping-amount">{{ $totals['shipping'] > 0 ? $currency_symbol . number_format($totals['shipping'], 2) : 'Complimentary' }}</span>
                </div>
                <div class="mobile-breakdown-row mobile-total-row">
                    <span>Total</span>
                    <span id="mobile-summary-total-amount" class="mobile-total-amt">{{ $currency_symbol }}{{ number_format($totals['total'], 2) }}</span>
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
                    <!-- 1. Country -->
                    <div class="form-group">
                        <label class="form-label" style="font-size:0.7rem;letter-spacing:0.1em;text-transform:uppercase;color:#737373;">Country *</label>
                        <select name="country" id="country" class="form-control" style="border-radius:0;border-color:#e5e5e5;background:#ffffff;cursor:pointer;" required>
                            <optgroup label="Pacific & Oceania">
                                <option value="Australia" selected>Australia (Default)</option>
                                <option value="New Zealand">New Zealand</option>
                                <option value="Fiji">Fiji</option>
                                <option value="Papua New Guinea">Papua New Guinea</option>
                                <option value="Samoa">Samoa</option>
                            </optgroup>
                            <optgroup label="North America">
                                <option value="United States">United States</option>
                                <option value="Canada">Canada</option>
                                <option value="Mexico">Mexico</option>
                            </optgroup>
                            <optgroup label="Europe">
                                <option value="United Kingdom">United Kingdom</option>
                                <option value="Germany">Germany</option>
                                <option value="France">France</option>
                                <option value="Italy">Italy</option>
                                <option value="Spain">Spain</option>
                                <option value="Netherlands">Netherlands</option>
                                <option value="Switzerland">Switzerland</option>
                                <option value="Sweden">Sweden</option>
                                <option value="Norway">Norway</option>
                                <option value="Denmark">Denmark</option>
                                <option value="Ireland">Ireland</option>
                                <option value="Belgium">Belgium</option>
                                <option value="Austria">Austria</option>
                                <option value="Poland">Poland</option>
                                <option value="Portugal">Portugal</option>
                            </optgroup>
                            <optgroup label="Asia">
                                <option value="Japan">Japan</option>
                                <option value="Singapore">Singapore</option>
                                <option value="Hong Kong">Hong Kong</option>
                                <option value="South Korea">South Korea</option>
                                <option value="China">China</option>
                                <option value="Taiwan">Taiwan</option>
                                <option value="Malaysia">Malaysia</option>
                                <option value="Thailand">Thailand</option>
                                <option value="Indonesia">Indonesia</option>
                                <option value="Philippines">Philippines</option>
                                <option value="India">India</option>
                                <option value="United Arab Emirates">United Arab Emirates</option>
                            </optgroup>
                            <optgroup label="South America">
                                <option value="Brazil">Brazil</option>
                                <option value="Argentina">Argentina</option>
                                <option value="Chile">Chile</option>
                                <option value="Colombia">Colombia</option>
                                <option value="Peru">Peru</option>
                            </optgroup>
                        </select>
                    </div>

                    <!-- 2. State / Territory & 3. City -->
                    <div class="form-row" style="display:flex;gap:12px;flex-wrap:wrap;">
                        <div class="form-group" style="flex:1;min-width:180px;">
                            <label class="form-label" style="font-size:0.7rem;letter-spacing:0.1em;text-transform:uppercase;color:#737373;">State / Territory *</label>
                            <select id="state_select" name="state" class="form-control" style="border-radius:0;border-color:#e5e5e5;background:#ffffff;cursor:pointer;" required>
                            </select>
                            <input type="text" id="state_custom" class="form-control" style="border-radius:0;border-color:#e5e5e5;display:none;margin-top:6px;" placeholder="Type State / Province / Territory">
                        </div>
                        <div class="form-group" style="flex:1;min-width:180px;">
                            <label class="form-label" style="font-size:0.7rem;letter-spacing:0.1em;text-transform:uppercase;color:#737373;">City *</label>
                            <select id="city_select" name="city" class="form-control" style="border-radius:0;border-color:#e5e5e5;background:#ffffff;cursor:pointer;" required>
                            </select>
                            <input type="text" id="city_custom" class="form-control" style="border-radius:0;border-color:#e5e5e5;display:none;margin-top:6px;" placeholder="Type City / Town / Suburb">
                        </div>
                    </div>

                    <!-- 4. Postcode / ZIP -->
                    <div class="form-group">
                        <label class="form-label" style="font-size:0.7rem;letter-spacing:0.1em;text-transform:uppercase;color:#737373;">Postcode / ZIP *</label>
                        <input type="text" name="postal_code" id="postal_code" class="form-control" style="border-radius:0;border-color:#e5e5e5;" value="{{ old('postal_code', $customer->postal_code ?? $savedShipping['postal_code'] ?? '') }}" placeholder="e.g. 2000" required>
                    </div>

                    <!-- 5. Address -->
                    <div class="form-group">
                        <label class="form-label" style="font-size:0.7rem;letter-spacing:0.1em;text-transform:uppercase;color:#737373;">Address *</label>
                        <input type="text" name="address" id="address" class="form-control" style="border-radius:0;border-color:#e5e5e5;" value="{{ old('address', $customer->address ?? $savedShipping['address'] ?? '') }}" placeholder="Street address, unit, apartment, suite" required>
                    </div>

                    {{-- Australia Post Delivery Options --}}
                    <div id="auspost-shipping-card" style="margin:20px 0;padding:18px 20px;background:#fafafa;border:1px solid #e5e5e5;">
                        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
                            <div style="display:flex;align-items:center;gap:8px;">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color:#000000;"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
                                <span style="font-size:0.72rem;letter-spacing:0.12em;text-transform:uppercase;font-weight:700;color:#000000;">Delivery Method &middot; Australia Post</span>
                            </div>
                            <span id="shipping-loading-spinner" style="display:none;font-size:0.68rem;letter-spacing:0.05em;color:#000000;background:#ffffff;border:1px solid #e5e5e5;padding:3px 8px;">Updating postage...</span>
                        </div>
                        <div style="font-size:0.72rem;color:#737373;margin-bottom:14px;">
                            Parcel weight: <strong id="card-weight-display">{{ $cartWeight ?? '0.5' }} kg</strong> &middot; Rates dynamically calculated from origin.
                        </div>
                        <div id="shipping-options-list" style="display:flex;flex-direction:column;gap:8px;">
                            @if(!empty($shippingRates))
                                @foreach($shippingRates as $rate)
                                    <label class="shipping-option-row" style="display:flex;justify-content:space-between;align-items:center;padding:12px 14px;background:#ffffff;border:1px solid {{ ($selectedRate && $selectedRate['code'] === $rate['code']) ? '#000000' : '#e5e5e5' }};cursor:pointer;transition:border-color 0.2s;">
                                        <div style="display:flex;align-items:center;gap:12px;">
                                            <input type="radio" name="shipping_service_code" value="{{ $rate['code'] }}" {{ ($selectedRate && $selectedRate['code'] === $rate['code']) ? 'checked' : '' }} style="accent-color:#000000;" onchange="selectShippingRate('{{ $rate['code'] }}', {{ $rate['cost'] }}, '{{ addslashes($rate['name']) }}')">
                                            <div>
                                                <div style="font-size:0.82rem;font-weight:600;color:#000000;">{{ $rate['name'] }}</div>
                                                <div style="font-size:0.7rem;color:#737373;">{{ $rate['delivery_time'] }}</div>
                                            </div>
                                        </div>
                                        <div style="font-family:'Cormorant Garamond',serif;font-size:1.1rem;font-weight:600;color:#000000;">
                                            {{ $currency_symbol }}{{ number_format($rate['cost'], 2) }}
                                        </div>
                                    </label>
                                @endforeach
                            @endif
                        </div>
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
                        <span id="summary-subtotal-amount">{{ $currency_symbol }}{{ number_format($totals['subtotal'], 2) }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;font-size:0.85rem;color:#525252;padding:6px 0;">
                        <div>
                            <span>Shipping</span>
                            <div id="summary-shipping-service-label" style="font-size:0.68rem;color:#a3a3a3;text-transform:uppercase;letter-spacing:0.05em;">{{ $selectedRate['name'] ?? 'Australia Post' }}</div>
                        </div>
                        <span id="summary-shipping-amount">{{ $totals['shipping'] > 0 ? $currency_symbol . number_format($totals['shipping'], 2) : 'Complimentary' }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding-top:16px;margin-top:12px;border-top:1px solid #e5e5e5;font-family:'Cormorant Garamond',serif;font-size:1.3rem;color:#000000;">
                        <span>Total</span>
                        <span id="summary-total-amount">{{ $currency_symbol }}{{ number_format($totals['total'], 2) }}</span>
                    </div>
                    <div style="margin-top:12px;padding:8px 10px;background:#f5f5f5;font-size:0.7rem;color:#525252;display:flex;align-items:center;justify-content:space-between;">
                        <span>Parcel Weight</span>
                        <strong id="summary-weight-label">{{ $cartWeight ?? '0.5' }} kg</strong>
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
    let currentTotal = {{ (float) $totals['total'] }};
    const currencySymbol = "{{ $currency_symbol }}";

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
                                value: currentTotal.toFixed(2)
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

    // ─── Country -> State/Territory -> City Cascading Location Dataset ───
    const locationData = {
        "Australia": {
            "New South Wales": ["Sydney", "Newcastle", "Central Coast", "Wollongong", "Maitland", "Tweed Heads", "Wagga Wagga", "Albury", "Coffs Harbour", "Port Macquarie", "Orange", "Dubbo", "Tamworth", "Bathurst", "Nowra", "Queanbeyan", "Lismore"],
            "Victoria": ["Melbourne", "Geelong", "Ballarat", "Bendigo", "Shepparton", "Mildura", "Warrnambool", "Wodonga", "Traralgon", "Wangaratta", "Sale", "Horsham", "Echuca", "Bairnsdale", "Mornington"],
            "Queensland": ["Brisbane", "Gold Coast", "Sunshine Coast", "Townsville", "Cairns", "Toowoomba", "Mackay", "Rockhampton", "Bundaberg", "Hervey Bay", "Gladstone", "Maryborough", "Mount Isa"],
            "Western Australia": ["Perth", "Mandurah", "Bunbury", "Geraldton", "Kalgoorlie", "Albany", "Busselton", "Karratha", "Broome", "Port Hedland", "Esperance"],
            "South Australia": ["Adelaide", "Mount Gambier", "Whyalla", "Murray Bridge", "Port Lincoln", "Port Augusta", "Port Pirie", "Victor Harbor", "Gawler"],
            "Tasmania": ["Hobart", "Launceston", "Devonport", "Burnie", "Kingston", "Ulverstone", "New Norfolk"],
            "Australian Capital Territory": ["Canberra", "Belconnen", "Gungahlin", "Tuggeranong", "Woden Valley"],
            "Northern Territory": ["Darwin", "Palmerston", "Alice Springs", "Katherine", "Nhulunbuy", "Tennant Creek"]
        },
        "New Zealand": {
            "Auckland": ["Auckland Central", "North Shore", "Manukau", "Waitakere"],
            "Wellington": ["Wellington Central", "Lower Hutt", "Upper Hutt", "Porirua"],
            "Canterbury": ["Christchurch", "Timaru", "Ashburton", "Rangiora"],
            "Waikato": ["Hamilton", "Taupo", "Cambridge", "Te Awamutu"],
            "Bay of Plenty": ["Tauranga", "Rotorua", "Whakatane"],
            "Otago": ["Dunedin", "Queenstown", "Wanaka", "Oamaru"],
            "Hawke's Bay": ["Napier", "Hastings", "Havelock North"],
            "Taranaki": ["New Plymouth", "Hawera"],
            "Northland": ["Whangarei", "Kerikeri"],
            "Southland": ["Invercargill", "Gore"],
            "Nelson-Tasman": ["Nelson", "Richmond"]
        },
        "United States": {
            "California": ["Los Angeles", "San Francisco", "San Diego", "San Jose", "Sacramento", "Fresno", "Oakland", "Long Beach", "Irvine"],
            "New York": ["New York City", "Buffalo", "Rochester", "Yonkers", "Syracuse", "Albany", "White Plains"],
            "Texas": ["Houston", "Dallas", "Austin", "San Antonio", "Fort Worth", "El Paso", "Arlington", "Plano"],
            "Florida": ["Miami", "Orlando", "Tampa", "Jacksonville", "Fort Lauderdale", "St. Petersburg", "Tallahassee"],
            "Washington": ["Seattle", "Spokane", "Tacoma", "Vancouver", "Bellevue", "Everett"],
            "Illinois": ["Chicago", "Aurora", "Naperville", "Rockford", "Joliet", "Springfield"],
            "Pennsylvania": ["Philadelphia", "Pittsburgh", "Allentown", "Erie", "Reading"],
            "Georgia": ["Atlanta", "Augusta", "Columbus", "Macon", "Savannah"],
            "Massachusetts": ["Boston", "Worcester", "Springfield", "Cambridge", "Lowell"],
            "New Jersey": ["Newark", "Jersey City", "Paterson", "Elizabeth", "Trenton"],
            "North Carolina": ["Charlotte", "Raleigh", "Greensboro", "Durham", "Winston-Salem"],
            "Ohio": ["Columbus", "Cleveland", "Cincinnati", "Toledo", "Akron"],
            "Virginia": ["Virginia Beach", "Norfolk", "Richmond", "Chesapeake", "Arlington"],
            "Colorado": ["Denver", "Colorado Springs", "Aurora", "Fort Collins", "Boulder"],
            "Arizona": ["Phoenix", "Tucson", "Mesa", "Chandler", "Scottsdale"]
        },
        "Canada": {
            "Ontario": ["Toronto", "Ottawa", "Mississauga", "Brampton", "Hamilton", "London", "Markham", "Vaughan"],
            "Quebec": ["Montreal", "Quebec City", "Laval", "Gatineau", "Longueuil", "Sherbrooke"],
            "British Columbia": ["Vancouver", "Victoria", "Surrey", "Burnaby", "Richmond", "Kelowna"],
            "Alberta": ["Calgary", "Edmonton", "Red Deer", "Lethbridge"],
            "Manitoba": ["Winnipeg", "Brandon"],
            "Nova Scotia": ["Halifax", "Sydney"]
        },
        "United Kingdom": {
            "England": ["London", "Manchester", "Birmingham", "Leeds", "Liverpool", "Bristol", "Newcastle", "Sheffield", "Oxford", "Cambridge"],
            "Scotland": ["Edinburgh", "Glasgow", "Aberdeen", "Dundee", "Inverness"],
            "Wales": ["Cardiff", "Swansea", "Newport", "Bangor"],
            "Northern Ireland": ["Belfast", "Derry", "Lisburn", "Newry"]
        },
        "Germany": {
            "Berlin": ["Berlin"],
            "Bavaria": ["Munich", "Nuremberg", "Augsburg", "Regensburg", "Würzburg"],
            "Hamburg": ["Hamburg"],
            "Hesse": ["Frankfurt", "Wiesbaden", "Kassel", "Darmstadt"],
            "North Rhine-Westphalia": ["Cologne", "Düsseldorf", "Dortmund", "Essen", "Bonn"],
            "Baden-Württemberg": ["Stuttgart", "Mannheim", "Karlsruhe", "Freiburg", "Heidelberg"],
            "Saxony": ["Leipzig", "Dresden"]
        },
        "France": {
            "Île-de-France": ["Paris", "Boulogne-Billancourt", "Saint-Denis", "Versailles"],
            "Auvergne-Rhône-Alpes": ["Lyon", "Grenoble", "Saint-Étienne", "Annecy"],
            "Provence-Alpes-Côte d'Azur": ["Marseille", "Nice", "Toulon", "Aix-en-Provence", "Cannes"],
            "Nouvelle-Aquitaine": ["Bordeaux", "Limoges", "Poitiers", "La Rochelle"],
            "Occitanie": ["Toulouse", "Montpellier", "Nîmes"]
        },
        "Italy": {
            "Lombardy": ["Milan", "Bergamo", "Brescia", "Monza", "Como"],
            "Lazio": ["Rome", "Latina", "Frosinone"],
            "Veneto": ["Venice", "Verona", "Padua", "Vicenza"],
            "Tuscany": ["Florence", "Pisa", "Siena", "Lucca"],
            "Campania": ["Naples", "Salerno"],
            "Piedmont": ["Turin", "Novara"]
        },
        "Spain": {
            "Madrid": ["Madrid"],
            "Catalonia": ["Barcelona", "Girona", "Tarragona"],
            "Andalusia": ["Seville", "Malaga", "Cordoba", "Granada", "Marbella"],
            "Valencia": ["Valencia", "Alicante", "Castellon"],
            "Basque Country": ["Bilbao", "San Sebastian"]
        },
        "Japan": {
            "Tokyo": ["Tokyo", "Shibuya", "Shinjuku", "Minato", "Ginza"],
            "Osaka": ["Osaka", "Sakai", "Higashiosaka"],
            "Kanagawa": ["Yokohama", "Kawasaki"],
            "Kyoto": ["Kyoto", "Uji"],
            "Aichi": ["Nagoya", "Toyota"],
            "Hokkaido": ["Sapporo", "Asahikawa", "Hakodate"]
        },
        "Singapore": {
            "Singapore": ["Singapore Central", "Orchard", "Jurong", "Tampines", "Woodlands", "Bedok"]
        },
        "Hong Kong": {
            "Hong Kong": ["Central", "Causeway Bay", "Tsim Sha Tsui", "Mong Kok", "Sha Tin", "Wan Chai"]
        },
        "South Korea": {
            "Seoul": ["Seoul", "Gangnam", "Mapo", "Songpa"],
            "Gyeonggi": ["Suwon", "Seongnam", "Goyang", "Yongin"],
            "Busan": ["Busan", "Haeundae"],
            "Incheon": ["Incheon"]
        },
        "China": {
            "Beijing": ["Beijing"],
            "Shanghai": ["Shanghai"],
            "Guangdong": ["Guangzhou", "Shenzhen", "Dongguan"],
            "Zhejiang": ["Hangzhou", "Ningbo"],
            "Jiangsu": ["Nanjing", "Suzhou"]
        },
        "Brazil": {
            "São Paulo": ["São Paulo", "Campinas", "Santos"],
            "Rio de Janeiro": ["Rio de Janeiro", "Niterói"],
            "Minas Gerais": ["Belo Horizonte", "Uberlândia"],
            "Bahia": ["Salvador"],
            "Paraná": ["Curitiba"]
        },
        "Argentina": {
            "Buenos Aires": ["Buenos Aires", "La Plata", "Mar del Plata"],
            "Córdoba": ["Córdoba"],
            "Santa Fe": ["Rosario", "Santa Fe"],
            "Mendoza": ["Mendoza"]
        },
        "Mexico": {
            "Mexico City": ["Mexico City"],
            "Jalisco": ["Guadalajara", "Puerto Vallarta"],
            "Nuevo León": ["Monterrey"],
            "Quintana Roo": ["Cancún", "Playa del Carmen"]
        },
        "Netherlands": {
            "North Holland": ["Amsterdam", "Haarlem"],
            "South Holland": ["Rotterdam", "The Hague", "Leiden"],
            "Utrecht": ["Utrecht"]
        },
        "Switzerland": {
            "Zurich": ["Zurich"],
            "Geneva": ["Geneva"],
            "Bern": ["Bern"],
            "Vaud": ["Lausanne"]
        },
        "Sweden": {
            "Stockholm": ["Stockholm"],
            "Västra Götaland": ["Gothenburg"],
            "Skåne": ["Malmö"]
        },
        "Norway": {
            "Oslo": ["Oslo"],
            "Vestland": ["Bergen"],
            "Trøndelag": ["Trondheim"]
        },
        "Denmark": {
            "Capital Region": ["Copenhagen"],
            "Central Denmark": ["Aarhus"],
            "Southern Denmark": ["Odense"]
        },
        "Ireland": {
            "Leinster": ["Dublin"],
            "Munster": ["Cork", "Limerick"],
            "Connacht": ["Galway"]
        },
        "Belgium": {
            "Brussels": ["Brussels"],
            "Flanders": ["Antwerp", "Ghent", "Bruges"],
            "Wallonia": ["Liège"]
        },
        "Austria": {
            "Vienna": ["Vienna"],
            "Salzburg": ["Salzburg"],
            "Tyrol": ["Innsbruck"]
        },
        "Poland": {
            "Masovian": ["Warsaw"],
            "Lesser Poland": ["Kraków"],
            "Lower Silesian": ["Wrocław"]
        },
        "Portugal": {
            "Lisbon": ["Lisbon", "Cascais", "Sintra"],
            "Porto": ["Porto"],
            "Faro (Algarve)": ["Faro", "Lagos"]
        },
        "United Arab Emirates": {
            "Dubai": ["Dubai"],
            "Abu Dhabi": ["Abu Dhabi", "Al Ain"],
            "Sharjah": ["Sharjah"]
        },
        "India": {
            "Maharashtra": ["Mumbai", "Pune"],
            "Delhi": ["New Delhi"],
            "Karnataka": ["Bengaluru"],
            "Tamil Nadu": ["Chennai"],
            "Telangana": ["Hyderabad"]
        },
        "Malaysia": {
            "Kuala Lumpur": ["Kuala Lumpur"],
            "Selangor": ["Petaling Jaya", "Shah Alam"],
            "Penang": ["George Town"]
        },
        "Thailand": {
            "Bangkok": ["Bangkok"],
            "Chiang Mai": ["Chiang Mai"],
            "Phuket": ["Phuket"]
        },
        "Indonesia": {
            "Jakarta": ["Jakarta"],
            "Bali": ["Denpasar", "Kuta", "Ubud"],
            "West Java": ["Bandung"]
        },
        "Philippines": {
            "Metro Manila": ["Manila", "Quezon City", "Makati"],
            "Cebu": ["Cebu City"],
            "Davao": ["Davao City"]
        },
        "Taiwan": {
            "Taipei": ["Taipei"],
            "Kaohsiung": ["Kaohsiung"],
            "Taichung": ["Taichung"]
        },
        "Chile": {
            "Santiago Metropolitan": ["Santiago", "Providencia"],
            "Valparaíso": ["Valparaíso", "Viña del Mar"]
        },
        "Colombia": {
            "Bogotá D.C.": ["Bogotá"],
            "Antioquia": ["Medellín"],
            "Valle del Cauca": ["Cali"]
        },
        "Peru": {
            "Lima": ["Lima", "Miraflores"],
            "Cusco": ["Cusco"],
            "Arequipa": ["Arequipa"]
        },
        "Fiji": {
            "Central": ["Suva"],
            "Western": ["Nadi", "Lautoka"]
        },
        "Papua New Guinea": {
            "National Capital District": ["Port Moresby"],
            "Morobe": ["Lae"]
        },
        "Samoa": {
            "Tuamasaga": ["Apia"]
        }
    };

    // DOM Elements
    const countrySelect = document.getElementById('country');
    const stateSelect = document.getElementById('state_select');
    const stateCustom = document.getElementById('state_custom');
    const citySelect = document.getElementById('city_select');
    const cityCustom = document.getElementById('city_custom');
    const postcodeInput = document.getElementById('postal_code');
    const loadingSpinner = document.getElementById('shipping-loading-spinner');
    const saveCheckbox = document.getElementById('save-info-checkbox');
    const storageKey = 'pistis_express_shipping';

    const initialSavedCountry = @json(old('country', $customer->country ?? $savedShipping['country'] ?? 'Australia'));
    const initialSavedState = @json(old('state', $customer->state ?? $savedShipping['state'] ?? ''));
    const initialSavedCity = @json(old('city', $customer->city ?? $savedShipping['city'] ?? ''));

    function getCountryStates(country) {
        if (locationData[country]) {
            return Object.keys(locationData[country]);
        }
        return [];
    }

    function getStateCities(country, state) {
        if (locationData[country] && locationData[country][state]) {
            return locationData[country][state];
        }
        return [];
    }

    function syncStateInput() {
        if (!stateSelect || !stateCustom) return;
        if (stateSelect.value === '__custom__') {
            stateCustom.style.display = 'block';
            stateCustom.required = true;
            stateSelect.removeAttribute('name');
            stateCustom.setAttribute('name', 'state');
        } else {
            stateCustom.style.display = 'none';
            stateCustom.required = false;
            stateCustom.removeAttribute('name');
            stateSelect.setAttribute('name', 'state');
            stateSelect.required = true;
        }
    }

    function syncCityInput() {
        if (!citySelect || !cityCustom) return;
        if (citySelect.value === '__custom__') {
            cityCustom.style.display = 'block';
            cityCustom.required = true;
            citySelect.removeAttribute('name');
            cityCustom.setAttribute('name', 'city');
        } else {
            cityCustom.style.display = 'none';
            cityCustom.required = false;
            cityCustom.removeAttribute('name');
            citySelect.setAttribute('name', 'city');
            citySelect.required = true;
        }
    }

    function populateStates(country, targetState = null, targetCity = null) {
        if (!stateSelect) return;
        const states = getCountryStates(country);

        stateSelect.innerHTML = '';

        if (states.length > 0) {
            states.forEach(st => {
                const opt = document.createElement('option');
                opt.value = st;
                opt.textContent = st;
                stateSelect.appendChild(opt);
            });
            const customOpt = document.createElement('option');
            customOpt.value = '__custom__';
            customOpt.textContent = '+ Other / Enter Custom State...';
            stateSelect.appendChild(customOpt);

            if (targetState && states.includes(targetState)) {
                stateSelect.value = targetState;
                if (stateCustom) {
                    stateCustom.style.display = 'none';
                    stateCustom.value = '';
                }
                syncStateInput();
                populateCities(country, targetState, targetCity);
            } else if (targetState && targetState.trim()) {
                stateSelect.value = '__custom__';
                if (stateCustom) {
                    stateCustom.style.display = 'block';
                    stateCustom.value = targetState;
                }
                syncStateInput();
                populateCities(country, '__custom__', targetCity);
            } else {
                stateSelect.value = states[0];
                if (stateCustom) {
                    stateCustom.style.display = 'none';
                    stateCustom.value = '';
                }
                syncStateInput();
                populateCities(country, states[0], targetCity);
            }
        } else {
            // Country with free-form state/region
            const customOpt = document.createElement('option');
            customOpt.value = '__custom__';
            customOpt.textContent = 'Enter State / Region';
            stateSelect.appendChild(customOpt);
            stateSelect.value = '__custom__';
            if (stateCustom) {
                stateCustom.style.display = 'block';
                stateCustom.value = targetState || '';
            }
            syncStateInput();
            populateCities(country, '__custom__', targetCity);
        }
    }

    function populateCities(country, state, targetCity = null) {
        if (!citySelect) return;
        const cities = getStateCities(country, state);

        citySelect.innerHTML = '';

        if (cities.length > 0) {
            cities.forEach(ct => {
                const opt = document.createElement('option');
                opt.value = ct;
                opt.textContent = ct;
                citySelect.appendChild(opt);
            });
            const customOpt = document.createElement('option');
            customOpt.value = '__custom__';
            customOpt.textContent = '+ Other / Enter Custom City...';
            citySelect.appendChild(customOpt);

            if (targetCity && cities.includes(targetCity)) {
                citySelect.value = targetCity;
                if (cityCustom) {
                    cityCustom.style.display = 'none';
                    cityCustom.value = '';
                }
                syncCityInput();
            } else if (targetCity && targetCity.trim()) {
                citySelect.value = '__custom__';
                if (cityCustom) {
                    cityCustom.style.display = 'block';
                    cityCustom.value = targetCity;
                }
                syncCityInput();
            } else {
                citySelect.value = cities[0];
                if (cityCustom) {
                    cityCustom.style.display = 'none';
                    cityCustom.value = '';
                }
                syncCityInput();
            }
        } else {
            // State with free-form city
            const customOpt = document.createElement('option');
            customOpt.value = '__custom__';
            customOpt.textContent = 'Enter City / Suburb';
            citySelect.appendChild(customOpt);
            citySelect.value = '__custom__';
            if (cityCustom) {
                cityCustom.style.display = 'block';
                cityCustom.value = targetCity || '';
            }
            syncCityInput();
        }
    }

    // Event Listeners for cascading selections
    if (countrySelect) {
        countrySelect.addEventListener('change', function() {
            populateStates(this.value);
            fetchShippingRates();
            persistShippingDetails();
        });
    }

    if (stateSelect) {
        stateSelect.addEventListener('change', function() {
            syncStateInput();
            if (this.value !== '__custom__') {
                populateCities(countrySelect ? countrySelect.value : 'Australia', this.value);
            } else {
                populateCities(countrySelect ? countrySelect.value : 'Australia', '__custom__');
            }
            fetchShippingRates();
            persistShippingDetails();
        });
    }

    if (citySelect) {
        citySelect.addEventListener('change', function() {
            syncCityInput();
            persistShippingDetails();
        });
    }

    if (stateCustom) {
        stateCustom.addEventListener('input', persistShippingDetails);
    }
    if (cityCustom) {
        cityCustom.addEventListener('input', persistShippingDetails);
    }

    // ─── Australia Post Dynamic Rate Calculation ──────────────────────────
    let calcTimeout = null;

    function debounceCalculateShipping() {
        clearTimeout(calcTimeout);
        calcTimeout = setTimeout(() => fetchShippingRates(), 500);
    }

    if (postcodeInput) {
        postcodeInput.addEventListener('input', debounceCalculateShipping);
        postcodeInput.addEventListener('change', () => fetchShippingRates());
    }

    async function fetchShippingRates(forcedServiceCode = null) {
        const country = countrySelect ? countrySelect.value.trim() : 'Australia';
        const postalCode = postcodeInput ? postcodeInput.value.trim() : '';

        if (!country) return;

        if (loadingSpinner) loadingSpinner.style.display = 'inline-block';

        const activeServiceRadio = document.querySelector('input[name="shipping_service_code"]:checked');
        const serviceCode = forcedServiceCode || (activeServiceRadio ? activeServiceRadio.value : null);

        try {
            const response = await fetch("{{ route('checkout.shipping-rates') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    country: country,
                    postal_code: postalCode,
                    service_code: serviceCode,
                })
            });

            const data = await response.json();
            if (data.success) {
                currentTotal = parseFloat(data.total);

                // Update summary figures
                const shipAmt = document.getElementById('summary-shipping-amount');
                const totAmt = document.getElementById('summary-total-amount');
                const shipLabel = document.getElementById('summary-shipping-service-label');
                const mobShipAmt = document.getElementById('mobile-summary-shipping-amount');
                const mobTotAmt = document.getElementById('mobile-summary-total-amount');
                const mobShipLabel = document.getElementById('mobile-shipping-service-label');
                const mobBtnTotal = document.querySelector('.summary-btn-total');
                const weightLabel = document.getElementById('summary-weight-label');
                const cardWeight = document.getElementById('card-weight-display');

                if (shipAmt) shipAmt.textContent = data.formatted_shipping;
                if (totAmt) totAmt.textContent = data.formatted_total;
                if (shipLabel && data.selected_rate) shipLabel.textContent = data.selected_rate.name;
                if (mobShipAmt) mobShipAmt.textContent = data.formatted_shipping;
                if (mobTotAmt) mobTotAmt.textContent = data.formatted_total;
                if (mobShipLabel && data.selected_rate) mobShipLabel.textContent = data.selected_rate.name;
                if (mobBtnTotal) mobBtnTotal.textContent = data.formatted_total;
                if (weightLabel) weightLabel.textContent = data.weight_kg + ' kg';
                if (cardWeight) cardWeight.textContent = data.weight_kg + ' kg';

                renderShippingOptions(data.rates, data.selected_rate);
            }
        } catch (err) {
            console.error('Failed to calculate AusPost shipping rates:', err);
        } finally {
            if (loadingSpinner) loadingSpinner.style.display = 'none';
        }
    }

    function renderShippingOptions(rates, selectedRate) {
        const container = document.getElementById('shipping-options-list');
        if (!container || !rates) return;

        container.innerHTML = rates.map(rate => {
            const isSelected = selectedRate && selectedRate.code === rate.code;
            const safeName = rate.name.replace(/'/g, "\\'");
            return `
                <label class="shipping-option-row" style="display:flex;justify-content:space-between;align-items:center;padding:12px 14px;background:#ffffff;border:1px solid ${isSelected ? '#000000' : '#e5e5e5'};cursor:pointer;transition:border-color 0.2s;">
                    <div style="display:flex;align-items:center;gap:12px;">
                        <input type="radio" name="shipping_service_code" value="${rate.code}" ${isSelected ? 'checked' : ''} style="accent-color:#000000;" onchange="selectShippingRate('${rate.code}', ${rate.cost}, '${safeName}')">
                        <div>
                            <div style="font-size:0.82rem;font-weight:600;color:#000000;">${rate.name}</div>
                            <div style="font-size:0.7rem;color:#737373;">${rate.delivery_time}</div>
                        </div>
                    </div>
                    <div style="font-family:'Cormorant Garamond',serif;font-size:1.1rem;font-weight:600;color:#000000;">
                        ${currencySymbol}${parseFloat(rate.cost).toFixed(2)}
                    </div>
                </label>
            `;
        }).join('');
    }

    window.selectShippingRate = function(code, cost, name) {
        fetchShippingRates(code);
    };

    // Client-side LocalStorage Auto-fill & Initial Setup
    try {
        const saved = JSON.parse(localStorage.getItem(storageKey) || '{}');
        ['first_name', 'last_name', 'email', 'phone', 'postal_code', 'address'].forEach(field => {
            const input = document.getElementById(field);
            if (input && !input.value.trim() && saved[field]) {
                input.value = saved[field];
            }
        });

        const activeCountry = initialSavedCountry || saved.country || 'Australia';
        if (countrySelect) {
            countrySelect.value = activeCountry;
        }

        const activeState = initialSavedState || saved.state || null;
        const activeCity = initialSavedCity || saved.city || null;
        populateStates(activeCountry, activeState, activeCity);
    } catch (e) {
        if (countrySelect) {
            countrySelect.value = initialSavedCountry || 'Australia';
            populateStates(countrySelect.value, initialSavedState, initialSavedCity);
        }
    }

    function persistShippingDetails() {
        if (saveCheckbox && !saveCheckbox.checked) {
            localStorage.removeItem(storageKey);
            return;
        }
        const data = {
            first_name: document.getElementById('first_name')?.value || '',
            last_name: document.getElementById('last_name')?.value || '',
            email: document.getElementById('email')?.value || '',
            phone: document.getElementById('phone')?.value || '',
            country: countrySelect?.value || 'Australia',
            state: stateSelect?.value === '__custom__' ? (stateCustom?.value || '') : (stateSelect?.value || ''),
            city: citySelect?.value === '__custom__' ? (cityCustom?.value || '') : (citySelect?.value || ''),
            postal_code: postcodeInput?.value || '',
            address: document.getElementById('address')?.value || '',
        };
        try {
            localStorage.setItem(storageKey, JSON.stringify(data));
        } catch (e) {}
    }

    ['first_name', 'last_name', 'email', 'phone', 'address', 'postal_code'].forEach(field => {
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
