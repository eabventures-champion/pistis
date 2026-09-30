@extends('layouts.admin')

@section('title', 'Settings')

@section('content')
<div class="admin-header mb-4">
    <h1 style="font-size:1.6rem; font-weight:700; margin-bottom:4px;">Settings & Brand Identity</h1>
    <p class="text-muted" style="font-size:0.9rem;">Manage your store details, uploaded logo, navigation branding, and payment configurations.</p>
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

<form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div style="display:grid; grid-template-columns:1.2fr 1fr; gap:28px; align-items:start;">
        
        {{-- Left Column: Store Details & Brand Logo --}}
        <div>
            {{-- Brand Logo Card --}}
            <div class="card mb-4">
                <div class="card-header">
                    <div style="display:flex; justify-content:space-between; align-items:center; gap:16px;">
                        <h3 style="font-size:1.05rem; font-weight:700; margin:0; text-transform:uppercase; letter-spacing:0.04em;">Store Logo & Brand Identity</h3>
                        @if(!empty($settings['store_logo_url']))
                            <span class="badge badge-success" style="background:#16a34a; color:#fff; padding:4px 12px; border-radius:20px; font-size:0.75rem; font-weight:600; letter-spacing:0.04em; white-space:nowrap; flex-shrink:0;">CUSTOM LOGO</span>
                        @else
                            <span class="badge badge-secondary" style="background:#737373; color:#fff; padding:4px 12px; border-radius:20px; font-size:0.75rem; font-weight:600; letter-spacing:0.04em; white-space:nowrap; flex-shrink:0;">DEFAULT [P] ICON</span>
                        @endif
                    </div>
                    <p class="text-muted" style="font-size:0.84rem; margin:6px 0 0 0; line-height:1.5;">
                        Upload and adjust the primary brand logo displayed across navigation bars and headers.
                    </p>
                </div>
                <div class="card-body">
                    {{-- Current Logo Preview --}}
                    <div class="form-group mb-4">
                        <label class="form-label" style="font-weight:600; font-size:0.88rem;">Current Logo Presentation</label>
                        <div style="padding:16px 20px; border-radius:10px; background:var(--bg-secondary); border:1px solid var(--border-color); display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:16px;">
                            <div style="display:flex; align-items:center; gap:16px;">
                                <div style="padding:16px 22px; background:#ffffff; border:1px solid #e5e5e5; border-radius:8px; display:inline-flex; align-items:center; justify-content:center; min-width:90px; min-height:80px; box-shadow:0 2px 8px rgba(0,0,0,0.04);">
                                    @if(!empty($settings['store_logo_url']))
                                        <img id="logoPreviewImg" src="{{ $settings['store_logo_url'] }}" alt="Store Logo" style="max-height:{{ $settings['store_logo_height'] }}px; width:auto; max-width:240px; object-fit:contain; border-radius:50%; aspect-ratio:1/1; box-shadow:0 2px 10px rgba(0,0,0,0.08);">
                                    @else
                                        <div style="display:flex; align-items:center; gap:10px;">
                                            <span class="brand-icon" style="width:28px; height:28px; background:#000; color:#fff; display:flex; align-items:center; justify-content:center; font-family:'Cormorant Garamond', serif; font-size:1.1rem; font-weight:bold;">P</span>
                                            <span style="font-weight:800; letter-spacing:0.25em; font-size:0.95rem; color:#000;">{{ $settings['store_name'] }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <strong style="font-size:0.9rem; display:block; color:var(--text-primary);">Header Brand Output</strong>
                                    <span class="text-muted" style="font-size:0.78rem;">Live height: <span id="logoHeightText">{{ $settings['store_logo_height'] }}px</span></span>
                                </div>
                            </div>

                            @if(!empty($settings['store_logo_url']))
                                <button type="button" class="btn btn-sm" onclick="confirmRemoveLogo()" style="color:#ef4444; border:1px solid #ef4444; background:transparent; padding:6px 14px; border-radius:6px; font-size:0.8rem; cursor:pointer;">
                                    Revert to Default Icon
                                </button>
                            @endif
                        </div>
                    </div>

                    {{-- Logo File Upload --}}
                    <div class="form-group mb-4">
                        <label class="form-label" style="font-weight:600; font-size:0.85rem;">Upload New Logo File</label>
                        <div id="logoDropBox" style="border:2px dashed var(--border-color); border-radius:10px; padding:24px 16px; text-align:center; background:var(--bg-secondary); cursor:pointer; transition:all 0.2s ease;">
                            <input type="file" name="store_logo" id="store_logo_input" accept="image/png,image/svg+xml,image/jpeg,image/webp,image/gif" style="display:none;" onchange="handleLogoSelect(this)">
                            <div id="logoUploadPlaceholder">
                                <span style="font-size:2rem; display:block; margin-bottom:6px;">🖼️</span>
                                <div style="font-weight:600; font-size:0.92rem; color:var(--text-primary); margin-bottom:2px;">
                                    Click to select image or drag & drop
                                </div>
                                <div class="text-muted" style="font-size:0.78rem;">
                                    Recommended: Transparent PNG or SVG vector · Max size: 5MB
                                </div>
                            </div>
                            <div id="newLogoPreviewWrap" style="display:none; padding:8px;">
                                <span style="font-weight:600; font-size:0.85rem; color:var(--text-primary);" id="newLogoFileName"></span>
                                <div style="margin-top:10px;">
                                    <img id="newLogoPreviewImg" src="" alt="Selected Preview" style="max-height:50px; width:auto; object-fit:contain; border:1px solid #ddd; padding:4px; border-radius:4px; background:#fff;">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Logo Height Slider --}}
                    <div class="form-group mb-4">
                        <label class="form-label" style="font-weight:600; font-size:0.85rem; display:flex; justify-content:space-between;">
                            <span>Adjust Logo Height in Navbar:</span>
                            <strong id="sliderValueText" style="color:var(--primary);">{{ $settings['store_logo_height'] }}px</strong>
                        </label>
                        <input type="range" name="store_logo_height" id="logoHeightSlider" min="24" max="120" step="2" value="{{ $settings['store_logo_height'] }}" class="form-control" style="cursor:pointer;" oninput="updateLogoHeightLive(this.value)">
                        <div style="display:flex; justify-content:space-between; font-size:0.72rem; color:var(--text-muted); margin-top:4px;">
                            <span>24px (Subtle)</span>
                            <span>48px</span>
                            <span>72px (Standard)</span>
                            <span>96px (Prominent)</span>
                            <span>120px (Grand)</span>
                        </div>
                    </div>

                    {{-- Hide Brand Name Text Toggle --}}
                    <div class="form-group" style="padding:12px 14px; background:var(--bg-secondary); border-radius:8px; border:1px solid var(--border-light);">
                        <label class="form-check" style="display:flex; justify-content:space-between; align-items:center; cursor:pointer; margin:0;">
                            <div>
                                <strong style="font-size:0.88rem; display:block; color:var(--text-primary);">Hide Store Name Text in Header</strong>
                                <span class="text-muted" style="font-size:0.78rem;">Check this if your uploaded logo image already incorporates the word "PISTIS" or your brand name.</span>
                            </div>
                            <input type="checkbox" name="store_hide_brand_text" value="1" {{ $settings['store_hide_brand_text'] ? 'checked' : '' }} style="width:18px; height:18px; cursor:pointer;">
                        </label>
                    </div>
                </div>
            </div>

            {{-- Store Contact & Address Card --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h3 style="font-size:1.05rem; font-weight:700; margin:0; text-transform:uppercase; letter-spacing:0.04em;">Store Details</h3>
                </div>
                <div class="card-body">
                    <div class="form-group mb-3">
                        <label class="form-label" style="font-weight:600; font-size:0.85rem;">Store Name</label>
                        <input type="text" name="store_name" class="form-control" value="{{ $settings['store_name'] }}">
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label" style="font-weight:600; font-size:0.85rem;">Store Email</label>
                        <input type="email" name="store_email" class="form-control" value="{{ $settings['store_email'] }}">
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label" style="font-weight:600; font-size:0.85rem;">Store Phone</label>
                        <input type="text" name="store_phone" class="form-control" value="{{ $settings['store_phone'] }}">
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label" style="font-weight:600; font-size:0.85rem;">Store Address</label>
                        <textarea name="store_address" class="form-control" rows="2">{{ $settings['store_address'] }}</textarea>
                    </div>
                    <div class="form-row" style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                        <div class="form-group">
                            <label class="form-label" style="font-weight:600; font-size:0.85rem;">Currency Symbol</label>
                            <input type="text" name="currency_symbol" class="form-control" value="{{ $settings['currency_symbol'] }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label" style="font-weight:600; font-size:0.85rem;">Currency Code</label>
                            <input type="text" name="currency_code" class="form-control" value="{{ $settings['currency_code'] }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column: Gateways & Actions --}}
        <div>
            {{-- Payment Gateway --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h3 style="font-size:1.05rem; font-weight:700; margin:0; text-transform:uppercase; letter-spacing:0.04em;">Payment Gateway</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label" style="font-weight:600; font-size:0.85rem;">Active Gateway</label>
                        <select name="active_payment_gateway" class="form-control">
                            <option value="paypal" selected>PayPal (Default)</option>
                        </select>
                        <p class="text-muted mt-2" style="font-size:0.8rem; margin:0;">API keys are configured in the .env file</p>
                    </div>
                </div>
            </div>

            {{-- Shopify Status --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h3 style="font-size:1.05rem; font-weight:700; margin:0; text-transform:uppercase; letter-spacing:0.04em;">Shopify Connection</h3>
                </div>
                <div class="card-body">
                    @if($settings['shopify_store_url'])
                        <div class="d-flex align-center gap-2 mb-3">
                            <span class="badge badge-success" style="background:#16a34a; color:#fff; padding:4px 10px; border-radius:20px; font-size:0.75rem;">Connected</span>
                            <span class="text-muted" style="font-size:0.85rem;">{{ $settings['shopify_store_url'] }}</span>
                        </div>
                    @else
                        <div class="d-flex align-center gap-2 mb-3">
                            <span class="badge badge-warning" style="background:#ca8a04; color:#fff; padding:4px 10px; border-radius:20px; font-size:0.75rem;">Not Configured</span>
                        </div>
                    @endif
                    <p class="text-muted" style="font-size:0.8rem; margin:0;">Credentials configured in .env file</p>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-lg w-100" style="padding:14px; font-weight:700; letter-spacing:0.05em;">
                SAVE SETTINGS & LOGO
            </button>
        </div>
    </div>
</form>

{{-- Form to handle logo removal --}}
<form id="removeLogoForm" action="{{ route('admin.settings.remove-logo') }}" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

<script>
    const logoDropBox = document.getElementById('logoDropBox');
    const logoInput = document.getElementById('store_logo_input');

    if (logoDropBox && logoInput) {
        logoDropBox.addEventListener('click', () => logoInput.click());

        ['dragenter', 'dragover'].forEach(eventName => {
            logoDropBox.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                logoDropBox.style.borderColor = 'var(--primary)';
                logoDropBox.style.background = 'rgba(0,0,0,0.02)';
            });
        });

        ['dragleave', 'drop'].forEach(eventName => {
            logoDropBox.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                logoDropBox.style.borderColor = 'var(--border-color)';
                logoDropBox.style.background = 'var(--bg-secondary)';
            });
        });

        logoDropBox.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;
            if (files && files.length > 0) {
                logoInput.files = files;
                handleLogoSelect(logoInput);
            }
        });
    }

    function handleLogoSelect(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            document.getElementById('logoUploadPlaceholder').style.display = 'none';
            document.getElementById('newLogoPreviewWrap').style.display = 'block';
            document.getElementById('newLogoFileName').innerText = 'Selected: ' + file.name;

            const preview = document.getElementById('newLogoPreviewImg');
            const fileURL = URL.createObjectURL(file);
            preview.src = fileURL;

            // Also update main preview if exists
            const mainPreview = document.getElementById('logoPreviewImg');
            if (mainPreview) {
                mainPreview.src = fileURL;
            }
        }
    }

    function updateLogoHeightLive(val) {
        document.getElementById('sliderValueText').innerText = val + 'px';
        const heightText = document.getElementById('logoHeightText');
        if (heightText) heightText.innerText = val + 'px';
        const mainPreview = document.getElementById('logoPreviewImg');
        if (mainPreview) {
            mainPreview.style.maxHeight = val + 'px';
        }
    }

    function confirmRemoveLogo() {
        if (confirm('Are you sure you want to remove the custom logo and revert to the default brand mark?')) {
            document.getElementById('removeLogoForm').submit();
        }
    }
</script>
@endsection
