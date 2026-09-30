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

            {{-- Color Variations --}}
            <div class="card mb-4">
                <div class="card-header d-flex justify-between align-center">
                    <div>
                        <h3 style="font-size:1rem;margin:0;">Piece Colors & Palette</h3>
                        <p class="text-muted" style="font-size:0.75rem;margin:4px 0 0;">Select or add available colors for this piece. Default options include <strong>Grey</strong> and <strong>Black</strong>.</p>
                    </div>
                </div>
                <div class="card-body">
                    {{-- Active Selected Colors --}}
                    <div style="margin-bottom:16px;">
                        <label class="form-label" style="font-size:0.75rem;text-transform:uppercase;letter-spacing:0.1em;color:var(--text-secondary);">Active Selected Colors</label>
                        <div id="selected-colors-container" style="display:flex;flex-wrap:wrap;gap:10px;min-height:42px;align-items:center;background:#fafafa;padding:12px;border:1px solid #e5e5e5;border-radius:4px;">
                            {{-- Populated by JavaScript --}}
                        </div>
                    </div>

                    {{-- Quick Palette Presets --}}
                    <div style="margin-bottom:18px;">
                        <span style="font-size:0.72rem;letter-spacing:0.1em;text-transform:uppercase;color:#737373;display:block;margin-bottom:8px;">Quick Color Presets</span>
                        <div style="display:flex;flex-wrap:wrap;gap:8px;">
                            <button type="button" class="btn btn-secondary btn-sm" onclick="togglePresetColor('Grey', '#737373')" style="font-size:0.75rem;display:inline-flex;align-items:center;gap:6px;border-radius:20px;padding:4px 12px;">
                                <span style="width:12px;height:12px;border-radius:50%;background:#737373;display:inline-block;border:1px solid rgba(0,0,0,0.2);"></span> Grey
                            </button>
                            <button type="button" class="btn btn-secondary btn-sm" onclick="togglePresetColor('Black', '#000000')" style="font-size:0.75rem;display:inline-flex;align-items:center;gap:6px;border-radius:20px;padding:4px 12px;">
                                <span style="width:12px;height:12px;border-radius:50%;background:#000000;display:inline-block;border:1px solid rgba(255,255,255,0.3);"></span> Black
                            </button>
                            <button type="button" class="btn btn-secondary btn-sm" onclick="togglePresetColor('Off-White', '#F5F5F0')" style="font-size:0.75rem;display:inline-flex;align-items:center;gap:6px;border-radius:20px;padding:4px 12px;">
                                <span style="width:12px;height:12px;border-radius:50%;background:#F5F5F0;display:inline-block;border:1px solid #d4d4d8;"></span> Off-White
                            </button>
                            <button type="button" class="btn btn-secondary btn-sm" onclick="togglePresetColor('Charcoal', '#262626')" style="font-size:0.75rem;display:inline-flex;align-items:center;gap:6px;border-radius:20px;padding:4px 12px;">
                                <span style="width:12px;height:12px;border-radius:50%;background:#262626;display:inline-block;"></span> Charcoal
                            </button>
                            <button type="button" class="btn btn-secondary btn-sm" onclick="togglePresetColor('Navy', '#0F172A')" style="font-size:0.75rem;display:inline-flex;align-items:center;gap:6px;border-radius:20px;padding:4px 12px;">
                                <span style="width:12px;height:12px;border-radius:50%;background:#0F172A;display:inline-block;"></span> Navy
                            </button>
                            <button type="button" class="btn btn-secondary btn-sm" onclick="togglePresetColor('Olive', '#3D4A3E')" style="font-size:0.75rem;display:inline-flex;align-items:center;gap:6px;border-radius:20px;padding:4px 12px;">
                                <span style="width:12px;height:12px;border-radius:50%;background:#3D4A3E;display:inline-block;"></span> Olive
                            </button>
                            <button type="button" class="btn btn-secondary btn-sm" onclick="togglePresetColor('Mocha', '#5C4033')" style="font-size:0.75rem;display:inline-flex;align-items:center;gap:6px;border-radius:20px;padding:4px 12px;">
                                <span style="width:12px;height:12px;border-radius:50%;background:#5C4033;display:inline-block;"></span> Mocha
                            </button>
                            <button type="button" class="btn btn-secondary btn-sm" onclick="togglePresetColor('Sand', '#C2B280')" style="font-size:0.75rem;display:inline-flex;align-items:center;gap:6px;border-radius:20px;padding:4px 12px;">
                                <span style="width:12px;height:12px;border-radius:50%;background:#C2B280;display:inline-block;"></span> Sand
                            </button>
                        </div>
                    </div>

                    {{-- Dynamic Add Custom Color --}}
                    <div style="background:#ffffff;padding:14px;border:1px dashed #d1d5db;border-radius:6px;">
                        <span style="font-size:0.72rem;letter-spacing:0.1em;text-transform:uppercase;color:#374151;display:block;margin-bottom:8px;font-weight:600;">+ Dynamic Custom Color</span>
                        <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
                            <input type="text" id="custom-color-name" class="form-control" placeholder="Enter color name (e.g. Heather Grey, Sage, Emerald...)" style="flex:1;min-width:200px;font-size:0.85rem;" onkeydown="if(event.key==='Enter'){event.preventDefault();addCustomColor();}">
                            <div style="display:flex;align-items:center;gap:8px;background:#f9fafb;border:1px solid #d1d5db;padding:4px 10px;border-radius:4px;">
                                <label for="custom-color-picker" style="font-size:0.7rem;text-transform:uppercase;color:#6b7280;margin:0;cursor:pointer;">Swatch</label>
                                <input type="color" id="custom-color-picker" value="#808080" style="width:28px;height:28px;border:none;background:none;cursor:pointer;padding:0;">
                                <span style="font-size:0.75rem;color:#374151;font-family:monospace;font-weight:600;" id="custom-color-hex">#808080</span>
                            </div>
                            <button type="button" class="btn btn-primary btn-sm" onclick="addCustomColor()" style="font-size:0.8rem;padding:7px 18px;">ADD COLOR</button>
                        </div>
                    </div>

                    <input type="hidden" name="colors_json" id="colors-json-input" value="[]">
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

