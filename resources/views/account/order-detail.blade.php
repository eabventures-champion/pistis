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
                        <div class="d-flex align-center gap-3" style="padding:12px 20px;border-bottom:1px solid var(--border-color);">
                            <div class="flex-1">
                                <div style="font-weight:600;color:var(--text-primary);">{{ $item->product_name }}</div>
                                <div class="text-muted" style="font-size:0.8rem;">
                                    SKU: {{ $item->product_sku }} • Qty: {{ $item->quantity }}
                                    @if($item->color)
                                        • Color: <span style="color:#000000;font-weight:600;text-transform:uppercase;">{{ $item->color }}</span>
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



