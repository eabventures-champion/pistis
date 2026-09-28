@extends('layouts.admin')

@section('title', 'Customers')

@section('content')
<div class="admin-header d-flex justify-between align-center mb-4">
    <h1>Customers</h1>
    <span class="text-muted" style="font-size:0.9rem;">Showing {{ $customers->total() }} customers</span>
</div>

<form action="{{ route('admin.customers.index') }}" method="GET" class="d-flex gap-2 mb-4">
    <input type="text" name="search" class="form-control" placeholder="Search customers..." value="{{ request('search') }}" style="max-width:300px;">
    <button type="submit" class="btn btn-secondary btn-sm">Search</button>
</form>

<div class="table-wrapper">
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Orders</th>
                <th>Joined</th>
                <th style="width:100px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($customers as $customer)
                <tr>
                    <td style="font-weight:600;color:var(--text-primary);">{{ $customer->full_name }}</td>
                    <td>{{ $customer->email }}</td>
                    <td>{{ $customer->phone ?? '—' }}</td>
                    <td><span class="badge badge-info">{{ $customer->orders_count }}</span></td>
                    <td>{{ $customer->created_at->format('M d, Y') }}</td>
                    <td><a href="{{ route('admin.customers.show', $customer) }}" class="btn btn-sm btn-secondary">View</a></td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <div class="empty-state-icon">👥</div>
                            <div class="empty-state-text">No customers found</div>
                            <p class="text-muted" style="font-size:0.85rem;max-width:300px;margin:0 auto;">
                                When customers register on your store, they will appear here. You can manage their profiles and view their order history.
                            </p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
{{ $customers->links() }}
@endsection



