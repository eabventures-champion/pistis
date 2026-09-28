@extends('layouts.app')

@section('title', 'My Orders — Pistis')

@section('content')
<div class="container" style="padding-top:40px;">
    <h1 style="margin-bottom:32px;">My Orders</h1>

    @if($orders->count() > 0)
        <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Total</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td style="font-weight:600;color:var(--text-primary);">{{ $order->order_number }}</td>
                            <td>{{ $order->created_at->format('M d, Y') }}</td>
                            <td><span class="badge badge-{{ $order->status_badge }}">{{ ucfirst($order->status) }}</span></td>
                            <td><span class="badge badge-{{ $order->payment_status_badge }}">{{ ucfirst($order->payment_status) }}</span></td>
                            <td style="font-weight:600;color:var(--text-primary);">{{ $currency_symbol }}{{ number_format($order->total, 2) }}</td>
                            <td><a href="{{ route('account.order-detail', $order->id) }}" class="btn btn-sm btn-secondary">View</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $orders->links() }}
    @else
        <div class="empty-state">
            <div class="empty-icon">📋</div>
            <h3>No orders yet</h3>
            <p>Your order history will appear here</p>
            <a href="{{ route('shop.index') }}" class="btn btn-primary">Start Shopping</a>
        </div>
    @endif
</div>
@endsection



