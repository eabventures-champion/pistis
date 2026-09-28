@extends('layouts.app')

@section('title', 'Order Confirmed — Pistis')

@section('content')
<div class="container" style="padding-top:60px;padding-bottom:60px;">
    <div style="max-width:600px;margin:0 auto;text-align:center;">
        <div style="font-size:4rem;margin-bottom:16px;">🎉</div>
        <h1 style="margin-bottom:8px;">Order Confirmed!</h1>
        <p class="text-muted" style="margin-bottom:32px;">Thank you for your purchase. Your order has been placed successfully.</p>

        <div class="card" style="text-align:left;margin-bottom:24px;">
            <div class="card-body">
                <div class="d-flex justify-between mb-2">
                    <span class="text-muted">Order Number</span>
                    <span style="font-weight:600;color:var(--text-primary);">{{ $order->order_number }}</span>
                </div>
                <div class="d-flex justify-between mb-2">
                    <span class="text-muted">Status</span>
                    <span class="badge badge-{{ $order->status_badge }}">{{ ucfirst($order->status) }}</span>
                </div>
                <div class="d-flex justify-between mb-2">
                    <span class="text-muted">Payment</span>
                    <span class="badge badge-{{ $order->payment_status_badge }}">{{ ucfirst($order->payment_status) }}</span>
                </div>
                <div class="d-flex justify-between">
                    <span class="text-muted">Total</span>
                    <span style="font-weight:700;font-size:1.2rem;color:var(--text-primary);">{{ $currency_symbol }}{{ number_format($order->total, 2) }}</span>
                </div>
            </div>
        </div>

        @auth('customer')
            <div style="background:#f9f9f9;border:1px solid #e5e5e5;padding:20px;text-align:left;margin-bottom:24px;">
                <div style="font-size:0.65rem;letter-spacing:0.15em;text-transform:uppercase;color:#737373;margin-bottom:4px;">ACCOUNT CREATED & SAVED</div>
                <h4 style="margin:0 0 6px;font-size:1rem;font-weight:600;color:#171717;">Welcome, {{ auth('customer')->user()->first_name }}!</h4>
                <p style="font-size:0.82rem;color:#525252;margin:0 0 10px;">Your purchase has been linked to your account (<strong>{{ auth('customer')->user()->email }}</strong>). Your shipping information has been saved for instant checkout next time.</p>
                <a href="{{ route('account.orders') }}" style="font-size:0.8rem;color:#171717;text-decoration:underline;font-weight:600;">View Order History →</a>
            </div>
        @endauth

        <div class="d-flex gap-3 justify-center">
            @auth('customer')
                <a href="{{ route('account.orders') }}" class="btn btn-secondary">View Orders</a>
            @endauth
            <a href="{{ route('shop.index') }}" class="btn btn-primary">Continue Shopping</a>
        </div>
    </div>
</div>
@endsection



