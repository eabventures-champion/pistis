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

            {{-- Color Variations & Per-Color Image Galleries --}}
            <div class="card mb-4">
                <div class="card-header d-flex justify-between align-center">
                    <div>
                        <h3 style="font-size:1rem;margin:0;">Piece Colors & Palette</h3>
                        <p class="text-muted" style="font-size:0.75rem;margin:4px 0 0;">Manage available colors for this piece. Default options include <strong>Grey</strong> and <strong>Black</strong>.</p>
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

                    {{-- Dynamic Add Custom Color --}}
                    <div style="background:#ffffff;padding:14px;border:1px dashed #d1d5db;border-radius:6px;margin-bottom:24px;">
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

                    {{-- Color-Specific Image Galleries Manager --}}
                    <div id="color-galleries-manager-section" style="border-top:1px solid #e5e5e5;padding-top:20px;">
                        <div style="display:flex;justify-content:space-between;align-items:baseline;margin-bottom:8px;flex-wrap:wrap;gap:8px;">
                            <label class="form-label" style="font-size:0.8rem;text-transform:uppercase;letter-spacing:0.1em;font-weight:700;color:#000000;margin:0;">
                                Color-Specific Image Galleries
                            </label>
                            <span class="text-muted" style="font-size:0.75rem;">1st Image = <strong>★ Main Cover</strong> · Remaining = <strong>Additional Items</strong></span>
                        </div>
                        <p class="text-muted" style="font-size:0.75rem;margin:0 0 16px;">
                            Select a color tab below to manage its piece images. When a customer selects that color on the shop page, the <strong>Main Cover</strong> fills the main stage and the <strong>Additional Items</strong> fill the vertical thumbnail strip.
                        </p>

                        {{-- Color Tabs --}}
                        <div id="color-gallery-tabs" style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:16px;">
                            {{-- Populated by JavaScript --}}
                        </div>

                        {{-- Active Color Gallery Upload Box --}}
                        <div id="active-color-gallery-panel" style="background:#fafafa;border:1px solid #e5e5e5;border-radius:6px;padding:18px;">
                            {{-- Populated by JavaScript --}}
                        </div>
                    </div>

                    <input type="hidden" name="colors_json" id="colors-json-input" value="[]">
                    {{-- Hidden file inputs container for each color --}}
                    <div id="color-file-inputs-container" style="display:none;"></div>
                </div>
            </div>

            {{-- Piece Sizes & Fit Card (Dynamic) --}}
            <div class="card mb-4">
                <div class="card-header d-flex justify-between align-center">
                    <div>
                        <h3 style="font-size:1rem;margin:0;">Piece Sizes & Dimensions</h3>
                        <p class="text-muted" style="font-size:0.75rem;margin:4px 0 0;">Manage available sizes for this piece. Customers will select a size before adding to bag.</p>
                    </div>
                </div>
                <div class="card-body">
                    {{-- Active Selected Sizes --}}
                    <div style="margin-bottom:16px;">
                        <label class="form-label" style="font-size:0.75rem;text-transform:uppercase;letter-spacing:0.1em;color:var(--text-secondary);">Active Available Sizes</label>
                        <div id="selected-sizes-container" style="display:flex;flex-wrap:wrap;gap:8px;min-height:42px;align-items:center;background:#fafafa;padding:12px;border:1px solid #e5e5e5;border-radius:4px;">
                            {{-- Populated by JavaScript --}}
                        </div>
                    </div>

                    {{-- Dynamic Custom Size Input --}}
                    <div style="background:#ffffff;padding:14px;border:1px dashed #d1d5db;border-radius:6px;">
                        <span style="font-size:0.72rem;letter-spacing:0.1em;text-transform:uppercase;color:#374151;display:block;margin-bottom:8px;font-weight:600;">+ Dynamic Custom Size</span>
                        <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
                            <input type="text" id="custom-size-name" class="form-control" placeholder="Enter custom size (e.g. S / 38R, Oversized, Tailored Fit...)" style="flex:1;min-width:220px;font-size:0.85rem;" onkeydown="if(event.key==='Enter'){event.preventDefault();addCustomSize();}">
                            <button type="button" class="btn btn-primary btn-sm" onclick="addCustomSize()" style="font-size:0.8rem;padding:7px 18px;">ADD SIZE</button>
                        </div>
                    </div>

                    <input type="hidden" name="sizes_json" id="sizes-json-input" value="[]">
                </div>
            </div>

            <div class="card">
                <div class="card-header d-flex justify-between align-center">
                    <div>
                        <h3 style="font-size:1rem;margin:0;">Product Images</h3>
                        <p class="text-muted" style="font-size:0.75rem;margin:2px 0 0;">General product photos. You can also assign any photo directly to an active color gallery.</p>
                    </div>
                    <span class="text-muted" style="font-size:0.8rem;" id="existing-image-counter">
                        {{ $product->images ? count($product->images) : 0 }} existing
                    </span>
                </div>
                <div class="card-body">
                    {{-- Existing Images Section --}}
                    @if($product->images && count($product->images) > 0)
                        <label class="form-label" style="font-size:0.8rem;text-transform:uppercase;letter-spacing:0.05em;color:var(--text-muted);margin-bottom:8px;">
                            Current Images (Click ✕ to mark for removal, or "+ To Active Color" to assign)
                        </label>
                        <div class="existing-images-grid mb-3">
                            @foreach($product->images as $i => $image)
                                <div class="existing-image-item" id="existing-img-{{ $i }}" style="position:relative;padding-bottom:28px;">
                                    <img src="{{ \App\Models\Product::formatImageUrl($image) }}" alt="Product Image {{ $i + 1 }}">
                                    @if($i === 0)
                                        <span class="badge-cover">Main Cover</span>
                                    @endif
                                    <label class="remove-toggle" title="Remove image on save">
                                        <input type="checkbox" name="remove_images[]" value="{{ $i }}" onchange="toggleImageRemoval(this, {{ $i }})">
                                        <span class="toggle-icon">✕</span>
                                    </label>
                                    <button type="button" onclick="assignExistingImageToActiveColor('{{ addslashes($image) }}')" style="position:absolute;bottom:0;left:0;right:0;font-size:0.62rem;padding:4px 2px;background:#ffffff;border:none;border-top:1px solid #e5e5e5;color:#000000;font-weight:600;text-transform:uppercase;cursor:pointer;letter-spacing:0.04em;" onmouseover="this.style.background='#000000';this.style.color='#ffffff';" onmouseout="this.style.background='#ffffff';this.style.color='#000000';" title="Add to currently selected color gallery">
                                        + To Color
                                    </button>
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
@php
    $initialColors = old('colors_json') 
        ? json_decode(old('colors_json'), true) 
        : ($product->colors_list ?? []);
    if (!is_array($initialColors) || empty($initialColors)) {
        $initialColors = [
            ['name' => 'Grey', 'code' => '#737373', 'existing_images' => []],
            ['name' => 'Black', 'code' => '#000000', 'existing_images' => []],
        ];
    }

    $initialSizes = old('sizes_json') 
        ? json_decode(old('sizes_json'), true) 
        : (!empty($product->sizes_list) ? $product->sizes_list : ['XS', 'S', 'M', 'L', 'XL']);
    if (!is_array($initialSizes)) {
        $initialSizes = ['XS', 'S', 'M', 'L', 'XL'];
    }
