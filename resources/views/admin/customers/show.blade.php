@extends('layouts.admin')

@section('title', $customer->full_name)

@section('content')
<div class="admin-header" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;margin-bottom:24px;">
    <div class="d-flex align-center gap-3">
        <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary btn-sm">← Back</a>
        <div style="display:flex;align-items:center;gap:12px;">
            <h1 style="margin:0;">{{ $customer->full_name }}</h1>
            @if($customer->isArchived())
                <span class="badge" style="background:#e4e4e7;color:#52525b;font-size:0.75rem;padding:4px 10px;font-weight:700;">ARCHIVED</span>
            @elseif($customer->isDisabled())
                <span class="badge" style="background:#dc2626;color:#ffffff;font-size:0.75rem;padding:4px 10px;font-weight:700;">DISABLED</span>
            @else
                <span class="badge" style="background:#000000;color:#ffffff;font-size:0.75rem;padding:4px 10px;font-weight:700;">ACTIVE</span>
            @endif
        </div>
    </div>

    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
        {{-- Disable / Enable Toggle --}}
        @if(!$customer->isDisabled())
            <form action="{{ route('admin.customers.disable', $customer) }}" method="POST" onsubmit="return confirm('Disable account for {{ addslashes($customer->full_name) }}? Logins and all purchases with email {{ addslashes($customer->email) }} will be blocked.');" style="margin:0;">
                @csrf
                <button type="submit" class="btn btn-sm" style="background:#fef2f2;color:#dc2626;border:1px solid #fecaca;display:inline-flex;align-items:center;gap:6px;cursor:pointer;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="4.93" y1="4.93" x2="19.07" y2="19.07"></line>
                    </svg>
                    <span>Disable Account</span>
                </button>
            </form>
        @else
            <form action="{{ route('admin.customers.enable', $customer) }}" method="POST" onsubmit="return confirm('Re-enable account for {{ addslashes($customer->full_name) }}? Account access and purchasing privileges will be restored.');" style="margin:0;">
                @csrf
                <button type="submit" class="btn btn-sm" style="background:#f0fdf4;color:#16a34a;border:1px solid #bbf7d0;display:inline-flex;align-items:center;gap:6px;cursor:pointer;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                    <span>Enable Account</span>
                </button>
            </form>
        @endif

        {{-- Archive / Restore Toggle --}}
        @if(!$customer->isArchived())
            <form action="{{ route('admin.customers.archive', $customer) }}" method="POST" onsubmit="return confirm('Move customer {{ addslashes($customer->full_name) }} to Archive?');" style="margin:0;">
                @csrf
                <button type="submit" class="btn btn-sm btn-secondary" style="display:inline-flex;align-items:center;gap:6px;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="21 8 21 21 3 21 3 8"></polyline>
                        <rect x="1" y="3" width="22" height="5"></rect>
                        <line x1="10" y1="12" x2="14" y2="12"></line>
                    </svg>
                    <span>Archive Customer</span>
                </button>
            </form>
        @else
            <form action="{{ route('admin.customers.unarchive', $customer) }}" method="POST" onsubmit="return confirm('Restore customer {{ addslashes($customer->full_name) }} to active?');" style="margin:0;">
                @csrf
                <button type="submit" class="btn btn-sm btn-secondary" style="display:inline-flex;align-items:center;gap:6px;color:#16a34a;">
                    <span>↺ Restore Customer</span>
                </button>
            </form>
        @endif

        {{-- Permanent Delete --}}
        <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST" onsubmit="return confirm('PERMANENTLY DELETE customer {{ addslashes($customer->full_name) }}? This cannot be undone.');" style="margin:0;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm" style="background:#ef4444;color:#ffffff;border:none;display:inline-flex;align-items:center;gap:6px;cursor:pointer;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="3 6 5 6 21 6"></polyline>
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                </svg>
                <span>Delete Customer</span>
            </button>
        </form>
    </div>
</div>

@if($customer->isDisabled())
    <div style="background:#fef2f2;border:1px solid #fecaca;color:#991b1b;padding:14px 18px;border-radius:6px;margin-bottom:24px;display:flex;align-items:center;gap:12px;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;">
            <circle cx="12" cy="12" r="10"></circle>
            <line x1="12" y1="8" x2="12" y2="12"></line>
            <line x1="12" y1="16" x2="12.01" y2="16"></line>
        </svg>
        <div style="font-size:0.88rem;">
            <strong>ACCOUNT DISABLED:</strong> Any purchases or order checkouts with the email <strong>{{ $customer->email }}</strong> are completely blocked. Customer login access is also suspended.
            @if($customer->disabled_reason)
                <div style="font-size:0.8rem;margin-top:2px;color:#7f1d1d;">Reason: {{ $customer->disabled_reason }}</div>
            @endif
        </div>
    </div>