// ─── Color Variations Logic ──────────────────────────────────────────
let productColors = @json(old('colors_json') ? json_decode(old('colors_json'), true) : ($product->colors_list ?? []));

function updateColorsUI() {
    const container = document.getElementById('selected-colors-container');
    const input = document.getElementById('colors-json-input');
    if (!container || !input) return;

    input.value = JSON.stringify(productColors);

    if (productColors.length === 0) {
        container.innerHTML = '<span class="text-muted" style="font-size:0.8rem;font-style:italic;">No colors selected yet. Click a quick preset or add a custom color above.</span>';
        return;
    }

    container.innerHTML = productColors.map((c, idx) => `
        <div class="color-badge-chip" style="display:inline-flex;align-items:center;gap:8px;background:#ffffff;border:1px solid #171717;border-radius:24px;padding:6px 14px;box-shadow:0 1px 3px rgba(0,0,0,0.06);">
            <span style="width:16px;height:16px;border-radius:50%;background:${c.code};display:inline-block;border:1px solid rgba(0,0,0,0.2);flex-shrink:0;"></span>
            <span style="font-size:0.82rem;font-weight:600;letter-spacing:0.03em;color:#000000;">${c.name}</span>
            <button type="button" onclick="removeColor(${idx})" style="background:none;border:none;color:#9ca3af;cursor:pointer;font-size:0.9rem;padding:0 2px;line-height:1;margin-left:4px;transition:color 0.15s;" onmouseover="this.style.color='#ef4444'" onmouseout="this.style.color='#9ca3af'" title="Remove color">✕</button>
        </div>
    `).join('');
}

function togglePresetColor(name, code) {
    const index = productColors.findIndex(c => c.name.toLowerCase() === name.toLowerCase());
    if (index > -1) {
        productColors.splice(index, 1);
    } else {
        productColors.push({ name: name, code: code });
    }
    updateColorsUI();
}

function addCustomColor() {
    const nameInput = document.getElementById('custom-color-name');
    const picker = document.getElementById('custom-color-picker');
    if (!nameInput) return;

    const name = nameInput.value.trim();
    if (!name) {
        nameInput.focus();
        return;
    }

    const code = picker ? picker.value : '#808080';
    if (!productColors.some(c => c.name.toLowerCase() === name.toLowerCase())) {
        productColors.push({ name: name, code: code });
        updateColorsUI();
    }
    nameInput.value = '';
    nameInput.focus();
}

function removeColor(idx) {
    productColors.splice(idx, 1);
    updateColorsUI();
}

document.addEventListener('DOMContentLoaded', () => {
    const picker = document.getElementById('custom-color-picker');
    const hexLabel = document.getElementById('custom-color-hex');
    if (picker && hexLabel) {
        picker.addEventListener('input', (e) => {
            hexLabel.textContent = e.target.value.toUpperCase();
        });
    }
    updateColorsUI();
});
</script>
@endpush



