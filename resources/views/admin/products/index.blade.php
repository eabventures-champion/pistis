@extends('layouts.admin')

@section('title', 'Products')

@section('content')
<div class="admin-header">
    <h1>Products</h1>
    <div class="d-flex gap-2">
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

<div class="table-wrapper">
    <table class="table">
        <thead>
            <tr>
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
                            <form action="{{ route('admin.shopify.sync-product', $product) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-secondary" title="Sync to Shopify">🔄</button>
                            </form>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this product?')">✕</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center" style="padding:40px;">
                        <div class="text-muted">No products found</div>
                        <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm mt-3">Add Your First Product</a>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{ $products->links() }}
@endsection



