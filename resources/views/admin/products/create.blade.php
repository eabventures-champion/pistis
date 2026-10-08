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

            {{-- Product Accordions & Specifications --}}
            @php
                $hasAccordionErrors = $errors->has('details_and_fit') || $errors->has('garment_care') || $errors->has('shipping_and_returns');
            @endphp
            <div class="card mb-4" id="accordions-card">
                <div class="card-header d-flex justify-between align-center" onclick="toggleAccordionsCard()" style="cursor:pointer;user-select:none;padding:16px 20px;">
                    <div>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <h3 style="font-size:1rem;margin:0;">Product Accordions & Specifications</h3>
                            <span class="badge" style="font-size:0.7rem;background:#f3f4f6;color:#374151;border:1px solid #e5e5e5;padding:2px 8px;border-radius:12px;font-weight:600;">
                                3 Sections
                            </span>
                        </div>
                        <p class="text-muted" style="font-size:0.75rem;margin:4px 0 0;">Customize the three luxury expandable accordion sections shown on the product page.</p>
                    </div>
                    <div style="display:flex;align-items:center;gap:8px;">
                        <span id="accordions-collapse-btn-text" style="font-size:0.75rem;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;">{{ $hasAccordionErrors ? 'Collapse' : 'Expand' }}</span>
                        <div id="accordions-collapse-arrow" style="width:24px;height:24px;border-radius:50%;background:#f3f4f6;display:flex;align-items:center;justify-content:center;transition:transform 0.25s ease;color:#18181b;font-size:0.75rem;transform:{{ $hasAccordionErrors ? 'rotate(180deg)' : 'rotate(0deg)' }};">
                            ▼
                        </div>
                    </div>
                </div>
                <div class="card-body" id="accordions-card-body" style="display:{{ $hasAccordionErrors ? 'block' : 'none' }};border-top:1px solid #e5e5e5;">
                    <div class="form-group mb-3">
                        <label class="form-label" style="font-weight:600;font-size:0.85rem;">Details & Fit Specification</label>
                        <textarea name="details_and_fit" class="form-control" rows="4" placeholder="Enter bullet points (one per line) or paragraph for this piece (e.g. 390 GSM fleece, relaxed 90s silhouette)...">{{ old('details_and_fit') }}</textarea>
                        <small class="text-muted" style="font-size:0.75rem;display:block;margin-top:4px;">Leave blank to use the store default from Settings.</small>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label" style="font-weight:600;font-size:0.85rem;">Garment Care (Optional Custom Override)</label>
                        <textarea name="garment_care" class="form-control" rows="3" placeholder="Leave empty to use store default garment care policy...">{{ old('garment_care') }}</textarea>
                        <small class="text-muted" style="font-size:0.75rem;display:block;margin-top:4px;">Leave empty to inherit standard store-wide care guidelines.</small>
                    </div>

                    <div class="form-group mb-0">
                        <label class="form-label" style="font-weight:600;font-size:0.85rem;">Shipping & Returns (Optional Custom Override)</label>
                        <textarea name="shipping_and_returns" class="form-control" rows="3" placeholder="Leave empty to use store default shipping & returns policy...">{{ old('shipping_and_returns') }}</textarea>
                        <small class="text-muted" style="font-size:0.75rem;display:block;margin-top:4px;">Leave empty to inherit standard store-wide shipping & return terms.</small>
                    </div>
                </div>
            </div>

            {{-- Color Variations & Per-Color Image Galleries --}}
            <div class="card mb-4" id="colors-palette-card">
                <div class="card-header d-flex justify-between align-center" onclick="togglePieceColorsCard()" style="cursor:pointer;user-select:none;padding:16px 20px;">
                    <div>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <h3 style="font-size:1rem;margin:0;">Piece Colors & Palette</h3>
                            <span id="colors-header-badge" class="badge" style="font-size:0.7rem;background:#f3f4f6;color:#374151;border:1px solid #e5e5e5;padding:2px 8px;border-radius:12px;font-weight:600;">
                                2 Colors
                            </span>
                        </div>
                        <p class="text-muted" style="font-size:0.75rem;margin:4px 0 0;">Default colors are <strong>Grey</strong> and <strong>Black</strong>. Click to expand and manage color-specific image galleries and pricing.</p>
                    </div>
                    <div style="display:flex;align-items:center;gap:8px;">
                        <span id="colors-collapse-btn-text" style="font-size:0.75rem;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;">Expand</span>
                        <div id="colors-collapse-arrow" style="width:24px;height:24px;border-radius:50%;background:#f3f4f6;display:flex;align-items:center;justify-content:center;transition:transform 0.25s ease;color:#18181b;font-size:0.75rem;">
                            ▼
                        </div>
                    </div>
                </div>
                <div class="card-body" id="colors-palette-body" style="display:none;border-top:1px solid #e5e5e5;">
                    {{-- Active Selected Colors --}}
                    <div style="margin-bottom:16px;">
                        <label class="form-label" style="font-size:0.75rem;text-transform:uppercase;letter-spacing:0.1em;color:var(--text-secondary);">Active Piece Colors</label>
                        <div id="selected-colors-container" style="display:flex;flex-wrap:wrap;gap:10px;min-height:42px;align-items:center;background:#fafafa;padding:12px;border:1px solid #e5e5e5;border-radius:4px;">
                            {{-- Populated by JavaScript --}}
                        </div>
                    </div>

                    {{-- Dynamic Add Custom Color --}}
                    <div style="background:#ffffff;padding:14px;border:1px dashed #d1d5db;border-radius:6px;margin-bottom:24px;">
                        <span style="font-size:0.72rem;letter-spacing:0.1em;text-transform:uppercase;color:#374151;display:block;margin-bottom:8px;font-weight:600;">+ Dynamic Custom Color</span>
                        <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
                            <input type="text" id="custom-color-name" class="form-control" placeholder="Enter color name (e.g. Heather Grey, Sage, Emerald...)" style="flex:1;min-width:200px;font-size:0.85rem;" onkeydown="if(event.key==='Enter'){event.preventDefault();addCustomColor();}">
                            <input type="number" id="custom-color-price" class="form-control" placeholder="Price ({{ $currency_symbol }}) - optional" step="0.01" min="0" style="width:150px;font-size:0.85rem;" onkeydown="if(event.key==='Enter'){event.preventDefault();addCustomColor();}">
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
                            Select a color tab below to upload its piece images. When a customer selects that color on the shop page, the <strong>Main Cover</strong> fills the main stage and the <strong>Additional Items</strong> fill the vertical thumbnail strip.
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

            {{-- Product Images Card (View-Only, Driven Exclusively by Color-Specific Image Galleries) --}}
            <div class="card">
                <div class="card-header d-flex justify-between align-center">
                    <div>
                        <div style="display:flex;align-items:center;gap:8px;">
                            <h3 style="font-size:1rem;margin:0;">Product Images</h3>
                            <span style="font-size:0.68rem;background:#f3f4f6;color:#374151;border:1px solid #e5e5e5;padding:2px 8px;border-radius:12px;font-weight:600;letter-spacing:0.02em;">
                                View Only
                            </span>
                        </div>
                        <p class="text-muted" style="font-size:0.75rem;margin:2px 0 0;">
                            Consolidated view of all piece photos from the Color-Specific Image Galleries above.
                        </p>
                    </div>
                    <span class="text-muted" style="font-size:0.8rem;font-weight:600;" id="all-product-images-counter">
                        0 images
                    </span>
                </div>
                <div class="card-body">
                    <div style="display:flex;align-items:center;justify-content:space-between;background:#f9fafb;border:1px solid #e5e5e5;border-radius:4px;padding:10px 14px;margin-bottom:16px;">
                        <div style="font-size:0.75rem;color:#4b5563;">
                            <span style="font-weight:600;color:#111827;">ℹ Synced with Color Galleries:</span> Images cannot be directly added or deleted here. Use the <strong>Color-Specific Image Galleries</strong> above to upload, remove, or reorder piece views.
                        </div>
                    </div>
                    <div id="all-product-images-container">
                        {{-- Dynamically populated from all color galleries by JavaScript --}}
                    </div>
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
                        <label class="form-label">Size Guide</label>
                        <select name="size_guide_id" class="form-control">
                            <option value="">Auto-detect from Category</option>
                            @foreach($sizeGuides as $guide)
                                <option value="{{ $guide->id }}" {{ old('size_guide_id') == $guide->id ? 'selected' : '' }}>
                                    {{ $guide->name }} {{ $guide->category ? '(' . $guide->category->name . ')' : '' }}
                                </option>
                            @endforeach
                        </select>
                        <span class="text-muted" style="font-size:0.75rem;margin-top:2px;display:block;">
                            Defaults to the category's size guide (e.g. Men or Women).
                        </span>
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
// ─── Consolidated Product Images Summary (Driven by Color Galleries) ─
function renderAllProductImagesCard() {
    const container = document.getElementById('all-product-images-container');
    const counter = document.getElementById('all-product-images-counter');
    if (!container) return;

    // Collect all images across all colors in order
    const allItems = [];

    productColors.forEach((c, cIdx) => {
        const existingImgs = c.existing_images || [];
        const dt = colorDataTransfers[cIdx];
        const stagedFiles = dt ? Array.from(dt.files) : [];

        // Existing images
        existingImgs.forEach((img, i) => {
            const formattedSrc = img.startsWith('http') || img.startsWith('/') ? img : `/storage/${img}`;
            allItems.push({
                src: formattedSrc,
                file: null,
                colorIndex: cIdx,
                colorName: c.name,
                colorCode: c.code,
                isColorCover: (i === 0),
                isOverallCover: (allItems.length === 0)
            });
        });

        // Staged files
        stagedFiles.forEach((file, j) => {
            allItems.push({
                src: null,
                file: file,
                colorIndex: cIdx,
                colorName: c.name,
                colorCode: c.code,
                isColorCover: (existingImgs.length === 0 && j === 0),
                isOverallCover: (allItems.length === 0)
            });
        });
    });

    const totalCount = allItems.length;
    if (counter) {
        counter.textContent = `${totalCount} ${totalCount === 1 ? 'image' : 'images'}`;
    }

    if (totalCount === 0) {
        container.innerHTML = `
            <div style="text-align:center;padding:36px 20px;background:#fafafa;border:1px dashed #d4d4d8;border-radius:6px;color:#71717a;">
                <div style="font-size:1.8rem;margin-bottom:8px;">🖼️</div>
                <div style="font-size:0.875rem;font-weight:600;color:#18181b;margin-bottom:4px;">No images in color galleries</div>
                <p style="margin:0;font-size:0.78rem;color:#71717a;max-width:400px;margin:0 auto;">
                    Select a color tab in the <strong>Color-Specific Image Galleries</strong> above and upload views. They will automatically appear here.
                </p>
            </div>
        `;
        return;
    }

    let gridHtml = `
        <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(130px, 1fr));gap:14px;">
    `;

    allItems.forEach((item, idx) => {
        const isOverall = item.isOverallCover;
        gridHtml += `
            <div style="position:relative;background:#ffffff;border:1px solid ${isOverall ? '#000000' : '#e5e5e5'};border-radius:4px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.05);display:flex;flex-direction:column;cursor:pointer;transition:transform 0.15s, box-shadow 0.15s;" 
                 onclick="jumpToColorGallery(${item.colorIndex})" 
                 title="Click to view / manage in ${item.colorName} gallery"
                 onmouseover="this.style.boxShadow='0 4px 10px rgba(0,0,0,0.12)';this.style.transform='translateY(-2px)';" 
                 onmouseout="this.style.boxShadow='0 1px 3px rgba(0,0,0,0.05)';this.style.transform='none';">
                <div style="aspect-ratio:3/4;overflow:hidden;position:relative;background:#f3f4f6;">
                    <img id="all-product-img-${idx}" src="${item.src || ''}" alt="Product Image" style="width:100%;height:100%;object-fit:cover;">
                    ${isOverall ? `
                        <span style="position:absolute;top:6px;left:6px;font-size:0.6rem;letter-spacing:0.06em;text-transform:uppercase;font-weight:700;padding:2px 6px;border-radius:2px;background:#000000;color:#ffffff;box-shadow:0 1px 2px rgba(0,0,0,0.3);">
                            ★ MAIN COVER
                        </span>
                    ` : ''}
                </div>
                <div style="padding:6px 8px;background:#fafafa;border-top:1px solid #f0f0f0;display:flex;align-items:center;justify-content:space-between;gap:6px;">
                    <div style="display:inline-flex;align-items:center;gap:5px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                        <span style="width:9px;height:9px;border-radius:50%;background:${item.colorCode};display:inline-block;border:1px solid rgba(0,0,0,0.2);flex-shrink:0;"></span>
                        <span style="font-size:0.7rem;font-weight:600;color:#18181b;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${item.colorName}</span>
                    </div>
                    ${item.isColorCover ? `
                        <span style="font-size:0.58rem;color:#52525b;background:#e4e4e7;padding:1px 5px;border-radius:3px;font-weight:600;letter-spacing:0.02em;text-transform:uppercase;">Cover</span>
                    ` : ''}
                </div>
            </div>
        `;
    });

    gridHtml += `</div>`;
    container.innerHTML = gridHtml;

    // Load FileReader for staged files
    allItems.forEach((item, idx) => {
        if (item.file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const imgEl = document.getElementById(`all-product-img-${idx}`);
                if (imgEl) imgEl.src = e.target.result;
            };
            reader.readAsDataURL(item.file);
        }
    });
}

