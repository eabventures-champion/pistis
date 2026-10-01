@extends('layouts.admin')

@section('title', 'Size Guides')

@push('styles')
<style>
    .action-icon-btn {
        width: 32px;
        height: 32px;
        padding: 0;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--border-color);
        background: #ffffff;
        color: var(--text-primary);
        cursor: pointer;
        transition: all 0.18s ease;
        flex-shrink: 0;
        text-decoration: none;
    }
    .action-icon-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
    }
    .edit-icon-btn:hover {
        background: #18181b;
        border-color: #18181b;
        color: #ffffff;
    }
    .edit-icon-btn:hover svg {
        stroke: #ffffff;
    }
    .delete-icon-btn:hover {
        background: #fef2f2;
        border-color: #fca5a5;
        color: #dc2626;
    }
    .delete-icon-btn:hover svg {
        stroke: #dc2626;
    }
    .status-badge-btn {
        background: none;
        border: none;
        padding: 0;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
    }
    .category-chip {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        background: #f4f4f5;
        color: #18181b;
        letter-spacing: 0.04em;
    }
    .category-chip.chip-men {
        background: #18181b;
        color: #ffffff;
    }
    .category-chip.chip-women {
        background: #27272a;
        color: #ffffff;
    }
</style>
@endpush

@section('content')
<div class="admin-header">
    <div>
        <h1>Size Guides</h1>
        <p class="text-muted" style="font-size:0.875rem;margin-top:4px;">
            Set up and manage dynamic measurement charts and fit specifications for Men, Women, and unisex apparel.
        </p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.size-guides.create') }}" class="btn btn-primary">+ Add New Size Guide</a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success mb-4" style="background:#f0fdf4;border:1px solid #bbf7d0;color:#166534;padding:12px 18px;border-radius:6px;font-size:0.875rem;">
        ✓ {{ session('success') }}
    </div>
@endif

<div class="card mb-4">
    <div class="card-header d-flex justify-between align-center">
        <div>
            <h3 style="font-size:1rem;margin:0;">Active Fit & Sizing Profiles</h3>
            <span class="text-muted" style="font-size:0.8rem;">Guides are automatically resolved by category (e.g. Men vs. Women) or product assignment</span>
        </div>
        <span class="badge badge-secondary">{{ $sizeGuides->count() }} Profiles</span>
    </div>

    <div class="card-body" style="padding:0;">
        @if($sizeGuides->count() > 0)
            <div class="table-responsive">
                <table class="table" style="width:100%;margin-bottom:0;">
                    <thead>
                        <tr>
                            <th style="padding:14px 20px;">Guide Title & Fit</th>
                            <th>Category</th>
                            <th>Sizes Defined</th>
                            <th>Measurements (Rows)</th>
                            <th>Unit</th>
                            <th>Status</th>
                            <th style="text-align:right;padding-right:20px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sizeGuides as $guide)
                            <tr style="border-bottom:1px solid var(--border-color);">
                                <td style="padding:16px 20px;">
                                    <div style="font-weight:600;color:var(--text-primary);font-size:0.95rem;">
                                        {{ $guide->name }}
                                    </div>
                                    <div class="text-muted" style="font-size:0.8rem;margin-top:3px;display:flex;align-items:center;gap:6px;">
                                        <span class="badge" style="background:#f4f4f5;color:#52525b;font-size:0.7rem;padding:2px 8px;border-radius:4px;">
                                            {{ $guide->fit_type ?: 'Standard Fit' }}
                                        </span>
                                        @if($guide->description)
                                            <span style="max-width:280px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;" title="{{ $guide->description }}">
                                                · {{ $guide->description }}
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if($guide->category)
                                        <span class="category-chip {{ strtolower($guide->category->slug) === 'men' ? 'chip-men' : (strtolower($guide->category->slug) === 'women' ? 'chip-women' : '') }}">
                                            🏷️ {{ $guide->category->name }}
                                        </span>
                                    @else
                                        <span class="badge badge-secondary" style="font-size:0.75rem;">Global / All</span>
                                    @endif
                                </td>
                                <td>
                                    <div style="display:flex;flex-wrap:wrap;gap:4px;max-width:240px;">
                                        @foreach($guide->sizes ?? [] as $sz)
                                            <span style="display:inline-block;padding:2px 6px;background:#f4f4f5;border:1px solid #e4e4e7;border-radius:3px;font-size:0.7rem;font-weight:600;">
                                                {{ $sz }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                                <td>
                                    <div style="font-size:0.85rem;font-weight:600;color:var(--text-primary);">
                                        {{ count($guide->measurements ?? []) }} Metrics
                                    </div>
                                    <div class="text-muted" style="font-size:0.75rem;margin-top:2px;">
                                        {{ implode(', ', array_slice(array_column($guide->measurements ?? [], 'name'), 0, 3)) }}
                                        @if(count($guide->measurements ?? []) > 3)
                                            ...
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span class="badge" style="background:#e0f2fe;color:#0369a1;font-weight:700;font-size:0.75rem;text-transform:uppercase;">
                                        {{ $guide->default_unit ?? 'cm' }}
                                    </span>
                                </td>
                                <td>
                                    <form action="{{ route('admin.size-guides.toggle-status', $guide) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="status-badge-btn" title="Click to toggle status">
                                            @if($guide->is_active)
                                                <span class="badge badge-success" style="font-size:0.75rem;">Active</span>
                                            @else
                                                <span class="badge badge-secondary" style="font-size:0.75rem;">Inactive</span>
                                            @endif
                                        </button>
                                    </form>
                                </td>
                                <td style="text-align:right;padding-right:20px;">
                                    <div class="d-flex gap-2 justify-end">
                                        <a href="{{ route('admin.size-guides.edit', $guide) }}" class="action-icon-btn edit-icon-btn" title="Edit Size Guide">
                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                            </svg>
                                        </a>
                                        <form action="{{ route('admin.size-guides.destroy', $guide) }}" method="POST" onsubmit="return confirm('Delete this size guide? Products linked to it will fall back to their category size profile.');" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-icon-btn delete-icon-btn" title="Delete Size Guide">
                                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
            </div>
        @else
            <div style="text-align:center;padding:60px 20px;color:#737373;">
                <div style="font-size:3rem;margin-bottom:12px;">📏</div>
                <h3 style="font-size:1.1rem;color:#171717;margin-bottom:6px;">No Size Guides Configured</h3>
                <p style="font-size:0.875rem;max-width:400px;margin:0 auto 18px;">
                    Create dynamic size guides for your Men and Women categories so shoppers can view accurate measurements.
                </p>
                <a href="{{ route('admin.size-guides.create') }}" class="btn btn-primary">+ Create First Size Guide</a>
            </div>
        @endif
    </div>
</div>
@endsection
