@extends('layouts.admin')

@section('title', 'Categories')

@section('content')
<div class="admin-header">
    <h1>Categories</h1>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;">
    {{-- Add Category Form --}}
    <div class="card">
        <div class="card-header"><h3 style="font-size:1rem;">Add Category</h3></div>
        <div class="card-body">
            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Name *</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Parent Category</label>
                    <select name="parent_id" class="form-control">
                        <option value="">None (Top Level)</option>
                        @foreach($categories->whereNull('parent_id') as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-check">
                        <input type="checkbox" name="is_active" value="1" checked>
                        <span class="form-label" style="margin:0;">Active</span>
                    </label>
                </div>
                <button type="submit" class="btn btn-primary w-100">Add Category</button>
            </form>
        </div>
    </div>

    {{-- Category List --}}
    <div class="card">
        <div class="card-header"><h3 style="font-size:1rem;">All Categories</h3></div>
        <div class="card-body" style="padding:0;">
            @forelse($categories as $category)
                <div class="d-flex justify-between align-center" style="padding:12px 20px;border-bottom:1px solid var(--border-color);">
                    <div>
                        <div style="font-weight:600;color:var(--text-primary);">
                            @if($category->parent)
                                <span class="text-muted">{{ $category->parent->name }} → </span>
                            @endif
                            {{ $category->name }}
                        </div>
                        <div class="text-muted" style="font-size:0.75rem;">{{ $category->products_count }} products</div>
                    </div>
                    <div class="d-flex gap-2 align-center">
                        <span class="badge badge-{{ $category->is_active ? 'success' : 'secondary' }}">{{ $category->is_active ? 'Active' : 'Inactive' }}</span>
                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-secondary" style="color:var(--danger);" onclick="return confirm('Delete this category?')">✕</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="empty-state" style="padding:24px;"><p class="text-muted">No categories yet</p></div>
            @endforelse
        </div>
    </div>
</div>
@endsection



