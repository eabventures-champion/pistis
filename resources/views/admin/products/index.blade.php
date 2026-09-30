@extends('layouts.admin')

@section('title', 'Products')

@section('content')
<div class="admin-header" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;margin-bottom:20px;">
    <div>
        <h1 style="margin:0 0 4px 0;">Products</h1>
        <div class="text-muted" style="font-size:0.85rem;">
            Total in catalog: <strong style="color:var(--text-primary);">{{ $totalProducts ?? $products->total() }}</strong>
        </div>
    </div>
    <div class="d-flex gap-2 align-center flex-wrap">
        @if(($totalProducts ?? $products->total()) > 0)
            <form action="{{ route('admin.products.destroy-all') }}" method="POST" style="display:inline;" onsubmit="return confirm('⚠️ DANGER: Are you sure you want to permanently delete ALL {{ $totalProducts ?? $products->total() }} products?\n\nThis action CANNOT be undone and will purge all products from your catalog.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm" style="display:inline-flex;align-items:center;gap:6px;background:#ef4444;color:#ffffff;border:none;padding:8px 14px;border-radius:4px;font-size:0.8rem;cursor:pointer;font-weight:500;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    </svg>
                    <span>Delete All Products ({{ $totalProducts ?? $products->total() }})</span>
                </button>
            </form>
        @endif

        <form action="{{ route('admin.shopify.sync-all') }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit" class="btn btn-secondary btn-sm" onclick="return confirm('Sync all products to Shopify?')">🔄 Sync All to Shopify</button>
        </form>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">+ Add Product</a>
    </div>
</div>

{{-- Filters --}}
<div class="d-flex gap-3 mb-4 flex-wrap">
    <form action="{{ route('admin.products.index') }}" method="GET" class="d-flex gap-2 flex-1">
        <input type="text" name="search" class="form-control" placeholder="Search products..." value="{{ request('search') }}" style="max-width:300px;">
        <select name="status" class="form-control" style="max-width:150px;" onchange="this.form.submit()">
            <option value="">All Status</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
            <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>Archived</option>
        </select>
        <select name="category" class="form-control" style="max-width:180px;" onchange="this.form.submit()">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-secondary btn-sm">Filter</button>
    </form>
</div>

