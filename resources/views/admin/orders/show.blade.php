@extends('layouts.admin')

@section('title', 'Order ' . $order->order_number)

@section('content')
<div class="admin-header" style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;">
    <div class="d-flex align-center gap-3">
        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary btn-sm">←</a>
        <div style="display:flex;align-items:center;gap:10px;">
            <h1 style="margin:0;">Order {{ $order->order_number }}</h1>
            @if($order->is_archived)
                <span class="badge" style="background:#e4e4e7;color:#52525b;font-size:0.7rem;letter-spacing:0.05em;padding:3px 8px;">ARCHIVED</span>
            @endif
        </div>
    </div>
    <div style="display:flex;align-items:center;gap:10px;">
        @if(!$order->is_archived)
            <form action="{{ route('admin.orders.archive', $order) }}" method="POST" style="margin:0;">
                @csrf
                <button type="submit" class="btn btn-secondary btn-sm" title="Move order to archive">
                    📦 Archive
                </button>
            </form>
        @else
            <form action="{{ route('admin.orders.unarchive', $order) }}" method="POST" style="margin:0;">
                @csrf
                <button type="submit" class="btn btn-secondary btn-sm" title="Restore order from archive">
                    ↺ Restore
                </button>
            </form>
        @endif

        <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" style="margin:0;" onsubmit="return confirm('Are you sure you want to permanently delete order {{ $order->order_number }}? This action cannot be undone.');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm" style="background:#fee2e2;color:#dc2626;border:1px solid #fecaca;padding:6px 14px;cursor:pointer;font-weight:600;">
                🗑 Delete
            </button>
        </form>
    </div>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:24px;">
    <div>
        {{-- Order Items --}}
        <div class="card mb-4">
            <div class="card-header"><h3 style="font-size:1rem;">Order Items</h3></div>
            <div class="card-body" style="padding:0;">
                @foreach($order->items as $item)
                    <div class="d-flex align-center gap-3" style="padding:12px 20px;border-bottom:1px solid var(--border-color);">
                        <div class="flex-1">
                            <div style="font-weight:600;color:var(--text-primary);">{{ $item->product_name }}</div>
                            <div class="text-muted" style="font-size:0.8rem;">SKU: {{ $item->product_sku }} • Qty: {{ $item->quantity }} × {{ $currency_symbol }}{{ number_format($item->price, 2) }}</div>
                        </div>
                        <div style="font-weight:600;color:var(--text-primary);">{{ $currency_symbol }}{{ number_format($item->total, 2) }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Customer Info --}}
        <div class="card">
            <div class="card-header"><h3 style="font-size:1rem;">Customer</h3></div>
            <div class="card-body">
                <div class="d-flex justify-between mb-2"><span class="text-muted">Name</span><span>{{ $order->customer_name }}</span></div>
                <div class="d-flex justify-between mb-2"><span class="text-muted">Email</span><span>{{ $order->customer_email }}</span></div>
                @if($order->shipping_address)
                    <div class="d-flex justify-between mb-2"><span class="text-muted">Phone</span><span>{{ $order->shipping_address['phone'] ?? 'N/A' }}</span></div>
                    <hr style="border-color:var(--border-color);margin:12px 0;">
                    <div class="text-muted" style="font-size:0.875rem;">
                        <strong>Shipping Address:</strong><br>
                        {{ $order->shipping_address['address'] ?? '' }}<br>
                        {{ $order->shipping_address['city'] ?? '' }}, {{ $order->shipping_address['state'] ?? '' }}<br>
                        {{ $order->shipping_address['country'] ?? '' }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div>
        {{-- Order Summary --}}
        <div class="card mb-4">
            <div class="card-header"><h3 style="font-size:1rem;">Summary</h3></div>
            <div class="card-body">
                <div class="d-flex justify-between mb-2"><span class="text-muted">Subtotal</span><span>{{ $currency_symbol }}{{ number_format($order->subtotal, 2) }}</span></div>
                <div class="d-flex justify-between mb-2"><span class="text-muted">Shipping</span><span>{{ $currency_symbol }}{{ number_format($order->shipping_cost, 2) }}</span></div>
                <div class="d-flex justify-between mb-2"><span class="text-muted">Tax</span><span>{{ $currency_symbol }}{{ number_format($order->tax, 2) }}</span></div>
                <hr style="border-color:var(--border-color);margin:12px 0;">
                <div class="d-flex justify-between" style="font-weight:700;font-size:1.2rem;color:var(--text-primary);">
                    <span>Total</span><span>{{ $currency_symbol }}{{ number_format($order->total, 2) }}</span>
                </div>
            </div>
        </div>

        {{-- Status Update --}}
        <div class="card mb-4">
            <div class="card-header"><h3 style="font-size:1rem;">Update Status</h3></div>
            <div class="card-body">
                <div class="d-flex justify-between mb-3">
                    <span class="text-muted">Payment</span>
                    <span class="badge badge-{{ $order->payment_status_badge }}">{{ ucfirst($order->payment_status) }}</span>
                </div>
                <div class="d-flex justify-between mb-3">
                    <span class="text-muted">Method</span>
                    <span>{{ ucfirst($order->payment_method ?? 'N/A') }}</span>
                </div>
                <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="form-group">
                        <label class="form-label">Order Status</label>
                        <select name="status" class="form-control">
                            @foreach(['pending', 'processing', 'shipped', 'delivered', 'cancelled'] as $s)
                                <option value="{{ $s }}" {{ $order->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Update Status</button>
                </form>
            </div>
        </div>

        @if($order->notes)
            <div class="card">
                <div class="card-header"><h3 style="font-size:1rem;">Notes</h3></div>
                <div class="card-body text-muted" style="font-size:0.875rem;">{{ $order->notes }}</div>
            </div>
        @endif
    </div>
</div>
@endsection