@endphp
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

// ─── Color Variations & Per-Color Image Galleries Logic ───────────────
let productColors = {!! json_encode($initialColors) !!};
if (!Array.isArray(productColors) || productColors.length === 0) {
    productColors = [
        { name: 'Grey', code: '#737373', existing_images: [] },
        { name: 'Black', code: '#000000', existing_images: [] }
    ];
}
// Normalize existing_images array on each color
productColors.forEach(c => {
    if (!c.existing_images) {
        c.existing_images = Array.isArray(c.images) ? [...c.images] : [];
    }
});

let activeColorIndex = 0;
// Maps colorIndex -> DataTransfer of newly selected files
const colorDataTransfers = {};

function initColorInputsContainer() {
    const container = document.getElementById('color-file-inputs-container');
    if (!container) return;
    container.innerHTML = '';
    productColors.forEach((c, idx) => {
        if (!colorDataTransfers[idx]) {
            colorDataTransfers[idx] = new DataTransfer();
        }
        const fileInput = document.createElement('input');
        fileInput.type = 'file';
        fileInput.id = `color-file-input-${idx}`;
        fileInput.name = `color_images[${idx}][]`;
        fileInput.multiple = true;
        fileInput.accept = 'image/*';
        fileInput.onchange = function() { handleColorFileInputChange(this, idx); };
        fileInput.files = colorDataTransfers[idx].files;
        container.appendChild(fileInput);
    });
}

