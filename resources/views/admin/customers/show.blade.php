@extends('layouts.admin')

@section('title', $customer->full_name)

@section('content')
<div class="admin-header">
    <div class="d-flex align-center gap-3">
        <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary btn-sm">←</a>
        <h1>{{ $customer->full_name }}</h1>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 2fr;gap:24px;">
    <div class="card">
        <div class="card-header"><h3 style="font-size:1rem;">Customer Details</h3></div>
        <div class="card-body">
            <div class="mb-2"><span class="text-muted">Email:</span> {{ $customer->email }}</div>
            <div class="mb-2"><span class="text-muted">Phone:</span> {{ $customer->phone ?? 'N/A' }}</div>
            <div class="mb-2"><span class="text-muted">Address:</span> {{ $customer->address ?? 'N/A' }}</div>
            <div class="mb-2"><span class="text-muted">City:</span> {{ $customer->city ?? 'N/A' }}</div>
            <div class="mb-2"><span class="text-muted">State:</span> {{ $customer->state ?? 'N/A' }}</div>
            <div class="mb-2"><span class="text-muted">Country:</span> {{ $customer->country ?? 'N/A' }}</div>
            <div><span class="text-muted">Joined:</span> {{ $customer->created_at->format('M d, Y') }}</div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h3 style="font-size:1rem;">Order History ({{ $customer->orders->count() }})</h3></div>
        <div class="card-body" style="padding:0;">
            @forelse($customer->orders as $order)
                <a href="{{ route('admin.orders.show', $order) }}" class="d-flex justify-between align-center" style="padding:10px 20px;border-bottom:1px solid var(--border-color);text-decoration:none;">
                    <div>
                        <div style="font-weight:600;color:var(--text-primary);font-size:0.85rem;">{{ $order->order_number }}</div>
                        <div class="text-muted" style="font-size:0.75rem;">{{ $order->created_at->format('M d, Y') }}</div>
                    </div>
                    <div class="text-right">
                        <span class="badge badge-{{ $order->status_badge }}">{{ ucfirst($order->status) }}</span>
                        <div style="font-weight:600;color:var(--text-primary);font-size:0.85rem;margin-top:2px;">{{ $currency_symbol }}{{ number_format($order->total, 2) }}</div>
                    </div>
                </a>
            @empty
                <div class="empty-state" style="padding:24px;"><p class="text-muted">No orders yet</p></div>
            @endforelse
        </div>
    </div>
</div>
@endsection



