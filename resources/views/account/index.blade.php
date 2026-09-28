@extends('layouts.app')

@section('title', 'My Account — Pistis')

@section('content')
<div class="container" style="padding-top:40px;">
    <h1 style="margin-bottom:32px;">My Account</h1>

    <div class="two-col">
        <div>
            <div class="card mb-4">
                <div class="card-header"><h3 style="font-size:1.1rem;">Profile Information</h3></div>
                <div class="card-body">
                    <form action="{{ route('account.update-profile') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">First Name</label>
                                <input type="text" name="first_name" class="form-control" value="{{ $customer->first_name }}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Last Name</label>
                                <input type="text" name="last_name" class="form-control" value="{{ $customer->last_name }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" value="{{ $customer->email }}" disabled>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control" value="{{ $customer->phone }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Address</label>
                            <input type="text" name="address" class="form-control" value="{{ $customer->address }}">
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">City</label>
                                <input type="text" name="city" class="form-control" value="{{ $customer->city }}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">State</label>
                                <input type="text" name="state" class="form-control" value="{{ $customer->state }}">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Country</label>
                                <input type="text" name="country" class="form-control" value="{{ $customer->country }}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Postal Code</label>
                                <input type="text" name="postal_code" class="form-control" value="{{ $customer->postal_code }}">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Profile</button>
                    </form>
                </div>
            </div>
        </div>

        <div>
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-between align-center">
                        <h3 style="font-size:1.1rem;">Recent Orders</h3>
                        <a href="{{ route('account.orders') }}" class="btn btn-sm btn-secondary">View All</a>
                    </div>
                </div>
                <div class="card-body" style="padding:0;">
                    @if($recentOrders->count() > 0)
                        @foreach($recentOrders as $order)
                            <a href="{{ route('account.order-detail', $order->id) }}" class="d-flex justify-between align-center" style="padding:12px 20px;border-bottom:1px solid var(--border-color);text-decoration:none;">
                                <div>
                                    <div style="font-weight:600;color:var(--text-primary);font-size:0.875rem;">{{ $order->order_number }}</div>
                                    <div class="text-muted" style="font-size:0.75rem;">{{ $order->created_at->format('M d, Y') }}</div>
                                </div>
                                <div class="text-right">
                                    <span class="badge badge-{{ $order->status_badge }}">{{ ucfirst($order->status) }}</span>
                                    <div style="font-weight:600;color:var(--text-primary);font-size:0.875rem;margin-top:4px;">{{ $currency_symbol }}{{ number_format($order->total, 2) }}</div>
                                </div>
                            </a>
                        @endforeach
                    @else
                        <div class="empty-state" style="padding:30px;">
                            <p class="text-muted">No orders yet</p>
                            <a href="{{ route('shop.index') }}" class="btn btn-primary btn-sm">Start Shopping</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection



