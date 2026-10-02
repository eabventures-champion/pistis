@extends('layouts.admin')

@section('title', 'Campaign Video')

@section('content')
<div class="admin-header d-flex justify-between align-center mb-4">
    <div>
        <h1 style="font-size: 1.6rem; font-weight: 700; margin-bottom: 4px;">Campaign & Lookbook Video</h1>
        <p class="text-muted" style="font-size: 0.9rem;">Configure the timed cinematic video that introduces your clothing wear, fabric motion, and editorial collections to visitors.</p>
    </div>
    @if($activeVideoUrl)
        <button type="button" class="btn btn-secondary" onclick="previewCampaignModal()" style="display:inline-flex; align-items:center; gap:8px;">
            <span>👁️</span> Test Live Appearance
        </button>
    @endif
</div>

@if(session('success'))
    <div class="alert alert-success mb-4" style="padding:14px 18px; border-radius:8px; background:var(--success-bg, rgba(22,163,74,0.1)); border:1px solid var(--success, #16a34a); color:var(--success, #16a34a);">
        {{ session('success') }}
    </div>
@endif

@if(isset($errors) && $errors->any())
    <div class="alert alert-danger mb-4" style="padding:14px 18px; border-radius:8px; background:var(--danger-bg, rgba(220,38,38,0.1)); border:1px solid var(--danger, #dc2626); color:var(--danger, #dc2626);">
        <ul style="margin:0; padding-left:20px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.campaign-video.update') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div style="display:grid; grid-template-columns: 1.5fr 1fr; gap: 28px; align-items: start;">
        
        {{-- Left Column: Media & Editorial Details --}}
        <div>
            {{-- Video Upload & Current Player Card --}}
            <div class="card mb-4">
                <div class="card-header">
                    <div style="display:flex; justify-content:space-between; align-items:center; gap:16px;">
                        <h3 style="font-size:1.05rem; font-weight:700; margin:0; text-transform:uppercase; letter-spacing:0.04em;">Campaign Video Media</h3>
                        @if($activeVideoUrl)
                            <span class="badge badge-success" style="background:#16a34a; color:#fff; padding:4px 12px; border-radius:20px; font-size:0.75rem; font-weight:600; letter-spacing:0.04em; white-space:nowrap; flex-shrink:0;">VIDEO ACTIVE</span>
                        @else
                            <span class="badge badge-warning" style="background:#ca8a04; color:#fff; padding:4px 12px; border-radius:20px; font-size:0.75rem; font-weight:600; letter-spacing:0.04em; white-space:nowrap; flex-shrink:0;">NO VIDEO LOADED</span>
                        @endif
                    </div>
                    <p class="text-muted" style="font-size:0.84rem; margin:6px 0 0 0; line-height:1.5;">
                        Upload the lookbook film showcasing your clothing drape, fabric, and styling.
                    </p>
                </div>
                <div class="card-body">
                    {{-- Current Video Preview Player --}}
                    @if($activeVideoUrl)
                        <div class="current-video-preview mb-4" style="background:#0a0a0a; border-radius:10px; overflow:hidden; border:1px solid rgba(255,255,255,0.1); position:relative;">
                            <video id="adminVideoPlayer" src="{{ $activeVideoUrl }}" controls playsinline style="width:100%; max-height:360px; object-fit:cover; display:block;"></video>
                            <div style="padding:12px 16px; background:#121212; display:flex; justify-content:space-between; align-items:center; border-top:1px solid rgba(255,255,255,0.06);">
                                <div style="font-size:0.8rem; color:#a3a3a3; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:70%;">
                                    <strong>Active Source:</strong> {{ $settings['video_path'] ? basename($settings['video_path']) : $settings['video_url'] }}
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmRemoveVideo()" style="color:#ef4444; border:1px solid #ef4444; background:transparent; padding:4px 12px; border-radius:6px; font-size:0.78rem; cursor:pointer;">
                                    Remove Video
                                </button>
                            </div>
                        </div>
                    @endif

                    {{-- File Upload Area --}}
                    <div class="form-group mb-4">
                        <label class="form-label" style="font-weight:600; font-size:0.88rem;">Upload Video File</label>
                        <div id="videoDropArea" style="border:2px dashed var(--border-color); border-radius:10px; padding:28px 20px; text-align:center; background:var(--bg-secondary); cursor:pointer; transition:border-color 0.2s ease;">
                            <input type="file" name="campaign_video_file" id="campaign_video_file" accept="video/mp4,video/webm,video/ogg,video/quicktime" style="display:none;" onchange="handleVideoFileSelect(this)">
                            <div id="uploadPlaceholder">
                                <span style="font-size:2.2rem; display:block; margin-bottom:8px;">🎬</span>
                                <div style="font-weight:600; font-size:0.95rem; color:var(--text-primary); margin-bottom:4px;">
                                    Click to select fashion campaign video or drag & drop
                                </div>
                                <div class="text-muted" style="font-size:0.8rem;">
                                    Supported formats: MP4, WebM, MOV · Max file size: 100MB
                                </div>
                            </div>
                            <div id="selectedFileInfo" style="display:none; padding:10px; background:rgba(0,0,0,0.05); border-radius:8px;">
                                <span style="font-weight:600; color:var(--text-primary);" id="selectedFileName"></span>
                                <div class="text-muted" style="font-size:0.8rem; margin-top:2px;" id="selectedFileSize"></div>
                                <video id="clientVideoPreview" controls style="max-width:100%; max-height:220px; margin-top:12px; border-radius:6px; display:none;"></video>
                            </div>
                        </div>
                    </div>

                    {{-- Direct Video URL (Optional Alternative) --}}
                    <div class="form-group">
                        <label class="form-label" style="font-weight:600; font-size:0.85rem;">Or External Video Stream URL (Optional CDN link)</label>
                        <input type="url" name="campaign_video_url" class="form-control" placeholder="https://cdn.example.com/editorial-campaign.mp4" value="{{ old('campaign_video_url', $settings['video_url']) }}">
                        <small class="text-muted" style="font-size:0.78rem; display:block; margin-top:4px;">Direct link to an .mp4/.webm file hosted on AWS S3, Cloudflare R2, or CDN.</small>
                    </div>
                </div>
            </div>

            {{-- Apparel & Lookbook Copy Card --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h3 style="font-size:1.05rem; font-weight:700; margin:0; text-transform:uppercase; letter-spacing:0.04em;">Apparel & Editorial Copy</h3>
                    <p class="text-muted" style="font-size:0.84rem; margin:6px 0 0 0; line-height:1.5;">
                        Text accompanying the video to engage shoppers with the collection.
                    </p>
                </div>
                <div class="card-body">
                    <div class="form-group mb-3">
                        <label class="form-label" style="font-weight:600; font-size:0.85rem;">Collection Badge / Tagline</label>
                        <input type="text" name="campaign_video_badge" class="form-control" value="{{ old('campaign_video_badge', $settings['badge']) }}" placeholder="e.g. AUTUMN / WINTER 2026 · RUNWAY PREMIERE">
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label" style="font-weight:600; font-size:0.85rem;">Campaign Headline <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="campaign_video_title" class="form-control" required value="{{ old('campaign_video_title', $settings['title']) }}" placeholder="e.g. THE NEW SILHOUETTE IN MOTION">
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label" style="font-weight:600; font-size:0.85rem;">Description / Lookbook Notes</label>
                        <textarea name="campaign_video_description" rows="3" class="form-control" placeholder="Describe the tailoring, fabric texture, styling, or craftsmanship...">{{ old('campaign_video_description', $settings['description']) }}</textarea>
                    </div>

                    <div class="form-row" style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                        <div class="form-group">
                            <label class="form-label" style="font-weight:600; font-size:0.85rem;">CTA Button Label</label>
                            <input type="text" name="campaign_video_cta_text" class="form-control" value="{{ old('campaign_video_cta_text', $settings['cta_text']) }}" placeholder="e.g. SHOP THE COLLECTION">
                        </div>
                        <div class="form-group">
                            <label class="form-label" style="font-weight:600; font-size:0.85rem;">CTA Button Target URL</label>
                            <input type="text" name="campaign_video_cta_url" class="form-control" value="{{ old('campaign_video_cta_url', $settings['cta_url']) }}" placeholder="/shop">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column: Timing & Rules Settings --}}
        <div>
            <div class="card mb-4">
                <div class="card-header">
                    <h3 style="font-size:1.05rem; font-weight:700; margin:0; text-transform:uppercase; letter-spacing:0.04em;">Timing & Display Rules</h3>
                    <p class="text-muted" style="font-size:0.84rem; margin:6px 0 0 0; line-height:1.5;">
                        Control when and how frequently the video emerges for visitors.
                    </p>
                </div>
                <div class="card-body">
                    {{-- Active Switch --}}
                    <div class="form-group mb-4" style="padding:14px 16px; background:var(--bg-secondary); border-radius:8px; border:1px solid var(--border-light);">
                        <label class="form-check" style="display:flex; justify-content:space-between; align-items:center; cursor:pointer; margin:0;">
                            <div>
                                <strong style="font-size:0.92rem; display:block; color:var(--text-primary);">Enable Campaign Video</strong>
                                <span class="text-muted" style="font-size:0.8rem;">When enabled, the video will automatically introduce itself to visitors.</span>
                            </div>
                            <input type="checkbox" name="campaign_video_enabled" value="1" {{ old('campaign_video_enabled', $settings['enabled']) ? 'checked' : '' }} style="width:20px; height:20px; cursor:pointer;">
                        </label>
                    </div>

                    {{-- Time Interval / Delay --}}
                    <div class="form-group mb-4">
                        <label class="form-label" style="font-weight:600; font-size:0.88rem; display:flex; justify-content:space-between;">
                            <span>Appearance Delay After Arrival:</span>
                            <span id="delayDisplay" style="font-weight:700; color:var(--primary);">{{ old('campaign_video_delay', $settings['delay']) }} seconds</span>
                        </label>
                        <input type="range" name="campaign_video_delay" id="delaySlider" min="1" max="60" step="1" value="{{ old('campaign_video_delay', $settings['delay']) }}" class="form-control" style="cursor:pointer;" oninput="document.getElementById('delayDisplay').innerText = this.value + ' seconds'">
                        <div style="display:flex; justify-content:space-between; font-size:0.75rem; color:var(--text-muted); margin-top:4px;">
                            <span>1s (Instant)</span>
                            <span>5s (Recommended)</span>
                            <span>15s</span>
                            <span>60s</span>
                        </div>
                        <small class="text-muted" style="font-size:0.78rem; display:block; margin-top:8px;">
                            The cinematic video smoothly transitions in after the shopper has been on the site for this duration.
                        </small>
                    </div>

                    {{-- Target Page Selection --}}
                    <div class="form-group mb-4">
                        <label class="form-label" style="font-weight:600; font-size:0.85rem;">Display Location</label>
                        <select name="campaign_video_target_page" class="form-control">
                            <option value="homepage" {{ old('campaign_video_target_page', $settings['target_page']) === 'homepage' ? 'selected' : '' }}>Homepage Only (Recommended for lookbooks)</option>
                            <option value="all" {{ old('campaign_video_target_page', $settings['target_page']) === 'all' ? 'selected' : '' }}>All Storefront Pages</option>
                        </select>
                    </div>

                    {{-- Frequency Rule --}}
                    <div class="form-group mb-4">
                        <label class="form-label" style="font-weight:600; font-size:0.85rem;">Visitor Display Frequency</label>
                        <select name="campaign_video_frequency" class="form-control">
                            <option value="once_on_site" {{ old('campaign_video_frequency', $settings['frequency']) === 'once_on_site' ? 'selected' : '' }}>Once on Website (Auto-plays once after delay, then manual only)</option>
                            <option value="once_per_session" {{ old('campaign_video_frequency', $settings['frequency']) === 'once_per_session' ? 'selected' : '' }}>Once Per Browsing Session</option>
                            <option value="once_per_day" {{ old('campaign_video_frequency', $settings['frequency']) === 'once_per_day' ? 'selected' : '' }}>Once Every 24 Hours</option>
                            <option value="always" {{ old('campaign_video_frequency', $settings['frequency']) === 'always' ? 'selected' : '' }}>Every Visit (Always trigger after delay)</option>
                        </select>
                        <small class="text-muted" style="font-size:0.78rem; display:block; margin-top:4px;">
                            After auto-playing once, visitors can re-watch the video anytime via the floating social tray or campaign launcher.
                        </small>
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit" class="btn btn-primary btn-lg w-100" style="padding:14px; font-weight:700; letter-spacing:0.05em;">
                        SAVE SETTINGS & VIDEO
                    </button>
                </div>
            </div>
        </div>

    </div>
</form>

{{-- Form to handle deletion of uploaded video --}}
<form id="removeVideoForm" action="{{ route('admin.campaign-video.remove-video') }}" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

{{-- Admin Frontend Preview Modal --}}
<div id="adminPreviewModal" style="display:none; position:fixed; inset:0; z-index:99999; background:rgba(0,0,0,0.90); backdrop-filter:blur(24px) saturate(180%); -webkit-backdrop-filter:blur(24px) saturate(180%); align-items:center; justify-content:center; padding:16px; box-sizing:border-box;">
    <div id="adminPreviewDialog" style="background:#000; border:1px solid rgba(255,255,255,0.16); border-radius:14px; width:auto; min-width:320px; max-width:min(860px, 94vw); max-height:min(90vh, 780px); overflow:hidden; box-shadow:0 30px 100px -10px rgba(0,0,0,0.95); position:relative; display:flex; flex-direction:column; margin:auto; transition:max-width 0.3s ease;">
        {{-- Modal Top Bar --}}
        <div style="flex-shrink:0; padding:14px 20px; border-bottom:1px solid rgba(255,255,255,0.08); display:flex; justify-content:space-between; align-items:center; background:linear-gradient(180deg, #141414 0%, #000 100%);">
            <div style="overflow:hidden; padding-right:12px;">
                <span id="previewModalBadge" style="font-size:0.68rem; letter-spacing:0.2em; text-transform:uppercase; color:#a3a3a3; font-weight:600; display:block;">{{ $settings['badge'] }}</span>
                <h4 id="previewModalTitle" style="color:#fff; font-size:1.2rem; margin:2px 0 0 0; font-family:'Cormorant Garamond', Georgia, serif; font-weight:600; letter-spacing:0.03em; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $settings['title'] }}</h4>
            </div>
            <button type="button" onclick="closeAdminPreviewModal()" style="flex-shrink:0; background:rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.15); color:#fff; width:32px; height:32px; border-radius:50%; font-size:1.1rem; cursor:pointer; display:flex; align-items:center; justify-content:center;">✕</button>
        </div>

        {{-- Video Player Box --}}
        <div id="adminPreviewFrame" style="flex:1 1 auto; min-height:240px; height:58vh; max-height:calc(88vh - 140px); position:relative; background:#000; display:flex; align-items:center; justify-content:center; overflow:hidden;">
            <video id="previewModalVideo" src="{{ $activeVideoUrl }}" playsinline loop controls style="max-width:100% !important; max-height:100% !important; width:auto !important; height:100% !important; object-fit:contain !important; object-position:center !important; display:block !important; margin:0 auto !important;"></video>
        </div>

        {{-- Modal Footer --}}
        <div id="adminPreviewFooter" style="flex-shrink:0; padding:14px 20px; background:#080808; border-top:1px solid rgba(255,255,255,0.08); display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
            <p id="previewModalDesc" style="color:#a3a3a3; font-size:0.82rem; margin:0; max-width:60%; line-height:1.4;">{{ $settings['description'] }}</p>
            <div style="display:flex; gap:12px; align-items:center; flex-shrink:0;">
                <button type="button" onclick="closeAdminPreviewModal()" style="background:transparent; border:none; color:#737373; font-size:0.82rem; cursor:pointer; padding:6px 10px;">Continue Browsing</button>
                <a href="{{ $settings['cta_url'] }}" target="_blank" id="previewModalCta" style="background:#fff; color:#000; padding:9px 18px; font-size:0.78rem; font-weight:700; text-decoration:none; letter-spacing:0.08em; text-transform:uppercase; border-radius:4px;">{{ $settings['cta_text'] }} →</a>
            </div>
        </div>
    </div>
