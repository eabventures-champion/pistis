@extends('layouts.admin')

@section('title', 'Customers')

@section('content')
<style>
    .table-wrapper {
        overflow: visible !important;
    }
    .customer-action-btn:hover {
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

<div class="admin-header" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;margin-bottom:20px;">
    <div>
        <h1 style="margin:0 0 4px 0;">CUSTOMERS</h1>
        <div class="text-muted" style="font-size:0.85rem;display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
            <span>Total: <strong style="color:var(--text-primary);">{{ $counts['all'] }}</strong></span>
            <span style="color:#d4d4d8;">•</span>
            <span>Active: <strong style="color:var(--text-primary);">{{ $counts['active'] }}</strong></span>
            <span style="color:#d4d4d8;">•</span>
            <span>Disabled: <strong style="color:#dc2626;">{{ $counts['disabled'] }}</strong></span>
            <span style="color:#d4d4d8;">•</span>
            <span>Archived: <strong style="color:var(--text-primary);">{{ $counts['archived'] }}</strong></span>
        </div>
    </div>

    {{-- Global Actions: Archive All, Disable All & Delete All --}}
    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
        @if($counts['active'] > 0)
            <form action="{{ route('admin.customers.disable-all') }}" method="POST" onsubmit="return confirm('DISABLE ALL {{ $counts['active'] }} active customer accounts? Disabled accounts cannot log in and any purchases with their emails will be completely blocked.');" style="margin:0;">
                @csrf
                <button type="submit" class="btn btn-sm" style="background:#fef2f2;color:#dc2626;border:1px solid #fecaca;display:inline-flex;align-items:center;gap:6px;font-size:0.78rem;cursor:pointer;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="4.93" y1="4.93" x2="19.07" y2="19.07"></line>
                    </svg>
                    <span>Disable All Active ({{ $counts['active'] }})</span>
                </button>
            </form>

            <form action="{{ route('admin.customers.archive-all') }}" method="POST" onsubmit="return confirm('ARCHIVE ALL {{ $counts['active'] }} active customer accounts? Archived customers will be moved to the archive.');" style="margin:0;">
                @csrf
                <button type="submit" class="btn btn-sm btn-secondary" style="display:inline-flex;align-items:center;gap:6px;font-size:0.78rem;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="21 8 21 21 3 21 3 8"></polyline>
                        <rect x="1" y="3" width="22" height="5"></rect>
                        <line x1="10" y1="12" x2="14" y2="12"></line>
                    </svg>
                    <span>Archive All Active ({{ $counts['active'] }})</span>
                </button>
            </form>
        @endif

        @if($counts['disabled'] > 0 && $view === 'disabled')
            <form action="{{ route('admin.customers.enable-all') }}" method="POST" onsubmit="return confirm('Re-enable all {{ $counts['disabled'] }} disabled customer accounts? Purchases and logins will be permitted.');" style="margin:0;">
                @csrf
                <button type="submit" class="btn btn-sm btn-secondary" style="display:inline-flex;align-items:center;gap:6px;font-size:0.78rem;color:#16a34a;">
                    <span>✓ Re-enable All ({{ $counts['disabled'] }})</span>
                </button>
            </form>
        @endif

        @if($counts['archived'] > 0 && $view === 'archived')
            <form action="{{ route('admin.customers.unarchive-all') }}" method="POST" onsubmit="return confirm('Restore all {{ $counts['archived'] }} archived customers back to active?');" style="margin:0;">
                @csrf
                <button type="submit" class="btn btn-sm btn-secondary" style="display:inline-flex;align-items:center;gap:6px;font-size:0.78rem;">
                    <span>↺ Restore All Archived ({{ $counts['archived'] }})</span>
                </button>
            </form>
        @endif

        @if($counts['all'] > 0)
            <form action="{{ route('admin.customers.destroy-all') }}" method="POST" id="destroy-all-form" onsubmit="return handleConfirmDeleteAll();" style="margin:0;">
                @csrf
                <input type="hidden" name="scope" value="{{ $view }}">
                <button type="submit" class="btn btn-sm" style="background:#ef4444;color:#ffffff;border:none;display:inline-flex;align-items:center;gap:6px;font-size:0.78rem;letter-spacing:0.04em;cursor:pointer;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    </svg>
                    <span>
                        @if($view === 'archived')
                            Delete All Archived ({{ $counts['archived'] }})
                        @elseif($view === 'disabled')
                            Delete All Disabled ({{ $counts['disabled'] }})
                        @elseif($view === 'active')
                            Delete All Active ({{ $counts['active'] }})
                        @else
                            Delete All Customers ({{ $counts['all'] }})
                        @endif
                    </span>
                </button>
            </form>
        @endif
    </div>
</div>

{{-- Tabs: All / Active / Disabled / Archived --}}
<div style="display:flex;align-items:center;gap:20px;border-bottom:1px solid var(--border-color);margin-bottom:20px;overflow-x:auto;">
    <a href="{{ route('admin.customers.index', array_merge(request()->except(['view', 'page']), ['view' => 'all'])) }}"
       style="padding:10px 4px;font-size:0.85rem;font-weight:{{ $view === 'all' ? '600' : '400' }};color:{{ $view === 'all' ? 'var(--text-primary)' : 'var(--text-muted)' }};border-bottom:2px solid {{ $view === 'all' ? 'var(--text-primary)' : 'transparent' }};text-decoration:none;display:inline-flex;align-items:center;gap:8px;white-space:nowrap;">
        All Customers
        <span style="background:{{ $view === 'all' ? 'var(--text-primary)' : '#f0f0f0' }};color:{{ $view === 'all' ? '#ffffff' : '#666666' }};padding:2px 8px;border-radius:10px;font-size:0.7rem;font-weight:600;">{{ $counts['all'] }}</span>
    </a>
    <a href="{{ route('admin.customers.index', array_merge(request()->except(['view', 'page']), ['view' => 'active'])) }}"
       style="padding:10px 4px;font-size:0.85rem;font-weight:{{ $view === 'active' ? '600' : '400' }};color:{{ $view === 'active' ? 'var(--text-primary)' : 'var(--text-muted)' }};border-bottom:2px solid {{ $view === 'active' ? 'var(--text-primary)' : 'transparent' }};text-decoration:none;display:inline-flex;align-items:center;gap:8px;white-space:nowrap;">
        Active
        <span style="background:{{ $view === 'active' ? 'var(--text-primary)' : '#f0f0f0' }};color:{{ $view === 'active' ? '#ffffff' : '#666666' }};padding:2px 8px;border-radius:10px;font-size:0.7rem;font-weight:600;">{{ $counts['active'] }}</span>
    </a>
    <a href="{{ route('admin.customers.index', array_merge(request()->except(['view', 'page']), ['view' => 'disabled'])) }}"
       style="padding:10px 4px;font-size:0.85rem;font-weight:{{ $view === 'disabled' ? '600' : '400' }};color:{{ $view === 'disabled' ? '#dc2626' : 'var(--text-muted)' }};border-bottom:2px solid {{ $view === 'disabled' ? '#dc2626' : 'transparent' }};text-decoration:none;display:inline-flex;align-items:center;gap:8px;white-space:nowrap;">
        Disabled
        <span style="background:{{ $view === 'disabled' ? '#dc2626' : ($counts['disabled'] > 0 ? '#fee2e2' : '#f0f0f0') }};color:{{ $view === 'disabled' ? '#ffffff' : ($counts['disabled'] > 0 ? '#dc2626' : '#666666') }};padding:2px 8px;border-radius:10px;font-size:0.7rem;font-weight:600;">{{ $counts['disabled'] }}</span>
    </a>
    <a href="{{ route('admin.customers.index', array_merge(request()->except(['view', 'page']), ['view' => 'archived'])) }}"
       style="padding:10px 4px;font-size:0.85rem;font-weight:{{ $view === 'archived' ? '600' : '400' }};color:{{ $view === 'archived' ? 'var(--text-primary)' : 'var(--text-muted)' }};border-bottom:2px solid {{ $view === 'archived' ? 'var(--text-primary)' : 'transparent' }};text-decoration:none;display:inline-flex;align-items:center;gap:8px;white-space:nowrap;">
        Archived
        <span style="background:{{ $view === 'archived' ? 'var(--text-primary)' : '#f0f0f0' }};color:{{ $view === 'archived' ? '#ffffff' : '#666666' }};padding:2px 8px;border-radius:10px;font-size:0.7rem;font-weight:600;">{{ $counts['archived'] }}</span>
    </a>
</div>

{{-- Search & Filters --}}
<div class="d-flex gap-3 mb-3">
    <form action="{{ route('admin.customers.index') }}" method="GET" class="d-flex gap-2 flex-1">
        <input type="hidden" name="view" value="{{ $view }}">
        <input type="text" name="search" class="form-control" placeholder="Search by name, email, phone..." value="{{ request('search') }}" style="max-width:320px;">
        <button type="submit" class="btn btn-secondary btn-sm">Search</button>
        @if(request('search'))
            <a href="{{ route('admin.customers.index', ['view' => $view]) }}" class="btn btn-sm btn-secondary" style="color:var(--text-muted);">Reset</a>
        @endif
    </form>
</div>

{{-- Bulk Actions Toolbar --}}
<div id="bulk-toolbar" style="display:none;background:#18181b;color:#ffffff;padding:10px 18px;margin-bottom:14px;border-radius:6px;align-items:center;justify-content:space-between;transition:all 0.2s ease;flex-wrap:wrap;gap:10px;">
    <div style="font-size:0.85rem;display:flex;align-items:center;gap:12px;">
        <span><strong id="selected-count">0</strong> customer(s) selected</span>
    </div>
    <div style="display:flex;gap:8px;flex-wrap:wrap;">
        <button type="button" onclick="executeBulkAction('disable')" class="btn btn-sm" style="background:#450a0a;color:#fca5a5;border:1px solid #7f1d1d;cursor:pointer;font-size:0.75rem;">
            🚫 Disable Selected
        </button>
        <button type="button" onclick="executeBulkAction('enable')" class="btn btn-sm" style="background:#052e16;color:#86efac;border:1px solid #14532d;cursor:pointer;font-size:0.75rem;">
            ✓ Enable Selected
        </button>
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

<form id="bulk-action-form" action="{{ route('admin.customers.bulk-action') }}" method="POST" style="display:none;">
    @csrf
    <input type="hidden" name="action" id="bulk-action-input">
    <div id="bulk-customer-inputs"></div>
</form>

<div class="table-wrapper">
    <table class="table">
        <thead>
            <tr>
                <th style="width:36px;text-align:center;">
                    <input type="checkbox" id="select-all-customers" style="cursor:pointer;accent-color:#000000;width:15px;height:15px;" title="Select all customers">
                </th>
                <th>CUSTOMER</th>
                <th>EMAIL</th>
                <th>PHONE</th>
                <th>ORDERS</th>
                <th>STATUS</th>
                <th>JOINED</th>
                <th style="width:100px;text-align:right;">ACTIONS</th>
            </tr>
        </thead>
        <tbody>
            @forelse($customers as $customer)
                <tr style="{{ $customer->isArchived() ? 'background:rgba(0,0,0,0.015);opacity:0.85;' : ($customer->isDisabled() ? 'background:rgba(239,68,68,0.03);' : '') }}">
                    <td style="text-align:center;">
                        <input type="checkbox" class="customer-checkbox" value="{{ $customer->id }}" style="cursor:pointer;accent-color:#000000;width:15px;height:15px;" onchange="updateBulkToolbar()">
                    </td>
                    <td>
                        <div style="display:flex;align-items:center;gap:12px;">
                            <div style="width:34px;height:34px;border-radius:50%;background:{{ $customer->isDisabled() ? '#dc2626' : '#000000' }};color:#ffffff;display:flex;align-items:center;justify-content:center;font-weight:600;font-size:0.8rem;letter-spacing:0.05em;flex-shrink:0;">
                                {{ strtoupper(substr($customer->first_name, 0, 1) . substr($customer->last_name, 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-weight:600;color:var(--text-primary);display:flex;align-items:center;gap:6px;">
                                    <span>{{ $customer->full_name }}</span>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span style="font-size:0.85rem;">{{ $customer->email }}</span>
                    </td>
                    <td>
                        <span style="font-size:0.85rem;color:var(--text-secondary);">{{ $customer->phone ?? '—' }}</span>
                    </td>
                    <td>
                        <span class="badge" style="background:#000000;color:#ffffff;font-size:0.75rem;padding:3px 8px;font-weight:700;">{{ $customer->orders_count }}</span>
                    </td>
                    <td>
                        @if($customer->isArchived())
                            <span class="badge" style="background:#e4e4e7;color:#52525b;font-weight:700;font-size:0.68rem;letter-spacing:0.06em;padding:4px 8px;">ARCHIVED</span>
                        @elseif($customer->isDisabled())
                            <span class="badge" style="background:#dc2626;color:#ffffff;font-weight:700;font-size:0.68rem;letter-spacing:0.06em;padding:4px 8px;" title="Account disabled — purchases and logins blocked">DISABLED</span>
                        @else
                            <span class="badge" style="background:#000000;color:#ffffff;font-weight:700;font-size:0.68rem;letter-spacing:0.06em;padding:4px 8px;">ACTIVE</span>
                        @endif
                    </td>
                    <td>
                        <div style="font-size:0.85rem;">{{ $customer->created_at->format('M d, Y') }}</div>
                        <div class="text-muted" style="font-size:0.75rem;">{{ $customer->created_at->diffForHumans() }}</div>
                    </td>
                    <td style="text-align:right;">
                        <div class="customer-action-dropdown-wrapper" style="position:relative;display:inline-block;text-align:left;">
                            <button type="button" 
                                    class="customer-action-btn" 
                                    onclick="toggleActionMenu(event, 'customer-menu-{{ $customer->id }}')" 
                                    title="Customer actions"
                                    style="width:32px;height:32px;border-radius:6px;border:1px solid #e4e4e7;background:#ffffff;color:#52525b;display:inline-flex;align-items:center;justify-content:center;cursor:pointer;transition:all 0.15s ease;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                    <circle cx="12" cy="5" r="2"/>
                                    <circle cx="12" cy="12" r="2"/>
                                    <circle cx="12" cy="19" r="2"/>
                                </svg>
                            </button>

                            {{-- Dropdown Menu --}}
                            <div id="customer-menu-{{ $customer->id }}" 
                                 class="customer-dropdown-menu"
                                 style="display:none;position:absolute;right:0;top:calc(100% + 6px);width:210px;background:#ffffff;border:1px solid #e4e4e7;border-radius:8px;box-shadow:0 12px 28px -4px rgba(0,0,0,0.12), 0 4px 10px -2px rgba(0,0,0,0.06);padding:6px;z-index:999;">
                                
                                {{-- View Details --}}
                                <a href="{{ route('admin.customers.show', $customer) }}" 
                                   class="dropdown-action-item"
                                   style="display:flex;align-items:center;gap:10px;padding:8px 12px;font-size:0.8rem;color:#18181b;text-decoration:none;border-radius:6px;transition:background 0.12s ease;font-weight:500;">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color:#71717a;">
                                        <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                    <span>View Profile</span>
                                </a>

                                {{-- Disable / Enable Account --}}
                                @if(!$customer->isDisabled())
                                    <form action="{{ route('admin.customers.disable', $customer) }}" method="POST" style="margin:0;" onsubmit="return confirm('Disable this customer account? Login and all purchases with email {{ addslashes($customer->email) }} will be blocked.');">
                                        @csrf
                                        <button type="submit" 
                                                class="dropdown-action-item"
                                                style="width:100%;display:flex;align-items:center;gap:10px;padding:8px 12px;font-size:0.8rem;color:#dc2626;background:none;border:none;cursor:pointer;border-radius:6px;transition:background 0.12s ease;font-weight:500;text-align:left;">
                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <line x1="4.93" y1="4.93" x2="19.07" y2="19.07"></line>
                                            </svg>
                                            <span>Disable Account</span>
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.customers.enable', $customer) }}" method="POST" style="margin:0;">
                                        @csrf
                                        <button type="submit" 
                                                class="dropdown-action-item"
                                                style="width:100%;display:flex;align-items:center;gap:10px;padding:8px 12px;font-size:0.8rem;color:#16a34a;background:none;border:none;cursor:pointer;border-radius:6px;transition:background 0.12s ease;font-weight:500;text-align:left;">
                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                                <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                            </svg>
                                            <span style="color:#16a34a;">Enable Account</span>
                                        </button>
                                    </form>
                                @endif

                                {{-- Archive / Restore --}}
                                @if(!$customer->isArchived())
                                    <form action="{{ route('admin.customers.archive', $customer) }}" method="POST" style="margin:0;">
                                        @csrf
                                        <button type="submit" 
                                                class="dropdown-action-item"
                                                style="width:100%;display:flex;align-items:center;gap:10px;padding:8px 12px;font-size:0.8rem;color:#18181b;background:none;border:none;cursor:pointer;border-radius:6px;transition:background 0.12s ease;font-weight:500;text-align:left;">
                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color:#71717a;">
                                                <polyline points="21 8 21 21 3 21 3 8"></polyline>
                                                <rect x="1" y="3" width="22" height="5"></rect>
                                                <line x1="10" y1="12" x2="14" y2="12"></line>
                                            </svg>
                                            <span>Archive Customer</span>
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.customers.unarchive', $customer) }}" method="POST" style="margin:0;">
                                        @csrf
                                        <button type="submit" 
                                                class="dropdown-action-item"
                                                style="width:100%;display:flex;align-items:center;gap:10px;padding:8px 12px;font-size:0.8rem;color:#18181b;background:none;border:none;cursor:pointer;border-radius:6px;transition:background 0.12s ease;font-weight:500;text-align:left;">
                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color:#16a34a;">
                                                <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/>
                                                <path d="M3 3v5h5"/>
                                            </svg>
                                            <span style="color:#16a34a;">Restore Customer</span>
                                        </button>
                                    </form>
                                @endif

                                <div style="height:1px;background:#f4f4f5;margin:4px 0;"></div>

                                {{-- Delete Permanently --}}
                                <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST" style="margin:0;" onsubmit="return confirm('Permanently delete customer {{ addslashes($customer->full_name) }}? This cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="dropdown-action-item dropdown-action-item-danger"
                                            style="width:100%;display:flex;align-items:center;gap:10px;padding:8px 12px;font-size:0.8rem;color:#dc2626;background:none;border:none;cursor:pointer;border-radius:6px;transition:background 0.12s ease;font-weight:500;text-align:left;">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                        </svg>
                                        <span>Delete Permanently</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">
                        <div class="empty-state" style="padding:48px 20px;text-align:center;">
                            <div class="empty-state-icon" style="font-size:2.5rem;margin-bottom:12px;">👥</div>
                            <div class="empty-state-text" style="font-size:1.1rem;font-weight:600;margin-bottom:6px;">No customers found</div>
                            <p class="text-muted" style="font-size:0.85rem;max-width:340px;margin:0 auto 16px;">
                                @if(request('search'))
                                    No customer records matched your query "{{ request('search') }}".
                                @elseif($view === 'disabled')
                                    There are currently no disabled customer accounts.
                                @elseif($view === 'archived')
                                    There are currently no archived customer accounts.
                                @else
                                    When clients register on your store, they will appear here.
                                @endif
                            </p>
                            @if(request('search'))
                                <a href="{{ route('admin.customers.index', ['view' => $view]) }}" class="btn btn-sm btn-secondary">Clear Search</a>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top:20px;">
    {{ $customers->links() }}
</div>

<script>
    // Confirmation for Delete All Customers
    function handleConfirmDeleteAll() {
        const promptText = prompt('WARNING: You are about to permanently DELETE all customers in this view. This action cannot be undone.\n\nType DELETE to confirm:');
        if (promptText === 'DELETE') {
            return true;
        } else if (promptText !== null) {
            alert('Action cancelled: verification phrase did not match.');
        }
        return false;
    }

    // Toggle row dropdown menu
    function toggleActionMenu(event, menuId) {
        event.stopPropagation();
        const menu = document.getElementById(menuId);
        const allMenus = document.querySelectorAll('.customer-dropdown-menu');

        allMenus.forEach(m => {
            if (m.id !== menuId) {
                m.style.display = 'none';
            }
        });

        if (menu) {
            menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
        }
    }

    // Close menus on outside click
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.customer-action-dropdown-wrapper')) {
            document.querySelectorAll('.customer-dropdown-menu').forEach(m => {
                m.style.display = 'none';
            });
        }
    });

    // Checkbox and Bulk Actions
    const selectAllCheckbox = document.getElementById('select-all-customers');
    const customerCheckboxes = document.querySelectorAll('.customer-checkbox');
    const bulkToolbar = document.getElementById('bulk-toolbar');
    const selectedCountSpan = document.getElementById('selected-count');

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            customerCheckboxes.forEach(cb => {
                cb.checked = selectAllCheckbox.checked;
            });
            updateBulkToolbar();
        });
    }

    function updateBulkToolbar() {
        const checked = document.querySelectorAll('.customer-checkbox:checked');
        const count = checked.length;

        if (selectedCountSpan) {
            selectedCountSpan.textContent = count;
        }

        if (bulkToolbar) {
            bulkToolbar.style.display = count > 0 ? 'flex' : 'none';
        }

        if (selectAllCheckbox) {
            selectAllCheckbox.checked = (count > 0 && count === customerCheckboxes.length);
            selectAllCheckbox.indeterminate = (count > 0 && count < customerCheckboxes.length);
        }
    }

    function executeBulkAction(action) {
        const checked = document.querySelectorAll('.customer-checkbox:checked');
        if (checked.length === 0) return;

        let confirmMsg = '';
        if (action === 'disable') {
            confirmMsg = `DISABLE ${checked.length} selected customer(s)? Their account access and all purchases with their emails will be blocked.`;
        } else if (action === 'enable') {
            confirmMsg = `RE-ENABLE ${checked.length} selected customer(s)?`;
        } else if (action === 'archive') {
            confirmMsg = `Archive ${checked.length} selected customer(s)?`;
        } else if (action === 'unarchive') {
            confirmMsg = `Restore ${checked.length} selected customer(s) to active?`;
        } else if (action === 'delete') {
            confirmMsg = `PERMANENTLY DELETE ${checked.length} selected customer(s)? This cannot be undone.`;
        }

        if (!confirm(confirmMsg)) return;

        const form = document.getElementById('bulk-action-form');
        const actionInput = document.getElementById('bulk-action-input');
        const inputsContainer = document.getElementById('bulk-customer-inputs');

        actionInput.value = action;
        inputsContainer.innerHTML = '';

        checked.forEach(cb => {
            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = 'customer_ids[]';
            hidden.value = cb.value;
            inputsContainer.appendChild(hidden);
        });

        form.submit();
    }
</script>
@endsection
