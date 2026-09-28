@extends('layouts.admin')

@section('title', 'Edit: ' . $product->name)

@section('content')
<div class="admin-header">
    <h1>Edit Product</h1>
    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">← Back to Products</a>
</div>

<form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div style="display:grid;grid-template-columns:2fr 1fr;gap:24px;">
        <div>
            <div class="card mb-4">
                <div class="card-header"><h3 style="font-size:1rem;">Product Information</h3></div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label">Product Name *</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
                        @error('name') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="5">{{ old('description', $product->description) }}</textarea>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Price ({{ $currency_symbol }}) *</label>
                            <input type="number" name="price" class="form-control" step="0.01" value="{{ old('price', $product->price) }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Compare Price ({{ $currency_symbol }})</label>
                            <input type="number" name="compare_price" class="form-control" step="0.01" value="{{ old('compare_price', $product->compare_price) }}">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">SKU</label>
                            <input type="text" name="sku" class="form-control" value="{{ old('sku', $product->sku) }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Stock Quantity *</label>
                            <input type="number" name="stock_quantity" class="form-control" value="{{ old('stock_quantity', $product->stock_quantity) }}" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Weight (kg)</label>
                        <input type="number" name="weight" class="form-control" step="0.01" value="{{ old('weight', $product->weight) }}">
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header d-flex justify-between align-center">
                    <h3 style="font-size:1rem;margin:0;">Product Images</h3>
                    <span class="text-muted" style="font-size:0.8rem;" id="existing-image-counter">
                        {{ $product->images ? count($product->images) : 0 }} existing
                    </span>
                </div>
                <div class="card-body">
                    {{-- Existing Images Section --}}
                    @if($product->images && count($product->images) > 0)
                        <label class="form-label" style="font-size:0.8rem;text-transform:uppercase;letter-spacing:0.05em;color:var(--text-muted);margin-bottom:8px;">
                            Current Images (Click ✕ to mark for removal)
                        </label>
                        <div class="existing-images-grid mb-3">
                            @foreach($product->images as $i => $image)
                                <div class="existing-image-item" id="existing-img-{{ $i }}">
                                    <img src="{{ \App\Models\Product::formatImageUrl($image) }}" alt="Product Image {{ $i + 1 }}">
                                    @if($i === 0)
                                        <span class="badge-cover">Main Cover</span>
                                    @endif
                                    <label class="remove-toggle" title="Remove image on save">
                                        <input type="checkbox" name="remove_images[]" value="{{ $i }}" onchange="toggleImageRemoval(this, {{ $i }})">
                                        <span class="toggle-icon">✕</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- New Images Upload Dropzone --}}
                    <label class="form-label" style="font-size:0.8rem;text-transform:uppercase;letter-spacing:0.05em;color:var(--text-muted);margin-bottom:8px;">
                        Add More Images
                    </label>
                    <div class="image-upload-dropzone" id="image-dropzone" onclick="document.getElementById('product-images-input').click()">
                        <input type="file" id="product-images-input" name="images[]" multiple accept="image/*" style="display:none;" onchange="handleImageSelection(this)">
                        <div class="dropzone-content">
                            <div class="dropzone-icon">📷</div>
                            <div class="dropzone-text">
                                <strong>Click to upload</strong> or drag & drop new images
                            </div>
                            <span class="dropzone-hint">PNG, JPG, WEBP, GIF up to 10MB each</span>
                        </div>
                    </div>
                    @error('images') <div class="form-error mt-2">{{ $message }}</div> @enderror
                    @error('images.*') <div class="form-error mt-2">{{ $message }}</div> @enderror

                    {{-- Live Previews for Newly Added Images --}}
                    <div id="image-previews-container" class="image-previews-grid mt-3" style="display:none;"></div>
                </div>
            </div>
        </div>

        <div>
            <div class="card mb-4">
                <div class="card-header"><h3 style="font-size:1rem;">Organization</h3></div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control">
                            <option value="active" {{ $product->status === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="draft" {{ $product->status === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="archived" {{ $product->status === 'archived' ? 'selected' : '' }}>Archived</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-control">
                            <option value="">No Category</option>
                            @foreach($categories as $id => $name)
                                <option value="{{ $id }}" {{ (old('category_id', $product->category_id) == $id) ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-check">
                            <input type="checkbox" name="featured" value="1" {{ $product->featured ? 'checked' : '' }}>
                            <span class="form-label" style="margin:0;">Featured Product</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header"><h3 style="font-size:1rem;">Shopify Sync</h3></div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-check">
                            <input type="checkbox" name="shopify_sync_enabled" value="1" {{ $product->shopify_sync_enabled ? 'checked' : '' }}>
                            <span class="form-label" style="margin:0;">Enable Shopify sync</span>
                        </label>
                    </div>
                    @if($product->shopify_product_id)
                        <div class="mt-2">
                            <span class="badge badge-success">✓ Synced</span>
                            <p class="text-muted mt-1" style="font-size:0.75rem;">Shopify ID: {{ $product->shopify_product_id }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-lg w-100 mb-2">Update Product</button>
            <button type="button" class="btn btn-secondary w-100" onclick="document.getElementById('sync-shopify-form').submit();">🔄 Sync to Shopify Now</button>
        </div>
    </div>
</form>

{{-- Separate Shopify Sync Form outside the main update form --}}
<form id="sync-shopify-form" action="{{ route('admin.shopify.sync-product', $product) }}" method="POST" style="display:none;">
    @csrf
</form>
@endsection

@push('scripts')
<script>
const imageInput = document.getElementById('product-images-input');
const dropzone = document.getElementById('image-dropzone');
const previewsContainer = document.getElementById('image-previews-container');

let selectedFiles = new DataTransfer();

function handleImageSelection(input) {
    if (!input.files || input.files.length === 0) return;
    
    for (let i = 0; i < input.files.length; i++) {
        if (selectedFiles.items.length >= 10) {
            alert('You can upload up to 10 additional images at once.');
            break;
        }
        selectedFiles.items.add(input.files[i]);
    }
    
    input.files = selectedFiles.files;
    renderPreviews();
}

function removeSelectedFile(index) {
    const newDT = new DataTransfer();
    for (let i = 0; i < selectedFiles.files.length; i++) {
        if (i !== index) {
            newDT.items.add(selectedFiles.files[i]);
        }
    }
    selectedFiles = newDT;
    imageInput.files = selectedFiles.files;
    renderPreviews();
}

function renderPreviews() {
    previewsContainer.innerHTML = '';
    const count = selectedFiles.files.length;
    
    if (count === 0) {
        previewsContainer.style.display = 'none';
        return;
    }
    
    previewsContainer.style.display = 'grid';
    
    Array.from(selectedFiles.files).forEach((file, idx) => {
        const reader = new FileReader();
        const card = document.createElement('div');
        card.className = 'image-preview-card animate-in';
        
        reader.onload = function(e) {
            card.innerHTML = `
                <div class="preview-img-wrapper">
                    <img src="${e.target.result}" alt="New Preview">
                    <span class="badge-new">New</span>
                    <button type="button" class="preview-remove-btn" title="Remove Image" onclick="removeSelectedFile(${idx})">✕</button>
                </div>
                <div class="preview-info">
                    <span class="preview-filename" title="${file.name}">${file.name}</span>
                    <span class="preview-filesize">${(file.size / 1024 / 1024).toFixed(2)} MB</span>
                </div>
            `;
        };
        reader.readAsDataURL(file);
        previewsContainer.appendChild(card);
    });
}

function toggleImageRemoval(checkbox, index) {
    const container = document.getElementById('existing-img-' + index);
    if (checkbox.checked) {
        container.classList.add('marked-for-removal');
    } else {
        container.classList.remove('marked-for-removal');
    }
}

// Drag and drop event listeners
['dragenter', 'dragover'].forEach(eventName => {
    dropzone.addEventListener(eventName, (e) => {
        e.preventDefault();
        e.stopPropagation();
        dropzone.classList.add('dragover');
    });
});

['dragleave', 'drop'].forEach(eventName => {
    dropzone.addEventListener(eventName, (e) => {
        e.preventDefault();
        e.stopPropagation();
        dropzone.classList.remove('dragover');
    });
});

dropzone.addEventListener('drop', (e) => {
    const dt = e.dataTransfer;
    if (dt && dt.files && dt.files.length > 0) {
        for (let i = 0; i < dt.files.length; i++) {
            if (dt.files[i].type.startsWith('image/')) {
                if (selectedFiles.items.length >= 10) {
                    alert('You can upload up to 10 additional images at once.');
                    break;
                }
                selectedFiles.items.add(dt.files[i]);
            }
        }
        imageInput.files = selectedFiles.files;
        renderPreviews();
    }
});
</script>
@endpush



