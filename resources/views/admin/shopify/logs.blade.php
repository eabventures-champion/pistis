@extends('layouts.admin')

@section('title', 'Shopify Sync & Logs')

@section('content')
<div class="admin-header">
    <h1>Shopify Sync & Logs</h1>
</div>

{{-- Actions --}}
<div class="d-flex gap-3 mb-4 flex-wrap">
    <form action="{{ route('admin.shopify.test-connection') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-secondary">🔌 Test Connection</button>
    </form>
    <form action="{{ route('admin.shopify.sync-all') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-primary" onclick="return confirm('Sync all products to Shopify?')">🔄 Sync All Products</button>
    </form>
    <form action="{{ route('admin.shopify.pull') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-secondary" onclick="return confirm('Pull all products from Shopify?')">📥 Pull from Shopify</button>
    </form>
</div>

{{-- Filters --}}
<div class="d-flex gap-3 mb-4">
    <form action="{{ route('admin.shopify.logs') }}" method="GET" class="d-flex gap-2">
        <select name="status" class="form-control" style="max-width:150px;" onchange="this.form.submit()">
            <option value="">All Status</option>
            <option value="success" {{ request('status') === 'success' ? 'selected' : '' }}>Success</option>
            <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
        </select>
        <select name="action" class="form-control" style="max-width:180px;" onchange="this.form.submit()">
            <option value="">All Actions</option>
            <option value="push" {{ request('action') === 'push' ? 'selected' : '' }}>Push</option>
            <option value="pull" {{ request('action') === 'pull' ? 'selected' : '' }}>Pull</option>
            <option value="inventory_update" {{ request('action') === 'inventory_update' ? 'selected' : '' }}>Inventory</option>
            <option value="webhook" {{ request('action') === 'webhook' ? 'selected' : '' }}>Webhook</option>
        </select>
    </form>
</div>

{{-- Logs Table --}}
<div class="table-wrapper">
    <table class="table">
        <thead>
            <tr>
                <th>Time</th>
                <th>Product</th>
                <th>Action</th>
                <th>Status</th>
                <th>Error</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $log)
                <tr>
                    <td style="white-space:nowrap;">{{ $log->created_at->format('M d, H:i:s') }}</td>
                    <td style="font-weight:500;color:var(--text-primary);">{{ $log->product?->name ?? 'N/A' }}</td>
                    <td>
                        <span class="badge badge-{{ $log->action === 'push' ? 'primary' : ($log->action === 'pull' ? 'info' : 'warning') }}">
                            {{ $log->action }}
                        </span>
                    </td>
                    <td>
                        <span class="badge badge-{{ $log->status === 'success' ? 'success' : ($log->status === 'failed' ? 'danger' : 'warning') }}">
                            {{ $log->status }}
                        </span>
                    </td>
                    <td style="max-width:300px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                        {{ $log->error_message ?? '—' }}
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center" style="padding:40px;"><div class="text-muted">No sync logs yet</div></td></tr>
            @endforelse
        </tbody>
    </table>
</div>

{{ $logs->links() }}
@endsection



