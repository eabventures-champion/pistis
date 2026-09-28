@extends('layouts.admin')

@section('title', 'Add Product')

@section('content')
<div class="admin-header">
    <h1>Add New Product</h1>
    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">← Back to Products</a>
</div>

<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div style="display:grid;grid-template-columns:2fr 1fr;gap:24px;">
        <div>
            <div class="card mb-4">
                <div class="card-header"><h3 style="font-size:1rem;">Product Information</h3></div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label">Product Name *</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        @error('name') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="5">{{ old('description') }}</textarea>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Price ({{ $currency_symbol }}) *</label>
                            <input type="number" name="price" class="form-control" step="0.01" value="{{ old('price') }}" required>
                            @error('price') <div class="form-error">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Compare Price ({{ $currency_symbol }})</label>
                            <input type="number" name="compare_price" class="form-control" step="0.01" value="{{ old('compare_price') }}">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">SKU</label>
                            <input type="text" name="sku" class="form-control" value="{{ old('sku') }}" placeholder="Auto-generated if empty">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Stock Quantity *</label>
                            <input type="number" name="stock_quantity" class="form-control" value="{{ old('stock_quantity', 0) }}" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Weight (kg)</label>
                        <input type="number" name="weight" class="form-control" step="0.01" value="{{ old('weight') }}">
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header d-flex justify-between align-center">
                    <h3 style="font-size:1rem;margin:0;">Product Images</h3>
                    <span class="text-muted" style="font-size:0.8rem;" id="image-counter">0 / 10 images</span>
                </div>
                <div class="card-body">
                    <div class="image-upload-dropzone" id="image-dropzone" onclick="document.getElementById('product-images-input').click()">
                        <input type="file" id="product-images-input" name="images[]" multiple accept="image/*" style="display:none;" onchange="handleImageSelection(this)">
                        <div class="dropzone-content">
                            <div class="dropzone-icon">📷</div>
                            <div class="dropzone-text">
                                <strong>Click to upload</strong> or drag & drop images here
                            </div>
                            <span class="dropzone-hint">PNG, JPG, WEBP, GIF up to 10MB each (max 10 images)</span>
                        </div>
                    </div>
                    @error('images') <div class="form-error mt-2">{{ $message }}</div> @enderror
                    @error('images.*') <div class="form-error mt-2">{{ $message }}</div> @enderror

                    {{-- Live Image Preview Grid --}}
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
                            <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="draft" {{ old('status', 'draft') === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="archived" {{ old('status') === 'archived' ? 'selected' : '' }}>Archived</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-control">
                            <option value="">No Category</option>
                            @foreach($categories as $id => $name)
                                <option value="{{ $id }}" {{ old('category_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-check">
                            <input type="checkbox" name="featured" value="1" {{ old('featured') ? 'checked' : '' }}>
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
                            <input type="checkbox" name="shopify_sync_enabled" value="1" {{ old('shopify_sync_enabled', true) ? 'checked' : '' }}>
                            <span class="form-label" style="margin:0;">Enable Shopify sync</span>
                        </label>
                        <p class="text-muted mt-1" style="font-size:0.8rem;">When enabled, this product will be synced to your Shopify store.</p>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-lg w-100">Create Product</button>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
const imageInput = document.getElementById('product-images-input');
const dropzone = document.getElementById('image-dropzone');
const previewsContainer = document.getElementById('image-previews-container');
const imageCounter = document.getElementById('image-counter');

// Maintain file collection using DataTransfer
let selectedFiles = new DataTransfer();

function handleImageSelection(input) {
    if (!input.files || input.files.length === 0) return;
    
    // Add files up to max 10
    for (let i = 0; i < input.files.length; i++) {
        if (selectedFiles.items.length >= 10) {
            alert('You can upload a maximum of 10 images.');
            break;
        }
        selectedFiles.items.add(input.files[i]);
    }
    
    // Sync back to input
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
    imageCounter.textContent = `${count} / 10 images`;
    
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
                    <img src="${e.target.result}" alt="Preview">
                    ${idx === 0 ? '<span class="badge-cover">Main Cover</span>' : ''}
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
                    alert('You can upload a maximum of 10 images.');
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



