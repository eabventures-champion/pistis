@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="admin-header">
    <h1>Dashboard</h1>
    <span class="text-muted">Welcome back, {{ auth()->user()->name }}</span>
</div>

{{-- Stats Grid --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Total Products</div>
        <div class="stat-value">{{ number_format($stats['total_products']) }}</div>
        <div class="stat-icon">📦</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Total Orders</div>
        <div class="stat-value">{{ number_format($stats['total_orders']) }}</div>
        <div class="stat-icon">🛍️</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Revenue</div>
        <div class="stat-value">{{ $currency_symbol }}{{ number_format($stats['total_revenue']) }}</div>
        <div class="stat-icon">💰</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Customers</div>
        <div class="stat-value">{{ number_format($stats['total_customers']) }}</div>
        <div class="stat-icon">👥</div>
    </div>
</div>

{{-- Secondary Stats --}}
<div class="stats-grid" style="grid-template-columns:repeat(4,1fr);">
    <div class="stat-card">
        <div class="stat-label">Active Products</div>
        <div class="stat-value" style="font-size:1.5rem;">{{ $stats['active_products'] }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Pending Orders</div>
        <div class="stat-value" style="font-size:1.5rem;color:var(--warning);">{{ $stats['pending_orders'] }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Shopify Synced</div>
        <div class="stat-value" style="font-size:1.5rem;color:var(--success);">{{ $stats['synced_products'] }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Failed Syncs (7d)</div>
        <div class="stat-value" style="font-size:1.5rem;color:{{ $stats['failed_syncs'] > 0 ? 'var(--danger)' : 'var(--success)' }};">{{ $stats['failed_syncs'] }}</div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;">
    {{-- Recent Orders --}}
    <div class="card">
        <div class="card-header d-flex justify-between align-center">
            <h3 style="font-size:1rem;">Recent Orders</h3>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-secondary">View All</a>
        </div>
        <div class="card-body" style="padding:0;">
            @forelse($recentOrders as $order)
                <a href="{{ route('admin.orders.show', $order) }}" class="d-flex justify-between align-center" style="padding:10px 20px;border-bottom:1px solid var(--border-color);text-decoration:none;">
                    <div>
                        <div style="font-weight:600;color:var(--text-primary);font-size:0.85rem;">{{ $order->order_number }}</div>
                        <div class="text-muted" style="font-size:0.75rem;">{{ $order->customer_name }} • {{ $order->created_at->diffForHumans() }}</div>
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

    {{-- Recent Sync Logs --}}
    <div class="card">
        <div class="card-header d-flex justify-between align-center">
            <h3 style="font-size:1rem;">Shopify Sync Activity</h3>
            <a href="{{ route('admin.shopify.logs') }}" class="btn btn-sm btn-secondary">View All</a>
        </div>
        <div class="card-body" style="padding:0;">
            @forelse($recentSyncLogs as $log)
                <div class="d-flex justify-between align-center" style="padding:10px 20px;border-bottom:1px solid var(--border-color);">
                    <div>
                        <div style="font-weight:500;color:var(--text-primary);font-size:0.85rem;">{{ $log->product?->name ?? 'N/A' }}</div>
                        <div class="text-muted" style="font-size:0.75rem;">{{ $log->action }} • {{ $log->created_at->diffForHumans() }}</div>
                    </div>
                    <span class="badge badge-{{ $log->status === 'success' ? 'success' : ($log->status === 'failed' ? 'danger' : 'warning') }}">{{ $log->status }}</span>
                </div>
            @empty
                <div class="empty-state" style="padding:24px;"><p class="text-muted">No sync activity</p></div>
            @endforelse
        </div>
    </div>
</div>
@endsection