function jumpToColorGallery(colorIndex) {
    setActiveColorTab(colorIndex);
    const gallerySection = document.getElementById('color-galleries-manager-section');
    if (gallerySection) {
        gallerySection.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
}

// ─── Color Variations & Per-Color Image Galleries Logic ───────────────
const currencySymbol = @json($currency_symbol);
let productColors = [
    { name: 'Grey', code: '#737373', price: null, compare_price: null, existing_images: [] },
    { name: 'Black', code: '#000000', price: null, compare_price: null, existing_images: [] }
];
let activeColorIndex = 0;
// Maps colorIndex -> DataTransfer of newly selected files
const colorDataTransfers = {};

function updateColorPrice(idx, val) {
    if (!productColors[idx]) return;
    productColors[idx].price = (val !== '' && val !== null) ? val : null;
    const input = document.getElementById('colors-json-input');
    if (input) input.value = JSON.stringify(productColors);
    renderColorGalleryTabs();
}

function updateColorComparePrice(idx, val) {
    if (!productColors[idx]) return;
    productColors[idx].compare_price = (val !== '' && val !== null) ? val : null;
    const input = document.getElementById('colors-json-input');
    if (input) input.value = JSON.stringify(productColors);
}

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

function toggleAccordionsCard() {
    const body = document.getElementById('accordions-card-body');
    const arrow = document.getElementById('accordions-collapse-arrow');
    const btnText = document.getElementById('accordions-collapse-btn-text');
    if (!body) return;
    const isCollapsed = body.style.display === 'none';
    if (isCollapsed) {
        body.style.display = 'block';
        if (arrow) arrow.style.transform = 'rotate(180deg)';
        if (btnText) btnText.textContent = 'Collapse';
    } else {
        body.style.display = 'none';
        if (arrow) arrow.style.transform = 'rotate(0deg)';
        if (btnText) btnText.textContent = 'Expand';
    }
}

function togglePieceColorsCard() {
    const body = document.getElementById('colors-palette-body');
    const arrow = document.getElementById('colors-collapse-arrow');
    const btnText = document.getElementById('colors-collapse-btn-text');
    if (!body) return;
    const isCollapsed = body.style.display === 'none';
    if (isCollapsed) {
        body.style.display = 'block';
        if (arrow) arrow.style.transform = 'rotate(180deg)';
        if (btnText) btnText.textContent = 'Collapse';
    } else {
        body.style.display = 'none';
        if (arrow) arrow.style.transform = 'rotate(0deg)';
        if (btnText) btnText.textContent = 'Expand';
    }
}

function updateColorsUI() {
    const container = document.getElementById('selected-colors-container');
    const input = document.getElementById('colors-json-input');
    const badge = document.getElementById('colors-header-badge');
    if (badge) {
        const count = productColors.length;
        badge.textContent = `${count} ${count === 1 ? 'Color' : 'Colors'}`;
    }
    if (!container || !input) return;

    input.value = JSON.stringify(productColors);

    if (productColors.length === 0) {
        container.innerHTML = '<span class="text-muted" style="font-size:0.8rem;font-style:italic;">No colors selected yet. Click a quick preset or add a custom color above.</span>';
        renderColorGalleryTabs();
        renderActiveColorGallery();
        renderAllProductImagesCard();
        return;
    }

    container.innerHTML = productColors.map((c, idx) => {
        const priceTag = (c.price !== undefined && c.price !== null && c.price !== '')
            ? `<span style="font-size:0.72rem;background:#f3f4f6;padding:2px 7px;border-radius:10px;font-weight:600;color:#18181b;">${currencySymbol}${parseFloat(c.price).toFixed(2)}</span>`
            : '';
        return `
            <div class="color-badge-chip" style="display:inline-flex;align-items:center;gap:8px;background:#ffffff;border:1px solid #171717;border-radius:24px;padding:6px 14px;box-shadow:0 1px 3px rgba(0,0,0,0.06);">
                <span style="width:16px;height:16px;border-radius:50%;background:${c.code};display:inline-block;border:1px solid rgba(0,0,0,0.2);flex-shrink:0;"></span>
                <span style="font-size:0.82rem;font-weight:600;letter-spacing:0.03em;color:#000000;">${c.name}</span>
                ${priceTag}
                <button type="button" onclick="removeColor(${idx})" style="background:none;border:none;color:#9ca3af;cursor:pointer;font-size:0.9rem;padding:0 2px;line-height:1;margin-left:4px;transition:color 0.15s;" onmouseover="this.style.color='#ef4444'" onmouseout="this.style.color='#9ca3af'" title="Remove color">✕</button>
            </div>
        `;
    }).join('');

    initColorInputsContainer();
    renderColorGalleryTabs();
    renderActiveColorGallery();
    renderAllProductImagesCard();
}

function togglePresetColor(name, code) {
    const index = productColors.findIndex(c => c.name.toLowerCase() === name.toLowerCase());
    if (index > -1) {
        removeColor(index);
    } else {
        productColors.push({ name: name, code: code, price: null, compare_price: null, existing_images: [] });
        activeColorIndex = productColors.length - 1;
        updateColorsUI();
    }
}

function addCustomColor() {
    const nameInput = document.getElementById('custom-color-name');
    const picker = document.getElementById('custom-color-picker');
    const priceInput = document.getElementById('custom-color-price');
    if (!nameInput) return;

    const name = nameInput.value.trim();
    if (!name) {
        nameInput.focus();
        return;
    }

    const code = picker ? picker.value : '#808080';
    const customPrice = priceInput && priceInput.value.trim() !== '' ? parseFloat(priceInput.value.trim()) : null;

    if (!productColors.some(c => c.name.toLowerCase() === name.toLowerCase())) {
        productColors.push({ name: name, code: code, price: customPrice, compare_price: null, existing_images: [] });
        activeColorIndex = productColors.length - 1;
        updateColorsUI();
    }
    nameInput.value = '';
    if (priceInput) priceInput.value = '';
    nameInput.focus();
}

function removeColor(idx) {
    productColors.splice(idx, 1);
    delete colorDataTransfers[idx];
    // Re-index remaining data transfers
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
        const priceLabel = (c.price !== undefined && c.price !== null && c.price !== '')
            ? `<span style="font-size:0.7rem;padding:2px 7px;border-radius:12px;background:${isActive ? 'rgba(255,255,255,0.25)' : '#e0e7ff'};color:${isActive ? '#ffffff' : '#3730a3'};font-weight:600;">${currencySymbol}${parseFloat(c.price).toFixed(2)}</span>`
            : '';
        return `
            <button type="button" 
                    onclick="setActiveColorTab(${idx})"
                    style="all:unset;cursor:pointer;display:inline-flex;align-items:center;gap:8px;padding:8px 16px;border-radius:4px;border:1px solid ${isActive ? '#000000' : '#e5e5e5'};background:${isActive ? '#000000' : '#ffffff'};color:${isActive ? '#ffffff' : '#000000'};transition:all 0.2s;font-size:0.8rem;">
                <span style="width:12px;height:12px;border-radius:50%;background:${c.code};display:inline-block;border:1px solid ${isActive ? 'rgba(255,255,255,0.4)' : 'rgba(0,0,0,0.2)'};"></span>
                <span style="font-weight:600;letter-spacing:0.04em;">${c.name}</span>
                ${priceLabel}
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

        // Render existing images
        existingImgs.forEach((img, i) => {
            const isMain = (i === 0);
            imagesGridHtml += `
                <div style="position:relative;background:#ffffff;border:1px solid ${isMain ? '#000000' : '#e5e5e5'};border-radius:4px;overflow:hidden;box-shadow:0 1px 4px rgba(0,0,0,0.06);display:flex;flex-direction:column;">
                    <div style="aspect-ratio:3/4;overflow:hidden;position:relative;background:#f3f4f6;">
                        <img src="/storage/${img}" alt="Preview" style="width:100%;height:100%;object-fit:cover;">
                        <span style="position:absolute;top:6px;left:6px;font-size:0.62rem;letter-spacing:0.06em;text-transform:uppercase;font-weight:700;padding:2px 6px;border-radius:2px;${isMain ? 'background:#000000;color:#ffffff;' : 'background:rgba(255,255,255,0.9);color:#000000;border:1px solid #d4d4d8;'}">
                            ${isMain ? '★ MAIN COVER' : `ADDITIONAL #${i}`}
                        </span>
                        <button type="button" onclick="removeExistingColorImage(${activeColorIndex}, ${i})" style="position:absolute;top:6px;right:6px;width:22px;height:22px;border-radius:50%;background:#ffffff;border:1px solid #e5e5e5;color:#ef4444;font-size:0.75rem;cursor:pointer;display:flex;align-items:center;justify-content:center;box-shadow:0 1px 3px rgba(0,0,0,0.15);" title="Remove this view">✕</button>
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

        {{-- Color Pricing Override Card --}}
        <div style="background:#ffffff;border:1px solid #e5e5e5;border-radius:6px;padding:14px 16px;margin-bottom:16px;">
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:10px;">
                <span style="font-size:0.75rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#111827;">
                    ${color.name} Pricing Override
                </span>
                <span style="font-size:0.7rem;background:#f3f4f6;color:#6b7280;padding:2px 8px;border-radius:12px;">
                    Optional &mdash; leave empty to inherit main product price
                </span>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                <div>
                    <label style="font-size:0.75rem;font-weight:600;display:block;margin-bottom:4px;color:#374151;">
                        Selling Price (${currencySymbol})
                    </label>
                    <input type="number" 
                           step="0.01" 
                           min="0" 
                           value="${color.price !== undefined && color.price !== null ? color.price : ''}" 
                           placeholder="Leave empty to use base price" 
                           class="form-control" 
                           style="font-size:0.85rem;" 
                           oninput="updateColorPrice(${activeColorIndex}, this.value)">
                    <small style="font-size:0.7rem;color:#737373;display:block;margin-top:3px;">
                        Price charged when customer selects <strong>${color.name}</strong>.
                    </small>
                </div>
                <div>
                    <label style="font-size:0.75rem;font-weight:600;display:block;margin-bottom:4px;color:#374151;">
                        Compare / Strikethrough Price (${currencySymbol})
                    </label>
                    <input type="number" 
                           step="0.01" 
                           min="0" 
                           value="${color.compare_price !== undefined && color.compare_price !== null ? color.compare_price : ''}" 
                           placeholder="Optional crossed-out price" 
                           class="form-control" 
                           style="font-size:0.85rem;" 
                           oninput="updateColorComparePrice(${activeColorIndex}, this.value)">
                    <small style="font-size:0.7rem;color:#737373;display:block;margin-top:3px;">
                        Crossed-out reference price for <strong>${color.name}</strong>.
                    </small>
                </div>
            </div>
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
    renderAllProductImagesCard();
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
    renderAllProductImagesCard();
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
    renderAllProductImagesCard();
}

function removeExistingColorImage(colorIdx, imgIndex) {
    const c = productColors[colorIdx];
    if (!c || !c.existing_images) return;
    c.existing_images.splice(imgIndex, 1);
    const input = document.getElementById('colors-json-input');
    if (input) input.value = JSON.stringify(productColors);
    renderColorGalleryTabs();
    renderActiveColorGallery();
    renderAllProductImagesCard();
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
    renderAllProductImagesCard();
}

// ─── Dynamic Sizes & Dimensions Logic ─────────────────────────────────
let productSizes = ['XS', 'S', 'M', 'L', 'XL'];

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



