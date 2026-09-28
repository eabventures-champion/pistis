@extends('layouts.admin')

@section('title', 'Orders')

@section('content')
<style>
    .table-wrapper {
        overflow: visible !important;
    }
    .order-action-btn:hover {
        background: #f4f4f5 !important;
        color: #18181b !important;
        border-color: #d4d4d8 !important;
    }
    .dropdown-action-item:hover {
        background: #f4f4f5 !important;
        color: #000000 !important;
    }
    .dropdown-action-item-danger:hover {
        background: #fee2e2 !important;
        color: #b91c1c !important;
    }
</style>

<div class="admin-header" style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
    <h1>Orders</h1>
    <div class="text-muted" style="font-size:0.85rem;display:flex;align-items:center;gap:10px;">
        <span>Total: <strong style="color:var(--text-primary);">{{ $counts['all'] }}</strong></span>
        <span style="color:#d4d4d8;">•</span>
        <span>Active: <strong style="color:var(--text-primary);">{{ $counts['active'] }}</strong></span>
        <span style="color:#d4d4d8;">•</span>
        <span>Archived: <strong style="color:var(--text-primary);">{{ $counts['archived'] }}</strong></span>
    </div>
</div>

{{-- Orders Tabs: All / Active / Archived --}}
<div style="display:flex;align-items:center;gap:20px;border-bottom:1px solid var(--border-color);margin-bottom:20px;">
    <a href="{{ route('admin.orders.index', array_merge(request()->except(['view', 'page']), ['view' => 'all'])) }}"
       style="padding:10px 4px;font-size:0.85rem;font-weight:{{ $view === 'all' ? '600' : '400' }};color:{{ $view === 'all' ? 'var(--text-primary)' : 'var(--text-muted)' }};border-bottom:2px solid {{ $view === 'all' ? 'var(--text-primary)' : 'transparent' }};text-decoration:none;display:inline-flex;align-items:center;gap:8px;">
        All Orders
        <span style="background:{{ $view === 'all' ? 'var(--text-primary)' : '#f0f0f0' }};color:{{ $view === 'all' ? '#ffffff' : '#666666' }};padding:2px 8px;border-radius:10px;font-size:0.7rem;font-weight:600;">{{ $counts['all'] }}</span>
    </a>
    <a href="{{ route('admin.orders.index', array_merge(request()->except(['view', 'page']), ['view' => 'active'])) }}"
       style="padding:10px 4px;font-size:0.85rem;font-weight:{{ $view === 'active' ? '600' : '400' }};color:{{ $view === 'active' ? 'var(--text-primary)' : 'var(--text-muted)' }};border-bottom:2px solid {{ $view === 'active' ? 'var(--text-primary)' : 'transparent' }};text-decoration:none;display:inline-flex;align-items:center;gap:8px;">
        Active
        <span style="background:{{ $view === 'active' ? 'var(--text-primary)' : '#f0f0f0' }};color:{{ $view === 'active' ? '#ffffff' : '#666666' }};padding:2px 8px;border-radius:10px;font-size:0.7rem;font-weight:600;">{{ $counts['active'] }}</span>
    </a>
    <a href="{{ route('admin.orders.index', array_merge(request()->except(['view', 'page']), ['view' => 'archived'])) }}"
       style="padding:10px 4px;font-size:0.85rem;font-weight:{{ $view === 'archived' ? '600' : '400' }};color:{{ $view === 'archived' ? 'var(--text-primary)' : 'var(--text-muted)' }};border-bottom:2px solid {{ $view === 'archived' ? 'var(--text-primary)' : 'transparent' }};text-decoration:none;display:inline-flex;align-items:center;gap:8px;">
        Archived
        <span style="background:{{ $view === 'archived' ? 'var(--text-primary)' : '#f0f0f0' }};color:{{ $view === 'archived' ? '#ffffff' : '#666666' }};padding:2px 8px;border-radius:10px;font-size:0.7rem;font-weight:600;">{{ $counts['archived'] }}</span>
    </a>
</div>

