@extends('layouts.app')

@section('title', 'Order Confirmed — Pistis')

@push('styles')
<style>
/* ─── Luxury Order Confirmed Page Styles ─────────────────── */
.order-success-container {
    max-width: 620px;
    margin: 0 auto;
    padding: 36px 20px 60px;
    box-sizing: border-box;
}

@media (max-width: 640px) {
    .order-success-container {
        padding: 20px 14px 44px;
    }
}

.success-header {
    text-align: center;
    margin-bottom: 22px;
}

.success-badge-icon {
    width: 58px;
    height: 58px;
    border-radius: 50%;
    background: #000000;
    color: #ffffff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 14px;
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.25);
    transition: transform 0.25s ease;
}

.success-badge-icon:hover {
    transform: scale(1.05);
}

.success-atelier-tag {
    font-family: 'Inter', sans-serif;
    font-size: 0.65rem;
    font-weight: 700;
    letter-spacing: 0.22em;
    text-transform: uppercase;
    color: #737373;
    margin-bottom: 6px;
}

.success-title {
    font-family: 'Cormorant Garamond', Georgia, serif;
    font-size: clamp(1.8rem, 5vw, 2.4rem);
    font-weight: 700;
    color: #171717;
    letter-spacing: 0.04em;
    text-transform: uppercase;
    line-height: 1.15;
    margin: 0 0 8px;
}

.success-subtitle {
    font-family: 'Inter', sans-serif;
    font-size: 0.85rem;
    color: #525252;
    line-height: 1.5;
    max-width: 460px;
    margin: 0 auto 16px;
}

/* Order Reference Pill */
.order-ref-pill {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #f5f5f5;
    border: 1px solid #e5e5e5;
    padding: 6px 14px;
    border-radius: 4px;
    margin-bottom: 4px;
    max-width: 100%;
}
.order-ref-label {
    font-family: 'Inter', sans-serif;
    font-size: 0.65rem;
    font-weight: 700;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: #737373;
}
.order-ref-number {
    font-family: 'Inter', monospace;
    font-size: 0.84rem;
    font-weight: 700;
    color: #171717;
    letter-spacing: 0.04em;
    word-break: break-all;
}
.order-ref-copy {
    background: none;
    border: none;
    cursor: pointer;
    padding: 3px 5px;
    color: #737373;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: color 0.15s ease;
    border-radius: 2px;
}
.order-ref-copy:hover {
    color: #000000;
    background: #e5e5e5;
}

/* Cards & Sections */
.success-card {
    background: #ffffff;
    border: 1px solid #e5e5e5;
    margin-bottom: 14px;
    overflow: hidden;
    text-align: left;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
}
.success-card-header {
    padding: 11px 16px;
    background: #fafafa;
    border-bottom: 1px solid #f0f0f0;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.success-card-title {
    font-family: 'Inter', sans-serif;
    font-size: 0.68rem;
    font-weight: 700;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    color: #737373;
    margin: 0;
}
.success-card-body {
    padding: 16px;
}

/* Order Info Rows */
.order-info-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 7px 0;
    font-size: 0.84rem;
    border-bottom: 1px dashed #f0f0f0;
}
.order-info-row:last-child {
    border-bottom: none;
    padding-bottom: 0;
}
.order-info-row:first-child {
    padding-top: 0;
}
.order-info-label {
    color: #737373;
    font-weight: 400;
}
.order-info-value {
    color: #171717;
    font-weight: 600;
    text-align: right;
}

