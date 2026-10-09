@extends('layouts.app')

@section('title', 'Order ' . $order->order_number . ' — Pistis')

@section('content')
<div class="container" style="padding-top:40px;">
    <div class="d-flex align-center gap-3 mb-4">
        <a href="{{ route('account.orders') }}" class="btn btn-secondary btn-sm">← Back</a>
        <h1>Order {{ $order->order_number }}</h1>
    </div>

    <div class="two-col">
        <div>
            <div class="card mb-4">
                <div class="card-header"><h3 style="font-size:1.1rem;">Order Items</h3></div>
                <div class="card-body" style="padding:0;">
                    @foreach($order->items as $item)
                        <div class="d-flex align-center gap-3" style="padding:14px 20px;border-bottom:1px solid var(--border-color);">
                            <div style="width:54px;height:68px;border-radius:4px;overflow:hidden;background:#f5f5f5;border:1px solid #e5e5e5;flex-shrink:0;display:flex;align-items:center;justify-content:center;">
                                @if($item->image_url)
                                    <img src="{{ $item->image_url }}" alt="{{ $item->product_name }}" style="width:100%;height:100%;object-fit:cover;display:block;">
                                @else
                                    <span style="font-size:1.4rem;color:#a3a3a3;">📦</span>
                                @endif
                            </div>
                            <div class="flex-1">
                                <div style="font-weight:600;color:var(--text-primary);">
                                    @if($item->product)
                                        <a href="{{ route('shop.show', $item->product->slug) }}" style="color:inherit;text-decoration:none;">
                                            {{ $item->product_name }}
                                        </a>
                                    @else
                                        {{ $item->product_name }}
                                    @endif
                                </div>
                                <div class="text-muted" style="font-size:0.8rem;margin-top:3px;">
                                    SKU: {{ $item->product_sku }} • Qty: {{ $item->quantity }}
                                    @if($item->color)
                                        • Color: <span style="color:#000000;font-weight:600;text-transform:uppercase;">{{ $item->color }}</span>
                                    @endif
                                    @if($item->size)
                                        • Size: <span style="color:#000000;font-weight:600;text-transform:uppercase;">{{ $item->size }}</span>
                                    @endif
                                </div>
                            </div>
                            <div style="font-weight:600;color:var(--text-primary);">{{ $currency_symbol }}{{ number_format($item->total, 2) }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div>
            {{-- Shipment & Tracking Banner --}}
            <div class="card mb-4" style="background:#fafafa;border:1px solid #e5e5e5;overflow:hidden;">
                <div class="card-header" style="background:#000000;color:#ffffff;padding:14px 20px;display:flex;justify-content:space-between;align-items:center;">
                    <div>
                        <div style="font-size:0.68rem;letter-spacing:0.18em;text-transform:uppercase;color:#a3a3a3;font-weight:700;">Courier Tracking</div>
                        <h3 style="font-size:1.05rem;margin:2px 0 0;color:#ffffff;">{{ $order->tracking_carrier ?? 'Australia Post' }}</h3>
                    </div>
                    <span class="badge badge-{{ $order->status_badge }}" style="font-size:0.75rem;padding:4px 10px;text-transform:uppercase;">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
                <div class="card-body" style="padding:20px;">
                    {{-- Progress Timeline --}}
                    @php
                        $steps = ['pending' => 'Order Placed', 'processing' => 'Atelier Preparing', 'shipped' => 'Dispatched', 'delivered' => 'Delivered'];
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
                        <div style="display:flex;align-items:center;justify-content:space-between;position:relative;margin-bottom:24px;padding:0 8px;">
                            {{-- Connecting line --}}
                            <div style="position:absolute;top:14px;left:30px;right:30px;height:2px;background:#e5e5e5;z-index:1;"></div>
                            <div style="position:absolute;top:14px;left:30px;width:{{ min(100, max(0, ($statusIndex / 3) * 100)) }}%;height:2px;background:#000000;z-index:2;transition:width 0.4s ease;"></div>

                            @foreach(['Placed', 'Preparing', 'Dispatched', 'Delivered'] as $i => $stepLabel)
                                <div style="position:relative;z-index:3;text-align:center;">
                                    <div style="width:28px;height:28px;border-radius:50%;margin:0 auto 6px;display:flex;align-items:center;justify-content:center;font-size:0.75rem;font-weight:700;
                                        background: {{ $i <= $statusIndex ? '#000000' : '#ffffff' }};
                                        color: {{ $i <= $statusIndex ? '#ffffff' : '#a3a3a3' }};
                                        border: 2px solid {{ $i <= $statusIndex ? '#000000' : '#d4d4d4' }};">
                                        @if($i < $statusIndex)
                                            ✓
                                        @else
                                            {{ $i + 1 }}
                                        @endif
                                    </div>
                                    <div style="font-size:0.72rem;font-weight:{{ $i === $statusIndex ? '700' : '500' }};color:{{ $i === $statusIndex ? '#000000' : '#737373' }};white-space:nowrap;">
                                        {{ $stepLabel }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div style="background:#fee2e2;color:#991b1b;padding:10px 14px;border-radius:4px;font-size:0.85rem;margin-bottom:16px;">
                            This order has been cancelled.
                        </div>
                    @endif

                    @if($order->tracking_number)
                        <div style="background:#ffffff;border:1px solid #e5e5e5;border-radius:6px;padding:16px;text-align:center;">
                            <div style="font-size:0.7rem;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;color:#737373;margin-bottom:4px;">
                                Consignment Number
                            </div>
                            <div style="font-family:monospace;font-size:1.15rem;font-weight:700;letter-spacing:0.08em;color:#171717;margin-bottom:12px;">
                                {{ $order->tracking_number }}
                            </div>
                            @if($order->shipped_at)
                                <div style="font-size:0.75rem;color:#737373;margin-bottom:14px;">
                                    Dispatched on {{ $order->shipped_at->format('d M Y, h:i A') }}
                                </div>
                            @endif
                            @if($order->tracking_url)
                                <a href="{{ $order->tracking_url }}" target="_blank" class="btn btn-primary" style="background:#dc2626;border-color:#dc2626;color:#ffffff;text-decoration:none;font-size:0.8rem;padding:9px 18px;display:inline-flex;align-items:center;gap:6px;font-weight:600;letter-spacing:0.04em;">
                                    Track on Australia Post &rarr;
                                </a>
                            @endif
                        </div>
                    @else
                        <div style="background:#ffffff;border:1px solid #e5e5e5;border-radius:6px;padding:14px;font-size:0.82rem;color:#525252;line-height:1.5;">
                            @if($order->status === 'processing')
                                <strong>Status: Atelier Crafting & Inspection</strong><br>
                                Your order is currently being prepared by our team. Once collected by <strong>Australia Post</strong>, your live tracking details and courier consignment link will automatically appear here.
                            @elseif($order->status === 'pending')
                                <strong>Status: Awaiting Atelier Processing</strong><br>
                                Your order has been placed. Tracking details will update once fulfillment begins.
                            @else
                                Tracking information will be updated once your consignment is scanned by the courier.
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-between mb-2">
                        <span class="text-muted">Status</span>
                        <span class="badge badge-{{ $order->status_badge }}">{{ ucfirst($order->status) }}</span>
                    </div>
                    <div class="d-flex justify-between mb-2">
                        <span class="text-muted">Payment</span>
                        <span class="badge badge-{{ $order->payment_status_badge }}">{{ ucfirst($order->payment_status) }}</span>
                    </div>
                    <div class="d-flex justify-between mb-2">
                        <span class="text-muted">Method</span>
                        <span>{{ ucfirst($order->payment_method ?? 'N/A') }}</span>
                    </div>
                    <hr style="border-color:var(--border-color);margin:12px 0;">
                    <div class="d-flex justify-between mb-1">
                        <span class="text-muted">Subtotal</span>
                        <span>{{ $currency_symbol }}{{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    <div class="d-flex justify-between mb-1">
                        <span class="text-muted">Shipping</span>
                        <span>{{ $currency_symbol }}{{ number_format($order->shipping_cost, 2) }}</span>
                    </div>
                    <div class="d-flex justify-between" style="font-weight:700;font-size:1.1rem;color:var(--text-primary);margin-top:8px;">
                        <span>Total</span>
                        <span>{{ $currency_symbol }}{{ number_format($order->total, 2) }}</span>
                    </div>
                </div>
            </div>

            @if($order->shipping_address)
                <div class="card">
                    <div class="card-header"><h3 style="font-size:1rem;">Shipping Address</h3></div>
                    <div class="card-body text-muted" style="font-size:0.875rem;">
                        {{ $order->shipping_address['address'] ?? '' }}<br>
                        {{ $order->shipping_address['city'] ?? '' }}, {{ $order->shipping_address['state'] ?? '' }}<br>
                        {{ $order->shipping_address['country'] ?? '' }}<br>
                        {{ $order->shipping_address['phone'] ?? '' }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection



