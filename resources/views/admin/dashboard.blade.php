@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="admin-header">
    <div style="display:flex;align-items:center;gap:12px;">
        <h1 style="margin:0;">Dashboard</h1>
        @if(($stats['pending_orders'] ?? 0) > 0)
            <span class="badge badge-warning" style="font-size:0.75rem;padding:4px 10px;border-radius:12px;font-weight:600;letter-spacing:0.04em;">
                ● {{ $stats['pending_orders'] }} ACTION REQUIRED
            </span>
        @endif
    </div>
    <span class="text-muted">Welcome back, {{ auth()->user()->name }}</span>
</div>

{{-- New Confirmed Orders Notification Banner --}}
@if(($stats['pending_orders'] ?? 0) > 0)
    <div style="background:#ffffff;border:1px solid #e5e5e5;border-left:4px solid #171717;border-radius:6px;padding:16px 20px;margin-bottom:24px;box-shadow:0 2px 8px rgba(0,0,0,0.03);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:14px;">
        <div style="display:flex;align-items:center;gap:14px;">
            <div style="width:42px;height:42px;border-radius:8px;background:#f5f5f5;display:flex;align-items:center;justify-content:center;font-size:1.3rem;flex-shrink:0;">
                🛍️
            </div>
            <div>
                <div style="font-weight:700;font-size:0.95rem;color:#171717;display:flex;align-items:center;gap:8px;">
                    <span>{{ $stats['pending_orders'] }} Order{{ $stats['pending_orders'] > 1 ? 's' : '' }} Requiring Fulfillment</span>
                    <span class="badge badge-warning" style="font-size:0.65rem;padding:2px 8px;border-radius:10px;">New / Processing</span>
                </div>
                <div style="font-size:0.8rem;color:#737373;margin-top:2px;">
                    Customer orders have been confirmed and are awaiting preparation and dispatch.
                </div>
            </div>
        </div>
        <div>
            <a href="{{ route('admin.orders.index', ['view' => 'active']) }}" class="btn btn-sm btn-primary" style="font-size:0.78rem;padding:7px 16px;letter-spacing:0.04em;text-transform:uppercase;">
                Review Orders →
            </a>
        </div>
    </div>
@endif

@push('styles')
<style>
.stats-grid-3 {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin-bottom: 32px;
}
@media (max-width: 1024px) {
    .stats-grid-3 {
        grid-template-columns: repeat(2, 1fr);
    }
}
@media (max-width: 640px) {
    .stats-grid-3 {
        grid-template-columns: 1fr;
    }
}
.stat-card-link {
    text-decoration: none;
    color: inherit;
    display: block;
    transition: transform 0.15s ease, box-shadow 0.15s ease, border-color 0.15s ease;
}
.stat-card-link:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
    border-color: #cbd5e1;
}
.stat-card .stat-label {
    padding-right: 52px;
}
.stat-card .stat-label .badge {
    white-space: nowrap;
    flex-shrink: 0;
}
.stat-card .stat-icon {
    pointer-events: none;
}
</style>
@endpush

{{-- Primary Stats Grid --}}
<div class="stats-grid">
    {{-- Combined Products: Total & Active --}}
    <a href="{{ route('admin.products.index') }}" class="stat-card stat-card-link">
        <div class="stat-label">Products</div>
        <div class="d-flex align-center gap-2" style="margin-bottom:2px;">
            <div class="stat-value">{{ number_format($stats['total_products']) }}</div>
            <span class="badge badge-success" style="font-size:0.7rem;padding:3px 9px;border-radius:12px;font-weight:600;letter-spacing:0.03em;">
                {{ $stats['active_products'] }} Active
            </span>
        </div>
        <div class="stat-icon">📦</div>
        <div style="font-size:0.75rem;color:var(--text-muted);margin-top:6px;">
            <span style="color:var(--success);font-weight:600;">● {{ $stats['active_products'] }} active</span> of {{ $stats['total_products'] }} total in catalog
        </div>
    </a>

    {{-- Total Orders --}}
    <a href="{{ route('admin.orders.index') }}" class="stat-card stat-card-link">
        <div class="stat-label">Total Orders</div>
        <div class="stat-value">{{ number_format($stats['total_orders']) }}</div>
        <div class="stat-icon">🛍️</div>
        <div style="font-size:0.75rem;color:var(--text-muted);margin-top:6px;">
            All client bespoke orders
        </div>
    </a>

    {{-- Revenue --}}
    <div class="stat-card">
        <div class="stat-label">Revenue</div>
        <div class="stat-value">{{ $currency_symbol }}{{ number_format($stats['total_revenue']) }}</div>
        <div class="stat-icon">💰</div>
        <div style="font-size:0.75rem;color:var(--text-muted);margin-top:6px;">
            Total paid transactions
        </div>
    </div>

    {{-- Customers --}}
    <a href="{{ route('admin.customers.index') }}" class="stat-card stat-card-link">
        <div class="stat-label">Customers</div>
        <div class="stat-value">{{ number_format($stats['total_customers']) }}</div>
        <div class="stat-icon">👥</div>
        <div style="font-size:0.75rem;color:var(--text-muted);margin-top:6px;">
            Registered atelier accounts
        </div>
    </a>
</div>

{{-- Operational & Fulfillment Stats Grid --}}
<div class="stats-grid-3">
    {{-- Pending / Processing --}}
    <a href="{{ route('admin.orders.index', ['view' => 'active']) }}" class="stat-card stat-card-link">
        <div class="stat-label">Pending / Processing</div>
        <div class="d-flex align-center gap-2" style="margin-bottom:2px;">
            <div class="stat-value" style="font-size:1.85rem;color:{{ ($stats['pending_orders'] ?? 0) > 0 ? 'var(--warning)' : 'var(--text-primary)' }};">
                {{ $stats['pending_orders'] }}
            </div>
            @if(($stats['pending_orders'] ?? 0) > 0)
                <span class="badge badge-warning" style="font-size:0.7rem;padding:3px 9px;border-radius:12px;font-weight:600;letter-spacing:0.03em;">Action Needed</span>
            @endif
        </div>
        <div class="stat-icon">⏳</div>
        <div style="font-size:0.75rem;color:var(--text-muted);margin-top:6px;">
            Awaiting atelier dispatch
        </div>
    </a>

    {{-- Delivered Orders (New) --}}
    <a href="{{ route('admin.orders.index', ['status' => 'delivered']) }}" class="stat-card stat-card-link">
        <div class="stat-label">Delivered Orders</div>
        <div class="d-flex align-center gap-2" style="margin-bottom:2px;">
            <div class="stat-value" style="font-size:1.85rem;color:var(--success);">
                {{ number_format($stats['delivered_orders'] ?? 0) }}
            </div>
            <span class="badge badge-success" style="font-size:0.7rem;padding:3px 9px;border-radius:12px;font-weight:600;letter-spacing:0.03em;">Fulfilled</span>
        </div>
        <div class="stat-icon">🚚</div>
        <div style="font-size:0.75rem;color:var(--text-muted);margin-top:6px;">
            Completed & received by client
        </div>
    </a>

    {{-- Combined Shopify Sync (Synced & Failed 7d) --}}
    <a href="{{ route('admin.shopify.logs') }}" class="stat-card stat-card-link">
        <div class="stat-label d-flex justify-between align-center">
            <span>Shopify Sync</span>
            @if(($stats['failed_syncs'] ?? 0) > 0)
                <span class="badge badge-danger" style="font-size:0.65rem;padding:2px 7px;border-radius:10px;font-weight:600;">
                    {{ $stats['failed_syncs'] }} Failed (7d)
                </span>
            @else
                <span class="badge badge-success" style="font-size:0.65rem;padding:2px 7px;border-radius:10px;font-weight:600;">
                    Healthy
                </span>
            @endif
        </div>
        <div class="d-flex align-center gap-4" style="margin-top:4px;">
            <div>
                <div class="stat-value" style="font-size:1.85rem;color:var(--success);line-height:1.1;">
                    {{ $stats['synced_products'] }}
                </div>
                <div style="font-size:0.72rem;color:var(--text-muted);font-weight:600;text-transform:uppercase;letter-spacing:0.04em;margin-top:3px;">
                    Synced
                </div>
            </div>
            <div style="width:1px;height:34px;background:var(--border-color);"></div>
            <div>
                <div class="stat-value" style="font-size:1.85rem;color:{{ ($stats['failed_syncs'] ?? 0) > 0 ? 'var(--danger)' : 'var(--text-muted)' }};line-height:1.1;">
                    {{ $stats['failed_syncs'] }}
                </div>
                <div style="font-size:0.72rem;color:var(--text-muted);font-weight:600;text-transform:uppercase;letter-spacing:0.04em;margin-top:3px;">
                    Failed (7d)
                </div>
            </div>
        </div>
        <div class="stat-icon" style="opacity:0.85;">🔄</div>
    </a>
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



