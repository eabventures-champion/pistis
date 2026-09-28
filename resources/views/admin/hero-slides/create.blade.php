@extends('layouts.admin')

@section('title', 'Add New Hero Slide')

@section('content')
<div class="admin-header">
    <div>
        <h1>Add New Hero Slide</h1>
        <p class="text-muted" style="font-size:0.875rem;margin-top:4px;">Craft a luxury fashion editorial slide for your homepage.</p>
    </div>
    <a href="{{ route('admin.hero-slides.index') }}" class="btn btn-secondary">← Back to Hero Slides</a>
</div>

<form action="{{ route('admin.hero-slides.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div style="display:grid;grid-template-columns:2fr 1fr;gap:24px;">
        <div>
            {{-- Editorial Typography & Content --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h3 style="font-size:1rem;margin:0;">Editorial Content & Typography</h3>
                </div>
                <div class="card-body">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Collection Tag / Label</label>
                            <input type="text" name="collection_name" class="form-control" value="{{ old('collection_name', 'NEW COLLECTION') }}" placeholder="e.g. NEW COLLECTION, AUTUMN / WINTER">
                            <p class="text-muted mt-1" style="font-size:0.78rem;">Displayed as a subtle uppercase editorial header above the title.</p>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Subtitle / Capsule Note</label>
                            <input type="text" name="subtitle" class="form-control" value="{{ old('subtitle') }}" placeholder="e.g. EDITION 01, CAPSULE SERIES">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Headline Title * (Multi-line Supported)</label>
                        <textarea name="title" class="form-control" rows="3" required placeholder="THE ART&#10;OF SIMPLICITY">{{ old('title') }}</textarea>
                        <p class="text-muted mt-1" style="font-size:0.78rem;">💡 <strong>Tip:</strong> Press <kbd>Enter</kbd> between words to create high-fashion multi-line title breaks (e.g. "THE ART" on line 1, "OF SIMPLICITY" on line 2).</p>
                        @error('title') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Editorial Description</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Timeless silhouettes designed for modern expression. Crafted from heavyweight organic textiles.">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Fashion Photography (Desktop & Mobile) --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h3 style="font-size:1rem;margin:0;">Campaign Photography</h3>
                </div>
                <div class="card-body">
                    {{-- Desktop Image --}}
                    <div class="form-group">
                        <label class="form-label">Desktop Image (High-res 16:9 or Landscape) *</label>
                        <div class="image-upload-dropzone" id="desktop-dropzone" onclick="document.getElementById('desktop-file-input').click()">
                            <input type="file" id="desktop-file-input" name="image" accept="image/*" style="display:none;" onchange="previewSingleImage(this, 'desktop-preview-container', 'desktop-preview-img')">
                            <div class="dropzone-content">
                                <div class="dropzone-icon">📷</div>
                                <div class="dropzone-text"><strong>Click to upload desktop image</strong> or drag & drop</div>
                                <span class="dropzone-hint">Recommended: 1600x1000px+, black & white or high-contrast fashion photography (Max 10MB)</span>
                            </div>
                        </div>
                        <div class="form-group mt-2">
                            <input type="url" name="image_url_input" class="form-control" value="{{ old('image_url_input') }}" placeholder="Or paste direct image URL (https://...)">
                        </div>
                        <div id="desktop-preview-container" class="mt-2" style="display:none;">
                            <img id="desktop-preview-img" src="" alt="Desktop Preview" style="max-height:160px;border-radius:var(--radius-md);border:1px solid var(--border-color);object-fit:cover;">
                        </div>
                        @error('image') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <hr style="border:0;border-top:1px solid var(--border-color);margin:20px 0;">

                    {{-- Mobile Image --}}
                    <div class="form-group">
                        <label class="form-label">Mobile Portrait Image (Optional — 4:5 or 9:16)</label>
                        <div class="image-upload-dropzone" id="mobile-dropzone" onclick="document.getElementById('mobile-file-input').click()">
                            <input type="file" id="mobile-file-input" name="mobile_image" accept="image/*" style="display:none;" onchange="previewSingleImage(this, 'mobile-preview-container', 'mobile-preview-img')">
                            <div class="dropzone-content">
                                <div class="dropzone-icon">📱</div>
                                <div class="dropzone-text"><strong>Click to upload mobile portrait image</strong></div>
                                <span class="dropzone-hint">If omitted, desktop image will be automatically used with smart responsive crop</span>
                            </div>
                        </div>
                        <div class="form-group mt-2">
                            <input type="url" name="mobile_image_url_input" class="form-control" value="{{ old('mobile_image_url_input') }}" placeholder="Or paste direct mobile image URL (https://...)">
                        </div>
                        <div id="mobile-preview-container" class="mt-2" style="display:none;">
                            <img id="mobile-preview-img" src="" alt="Mobile Preview" style="max-height:140px;border-radius:var(--radius-md);border:1px solid var(--border-color);object-fit:cover;">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Call To Action Buttons --}}
            <div class="card">
                <div class="card-header">
                    <h3 style="font-size:1rem;margin:0;">Call-to-Action Buttons</h3>
                </div>
                <div class="card-body">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Primary Button Text</label>
                            <input type="text" name="primary_button_text" class="form-control" value="{{ old('primary_button_text', 'SHOP COLLECTION') }}" placeholder="e.g. SHOP COLLECTION">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Primary Button URL</label>
                            <input type="text" name="primary_button_url" class="form-control" value="{{ old('primary_button_url', '/shop') }}" placeholder="e.g. /shop or /shop/category-slug">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Secondary Button Text (Optional)</label>
                            <input type="text" name="secondary_button_text" class="form-control" value="{{ old('secondary_button_text') }}" placeholder="e.g. VIEW LOOKBOOK">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Secondary Button URL (Optional)</label>
                            <input type="text" name="secondary_button_url" class="form-control" value="{{ old('secondary_button_url') }}" placeholder="e.g. /shop">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div>
            {{-- Publishing & Timing Settings --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h3 style="font-size:1rem;margin:0;">Publishing & Display</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-check">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                            <span class="form-label" style="margin:0;font-weight:600;">Active on Homepage</span>
                        </label>
                        <p class="text-muted mt-1" style="font-size:0.78rem;">When enabled, this slide appears in the homepage cinematic slider.</p>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Display Duration (Seconds)</label>
                        <input type="number" name="display_duration" class="form-control" min="3" max="60" value="{{ old('display_duration', 7) }}">
                        <p class="text-muted mt-1" style="font-size:0.78rem;">Time before auto-advancing to the next slide.</p>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Sort Order</label>
                        <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $nextOrder) }}">
                    </div>
                </div>
            </div>

            {{-- Campaign Scheduling --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h3 style="font-size:1rem;margin:0;">Campaign Scheduling (Optional)</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label">Starts At</label>
                        <input type="datetime-local" name="starts_at" class="form-control" value="{{ old('starts_at') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Ends At</label>
                        <input type="datetime-local" name="ends_at" class="form-control" value="{{ old('ends_at') }}">
                    </div>
                    <p class="text-muted" style="font-size:0.75rem;">Leave blank to run indefinitely.</p>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-lg w-100">Create Hero Slide</button>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
function previewSingleImage(input, containerId, imgId) {
    if (!input.files || !input.files[0]) return;
    const reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById(imgId).src = e.target.result;
        document.getElementById(containerId).style.display = 'block';
    };
    reader.readAsDataURL(input.files[0]);
}

// Drag & drop dropzone listeners
['desktop-dropzone', 'mobile-dropzone'].forEach(id => {
    const el = document.getElementById(id);
    if (!el) return;
    
    ['dragenter', 'dragover'].forEach(evt => {
        el.addEventListener(evt, e => {
            e.preventDefault();
            e.stopPropagation();
            el.classList.add('dragover');
        });
    });

    ['dragleave', 'drop'].forEach(evt => {
        el.addEventListener(evt, e => {
            e.preventDefault();
            e.stopPropagation();
            el.classList.remove('dragover');
        });
    });

    el.addEventListener('drop', e => {
        const fileInput = el.querySelector('input[type="file"]');
        if (e.dataTransfer.files && e.dataTransfer.files[0]) {
            fileInput.files = e.dataTransfer.files;
            const containerId = id.includes('desktop') ? 'desktop-preview-container' : 'mobile-preview-container';
            const imgId = id.includes('desktop') ? 'desktop-preview-img' : 'mobile-preview-img';
            previewSingleImage(fileInput, containerId, imgId);
        }
    });
});
</script>
@endpush