{{-- Filters --}}
<div class="d-flex gap-3 mb-3">
    <form action="{{ route('admin.orders.index') }}" method="GET" class="d-flex gap-2 flex-1">
        <input type="hidden" name="view" value="{{ $view }}">
        <input type="text" name="search" class="form-control" placeholder="Search by order no, email, name..." value="{{ request('search') }}" style="max-width:300px;">
        <select name="status" class="form-control" style="max-width:150px;" onchange="this.form.submit()">
            <option value="">All Status</option>
            @foreach(['pending', 'processing', 'shipped', 'delivered', 'cancelled'] as $s)
                <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        <select name="payment_status" class="form-control" style="max-width:150px;" onchange="this.form.submit()">
            <option value="">All Payments</option>
            @foreach(['pending', 'paid', 'failed', 'refunded'] as $ps)
                <option value="{{ $ps }}" {{ request('payment_status') === $ps ? 'selected' : '' }}>{{ ucfirst($ps) }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-secondary btn-sm">Filter</button>
        @if(request('search') || request('status') || request('payment_status'))
            <a href="{{ route('admin.orders.index', ['view' => $view]) }}" class="btn btn-sm btn-secondary" style="color:var(--text-muted);">Reset</a>
        @endif
    </form>
</div>

{{-- Bulk Actions Toolbar --}}
<div id="bulk-toolbar" style="display:none;background:#18181b;color:#ffffff;padding:10px 18px;margin-bottom:14px;border-radius:4px;display:none;align-items:center;justify-content:space-between;transition:all 0.2s ease;">
    <div style="font-size:0.85rem;display:flex;align-items:center;gap:12px;">
        <span><strong id="selected-count">0</strong> order(s) selected</span>
    </div>
    <div style="display:flex;gap:8px;">
        <button type="button" onclick="executeBulkAction('archive')" class="btn btn-sm" style="background:#27272a;color:#ffffff;border:1px solid #3f3f46;cursor:pointer;font-size:0.75rem;">
            📦 Archive Selected
        </button>
        <button type="button" onclick="executeBulkAction('unarchive')" class="btn btn-sm" style="background:#27272a;color:#ffffff;border:1px solid #3f3f46;cursor:pointer;font-size:0.75rem;">
            ↺ Restore Selected
        </button>
        <button type="button" onclick="executeBulkAction('delete')" class="btn btn-sm" style="background:#dc2626;color:#ffffff;border:none;cursor:pointer;font-size:0.75rem;">
            🗑 Delete Selected
        </button>
    </div>
</div>

<form id="bulk-action-form" action="{{ route('admin.orders.bulk-action') }}" method="POST" style="display:none;">
    @csrf
    <input type="hidden" name="action" id="bulk-action-input">
    <div id="bulk-order-inputs"></div>
</form>

<div class="table-wrapper">
    <table class="table">
        <thead>
            <tr>
                <th style="width:36px;text-align:center;">
                    <input type="checkbox" id="select-all-orders" style="cursor:pointer;accent-color:#000000;width:15px;height:15px;" title="Select all orders">
                </th>
                <th>Order</th>
                <th>Customer</th>
                <th>Status</th>
                <th>Payment</th>
                <th>Total</th>
                <th>Date</th>
                <th style="text-align:right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
                <tr style="{{ $order->is_archived ? 'background:rgba(0,0,0,0.015);opacity:0.85;' : '' }}">
                    <td style="text-align:center;">
                        <input type="checkbox" class="order-checkbox" value="{{ $order->id }}" style="cursor:pointer;accent-color:#000000;width:15px;height:15px;" onchange="updateBulkToolbar()">
                    </td>
                    <td>
                        <div style="display:flex;align-items:center;gap:8px;">
                            <span style="font-weight:600;color:var(--text-primary);">{{ $order->order_number }}</span>
                            @if($order->is_archived)
                                <span class="badge" style="background:#e4e4e7;color:#52525b;font-size:0.65rem;letter-spacing:0.05em;padding:2px 6px;">ARCHIVED</span>
                            @endif
                        </div>
                    </td>
                    <td>
                        <div style="font-weight:500;">{{ $order->customer_name }}</div>
                        <div class="text-muted" style="font-size:0.75rem;">{{ $order->customer_email }}</div>
                    </td>
                    <td><span class="badge badge-{{ $order->status_badge }}">{{ ucfirst($order->status) }}</span></td>
                    <td><span class="badge badge-{{ $order->payment_status_badge }}">{{ ucfirst($order->payment_status) }}</span></td>
                    <td style="font-weight:600;color:var(--text-primary);">{{ $currency_symbol }}{{ number_format($order->total, 2) }}</td>
                    <td>
                        <div>{{ $order->created_at->format('M d, Y') }}</div>
                        <div class="text-muted" style="font-size:0.75rem;">{{ $order->created_at->format('h:i A') }}</div>
                    </td>
                    <td style="text-align:right;">
                        <div class="order-action-dropdown-wrapper" style="position:relative;display:inline-block;text-align:left;">
                            <button type="button" 
                                    class="order-action-btn" 
                                    onclick="toggleActionMenu(event, 'order-menu-{{ $order->id }}')" 
                                    title="Order actions"
                                    style="width:34px;height:34px;border-radius:6px;border:1px solid #e4e4e7;background:#ffffff;color:#52525b;display:inline-flex;align-items:center;justify-content:center;cursor:pointer;transition:all 0.15s ease;">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                    <circle cx="12" cy="5" r="2.2"/>
                                    <circle cx="12" cy="12" r="2.2"/>
                                    <circle cx="12" cy="19" r="2.2"/>
                                </svg>
                            </button>

                            {{-- Compact Dropdown Menu --}}
                            <div id="order-menu-{{ $order->id }}" 
                                 class="order-dropdown-menu"
                                 style="display:none;position:absolute;right:0;top:calc(100% + 6px);width:180px;background:#ffffff;border:1px solid #e4e4e7;border-radius:8px;box-shadow:0 12px 28px -4px rgba(0,0,0,0.12), 0 4px 10px -2px rgba(0,0,0,0.06);padding:6px;z-index:999;">
                                
                                {{-- View Details --}}
                                <a href="{{ route('admin.orders.show', $order) }}" 
                                   class="dropdown-action-item"
                                   style="display:flex;align-items:center;gap:10px;padding:8px 12px;font-size:0.8rem;color:#18181b;text-decoration:none;border-radius:6px;transition:background 0.12s ease;font-weight:500;">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color:#71717a;">
                                        <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                    <span>View Details</span>
                                </a>

                                {{-- Archive / Restore --}}
                                @if(!$order->is_archived)
                                    <form action="{{ route('admin.orders.archive', $order) }}" method="POST" style="margin:0;">
                                        @csrf
                                        <button type="submit" 
                                                class="dropdown-action-item"
                                                style="width:100%;display:flex;align-items:center;gap:10px;padding:8px 12px;font-size:0.8rem;color:#18181b;background:none;border:none;cursor:pointer;border-radius:6px;transition:background 0.12s ease;font-weight:500;text-align:left;">
                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color:#71717a;">
                                                <rect width="20" height="5" x="2" y="3" rx="1"/>
                                                <path d="M4 8v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8"/>
                                                <path d="M10 12h4"/>
                                            </svg>
                                            <span>Archive Order</span>
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.orders.unarchive', $order) }}" method="POST" style="margin:0;">
                                        @csrf
                                        <button type="submit" 
                                                class="dropdown-action-item"
                                                style="width:100%;display:flex;align-items:center;gap:10px;padding:8px 12px;font-size:0.8rem;color:#18181b;background:none;border:none;cursor:pointer;border-radius:6px;transition:background 0.12s ease;font-weight:500;text-align:left;">
                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color:#16a34a;">
                                                <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/>
                                                <path d="M3 3v5h5"/>
                                            </svg>
                                            <span>Restore Order</span>
                                        </button>
                                    </form>
                                @endif

                                <div style="height:1px;background:#f4f4f5;margin:4px 0;"></div>

                                {{-- Delete --}}
                                <button type="button" 
                                        onclick="openDeleteModal('{{ $order->order_number }}', '{{ route('admin.orders.destroy', $order) }}')" 
                                        class="dropdown-action-item-danger"
                                        style="width:100%;display:flex;align-items:center;gap:10px;padding:8px 12px;font-size:0.8rem;color:#dc2626;background:none;border:none;cursor:pointer;border-radius:6px;transition:background 0.12s ease;font-weight:500;text-align:left;">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M3 6h18"/>
                                        <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/>
                                        <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>
                                        <line x1="10" x2="10" y1="11" y2="17"/>
                                        <line x1="14" x2="14" y1="11" y2="17"/>
                                    </svg>
                                    <span>Delete Order</span>
                                </button>
                            </div>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center" style="padding:48px 20px;">
                        <div style="font-size:1.5rem;margin-bottom:8px;">📦</div>
                        <div style="font-weight:500;margin-bottom:4px;">No orders found</div>
                        <div class="text-muted" style="font-size:0.85rem;">
                            @if($view === 'archived')
                                No archived orders exist.
                            @elseif($view === 'active')
                                No active orders matching criteria.
                            @else
                                No customer orders matching your criteria.
                            @endif
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{ $orders->links() }}