@endif

<div style="display:grid;grid-template-columns:1fr 2fr;gap:24px;">
    <div class="card">
        <div class="card-header"><h3 style="font-size:1rem;margin:0;">Customer Profile</h3></div>
        <div class="card-body">
            <div class="mb-3" style="display:flex;align-items:center;gap:12px;">
                <div style="width:48px;height:48px;border-radius:50%;background:{{ $customer->isDisabled() ? '#dc2626' : '#000000' }};color:#ffffff;display:flex;align-items:center;justify-content:center;font-weight:600;font-size:1.1rem;letter-spacing:0.05em;">
                    {{ strtoupper(substr($customer->first_name, 0, 1) . substr($customer->last_name, 0, 1)) }}
                </div>
                <div>
                    <div style="font-weight:700;font-size:1rem;color:var(--text-primary);">{{ $customer->full_name }}</div>
                    <div class="text-muted" style="font-size:0.8rem;">Customer ID #{{ $customer->id }}</div>
                </div>
            </div>

            <div style="border-top:1px solid var(--border-color);padding-top:16px;">
                <div class="mb-2"><span class="text-muted" style="font-size:0.85rem;">Email:</span> <strong style="font-size:0.85rem;">{{ $customer->email }}</strong></div>
                <div class="mb-2"><span class="text-muted" style="font-size:0.85rem;">Phone:</span> <span style="font-size:0.85rem;">{{ $customer->phone ?? '—' }}</span></div>
                <div class="mb-2"><span class="text-muted" style="font-size:0.85rem;">Address:</span> <span style="font-size:0.85rem;">{{ $customer->address ?? '—' }}</span></div>
                <div class="mb-2"><span class="text-muted" style="font-size:0.85rem;">City / State:</span> <span style="font-size:0.85rem;">{{ $customer->city ?? '—' }} {{ $customer->state ? ', ' . $customer->state : '' }}</span></div>
                <div class="mb-2"><span class="text-muted" style="font-size:0.85rem;">Country / ZIP:</span> <span style="font-size:0.85rem;">{{ $customer->country ?? '—' }} {{ $customer->postal_code ? '(' . $customer->postal_code . ')' : '' }}</span></div>
                <div class="mb-2"><span class="text-muted" style="font-size:0.85rem;">Joined:</span> <span style="font-size:0.85rem;">{{ $customer->created_at->format('M d, Y · h:i A') }}</span></div>
                
                @if($customer->isDisabled())
                    <div class="mb-2" style="color:#dc2626;"><span class="text-muted" style="font-size:0.85rem;">Disabled At:</span> <span style="font-size:0.85rem;font-weight:600;">{{ $customer->disabled_at ? $customer->disabled_at->format('M d, Y · h:i A') : 'Yes' }}</span></div>
                @endif

                @if($customer->isArchived())
                    <div class="mb-2" style="color:#6b7280;"><span class="text-muted" style="font-size:0.85rem;">Archived At:</span> <span style="font-size:0.85rem;">{{ $customer->archived_at->format('M d, Y · h:i A') }}</span></div>
                @endif
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;">
            <h3 style="font-size:1rem;margin:0;">Order History ({{ $customer->orders->count() }})</h3>
        </div>
        <div class="card-body" style="padding:0;">
            @forelse($customer->orders as $order)
                <a href="{{ route('admin.orders.show', $order) }}" class="d-flex justify-between align-center" style="padding:14px 20px;border-bottom:1px solid var(--border-color);text-decoration:none;transition:background 0.15s ease;">
                    <div>
                        <div style="font-weight:600;color:var(--text-primary);font-size:0.88rem;">{{ $order->order_number }}</div>
                        <div class="text-muted" style="font-size:0.75rem;">{{ $order->created_at->format('M d, Y · h:i A') }}</div>
                    </div>
                    <div class="text-right">
                        <span class="badge badge-{{ $order->status_badge }}">{{ ucfirst($order->status) }}</span>
                        <div style="font-weight:600;color:var(--text-primary);font-size:0.88rem;margin-top:4px;">{{ $currency_symbol ?? '$' }}{{ number_format($order->total, 2) }}</div>
                    </div>
                </a>
            @empty
                <div class="empty-state" style="padding:36px 20px;text-align:center;">
                    <div style="font-size:2rem;margin-bottom:8px;">📦</div>
                    <p class="text-muted" style="margin:0;font-size:0.88rem;">No orders placed yet by this customer.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