/* Items Preview List */
.order-items-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}
.order-item-row {
    display: flex;
    align-items: center;
    gap: 12px;
}
.order-item-thumb {
    width: 48px;
    height: 60px;
    object-fit: cover;
    background: #f5f5f5;
    border: 1px solid #ebebeb;
    flex-shrink: 0;
}
.order-item-details {
    flex: 1;
    min-width: 0;
}
.order-item-name {
    font-family: 'Cormorant Garamond', Georgia, serif;
    font-size: 1.05rem;
    font-weight: 600;
    color: #171717;
    line-height: 1.25;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.order-item-meta {
    font-size: 0.72rem;
    color: #737373;
    margin-top: 2px;
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
    align-items: center;
}
.order-item-price {
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.05rem;
    font-weight: 700;
    color: #000000;
    text-align: right;
    flex-shrink: 0;
}

/* Totals Box */
.order-totals-box {
    margin-top: 12px;
    padding-top: 10px;
    border-top: 1px solid #ebebeb;
}
.order-total-highlight {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    padding-top: 8px;
    margin-top: 8px;
    border-top: 1px solid #171717;
}
.order-total-highlight-label {
    font-family: 'Inter', sans-serif;
    font-size: 0.76rem;
    font-weight: 700;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: #171717;
}
.order-total-highlight-value {
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.35rem;
    font-weight: 700;
    color: #000000;
}

/* ─── Client Account Card Redesign ────────────────────────── */
.client-account-card {
    background: #ffffff;
    border: 1px solid #e5e5e5;
    margin-bottom: 16px;
    overflow: hidden;
    text-align: left;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.02);
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
}
.client-account-card:hover {
    border-color: #d4d4d4;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.04);
}
.client-card-inner {
    padding: 16px 18px;
}
.client-card-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding-bottom: 13px;
    border-bottom: 1px solid #f0f0f0;
}
.client-profile-group {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
}
.client-avatar-monogram {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    background: #000000;
    color: #ffffff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-family: 'Cormorant Garamond', Georgia, serif;
    font-size: 1.25rem;
    font-weight: 700;
    flex-shrink: 0;
    letter-spacing: 0.02em;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.12);
}
.client-meta-info {
    min-width: 0;
}
.client-meta-tag {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-family: 'Inter', sans-serif;
    font-size: 0.62rem;
    font-weight: 700;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    color: #15803d;
    margin-bottom: 2px;
}
.client-pulse-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #16a34a;
    display: inline-block;
}
.client-welcome-heading {
    font-family: 'Cormorant Garamond', Georgia, serif;
    font-size: 1.18rem;
    font-weight: 700;
    color: #171717;
    line-height: 1.2;
    margin: 0 0 2px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.client-email-badge {
    font-family: 'Inter', sans-serif;
    font-size: 0.76rem;
    color: #737373;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.client-history-link-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 12px;
    background: #fafafa;
    border: 1px solid #e5e5e5;
    border-radius: 2px;
    font-family: 'Inter', sans-serif;
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: #171717;
    text-decoration: none;
    flex-shrink: 0;
    transition: all 0.2s ease;
}
.client-history-link-btn:hover {
    background: #000000;
    color: #ffffff;
    border-color: #000000;
}
.client-history-link-btn svg {
    transition: transform 0.2s ease;
}
.client-history-link-btn:hover svg {
    transform: translateX(2px);
}
.client-perks-row {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 8px 12px;
    padding-top: 12px;
}
.client-perk-item {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-family: 'Inter', sans-serif;
    font-size: 0.72rem;
    font-weight: 500;
    color: #525252;
}
.client-perk-item svg {
    color: #15803d;
    flex-shrink: 0;
}

/* ─── Luxury Action Buttons Redesign ────────────────────────── */
.confirmation-actions {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-top: 24px;
}
.confirmation-actions-row {
    display: flex;
    gap: 12px;
}
@media (max-width: 540px) {
    .confirmation-actions-row {
        flex-direction: column;
    }
}
.btn-confirm-primary {
    flex: 1;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 9px;
    background: #000000;
    color: #ffffff;
    border: 1px solid #000000;
    padding: 13px 20px;
    font-family: 'Inter', sans-serif;
    font-size: 0.76rem;
    font-weight: 700;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    text-decoration: none;
    white-space: nowrap;
    transition: all 0.2s ease;
    cursor: pointer;
    min-height: 48px;
    box-sizing: border-box;
}
.btn-confirm-primary:hover {
    background: #262626;
    color: #ffffff;
    border-color: #262626;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.15);
}
.btn-confirm-primary svg {
    transition: transform 0.2s ease;
}
.btn-confirm-primary:hover svg {
    transform: translateX(3px);
}
.btn-confirm-secondary {
    flex: 1;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    background: #ffffff;
    color: #171717;
    border: 1px solid #171717;
    padding: 13px 20px;
    font-family: 'Inter', sans-serif;
    font-size: 0.76rem;
    font-weight: 700;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    text-decoration: none;
    white-space: nowrap;
    transition: all 0.2s ease;
    cursor: pointer;
    min-height: 48px;
    box-sizing: border-box;
}
.btn-confirm-secondary:hover {
    background: #171717;
    color: #ffffff;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.1);
}
.btn-confirm-secondary svg {
    transition: transform 0.2s ease;
}
.btn-confirm-secondary:hover svg {
    transform: translateY(-1px);
}
.confirmation-actions-utility {
    display: flex;
    justify-content: center;
    margin-top: 4px;
}
.btn-confirm-print {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    background: transparent;
    color: #737373;
    border: 1px dashed #d4d4d4;
    padding: 9px 20px;
    font-family: 'Inter', sans-serif;
    font-size: 0.72rem;
    font-weight: 600;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    text-decoration: none;
    white-space: nowrap;
    transition: all 0.2s ease;
    cursor: pointer;
    box-sizing: border-box;
}
.btn-confirm-print:hover {
    background: #ffffff;
    color: #000000;
    border-color: #000000;
    border-style: solid;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

/* Print Styles */
@media print {
    #main-navbar, .footer, .confirmation-actions, .order-ref-copy, .client-account-card {
        display: none !important;
    }
    .order-success-container {
        padding: 0;
        max-width: 100%;
    }
    .success-card {
        box-shadow: none;
        border: 1px solid #ccc;
    }
}
</style>
@endpush

@section('content')
<div class="order-success-container">
    {{-- Header Section --}}
    <div class="success-header">
        <div class="success-badge-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
        </div>
        <div class="success-atelier-tag">Pistis Garment Archive</div>
        <h1 class="success-title">Order Confirmed</h1>
        <p class="success-subtitle">
            Thank you, <strong>{{ $order->customer_name ? explode(' ', trim($order->customer_name))[0] : 'valued client' }}</strong>. 
            Your bespoke order has been placed successfully and is being prepared with atelier craftsmanship.
        </p>

        {{-- Order Reference Pill (Tap to Copy) --}}
        <div class="order-ref-pill">
            <span class="order-ref-label">Ref:</span>
            <span class="order-ref-number" id="order-ref-value">{{ $order->order_number }}</span>
            <button type="button" 
                    class="order-ref-copy" 
                    onclick="copyOrderRefNumber('{{ $order->order_number }}', this)" 
                    title="Copy order reference" 
                    aria-label="Copy order reference">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                    <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                </svg>
            </button>
        </div>
    </div>

    {{-- Order Status & Payment Card --}}
    <div class="success-card">
        <div class="success-card-header">
            <span class="success-card-title">Order Details</span>
            <span style="font-size:0.72rem;font-family:'Inter',sans-serif;color:#737373;">{{ $order->created_at->format('M d, Y') }}</span>
        </div>
        <div class="success-card-body" style="padding:14px 16px;">
            <div class="order-info-row">
                <span class="order-info-label">Order Status</span>
                <span class="badge badge-{{ $order->status_badge }}">{{ ucfirst($order->status) }}</span>
            </div>
            <div class="order-info-row">
                <span class="order-info-label">Payment</span>
                <span class="badge badge-{{ $order->payment_status_badge }}">{{ ucfirst($order->payment_status) }}</span>
            </div>
            @if($order->payment_method)
                <div class="order-info-row">
                    <span class="order-info-label">Method</span>
                    <span class="order-info-value" style="font-size:0.8rem;text-transform:uppercase;">{{ ucfirst($order->payment_method) }}</span>
                </div>
            @endif
            <div class="order-info-row">
                <span class="order-info-label">Total Amount</span>
                <span class="order-info-value" style="font-family:'Cormorant Garamond',serif;font-size:1.18rem;font-weight:700;">{{ $currency_symbol }}{{ number_format($order->total, 2) }}</span>
            </div>
        </div>
    </div>

    {{-- Shipment & Australia Post Tracking Card --}}
    <div class="success-card">
        <div class="success-card-header">
            <span class="success-card-title">Courier & Dispatch Tracking</span>
            <span style="font-size:0.72rem;font-family:'Inter',sans-serif;color:#737373;">{{ $order->tracking_carrier ?? 'Australia Post' }}</span>
        </div>
        <div class="success-card-body" style="padding:16px;">
            @php
                $statusIndex = match($order->status) {
                    'pending' => 0,
                    'processing' => 1,
                    'shipped' => 2,
                    'delivered' => 3,
                    'cancelled' => -1,
                    default => 0,
                };
            @endphp

            @if($order->status !== 'cancelled')
                {{-- Status Step Pipeline --}}
                <div style="display:flex;align-items:center;justify-content:space-between;position:relative;margin-bottom:20px;padding:4px 6px;">
                    <div style="position:absolute;top:16px;left:24px;right:24px;height:2px;background:#e5e5e5;z-index:1;"></div>
                    <div style="position:absolute;top:16px;left:24px;width:{{ min(100, max(0, ($statusIndex / 3) * 100)) }}%;height:2px;background:#000000;z-index:2;transition:width 0.4s ease;"></div>

                    @foreach(['Order Placed', 'Atelier Crafting', 'Dispatched', 'Delivered'] as $i => $stepLabel)
                        <div style="position:relative;z-index:3;text-align:center;">
                            <div style="width:24px;height:24px;border-radius:50%;margin:0 auto 5px;display:flex;align-items:center;justify-content:center;font-size:0.7rem;font-weight:700;
                                background: {{ $i <= $statusIndex ? '#000000' : '#ffffff' }};
                                color: {{ $i <= $statusIndex ? '#ffffff' : '#a3a3a3' }};
                                border: 2px solid {{ $i <= $statusIndex ? '#000000' : '#d4d4d4' }};">
                                @if($i < $statusIndex)
                                    ✓
                                @else
                                    {{ $i + 1 }}
                                @endif
                            </div>
                            <div style="font-size:0.68rem;font-weight:{{ $i === $statusIndex ? '700' : '500' }};color:{{ $i === $statusIndex ? '#000000' : '#737373' }};white-space:nowrap;">
                                {{ $stepLabel }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            @if($order->tracking_number)
                <div style="background:#0b0f19;border-radius:4px;padding:16px 20px;text-align:center;color:#ffffff;margin-top:6px;">
                    <div style="font-size:0.68rem;letter-spacing:0.16em;text-transform:uppercase;color:#9ca3af;font-weight:700;margin-bottom:4px;">
                        Australia Post Consignment
                    </div>
                    <div style="font-family:monospace;font-size:1.15rem;font-weight:700;letter-spacing:0.08em;color:#ffffff;margin-bottom:8px;">
                        {{ $order->tracking_number }}
                    </div>
                    @if($order->shipped_at)
                        <div style="font-size:0.72rem;color:#9ca3af;margin-bottom:12px;">
                            Dispatched on {{ $order->shipped_at->format('d M Y, h:i A') }}
                        </div>
                    @endif
                    @if($order->tracking_url)
                        <div>
                            <a href="{{ $order->tracking_url }}" target="_blank" style="display:inline-block;background:#dc2626;color:#ffffff;font-size:0.75rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;text-decoration:none;padding:10px 22px;border-radius:3px;">
                                Track on Australia Post &rarr;
                            </a>
                        </div>
                    @endif
                </div>
            @else
                <div style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:4px;padding:12px 14px;font-size:0.78rem;color:#525252;line-height:1.5;">
                    <div style="font-weight:600;color:#171717;margin-bottom:2px;">
                        @if($order->status === 'processing')
                            Atelier Crafting & Packaging in Progress
                        @elseif($order->status === 'pending')
                            Order Queued for Fulfillment
                        @else
                            Fulfillment in Progress
                        @endif
                    </div>
                    <div>
                        Your bespoke garments are being prepared at our atelier. As soon as your package is dispatched with Australia Post, a live tracking link will be sent to <strong>{{ $order->customer_email }}</strong> and updated right here.
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Ordered Items Summary --}}
    @if($order->items && $order->items->count() > 0)
        <div class="success-card">
            <div class="success-card-header">
                <span class="success-card-title">Ordered Pieces ({{ $order->items->sum('quantity') }})</span>
                <span style="font-size:0.7rem;color:#737373;">Verified Capsule</span>
            </div>
            <div class="success-card-body">
                <div class="order-items-list">
                    @foreach($order->items as $item)
                        <div class="order-item-row">
                            <img src="{{ $item->image_url ?? asset('images/placeholder.jpg') }}" 
                                 alt="{{ $item->product_name }}" 
                                 class="order-item-thumb"
                                 onerror="this.onerror=null; this.src='{{ asset('images/placeholder.jpg') }}';">
                            <div class="order-item-details">
                                <div class="order-item-name">{{ $item->product_name }}</div>
                                <div class="order-item-meta">
                                    @if($item->color)
                                        <span style="font-weight:600;color:#171717;">{{ $item->color }}</span>
                                        <span>·</span>
                                    @endif
                                    @if($item->size)
                                        <span>Size: <strong>{{ $item->size }}</strong></span>
                                        <span>·</span>
                                    @endif
                                    <span>Qty: {{ $item->quantity }}</span>
                                </div>
                            </div>
                            <div class="order-item-price">
                                {{ $currency_symbol }}{{ number_format($item->total, 2) }}
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Totals Breakdown --}}
                <div class="order-totals-box">
                    <div class="order-info-row">
                        <span class="order-info-label">Subtotal</span>
                        <span class="order-info-value">{{ $currency_symbol }}{{ number_format($order->subtotal ?? $order->items->sum('total'), 2) }}</span>
                    </div>
                    @if((float)$order->shipping_cost > 0)
                        <div class="order-info-row">
                            <span class="order-info-label">Shipping</span>
                            <span class="order-info-value">{{ $currency_symbol }}{{ number_format($order->shipping_cost, 2) }}</span>
                        </div>
                    @else
                        <div class="order-info-row">
                            <span class="order-info-label">Shipping</span>
                            <span class="order-info-value" style="color:#15803d;font-weight:600;">Complimentary</span>
                        </div>
                    @endif
                    @if((float)$order->tax > 0)
                        <div class="order-info-row">
                            <span class="order-info-label">Taxes & Duties</span>
                            <span class="order-info-value">{{ $currency_symbol }}{{ number_format($order->tax, 2) }}</span>
                        </div>
                    @endif
                    <div class="order-total-highlight">
                        <span class="order-total-highlight-label">Grand Total</span>
                        <span class="order-total-highlight-value">{{ $currency_symbol }}{{ number_format($order->total, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Shipping & Delivery Details --}}
    @if(!empty($order->shipping_address))
        <div class="success-card">
            <div class="success-card-header">
                <span class="success-card-title">Delivery Destination</span>
                <span style="font-size:0.7rem;color:#737373;">Express Courier</span>
            </div>
            <div class="success-card-body" style="padding:13px 16px;font-size:0.82rem;color:#525252;line-height:1.5;">
                <div style="font-weight:600;color:#171717;margin-bottom:2px;">{{ $order->customer_name }}</div>
                <div>{{ $order->shipping_address['address'] ?? '' }}</div>
                <div>
                    {{ $order->shipping_address['city'] ?? '' }}
                    @if(!empty($order->shipping_address['state']))
                        , {{ $order->shipping_address['state'] }}
                    @endif
                    @if(!empty($order->shipping_address['country']))
                        , {{ $order->shipping_address['country'] }}
                    @endif
                </div>
                @if(!empty($order->shipping_address['phone']))
                    <div style="color:#737373;font-size:0.78rem;margin-top:4px;">Contact: {{ $order->shipping_address['phone'] }}</div>
                @endif
            </div>
        </div>
    @endif

    {{-- Account Saved / Client Profile Card --}}
    @auth('customer')
        <div class="client-account-card">
            <div class="client-card-inner">
                <div class="client-card-top">
                    <div class="client-profile-group">
                        <div class="client-avatar-monogram">
                            {{ strtoupper(substr(auth('customer')->user()->first_name ?: ($order->customer_name ?: 'C'), 0, 1)) }}
                        </div>
                        <div class="client-meta-info">
                            <div class="client-meta-tag">
                                <span class="client-pulse-dot"></span>
                                <span>Verified Client Profile</span>
                            </div>
                            <h4 class="client-welcome-heading">
                                Welcome, {{ auth('customer')->user()->first_name }}
                            </h4>
                            <div class="client-email-badge">
                                Connected as <strong>{{ auth('customer')->user()->email }}</strong>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('account.orders') }}" class="client-history-link-btn" title="View order history in client account">
                        <span>Archive</span>
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>
                        </svg>
                    </a>
                </div>
                <div class="client-perks-row">
                    <div class="client-perk-item">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                        <span>Express Checkout Saved</span>
                    </div>
                    <div class="client-perk-item">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                        <span>Capsule Archived</span>
                    </div>
                    <div class="client-perk-item">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                        <span>Live Tracking Active</span>
                    </div>
                </div>
            </div>
        </div>
    @endauth

    {{-- Luxury Action Buttons --}}
    <div class="confirmation-actions">
        <div class="confirmation-actions-row">
            <a href="{{ route('shop.index') }}" class="btn-confirm-primary">
                <span>Continue Shopping</span>
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                    <polyline points="12 5 19 12 12 19"></polyline>
                </svg>
            </a>
            @auth('customer')
                <a href="{{ route('account.orders') }}" class="btn-confirm-secondary">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <path d="M16 10a4 4 0 0 1-8 0"></path>
                    </svg>
                    <span>View My Orders</span>
                </a>
            @endauth
        </div>
        <div class="confirmation-actions-utility">
            <button type="button" onclick="window.print()" class="btn-confirm-print" title="Print order confirmation receipt">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 6 2 18 2 18 9"></polyline>
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                    <rect x="6" y="14" width="12" height="8"></rect>
                </svg>
                <span>Print Official Receipt</span>
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
function copyOrderRefNumber(orderNum, btn) {
    if (!orderNum) return;
    const onSuccess = function() {
        if (!btn) return;
        const orig = btn.innerHTML;
        btn.innerHTML = '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#15803d" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>';
        setTimeout(() => { btn.innerHTML = orig; }, 2000);
        if (typeof showToast === 'function') {
            showToast('Order reference copied to clipboard ✓');
        }
    };

    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(orderNum).then(onSuccess).catch(function() {
            fallbackCopy(orderNum, onSuccess);
        });
    } else {
        fallbackCopy(orderNum, onSuccess);
    }
}

function fallbackCopy(text, callback) {
    const el = document.createElement('textarea');
    el.value = text;
    el.setAttribute('readonly', '');
    el.style.position = 'absolute';
    el.style.left = '-9999px';
    document.body.appendChild(el);
    el.select();
    try {
        document.execCommand('copy');
        callback();
    } catch (err) {}
    document.body.removeChild(el);
}
</script>
@endpush
@endsection