{{-- Delete Confirmation Modal --}}
<div id="delete-modal" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.55);z-index:9999;align-items:center;justify-content:center;padding:20px;">
    <div style="background:#ffffff;border:1px solid var(--border-color);max-width:440px;width:100%;padding:28px;box-shadow:0 20px 40px rgba(0,0,0,0.2);animation:modalFadeIn 0.2s ease;">
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
            <div style="width:36px;height:36px;border-radius:50%;background:#fee2e2;color:#dc2626;display:flex;align-items:center;justify-content:center;font-weight:700;">!</div>
            <h3 style="font-family:'Cormorant Garamond',serif;font-size:1.4rem;font-weight:600;margin:0;color:#18181b;">Delete Order</h3>
        </div>
        <p style="font-size:0.875rem;color:#52525b;line-height:1.5;margin-bottom:20px;">
            Are you sure you want to permanently delete order <strong id="delete-modal-order-number" style="color:#18181b;"></strong>? All purchased items, quantities, and records associated with this order will be permanently erased.
        </p>
        <div style="display:flex;justify-content:flex-end;gap:10px;">
            <button type="button" onclick="closeDeleteModal()" class="btn btn-secondary btn-sm" style="padding:8px 16px;">Cancel</button>
            <form id="delete-modal-form" method="POST" style="margin:0;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm" style="background:#dc2626;color:#ffffff;border:none;padding:8px 16px;cursor:pointer;font-weight:600;">Permanently Delete</button>
            </form>
        </div>
    </div>
