@extends('layouts.admin')

@section('title', 'Edit Hero Slide')

@section('content')
<div class="admin-header">
    <div>
        <h1>Edit Hero Slide</h1>
        <p class="text-muted" style="font-size:0.875rem;margin-top:4px;">Update slide content, campaign imagery, and interactive links.</p>
    </div>
    <a href="{{ route('admin.hero-slides.index') }}" class="btn btn-secondary">← Back to Hero Slides</a>
</div>

<form action="{{ route('admin.hero-slides.update', $heroSlide) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
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
                            <input type="text" name="collection_name" class="form-control" value="{{ old('collection_name', $heroSlide->collection_name) }}" placeholder="e.g. NEW COLLECTION, AUTUMN / WINTER">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Subtitle / Capsule Note</label>
                            <input type="text" name="subtitle" class="form-control" value="{{ old('subtitle', $heroSlide->subtitle) }}" placeholder="e.g. EDITION 01, CAPSULE SERIES">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Headline Title * (Multi-line Supported)</label>
                        <textarea name="title" class="form-control" rows="3" required placeholder="THE ART&#10;OF SIMPLICITY">{{ old('title', $heroSlide->title) }}</textarea>
                        <p class="text-muted mt-1" style="font-size:0.78rem;">💡 Press <kbd>Enter</kbd> between words to create multi-line editorial headline breaks.</p>
                        @error('title') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Editorial Description</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description', $heroSlide->description) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Campaign Photography --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h3 style="font-size:1rem;margin:0;">Campaign Photography</h3>
                </div>
                <div class="card-body">
                    {{-- Desktop Image --}}
                    <div class="form-group">
                        <label class="form-label">Desktop Image (High-res 16:9 or Landscape)</label>
                        @if($heroSlide->image)
                            <div class="mb-2">
                                <span class="text-muted" style="font-size:0.75rem;display:block;margin-bottom:4px;">Current Desktop Image:</span>
                                <img src="{{ $heroSlide->image_url }}" alt="" style="max-height:140px;border-radius:var(--radius-md);border:1px solid var(--border-color);object-fit:cover;">
                            </div>
                        @endif
                        <div class="image-upload-dropzone" id="desktop-dropzone" onclick="document.getElementById('desktop-file-input').click()">
                            <input type="file" id="desktop-file-input" name="image" accept="image/*" style="display:none;" onchange="previewSingleImage(this, 'desktop-preview-container', 'desktop-preview-img')">
                            <div class="dropzone-content">
                                <div class="dropzone-icon">📷</div>
                                <div class="dropzone-text"><strong>Click to upload new desktop image</strong> or drag & drop</div>
                                <span class="dropzone-hint">Leave untouched to keep current image</span>
                            </div>
                        </div>
                        <div class="form-group mt-2">
                            <input type="url" name="image_url_input" class="form-control" value="{{ old('image_url_input') }}" placeholder="Or paste direct image URL (https://...)">
                        </div>
                        <div id="desktop-preview-container" class="mt-2" style="display:none;">
                            <span class="badge badge-success mb-1">New Selected:</span>
                            <img id="desktop-preview-img" src="" alt="Desktop Preview" style="max-height:160px;border-radius:var(--radius-md);border:1px solid var(--border-color);object-fit:cover;display:block;">
                        </div>
                        @error('image') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <hr style="border:0;border-top:1px solid var(--border-color);margin:20px 0;">

                    {{-- Mobile Image --}}
                    <div class="form-group">
                        <label class="form-label">Mobile Portrait Image (Optional — 4:5 or 9:16)</label>
                        @if($heroSlide->mobile_image)
                            <div class="mb-2">
                                <span class="text-muted" style="font-size:0.75rem;display:block;margin-bottom:4px;">Current Mobile Image:</span>
                                <img src="{{ $heroSlide->mobile_image_url }}" alt="" style="max-height:120px;border-radius:var(--radius-md);border:1px solid var(--border-color);object-fit:cover;">
                            </div>
                        @endif
                        <div class="image-upload-dropzone" id="mobile-dropzone" onclick="document.getElementById('mobile-file-input').click()">
                            <input type="file" id="mobile-file-input" name="mobile_image" accept="image/*" style="display:none;" onchange="previewSingleImage(this, 'mobile-preview-container', 'mobile-preview-img')">
                            <div class="dropzone-content">
                                <div class="dropzone-icon">📱</div>
                                <div class="dropzone-text"><strong>Click to upload new mobile portrait image</strong></div>
                                <span class="dropzone-hint">Leave untouched to keep current mobile image</span>
                            </div>
                        </div>
                        <div class="form-group mt-2">
                            <input type="url" name="mobile_image_url_input" class="form-control" value="{{ old('mobile_image_url_input') }}" placeholder="Or paste direct mobile image URL (https://...)">
                        </div>
                        <div id="mobile-preview-container" class="mt-2" style="display:none;">
                            <span class="badge badge-success mb-1">New Selected:</span>
                            <img id="mobile-preview-img" src="" alt="Mobile Preview" style="max-height:140px;border-radius:var(--radius-md);border:1px solid var(--border-color);object-fit:cover;display:block;">
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
                            <input type="text" name="primary_button_text" class="form-control" value="{{ old('primary_button_text', $heroSlide->primary_button_text) }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Primary Button URL</label>
                            <input type="text" name="primary_button_url" class="form-control" value="{{ old('primary_button_url', $heroSlide->primary_button_url) }}">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Secondary Button Text (Optional)</label>
                            <input type="text" name="secondary_button_text" class="form-control" value="{{ old('secondary_button_text', $heroSlide->secondary_button_text) }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Secondary Button URL (Optional)</label>
                            <input type="text" name="secondary_button_url" class="form-control" value="{{ old('secondary_button_url', $heroSlide->secondary_button_url) }}">
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
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $heroSlide->is_active) ? 'checked' : '' }}>
                            <span class="form-label" style="margin:0;font-weight:600;">Active on Homepage</span>
                        </label>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Display Duration (Seconds)</label>
                        <input type="number" name="display_duration" class="form-control" min="3" max="60" value="{{ old('display_duration', $heroSlide->display_duration) }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Sort Order</label>
                        <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $heroSlide->sort_order) }}">
                    </div>
                </div>
            </div>

            {{-- Campaign Scheduling --}}
            <div class="card mb-4">
                <div class="card-header d-flex justify-between align-center">
                    <h3 style="font-size:1rem;margin:0;">Campaign Scheduling (Optional)</h3>
                    @if($heroSlide->ends_at && $heroSlide->ends_at->isPast())
                        <span class="badge" style="background:#fee2e2;color:#991b1b;border:1px solid #fecaca;font-size:0.7rem;font-weight:700;">
                            CAMPAIGN ENDED
                        </span>
                    @elseif($heroSlide->starts_at && $heroSlide->starts_at->isFuture())
                        <span class="badge" style="background:#e0f2fe;color:#0369a1;border:1px solid #bae6fd;font-size:0.7rem;font-weight:700;">
                            SCHEDULED
                        </span>
                    @elseif($heroSlide->starts_at || $heroSlide->ends_at)
                        <span class="badge" style="background:#dcfce7;color:#15803d;border:1px solid #bbf7d0;font-size:0.7rem;font-weight:700;">
                            CAMPAIGN LIVE
                        </span>
                    @endif
                </div>
                <div class="card-body">
                    {{-- Alert if campaign has already concluded --}}
                    @if($heroSlide->ends_at && $heroSlide->ends_at->isPast())
                        <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:6px;padding:12px 14px;margin-bottom:16px;">
                            <div style="display:flex;align-items:center;gap:6px;color:#991b1b;font-size:0.8rem;font-weight:700;text-transform:uppercase;letter-spacing:0.04em;">
                                <span>⏰</span> Campaign Concluded
                            </div>
                            <p style="margin:4px 0 10px;font-size:0.78rem;color:#7f1d1d;line-height:1.4;">
                                This campaign ended on <strong>{{ $heroSlide->ends_at->format('M d, Y · h:i A') }}</strong>.
                                Choose an option below or restart the schedule to make it an active standard slide on the homepage.
                            </p>
                            <div style="display:flex;gap:8px;flex-wrap:wrap;">
                                <button type="button" class="btn btn-secondary btn-sm" onclick="restartScheduleToStandard()" style="font-size:0.75rem;padding:5px 12px;display:inline-flex;align-items:center;gap:5px;background:#ffffff;border:1px solid #d1d5db;color:#111827;font-weight:600;">
                                    <span>↺</span> Restart Schedule (Clear Dates Like Image 1)
                                </button>
                                <button type="button" class="btn btn-secondary btn-sm" onclick="document.getElementById('starts-at-input').focus()" style="font-size:0.75rem;padding:5px 12px;background:#ffffff;border:1px solid #d1d5db;color:#111827;font-weight:600;">
                                    <span>📅</span> Reschedule at Another Date
                                </button>
                            </div>
                        </div>
                    @endif

                    <div class="form-group mb-3">
                        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px;">
                            <label class="form-label" style="margin:0;">Starts At</label>
                            <button type="button" onclick="document.getElementById('starts-at-input').value=''" style="background:none;border:none;color:#6b7280;font-size:0.72rem;cursor:pointer;padding:0;text-decoration:underline;">
                                Clear
                            </button>
                        </div>
                        <input type="datetime-local" id="starts-at-input" name="starts_at" class="form-control" value="{{ old('starts_at', $heroSlide->starts_at ? $heroSlide->starts_at->format('Y-m-d\TH:i') : '') }}">
                    </div>

                    <div class="form-group mb-3">
                        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px;">
                            <label class="form-label" style="margin:0;">Ends At</label>
                            <button type="button" onclick="document.getElementById('ends-at-input').value=''" style="background:none;border:none;color:#6b7280;font-size:0.72rem;cursor:pointer;padding:0;text-decoration:underline;">
                                Clear
                            </button>
                        </div>
                        <input type="datetime-local" id="ends-at-input" name="ends_at" class="form-control" value="{{ old('ends_at', $heroSlide->ends_at ? $heroSlide->ends_at->format('Y-m-d\TH:i') : '') }}">
                    </div>

                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;">
                        <span class="text-muted" style="font-size:0.72rem;">Leave blank to run slide indefinitely without schedule.</span>
                        <button type="button" onclick="restartScheduleToStandard()" style="background:none;border:none;color:#2563eb;font-size:0.72rem;cursor:pointer;padding:0;font-weight:600;text-decoration:underline;">
                            Reset both to blank
                        </button>
                    </div>

                    {{-- Post-Campaign Action Options --}}
                    <div style="border-top:1px solid #e5e5e5;padding-top:14px;">
                        <label class="form-label" style="font-size:0.8rem;text-transform:uppercase;letter-spacing:0.06em;font-weight:700;color:#000000;margin-bottom:6px;display:block;">
                            When Campaign Ends:
                        </label>
                        <p class="text-muted" style="font-size:0.75rem;margin:0 0 10px;">
                            Choose what happens once the scheduled end date/time arrives:
                        </p>

                        @php
                            $currentAction = old('post_campaign_action', $heroSlide->post_campaign_action ?? 'hide');
                        @endphp
                        <div style="display:flex;flex-direction:column;gap:10px;">
                            {{-- Option 1: Hide slide --}}
                            <label id="box-action-hide" style="display:flex;align-items:flex-start;gap:10px;background:#ffffff;border:1.5px solid {{ $currentAction === 'hide' ? '#000000' : '#e5e5e5' }};border-radius:6px;padding:12px;cursor:pointer;transition:border-color 0.15s;">
                                <input type="radio" name="post_campaign_action" value="hide" {{ $currentAction === 'hide' ? 'checked' : '' }} onchange="togglePostCampaignBoxes()" style="margin-top:3px;">
                                <div>
                                    <span style="font-size:0.82rem;font-weight:700;color:#111827;display:block;">
                                        1. Do not show slide on homepage (Reschedule later)
                                    </span>
                                    <span class="text-muted" style="font-size:0.74rem;display:block;margin-top:3px;line-height:1.4;">
                                        Hides the slide from the homepage when the campaign is over. Keeps the scheduled start and end dates saved so you can easily reschedule it for another date.
                                    </span>
                                </div>
                            </label>

                            {{-- Option 2: Keep showing & reset schedule --}}
                            <label id="box-action-keep" style="display:flex;align-items:flex-start;gap:10px;background:#ffffff;border:1.5px solid {{ $currentAction === 'keep_showing' ? '#000000' : '#e5e5e5' }};border-radius:6px;padding:12px;cursor:pointer;transition:border-color 0.15s;">
                                <input type="radio" name="post_campaign_action" value="keep_showing" {{ $currentAction === 'keep_showing' ? 'checked' : '' }} onchange="togglePostCampaignBoxes()" style="margin-top:3px;">
                                <div>
                                    <span style="font-size:0.82rem;font-weight:700;color:#111827;display:block;">
                                        2. Show slide & allow it to be part of the slides (Restart schedule like Image 1)
                                    </span>
                                    <span class="text-muted" style="font-size:0.74rem;display:block;margin-top:3px;line-height:1.4;">
                                        Keeps the slide live on the homepage as a standard evergreen slide, automatically clearing the Starts At & Ends At dates back to blank.
                                    </span>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-lg w-100 mb-2">Update Hero Slide</button>
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

function restartScheduleToStandard() {
    const startInput = document.getElementById('starts-at-input');
    const endInput = document.getElementById('ends-at-input');
    if (startInput) startInput.value = '';
    if (endInput) endInput.value = '';
    const keepRadio = document.querySelector('input[name="post_campaign_action"][value="keep_showing"]');
    if (keepRadio) {
        keepRadio.checked = true;
        togglePostCampaignBoxes();
    }
}

function togglePostCampaignBoxes() {
    const hideRadio = document.querySelector('input[name="post_campaign_action"][value="hide"]');
    const boxHide = document.getElementById('box-action-hide');
    const boxKeep = document.getElementById('box-action-keep');
    if (boxHide && boxKeep && hideRadio) {
        boxHide.style.borderColor = hideRadio.checked ? '#000000' : '#e5e5e5';
        boxKeep.style.borderColor = !hideRadio.checked ? '#000000' : '#e5e5e5';
    }
}
</script>
@endpush