function updateColorsUI() {
    const container = document.getElementById('selected-colors-container');
    const input = document.getElementById('colors-json-input');
    if (!container || !input) return;

    input.value = JSON.stringify(productColors);

    if (productColors.length === 0) {
        container.innerHTML = '<span class="text-muted" style="font-size:0.8rem;font-style:italic;">No colors selected yet. Click a quick preset or add a custom color above.</span>';
        renderColorGalleryTabs();
        renderActiveColorGallery();
        return;
    }

    container.innerHTML = productColors.map((c, idx) => `
        <div class="color-badge-chip" style="display:inline-flex;align-items:center;gap:8px;background:#ffffff;border:1px solid #171717;border-radius:24px;padding:6px 14px;box-shadow:0 1px 3px rgba(0,0,0,0.06);">
            <span style="width:16px;height:16px;border-radius:50%;background:${c.code};display:inline-block;border:1px solid rgba(0,0,0,0.2);flex-shrink:0;"></span>
            <span style="font-size:0.82rem;font-weight:600;letter-spacing:0.03em;color:#000000;">${c.name}</span>
            <button type="button" onclick="removeColor(${idx})" style="background:none;border:none;color:#9ca3af;cursor:pointer;font-size:0.9rem;padding:0 2px;line-height:1;margin-left:4px;transition:color 0.15s;" onmouseover="this.style.color='#ef4444'" onmouseout="this.style.color='#9ca3af'" title="Remove color">✕</button>
        </div>
    `).join('');

    initColorInputsContainer();
    renderColorGalleryTabs();
    renderActiveColorGallery();
}

function togglePresetColor(name, code) {
    const index = productColors.findIndex(c => c.name.toLowerCase() === name.toLowerCase());
    if (index > -1) {
        removeColor(index);
    } else {
        productColors.push({ name: name, code: code, existing_images: [] });
        activeColorIndex = productColors.length - 1;
        updateColorsUI();
    }
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
        productColors.push({ name: name, code: code, existing_images: [] });
        activeColorIndex = productColors.length - 1;
        updateColorsUI();
    }
    nameInput.value = '';
    nameInput.focus();
}

function removeColor(idx) {
    productColors.splice(idx, 1);
    delete colorDataTransfers[idx];
    const newDTMap = {};
    productColors.forEach((_, i) => {
        newDTMap[i] = colorDataTransfers[i >= idx ? i + 1 : i] || new DataTransfer();
    });
    Object.assign(colorDataTransfers, newDTMap);
    if (activeColorIndex >= productColors.length) {
        activeColorIndex = Math.max(0, productColors.length - 1);
    }
    updateColorsUI();
}

function setActiveColorTab(idx) {
    if (idx < 0 || idx >= productColors.length) return;
    activeColorIndex = idx;
    renderColorGalleryTabs();
    renderActiveColorGallery();
}