{{-- Bulk Selection Action Toolbar --}}
<form id="bulk-action-form" action="{{ route('admin.products.bulk-action') }}" method="POST">
    @csrf
    <div id="bulk-bar" style="display:none;background:#f8fafc;border:1px solid #e2e8f0;border-radius:6px;padding:10px 16px;margin-bottom:16px;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;">
        <div style="font-size:0.85rem;font-weight:600;color:var(--text-primary);display:flex;align-items:center;gap:8px;">
            <span style="display:inline-block;width:8px;height:8px;background:#3b82f6;border-radius:50%;"></span>
            <span><strong id="selected-count">0</strong> product(s) selected</span>
        </div>
        <div class="d-flex gap-2 align-center flex-wrap">
            <button type="submit" name="action" value="activate" class="btn btn-sm btn-secondary" style="font-size:0.78rem;">✓ Mark Active</button>
            <button type="submit" name="action" value="draft" class="btn btn-sm btn-secondary" style="font-size:0.78rem;">✎ Mark Draft</button>
            <button type="submit" name="action" value="archive" class="btn btn-sm btn-secondary" style="font-size:0.78rem;">📦 Archive</button>
            <button type="submit" name="action" value="delete" class="btn btn-sm btn-danger" style="background:#ef4444;color:#fff;border:none;font-size:0.78rem;" onclick="return confirm('⚠️ Are you sure you want to permanently delete the selected products? This action cannot be undone.')">🗑️ Delete Selected</button>
        </div>
    </div>

    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th style="width:36px;text-align:center;">
                        <input type="checkbox" id="select-all" onclick="toggleSelectAll(this)" style="cursor:pointer;">
                    </th>
                    <th>Product</th>
                    <th>SKU</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th>Shopify</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr>
                        <td style="text-align:center;">
                            <input type="checkbox" name="selected_ids[]" value="{{ $product->id }}" class="product-checkbox" style="cursor:pointer;">
                        </td>
                        <td>
                            <div class="d-flex align-center gap-3">
                                <div style="width:40px;height:40px;border-radius:var(--radius-sm);overflow:hidden;background:var(--bg-glass);flex-shrink:0;">
                                    @if($product->primary_image)
                                        <img src="{{ asset('storage/' . $product->primary_image) }}" alt="" style="width:100%;height:100%;object-fit:cover;">
                                    @else
                                        <div class="no-image" style="font-size:1rem;">📦</div>
                                    @endif
                                </div>
                                <div>
                                    <div style="font-weight:600;color:var(--text-primary);">{{ $product->name }}</div>
                                    @if($product->featured) <span class="badge badge-primary" style="font-size:0.6rem;">Featured</span> @endif
                                </div>
                            </div>
                        </td>
                        <td><code style="font-size:0.75rem;">{{ $product->sku }}</code></td>
                        <td>{{ $product->category?->name ?? '—' }}</td>
                        <td style="font-weight:600;color:var(--text-primary);">{{ $product->formatted_price }}</td>
                        <td>
                            <span class="{{ $product->stock_quantity > 0 ? 'text-success' : 'text-danger' }}">
                                {{ $product->stock_quantity }}
                            </span>
                        </td>
                        <td><span class="badge badge-{{ $product->status === 'active' ? 'success' : ($product->status === 'draft' ? 'warning' : 'secondary') }}">{{ $product->status }}</span></td>
                        <td>
                            @if($product->shopify_product_id)
                                <span class="badge badge-success">✓ Synced</span>
                            @elseif($product->shopify_sync_enabled)
                                <span class="badge badge-warning">Pending</span>
                            @else
                                <span class="badge badge-secondary">Disabled</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-secondary">Edit</a>
                                <button type="submit" form="shopify-sync-{{ $product->id }}" class="btn btn-sm btn-secondary" title="Sync to Shopify">🔄</button>
                                <button type="submit" form="product-delete-{{ $product->id }}" class="btn btn-sm btn-danger" onclick="return confirm('Delete this product permanently?')">✕</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center" style="padding:40px;">
                            <div class="text-muted">No products found</div>
                            <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm mt-3">Add Your First Product</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</form>

{{-- Hidden individual forms for Shopify sync & individual delete to prevent nested forms --}}
@foreach($products as $product)
    <form id="shopify-sync-{{ $product->id }}" action="{{ route('admin.shopify.sync-product', $product) }}" method="POST" style="display:none;">
        @csrf
    </form>
    <form id="product-delete-{{ $product->id }}" action="{{ route('admin.products.destroy', $product) }}" method="POST" style="display:none;">
        @csrf
        @method('DELETE')
    </form>
@endforeach

{{ $products->links() }}

@push('scripts')
<script>
    function toggleSelectAll(master) {
        document.querySelectorAll('.product-checkbox').forEach(cb => {
            cb.checked = master.checked;
        });
        updateBulkBar();
    }

    document.querySelectorAll('.product-checkbox').forEach(cb => {
        cb.addEventListener('change', function() {
            const allCheckboxes = document.querySelectorAll('.product-checkbox');
            const master = document.getElementById('select-all');
            if (master) {
                master.checked = allCheckboxes.length > 0 && Array.from(allCheckboxes).every(c => c.checked);
            }
            updateBulkBar();
        });
    });

    function updateBulkBar() {
        const checked = document.querySelectorAll('.product-checkbox:checked').length;
        const bar = document.getElementById('bulk-bar');
        const count = document.getElementById('selected-count');
        if (bar && count) {
            count.textContent = checked;
            bar.style.display = checked > 0 ? 'flex' : 'none';
        }
    }
</script>
@endpush
@endsection