</div>

<script>
    const dropArea = document.getElementById('videoDropArea');
    const fileInput = document.getElementById('campaign_video_file');

    if (dropArea && fileInput) {
        dropArea.addEventListener('click', () => fileInput.click());

        ['dragenter', 'dragover'].forEach(eventName => {
            dropArea.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropArea.style.borderColor = 'var(--primary)';
                dropArea.style.background = 'rgba(0,0,0,0.02)';
            });
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropArea.style.borderColor = 'var(--border-color)';
                dropArea.style.background = 'var(--bg-secondary)';
            });
        });

        dropArea.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;
            if (files && files.length > 0) {
                fileInput.files = files;
                handleVideoFileSelect(fileInput);
            }
        });
    }

    function handleVideoFileSelect(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            document.getElementById('uploadPlaceholder').style.display = 'none';
            document.getElementById('selectedFileInfo').style.display = 'block';
            document.getElementById('selectedFileName').innerText = 'Selected: ' + file.name;
            document.getElementById('selectedFileSize').innerText = 'Size: ' + (file.size / (1024 * 1024)).toFixed(2) + ' MB';

            const preview = document.getElementById('clientVideoPreview');
            const fileURL = URL.createObjectURL(file);
            preview.src = fileURL;
            preview.style.display = 'block';
        }
    }

    function confirmRemoveVideo() {
        if (confirm('Are you sure you want to remove the current campaign video?')) {
            document.getElementById('removeVideoForm').submit();
        }
    }

    function previewCampaignModal() {
        const modal = document.getElementById('adminPreviewModal');
        const dialog = document.getElementById('adminPreviewDialog');
        const video = document.getElementById('previewModalVideo');
        if (modal) {
            modal.style.display = 'flex';
            if (video) {
                if (video.videoHeight && video.videoWidth && video.videoHeight > video.videoWidth) {
                    dialog.style.maxWidth = 'min(440px, 92vw)';
                } else {
                    dialog.style.maxWidth = 'min(860px, 94vw)';
                }
                video.currentTime = 0;
                video.play().catch(() => {});
            }
        }
    }

    function closeAdminPreviewModal() {
        const modal = document.getElementById('adminPreviewModal');
        const video = document.getElementById('previewModalVideo');
        if (modal) {
            modal.style.display = 'none';
            if (video) {
                video.pause();
            }
        }
    }
</script>
@endsection