function getColorTotalImages(idx) {
    const c = productColors[idx];
    if (!c) return 0;
    const existingCount = (c.existing_images || []).length;
    const dt = colorDataTransfers[idx];
    const newCount = dt ? dt.files.length : 0;
    return existingCount + newCount;
}

function renderColorGalleryTabs() {
    const tabsContainer = document.getElementById('color-gallery-tabs');
    if (!tabsContainer) return;

    if (productColors.length === 0) {
        tabsContainer.innerHTML = '';
        return;
    }

    tabsContainer.innerHTML = productColors.map((c, idx) => {
        const isActive = idx === activeColorIndex;
        const totalImgs = getColorTotalImages(idx);
        return `
            <button type="button" 
                    onclick="setActiveColorTab(${idx})"
                    style="all:unset;cursor:pointer;display:inline-flex;align-items:center;gap:8px;padding:8px 16px;border-radius:4px;border:1px solid ${isActive ? '#000000' : '#e5e5e5'};background:${isActive ? '#000000' : '#ffffff'};color:${isActive ? '#ffffff' : '#000000'};transition:all 0.2s;font-size:0.8rem;">
                <span style="width:12px;height:12px;border-radius:50%;background:${c.code};display:inline-block;border:1px solid ${isActive ? 'rgba(255,255,255,0.4)' : 'rgba(0,0,0,0.2)'};"></span>
                <span style="font-weight:600;letter-spacing:0.04em;">${c.name}</span>
                <span style="font-size:0.7rem;padding:2px 7px;border-radius:12px;background:${isActive ? 'rgba(255,255,255,0.2)' : '#f3f4f6'};color:${isActive ? '#ffffff' : '#4b5563'};font-weight:500;">
                    ${totalImgs} ${totalImgs === 1 ? 'image' : 'images'}
                </span>
            </button>
        `;
    }).join('');
}

