@extends('layouts.admin')

@section('title', 'Inner Circle Subscribers')

@section('content')
<style>
    .subscriber-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        border-radius: 9999px;
        font-size: 0.72rem;
        font-weight: 600;
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }
    .subscriber-badge-active {
        background: #dcfce7;
        color: #15803d;
        border: 1px solid #bbf7d0;
    }
    .subscriber-badge-unsubscribed {
        background: #f1f5f9;
        color: #64748b;
        border: 1px solid #e2e8f0;
    }
    .sub-filter-tab {
        padding: 8px 16px;
        font-size: 0.8rem;
        font-weight: 500;
        letter-spacing: 0.05em;
        text-decoration: none;
        color: #71717a;
        border-bottom: 2px solid transparent;
        transition: all 0.2s;
    }
    .sub-filter-tab:hover {
        color: #000000;
    }
    .sub-filter-tab.active {
        color: #000000;
        border-bottom-color: #000000;
        font-weight: 600;
    }
    .action-icon-btn {
        background: none;
        border: 1px solid #e4e4e7;
        border-radius: 6px;
        padding: 6px 10px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 0.75rem;
        color: #27272a;
        transition: all 0.15s;
    }
    .action-icon-btn:hover {
        background: #f4f4f5;
        border-color: #d4d4d8;
    }
    .action-icon-btn.btn-danger-soft:hover {
        background: #fee2e2;
        color: #b91c1c;
        border-color: #fca5a5;
    }
</style>

<div class="admin-header" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;margin-bottom:24px;">
    <div>
        <h1 style="margin:0 0 6px 0;letter-spacing:0.04em;">INNER CIRCLE SUBSCRIBERS</h1>
        <p class="text-muted" style="font-size:0.85rem;margin:0;">
            Manage newsletter subscribers, monitor audience growth, and export contacts for private lookbook campaigns.
        </p>
    </div>

    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
        <a href="{{ route('admin.subscribers.campaign') }}" class="btn btn-primary" style="display:inline-flex;align-items:center;gap:6px;font-size:0.8rem;">
            <span>📢 Send Lookbook Campaign</span>
        </a>
        <a href="{{ route('admin.subscribers.export', ['status' => $view === 'all' ? '' : $view]) }}" class="btn btn-secondary" style="display:inline-flex;align-items:center;gap:6px;font-size:0.8rem;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                <polyline points="7 10 12 15 17 10"></polyline>
                <line x1="12" y1="15" x2="12" y2="3"></line>
            </svg>
            <span>Export to CSV</span>
        </a>
    </div>
</div>

{{-- Top Metrics Cards --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(200px, 1fr));gap:16px;margin-bottom:24px;">
    <div class="card" style="padding:18px 20px;">
        <span class="text-muted" style="font-size:0.75rem;text-transform:uppercase;letter-spacing:0.06em;font-weight:600;">Total Audience</span>
        <div style="font-size:1.75rem;font-weight:700;margin-top:6px;color:var(--text-primary);">{{ number_format($counts['all']) }}</div>
        <span class="text-muted" style="font-size:0.75rem;margin-top:4px;display:block;">All recorded signups</span>
    </div>
    <div class="card" style="padding:18px 20px;">
        <span class="text-muted" style="font-size:0.75rem;text-transform:uppercase;letter-spacing:0.06em;font-weight:600;">Active Members</span>
        <div style="font-size:1.75rem;font-weight:700;margin-top:6px;color:#15803d;">{{ number_format($counts['active']) }}</div>
        <span class="text-muted" style="font-size:0.75rem;margin-top:4px;display:block;">Eligible for lookbooks & drops</span>
    </div>
    <div class="card" style="padding:18px 20px;">
        <span class="text-muted" style="font-size:0.75rem;text-transform:uppercase;letter-spacing:0.06em;font-weight:600;">Joined This Month</span>
        <div style="font-size:1.75rem;font-weight:700;margin-top:6px;color:var(--text-primary);">+{{ number_format($counts['this_month']) }}</div>
        <span class="text-muted" style="font-size:0.75rem;margin-top:4px;display:block;">Growth in {{ date('F') }}</span>
    </div>
    <div class="card" style="padding:18px 20px;">
        <span class="text-muted" style="font-size:0.75rem;text-transform:uppercase;letter-spacing:0.06em;font-weight:600;">Unsubscribed</span>
        <div style="font-size:1.75rem;font-weight:700;margin-top:6px;color:#64748b;">{{ number_format($counts['unsubscribed']) }}</div>
        <span class="text-muted" style="font-size:0.75rem;margin-top:4px;display:block;">Opted out from emails</span>
    </div>
</div>

{{-- Main Card --}}
<div class="card">
    {{-- Tabs & Search Toolbar --}}
    <div style="padding:14px 20px;border-bottom:1px solid #f4f4f5;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
        <div style="display:flex;align-items:center;gap:8px;">
            <a href="{{ route('admin.subscribers.index', ['view' => 'all', 'search' => request('search')]) }}" class="sub-filter-tab {{ $view === 'all' ? 'active' : '' }}">
                All ({{ $counts['all'] }})
            </a>
            <a href="{{ route('admin.subscribers.index', ['view' => 'active', 'search' => request('search')]) }}" class="sub-filter-tab {{ $view === 'active' ? 'active' : '' }}">
                Active ({{ $counts['active'] }})
            </a>
            <a href="{{ route('admin.subscribers.index', ['view' => 'unsubscribed', 'search' => request('search')]) }}" class="sub-filter-tab {{ $view === 'unsubscribed' ? 'active' : '' }}">
                Unsubscribed ({{ $counts['unsubscribed'] }})
            </a>
        </div>

        <form method="GET" action="{{ route('admin.subscribers.index') }}" style="display:flex;align-items:center;gap:8px;margin:0;">
            <input type="hidden" name="view" value="{{ $view }}">
            <div style="position:relative;">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search email address..." 
                       style="padding:6px 12px 6px 32px;font-size:0.8rem;border:1px solid #e4e4e7;border-radius:6px;width:240px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#a1a1aa" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     style="position:absolute;left:10px;top:50%;transform:translateY(-50%);">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </div>
            @if(request('search'))
                <a href="{{ route('admin.subscribers.index', ['view' => $view]) }}" class="btn btn-sm btn-secondary" style="font-size:0.75rem;">Clear</a>
            @endif
        </form>
    </div>

    {{-- Subscribers Table --}}
    <div class="table-responsive">
        @if($subscribers->count() > 0)
            <table class="table" style="width:100%;margin:0;">
                <thead>
                    <tr style="border-bottom:1px solid #f4f4f5;background:#fafafa;font-size:0.72rem;letter-spacing:0.06em;text-transform:uppercase;color:#71717a;">
                        <th style="padding:12px 20px;text-align:left;">Subscriber Email</th>
                        <th style="padding:12px 16px;text-align:left;">Status</th>
                        <th style="padding:12px 16px;text-align:left;">Origin</th>
                        <th style="padding:12px 16px;text-align:left;">Subscribed Date</th>
                        <th style="padding:12px 20px;text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($subscribers as $subscriber)
                        <tr style="border-bottom:1px solid #f4f4f5;font-size:0.85rem;">
                            <td style="padding:14px 20px;">
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <div style="width:32px;height:32px;border-radius:50%;background:#09090b;color:#ffffff;display:flex;align-items:center;justify-content:center;font-size:0.75rem;font-weight:600;flex-shrink:0;">
                                        {{ strtoupper(substr($subscriber->email, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div style="font-weight:600;color:var(--text-primary);">{{ $subscriber->email }}</div>
                                        <div class="text-muted" style="font-size:0.72rem;">IP: {{ $subscriber->ip_address ?: 'Unknown' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td style="padding:14px 16px;">
                                @if($subscriber->status === 'active')
                                    <span class="subscriber-badge subscriber-badge-active">
                                        <span style="width:6px;height:6px;border-radius:50%;background:#16a34a;"></span>
                                        Active
                                    </span>
                                @else
                                    <span class="subscriber-badge subscriber-badge-unsubscribed">
                                        <span style="width:6px;height:6px;border-radius:50%;background:#94a3b8;"></span>
                                        Unsubscribed
                                    </span>
                                @endif
                            </td>
                            <td style="padding:14px 16px;">
                                <span class="text-muted" style="font-size:0.78rem;">
                                    {{ ucwords(str_replace('_', ' ', $subscriber->source ?: 'Website Footer')) }}
                                </span>
                            </td>
                            <td style="padding:14px 16px;">
                                <div style="color:var(--text-primary);font-size:0.82rem;">
                                    {{ $subscriber->subscribed_at ? $subscriber->subscribed_at->format('M d, Y') : $subscriber->created_at->format('M d, Y') }}
                                </div>
                                <div class="text-muted" style="font-size:0.72rem;">
                                    {{ ($subscriber->subscribed_at ?: $subscriber->created_at)->diffForHumans() }}
                                </div>
                            </td>
                            <td style="padding:14px 20px;text-align:right;">
                                <div style="display:inline-flex;align-items:center;gap:8px;">
                                    {{-- Status Toggle --}}
                                    <form action="{{ route('admin.subscribers.toggle-status', $subscriber) }}" method="POST" style="margin:0;">
                                        @csrf
                                        <button type="submit" class="action-icon-btn" title="{{ $subscriber->status === 'active' ? 'Mark as Unsubscribed' : 'Restore Active Status' }}">
                                            @if($subscriber->status === 'active')
                                                <span>Disable</span>
                                            @else
                                                <span style="color:#16a34a;">Enable</span>
                                            @endif
                                        </button>
                                    </form>

                                    {{-- Delete --}}
                                    <form action="{{ route('admin.subscribers.destroy', $subscriber) }}" method="POST" style="margin:0;" onsubmit="return confirm('Remove {{ $subscriber->email }} from the subscriber list?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-icon-btn btn-danger-soft" title="Delete subscriber">
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if($subscribers->hasPages())
                <div style="padding:16px 20px;border-top:1px solid #f4f4f5;">
                    {{ $subscribers->links() }}
                </div>
            @endif
        @else
            <div style="padding:60px 20px;text-align:center;">
                <div style="font-size:2.5rem;margin-bottom:12px;">✉️</div>
                <h3 style="font-size:1.1rem;margin:0 0 6px 0;">No subscribers found</h3>
                <p class="text-muted" style="font-size:0.85rem;margin:0;max-width:400px;margin-left:auto;margin-right:auto;">
                    @if(request('search'))
                        No email addresses matched "{{ request('search') }}".
                    @else
                        Visitors who subscribe to the Inner Circle via the website footer will appear here automatically.
                    @endif
                </p>
            </div>
        @endif
    </div>
</div>
@endsection