</div>

<script>
    // Select all checkboxes
    const selectAllCheckbox = document.getElementById('select-all-orders');
    const orderCheckboxes = document.querySelectorAll('.order-checkbox');
    const bulkToolbar = document.getElementById('bulk-toolbar');
    const selectedCountSpan = document.getElementById('selected-count');

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            orderCheckboxes.forEach(cb => cb.checked = selectAllCheckbox.checked);
            updateBulkToolbar();
        });
    }

    function updateBulkToolbar() {
        const checked = document.querySelectorAll('.order-checkbox:checked');
        const count = checked.length;
        if (selectedCountSpan) selectedCountSpan.textContent = count;
        if (bulkToolbar) {
            bulkToolbar.style.display = count > 0 ? 'flex' : 'none';
        }
        if (selectAllCheckbox) {
            selectAllCheckbox.checked = count > 0 && count === orderCheckboxes.length;
        }
    }

    function executeBulkAction(action) {
        const checked = document.querySelectorAll('.order-checkbox:checked');
        if (checked.length === 0) return;

        let confirmMsg = '';
        if (action === 'delete') {
            confirmMsg = `Are you sure you want to PERMANENTLY delete ${checked.length} selected order(s)? This action cannot be undone.`;
        } else if (action === 'archive') {
            confirmMsg = `Move ${checked.length} selected order(s) to Archive?`;
        } else if (action === 'unarchive') {
            confirmMsg = `Restore ${checked.length} selected order(s) from Archive?`;
        }

        if (confirm(confirmMsg)) {
            const form = document.getElementById('bulk-action-form');
            const actionInput = document.getElementById('bulk-action-input');
            const container = document.getElementById('bulk-order-inputs');

            actionInput.value = action;
            container.innerHTML = '';

            checked.forEach(cb => {
                const hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = 'order_ids[]';
                hidden.value = cb.value;
                container.appendChild(hidden);
            });

            form.submit();
        }
    }

    // Single order delete modal
    function openDeleteModal(orderNumber, actionUrl) {
        document.getElementById('delete-modal-order-number').textContent = orderNumber;
        document.getElementById('delete-modal-form').action = actionUrl;
        const modal = document.getElementById('delete-modal');
        modal.style.display = 'flex';
    }

    function closeDeleteModal() {
        const modal = document.getElementById('delete-modal');
        modal.style.display = 'none';
    }

    // Toggle action dropdown menu
    function toggleActionMenu(event, menuId) {
        event.stopPropagation();
        const menu = document.getElementById(menuId);
        if (!menu) return;

        const isCurrentlyOpen = menu.style.display === 'block';

        // Close all action dropdown menus
        document.querySelectorAll('.order-dropdown-menu').forEach(m => m.style.display = 'none');

        if (!isCurrentlyOpen) {
            menu.style.display = 'block';

            // Auto-flip upward if too close to bottom of screen
            const rect = menu.getBoundingClientRect();
            if (rect.bottom > (window.innerHeight || document.documentElement.clientHeight) - 20) {
                menu.style.top = 'auto';
                menu.style.bottom = 'calc(100% + 6px)';
            } else {
                menu.style.top = 'calc(100% + 6px)';
                menu.style.bottom = 'auto';
            }
        }
    }

    // Close dropdown when clicking anywhere outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.order-action-dropdown-wrapper')) {
            document.querySelectorAll('.order-dropdown-menu').forEach(m => m.style.display = 'none');
        }
    });

    // Close modal or dropdown on escape
    window.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.order-dropdown-menu').forEach(m => m.style.display = 'none');
            closeDeleteModal();
        }
    });
</script>
@endsection