function renderActiveColorGallery() {
    const panel = document.getElementById('active-color-gallery-panel');
    if (!panel) return;

    if (productColors.length === 0 || !productColors[activeColorIndex]) {
        panel.innerHTML = `
            <div style="text-align:center;padding:28px 20px;color:#737373;">
                <p style="margin:0;font-size:0.85rem;">No colors selected. Add at least one color above to manage per-color cover and additional views.</p>
            </div>
        `;
        return;
    }

    const color = productColors[activeColorIndex];
    const dt = colorDataTransfers[activeColorIndex] || new DataTransfer();
    const existingImgs = color.existing_images || [];
    const newFiles = Array.from(dt.files);
    const totalCount = existingImgs.length + newFiles.length;

    let imagesGridHtml = '';
    if (totalCount > 0) {
        imagesGridHtml = `
            <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(130px, 1fr));gap:14px;margin-top:16px;" id="color-previews-grid-${activeColorIndex}">
        `;

        // Render existing images for this color
        existingImgs.forEach((img, i) => {
            const isMain = (i === 0);
            const formattedSrc = img.startsWith('http') || img.startsWith('/') ? img : `/storage/${img}`;
            imagesGridHtml += `
                <div style="position:relative;background:#ffffff;border:1px solid ${isMain ? '#000000' : '#e5e5e5'};border-radius:4px;overflow:hidden;box-shadow:0 1px 4px rgba(0,0,0,0.06);display:flex;flex-direction:column;">
                    <div style="aspect-ratio:3/4;overflow:hidden;position:relative;background:#f3f4f6;">
                        <img src="${formattedSrc}" alt="Preview" style="width:100%;height:100%;object-fit:cover;">
                        <span style="position:absolute;top:6px;left:6px;font-size:0.62rem;letter-spacing:0.06em;text-transform:uppercase;font-weight:700;padding:2px 6px;border-radius:2px;${isMain ? 'background:#000000;color:#ffffff;' : 'background:rgba(255,255,255,0.9);color:#000000;border:1px solid #d4d4d8;'}">
                            ${isMain ? '★ MAIN COVER' : `ADDITIONAL #${i}`}
                        </span>
                        <button type="button" onclick="removeExistingColorImage(${activeColorIndex}, ${i})" style="position:absolute;top:6px;right:6px;width:22px;height:22px;border-radius:50%;background:#ffffff;border:1px solid #e5e5e5;color:#ef4444;font-size:0.75rem;cursor:pointer;display:flex;align-items:center;justify-content:center;box-shadow:0 1px 3px rgba(0,0,0,0.15);" title="Remove this view from ${color.name}">✕</button>
                    </div>
                    ${!isMain ? `
                        <button type="button" onclick="makeExistingColorMainCover(${activeColorIndex}, ${i})" style="all:unset;cursor:pointer;text-align:center;font-size:0.68rem;letter-spacing:0.04em;text-transform:uppercase;padding:6px 4px;background:#f9fafb;border-top:1px solid #e5e5e5;color:#111827;font-weight:600;" onmouseover="this.style.background='#000000';this.style.color='#ffffff';" onmouseout="this.style.background='#f9fafb';this.style.color='#111827';">
                            Make Main Cover
                        </button>
                    ` : `
                        <div style="text-align:center;font-size:0.65rem;letter-spacing:0.05em;text-transform:uppercase;padding:6px 4px;background:#000000;color:#ffffff;font-weight:600;">
                            Active Cover View
                        </div>
                    `}
                </div>
            `;
        });

        // Render newly staged files
        newFiles.forEach((file, j) => {
            const overallIndex = existingImgs.length + j;
            const isMain = (overallIndex === 0);
            imagesGridHtml += `
                <div style="position:relative;background:#ffffff;border:1px solid ${isMain ? '#000000' : '#e5e5e5'};border-radius:4px;overflow:hidden;box-shadow:0 1px 4px rgba(0,0,0,0.06);display:flex;flex-direction:column;" id="new-color-card-${activeColorIndex}-${j}">
                    <div style="aspect-ratio:3/4;overflow:hidden;position:relative;background:#f3f4f6;">
                        <img src="" id="new-img-preview-${activeColorIndex}-${j}" alt="Staged Preview" style="width:100%;height:100%;object-fit:cover;">
                        <span style="position:absolute;top:6px;left:6px;font-size:0.62rem;letter-spacing:0.06em;text-transform:uppercase;font-weight:700;padding:2px 6px;border-radius:2px;${isMain ? 'background:#000000;color:#ffffff;' : 'background:rgba(255,255,255,0.9);color:#000000;border:1px solid #d4d4d8;'}">
                            ${isMain ? '★ MAIN COVER' : `ADDITIONAL #${overallIndex}`}
                        </span>
                        <button type="button" onclick="removeStagedColorImage(${activeColorIndex}, ${j})" style="position:absolute;top:6px;right:6px;width:22px;height:22px;border-radius:50%;background:#ffffff;border:1px solid #e5e5e5;color:#ef4444;font-size:0.75rem;cursor:pointer;display:flex;align-items:center;justify-content:center;box-shadow:0 1px 3px rgba(0,0,0,0.15);" title="Remove">✕</button>
                    </div>
                    ${!isMain ? `
                        <button type="button" onclick="makeStagedColorMainCover(${activeColorIndex}, ${j})" style="all:unset;cursor:pointer;text-align:center;font-size:0.68rem;letter-spacing:0.04em;text-transform:uppercase;padding:6px 4px;background:#f9fafb;border-top:1px solid #e5e5e5;color:#111827;font-weight:600;" onmouseover="this.style.background='#000000';this.style.color='#ffffff';" onmouseout="this.style.background='#f9fafb';this.style.color='#111827';">
                            Make Main Cover
                        </button>
                    ` : `
                        <div style="text-align:center;font-size:0.65rem;letter-spacing:0.05em;text-transform:uppercase;padding:6px 4px;background:#000000;color:#ffffff;font-weight:600;">
                            Active Cover View
                        </div>
                    `}
                </div>
            `;
        });

        imagesGridHtml += `</div>`;
    }

    panel.innerHTML = `
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;flex-wrap:wrap;gap:8px;">
            <div style="display:flex;align-items:center;gap:10px;">
                <span style="width:18px;height:18px;border-radius:50%;background:${color.code};display:inline-block;border:1px solid rgba(0,0,0,0.25);box-shadow:inset 0 1px 2px rgba(0,0,0,0.15);"></span>
                <span style="font-size:0.95rem;font-weight:600;letter-spacing:0.02em;color:#000000;">
                    Piece Views for <strong>${color.name}</strong>
                </span>
                <span style="font-size:0.72rem;color:#737373;">(${totalCount} ${totalCount === 1 ? 'view uploaded' : 'views uploaded'})</span>
            </div>
            <button type="button" class="btn btn-secondary btn-sm" onclick="triggerColorUpload(${activeColorIndex})" style="font-size:0.75rem;padding:5px 12px;display:inline-flex;align-items:center;gap:6px;">
                <span>+ Upload Views for ${color.name}</span>
            </button>
        </div>

        <div class="image-upload-dropzone" style="padding:22px;border:1.5px dashed #d1d5db;background:#ffffff;cursor:pointer;border-radius:4px;text-align:center;" onclick="triggerColorUpload(${activeColorIndex})">
            <div style="font-size:1.4rem;margin-bottom:4px;">📸</div>
            <div style="font-size:0.85rem;color:#111827;font-weight:500;">
                Click to upload images specifically for <strong>${color.name}</strong>
            </div>
            <span style="font-size:0.72rem;color:#6b7280;display:block;margin-top:2px;">
                1st image becomes the <strong>Main Cover</strong> when ${color.name} is clicked · subsequent populate vertical thumbnails
            </span>
        </div>

        ${imagesGridHtml}
    `;

    // Load asynchronous preview thumbnails for newly staged files
    newFiles.forEach((file, j) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const imgEl = document.getElementById(`new-img-preview-${activeColorIndex}-${j}`);
            if (imgEl) imgEl.src = e.target.result;
        };
        reader.readAsDataURL(file);
    });
}

function triggerColorUpload(colorIdx) {
    const input = document.getElementById(`color-file-input-${colorIdx}`);
    if (input) input.click();
}

function handleColorFileInputChange(input, colorIdx) {
    if (!input.files || input.files.length === 0) return;
    if (!colorDataTransfers[colorIdx]) {
        colorDataTransfers[colorIdx] = new DataTransfer();
    }
    for (let i = 0; i < input.files.length; i++) {
        colorDataTransfers[colorIdx].items.add(input.files[i]);
    }
    input.files = colorDataTransfers[colorIdx].files;
    renderColorGalleryTabs();
    renderActiveColorGallery();
}

function removeStagedColorImage(colorIdx, fileIndex) {
    const dt = colorDataTransfers[colorIdx];
    if (!dt) return;
    const newDT = new DataTransfer();
    for (let i = 0; i < dt.files.length; i++) {
        if (i !== fileIndex) {
            newDT.items.add(dt.files[i]);
        }
    }
    colorDataTransfers[colorIdx] = newDT;
    const input = document.getElementById(`color-file-input-${colorIdx}`);
    if (input) input.files = newDT.files;
    renderColorGalleryTabs();
    renderActiveColorGallery();
}

function makeStagedColorMainCover(colorIdx, fileIndex) {
    const dt = colorDataTransfers[colorIdx];
    if (!dt) return;
    const targetFile = dt.files[fileIndex];
    const newDT = new DataTransfer();
    newDT.items.add(targetFile);
    for (let i = 0; i < dt.files.length; i++) {
        if (i !== fileIndex) {
            newDT.items.add(dt.files[i]);
        }
    }
    colorDataTransfers[colorIdx] = newDT;
    const input = document.getElementById(`color-file-input-${colorIdx}`);
    if (input) input.files = newDT.files;
    renderColorGalleryTabs();
    renderActiveColorGallery();
}

function removeExistingColorImage(colorIdx, imgIndex) {
    const c = productColors[colorIdx];
    if (!c || !c.existing_images) return;
    c.existing_images.splice(imgIndex, 1);
    const input = document.getElementById('colors-json-input');
    if (input) input.value = JSON.stringify(productColors);
    renderColorGalleryTabs();
    renderActiveColorGallery();
}

function makeExistingColorMainCover(colorIdx, imgIndex) {
    const c = productColors[colorIdx];
    if (!c || !c.existing_images) return;
    const item = c.existing_images.splice(imgIndex, 1)[0];
    c.existing_images.unshift(item);
    const input = document.getElementById('colors-json-input');
    if (input) input.value = JSON.stringify(productColors);
    renderColorGalleryTabs();
    renderActiveColorGallery();
}

function assignExistingImageToActiveColor(imgPath) {
    if (productColors.length === 0) {
        alert('Please add or select a color first.');
        return;
    }
    const color = productColors[activeColorIndex];
    if (!color.existing_images) color.existing_images = [];
    if (!color.existing_images.includes(imgPath)) {
        color.existing_images.push(imgPath);
        const input = document.getElementById('colors-json-input');
        if (input) input.value = JSON.stringify(productColors);
        renderColorGalleryTabs();
        renderActiveColorGallery();
    }
}

// ─── Dynamic Sizes & Dimensions Logic ─────────────────────────────────
let productSizes = {!! json_encode($initialSizes) !!};
if (!Array.isArray(productSizes)) productSizes = ['XS', 'S', 'M', 'L', 'XL'];

function updateSizesUI() {
    const container = document.getElementById('selected-sizes-container');
    const input = document.getElementById('sizes-json-input');
    if (!container || !input) return;

    input.value = JSON.stringify(productSizes);

    if (productSizes.length === 0) {
        container.innerHTML = '<span class="text-muted" style="font-size:0.8rem;font-style:italic;">No sizes specified. Enter a custom size below to add.</span>';
    } else {
        container.innerHTML = productSizes.map((s, idx) => `
            <div class="size-chip" style="display:inline-flex;align-items:center;gap:6px;background:#ffffff;border:1px solid #171717;border-radius:4px;padding:4px 10px;box-shadow:0 1px 2px rgba(0,0,0,0.05);">
                <span style="font-size:0.8rem;font-weight:600;letter-spacing:0.04em;color:#000000;">${s}</span>
                <button type="button" onclick="removeSize(${idx})" style="background:none;border:none;color:#9ca3af;cursor:pointer;font-size:0.85rem;padding:0;line-height:1;margin-left:3px;" onmouseover="this.style.color='#ef4444'" onmouseout="this.style.color='#9ca3af'" title="Remove size">✕</button>
            </div>
        `).join('');
    }
}

function togglePresetSize(size) {
    const index = productSizes.indexOf(size);
    if (index > -1) {
        productSizes.splice(index, 1);
    } else {
        productSizes.push(size);
    }
    updateSizesUI();
}

function addCustomSize() {
    const input = document.getElementById('custom-size-name');
    if (!input) return;
    const val = input.value.trim();
    if (!val) {
        input.focus();
        return;
    }
    if (!productSizes.includes(val)) {
        productSizes.push(val);
        updateSizesUI();
    }
    input.value = '';
    input.focus();
}

function removeSize(idx) {
    productSizes.splice(idx, 1);
    updateSizesUI();
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
    updateSizesUI();
});
</script>
@endpush



