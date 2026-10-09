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
                    <div class="form-group mb-3" style="padding:12px 14px; background:var(--bg-secondary); border-radius:8px; border:1px solid var(--border-light);">
                        <label class="form-check" style="display:flex; justify-content:space-between; align-items:center; cursor:pointer; margin:0;">
                            <div>
                                <strong style="font-size:0.88rem; display:block; color:var(--text-primary);">Hide Store Name Text in Header</strong>
                                <span class="text-muted" style="font-size:0.78rem;">Check this if your uploaded logo image already incorporates the word "PISTIS" or your brand name.</span>
                            </div>
                            <input type="checkbox" name="store_hide_brand_text" value="1" {{ $settings['store_hide_brand_text'] ? 'checked' : '' }} style="width:18px; height:18px; cursor:pointer; accent-color:#000000;">
                        </label>
                    </div>

                    {{-- Homepage Categories Visibility Toggle --}}
                    <div class="form-group mb-3" style="padding:12px 14px; background:var(--bg-secondary); border-radius:8px; border:1px solid var(--border-light);">
                        <label class="form-check" style="display:flex; justify-content:space-between; align-items:center; cursor:pointer; margin:0;">
                            <div>
                                <strong style="font-size:0.88rem; display:block; color:var(--text-primary);">Show "Shop by Category" on Homepage</strong>
                                <span class="text-muted" style="font-size:0.78rem;">Display the categories grid on the storefront homepage. Uncheck to hide the entire category section.</span>
                            </div>
                            <input type="checkbox" name="homepage_show_categories" value="1" {{ ($settings['homepage_show_categories'] ?? true) ? 'checked' : '' }} style="width:18px; height:18px; cursor:pointer; accent-color:#000000;">
                        </label>
                    </div>

                    {{-- Editorial Selection Category Badges Visibility Toggle --}}
                    <div class="form-group" style="padding:12px 14px; background:var(--bg-secondary); border-radius:8px; border:1px solid var(--border-light);">
                        <label class="form-check" style="display:flex; justify-content:space-between; align-items:center; cursor:pointer; margin:0;">
                            <div>
                                <strong style="font-size:0.88rem; display:block; color:var(--text-primary);">Show Category Badges in Editorial Selection</strong>
                                <span class="text-muted" style="font-size:0.78rem;">Display the category filter badges (All, Clothing, Footwear, etc.) next to "Editorial Selection" on the homepage. Uncheck to hide.</span>
                            </div>
                            <input type="checkbox" name="homepage_show_editorial_categories" value="1" {{ ($settings['homepage_show_editorial_categories'] ?? true) ? 'checked' : '' }} style="width:18px; height:18px; cursor:pointer; accent-color:#000000;">
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

            {{-- Store Policies & Product Accordions Card --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h3 style="font-size:1.05rem; font-weight:700; margin:0; text-transform:uppercase; letter-spacing:0.04em;">Store Policies & Product Accordions</h3>
                    <p class="text-muted" style="font-size:0.84rem; margin:6px 0 0 0; line-height:1.5;">
                        Configure default policies and specifications that automatically appear in product accordions across the storefront. Individual products can override these at any time.
                    </p>
                </div>
                <div class="card-body">
                    <div class="form-group mb-4">
                        <label class="form-label" style="font-weight:600; font-size:0.85rem;">Default Details & Fit Specification</label>
                        <textarea name="default_details_and_fit" class="form-control" rows="4" placeholder="Enter bullet points (one per line) or text for piece specifications...">{{ $settings['default_details_and_fit'] }}</textarea>
                        <small class="text-muted" style="font-size:0.75rem; display:block; margin-top:4px;">Used as fallback if a product does not have custom piece details specified.</small>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label" style="font-weight:600; font-size:0.85rem;">Default Shipping & Returns Policy</label>
                        <textarea name="default_shipping_and_returns" class="form-control" rows="4" placeholder="Enter standard shipping and return terms...">{{ $settings['default_shipping_and_returns'] }}</textarea>
                        <small class="text-muted" style="font-size:0.75rem; display:block; margin-top:4px;">Displayed in the 'Shipping & Returns' accordion on all product pages.</small>
                    </div>

                    <div class="form-group mb-2">
                        <label class="form-label" style="font-weight:600; font-size:0.85rem;">Default Garment Care Guidelines</label>
                        <textarea name="default_garment_care" class="form-control" rows="4" placeholder="Enter general wash and garment preservation instructions...">{{ $settings['default_garment_care'] }}</textarea>
                        <small class="text-muted" style="font-size:0.75rem; display:block; margin-top:4px;">Displayed in the 'Garment Care' accordion on all product pages.</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column: Gateways & Actions --}}
        <div>
            {{-- Floating Social Media & Handles Card --}}
            <div class="card mb-4" id="social-settings" style="border:1px solid #e2e8f0;box-shadow:0 4px 12px rgba(0,0,0,0.03);">
                <div class="card-header" style="background:#0f172a;color:#ffffff;border-top-left-radius:8px;border-top-right-radius:8px;padding:18px 20px;">
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <div style="display:flex;align-items:center;gap:10px;">
                            <span style="font-size:1.2rem;">📱</span>
                            <div>
                                <h3 style="font-size:1.05rem;font-weight:700;margin:0;color:#ffffff;letter-spacing:0.02em;">Floating Social Media Widget</h3>
                                <p style="font-size:0.75rem;color:#94a3b8;margin:2px 0 0 0;">Manage your website's floating luxury social handle & channels</p>
                            </div>
                        </div>
                        <span class="badge" style="background:rgba(255,255,255,0.15);color:#ffffff;font-size:0.7rem;padding:4px 10px;border-radius:12px;font-weight:600;">PREMIUM FEATURE</span>
                    </div>
                </div>
                <div class="card-body">
                    {{-- Enable Toggle Switch --}}
                    <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 16px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;margin-bottom:20px;">
                        <div>
                            <strong style="font-size:0.88rem;color:#0f172a;display:block;">Enable Floating Social Feature</strong>
                            <small class="text-muted" style="font-size:0.75rem;">Display floating glassmorphic luxury handle pill on customer storefront</small>
                        </div>
                        <label class="switch" style="position:relative;display:inline-block;width:44px;height:24px;margin:0;">
                            <input type="checkbox" name="social_floating_enabled" value="1" {{ !empty($settings['social_floating_enabled']) ? 'checked' : '' }} onchange="toggleSocialPreview(this.checked)" style="opacity:0;width:0;height:0;">
                            <span class="slider" style="position:absolute;cursor:pointer;top:0;left:0;right:0;bottom:0;background-color:#cbd5e1;transition:.3s;border-radius:24px;"></span>
                        </label>
                    </div>

                    {{-- Primary Social Handle & Position --}}
                    <div style="display:grid;grid-template-columns:1.2fr 1fr;gap:14px;margin-bottom:16px;">
                        <div class="form-group mb-0">
                            <label class="form-label" style="font-weight:700;font-size:0.82rem;text-transform:uppercase;letter-spacing:0.04em;">Primary Social Handle</label>
                            <input type="text" name="social_primary_handle" id="admin_primary_handle" class="form-control" value="{{ $settings['social_primary_handle'] }}" placeholder="@pistisofficial" oninput="updateLiveAdminPreview()">
                            <small class="text-muted" style="font-size:0.72rem;display:block;margin-top:4px;">Main handle displayed on the floating pill (e.g. <code>@pistisofficial</code>)</small>
                        </div>
                        <div class="form-group mb-0">
                            <label class="form-label" style="font-weight:700;font-size:0.82rem;text-transform:uppercase;letter-spacing:0.04em;">Floating Position</label>
                            <select name="social_floating_position" class="form-control" style="font-size:0.85rem;">
                                <option value="bottom-left" {{ ($settings['social_floating_position'] ?? '') === 'bottom-left' ? 'selected' : '' }}>Bottom Left (Recommended)</option>
                                <option value="bottom-right" {{ ($settings['social_floating_position'] ?? '') === 'bottom-right' ? 'selected' : '' }}>Bottom Right</option>
                                <option value="left-center" {{ ($settings['social_floating_position'] ?? '') === 'left-center' ? 'selected' : '' }}>Left Side (Middle)</option>
                                <option value="right-center" {{ ($settings['social_floating_position'] ?? '') === 'right-center' ? 'selected' : '' }}>Right Side (Middle)</option>
                            </select>
                            <small class="text-muted" style="font-size:0.72rem;display:block;margin-top:4px;">Screen corner or side placement</small>
                        </div>
                    </div>

                    {{-- Brand Tagline / Subtitle --}}
                    <div class="form-group mb-4">
                        <label class="form-label" style="font-weight:700;font-size:0.82rem;text-transform:uppercase;letter-spacing:0.04em;">Flyout Tagline / Label</label>
                        <input type="text" name="social_floating_tagline" id="admin_tagline" class="form-control" value="{{ $settings['social_floating_tagline'] }}" placeholder="Official Atelier & Runway Archive">
                        <small class="text-muted" style="font-size:0.72rem;display:block;margin-top:4px;">Short description shown when customer expands the social flyout</small>
                    </div>

                    {{-- Live Admin Preview Box --}}
                    <div id="admin-preview-box" style="background:#0a0a0a;border-radius:10px;padding:16px;margin-bottom:22px;border:1px solid rgba(255,255,255,0.1);transition:opacity 0.2s;{{ empty($settings['social_floating_enabled']) ? 'opacity:0.4;' : '' }}">
                        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;">
                            <span style="font-size:0.68rem;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;color:#9ca3af;">Live Storefront Pill Preview</span>
                            <span style="font-size:0.68rem;color:#10b981;font-weight:600;" id="admin-preview-status">● {{ !empty($settings['social_floating_enabled']) ? 'Active Preview' : 'Feature Disabled' }}</span>
                        </div>
                        <div style="display:flex;align-items:center;justify-content:center;padding:14px;background:#18181b;border-radius:8px;">
                            <div style="display:inline-flex;align-items:center;gap:10px;padding:8px 16px;background:rgba(0,0,0,0.85);border:1px solid rgba(255,255,255,0.2);border-radius:999px;color:#fff;box-shadow:0 8px 24px rgba(0,0,0,0.4);">
                                <span style="width:8px;height:8px;border-radius:50%;background:#10b981;box-shadow:0 0 6px #10b981;"></span>
                                <div style="display:flex;flex-direction:column;line-height:1.2;">
                                    <span style="font-size:0.82rem;font-weight:700;letter-spacing:0.04em;" id="preview_handle">{{ $settings['social_primary_handle'] ?: '@pistisofficial' }}</span>
                                    <span style="font-size:0.58rem;font-weight:600;letter-spacing:0.12em;color:#9ca3af;text-transform:uppercase;">ATELIER CONNECT</span>
                                </div>
                                <span style="font-size:0.75rem;margin-left:4px;color:#9ca3af;">↗</span>
                            </div>
                        </div>
                    </div>

                    {{-- Channel Links & Handles --}}
                    <h4 style="font-size:0.85rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:#0f172a;margin:0 0 8px 0;">Platform Channels & Links</h4>
                    <p class="text-muted" style="font-size:0.75rem;margin:0 0 14px 0;">Enter full URL (<code>https://...</code>) or username/handle (<code>@pistisofficial</code>). Empty channels will be automatically hidden.</p>

                    <div class="form-group mb-3">
                        <label class="form-label" style="font-weight:600;font-size:0.8rem;display:flex;align-items:center;gap:6px;">
                            <span>📸</span> Instagram Profile / Handle
                        </label>
                        <input type="text" name="social_instagram" class="form-control" value="{{ $settings['social_instagram'] }}" placeholder="https://instagram.com/pistisofficial or @pistisofficial">
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label" style="font-weight:600;font-size:0.8rem;display:flex;align-items:center;gap:6px;">
                            <span>🎵</span> TikTok Profile / Handle
                        </label>
                        <input type="text" name="social_tiktok" class="form-control" value="{{ $settings['social_tiktok'] }}" placeholder="https://tiktok.com/@pistisofficial or @pistisofficial">
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label" style="font-weight:600;font-size:0.8rem;display:flex;align-items:center;gap:6px;">
                            <span>✖</span> X (Twitter) Profile / Handle
                        </label>
                        <input type="text" name="social_twitter" class="form-control" value="{{ $settings['social_twitter'] }}" placeholder="https://x.com/pistisofficial or @pistisofficial">
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label" style="font-weight:600;font-size:0.8rem;display:flex;align-items:center;gap:6px;">
                            <span>▶</span> YouTube Channel URL
                        </label>
                        <input type="text" name="social_youtube" class="form-control" value="{{ $settings['social_youtube'] }}" placeholder="https://youtube.com/@pistisofficial">
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label" style="font-weight:600;font-size:0.8rem;display:flex;align-items:center;gap:6px;">
                            <span>📌</span> Pinterest Profile URL
                        </label>
                        <input type="text" name="social_pinterest" class="form-control" value="{{ $settings['social_pinterest'] }}" placeholder="https://pinterest.com/pistisofficial">
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label" style="font-weight:600;font-size:0.8rem;display:flex;align-items:center;gap:6px;">
                            <span>💬</span> WhatsApp Concierge / Phone Number
                        </label>
                        <input type="text" name="social_whatsapp" class="form-control" value="{{ $settings['social_whatsapp'] }}" placeholder="+1 234 567 8900 or https://wa.me/...">
                    </div>

                    <div class="form-group mb-0">
                        <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:0.82rem;font-weight:600;color:#334155;">
                            <input type="checkbox" name="social_show_in_footer" value="1" {{ !empty($settings['social_show_in_footer']) ? 'checked' : '' }} style="width:16px;height:16px;accent-color:#0f172a;">
                            Also show social icon links in website footer
                        </label>
                    </div>
                </div>
            </div>

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
            {{-- Australia Post (AusPost) Settings --}}
            <div class="card mb-4">
                <div class="card-header d-flex justify-between align-center" style="display:flex; justify-content:space-between; align-items:center;">
                    <h3 style="font-size:1.05rem; font-weight:700; margin:0; text-transform:uppercase; letter-spacing:0.04em;">Australia Post (AusPost)</h3>
                    @if(!empty($settings['auspost_api_key']))
                        <span class="badge" style="background:#16a34a; color:#fff; padding:3px 8px; border-radius:12px; font-size:0.7rem;">API Configured</span>
                    @else
                        <span class="badge" style="background:#64748b; color:#fff; padding:3px 8px; border-radius:12px; font-size:0.7rem;">Active (Fallback Ready)</span>
                    @endif
                </div>
                <div class="card-body">
                    <div class="form-group mb-3">
                        <label class="form-label" style="font-weight:600; font-size:0.85rem;">AusPost API Key</label>
                        <input type="text" name="auspost_api_key" class="form-control" value="{{ $settings['auspost_api_key'] }}" placeholder="e.g. 28a1... (from developers.auspost.com.au)">
                        <small class="text-muted" style="font-size:0.75rem; display:block; margin-top:4px;">Obtained from Australia Post Developer Centre. If left blank, automatic domestic & international rate tables are used.</small>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label" style="font-weight:600; font-size:0.85rem;">Dispatch Origin Postcode</label>
                        <input type="text" name="auspost_origin_postcode" class="form-control" value="{{ $settings['auspost_origin_postcode'] }}" placeholder="e.g. 2000" style="max-width:180px;">
                        <small class="text-muted" style="font-size:0.75rem; display:block; margin-top:4px;">Australian postcode your store ships from.</small>
                    </div>
                    <div class="form-group">
                        <label class="form-label" style="font-weight:600; font-size:0.85rem;">Default Domestic Base Rate ({{ $settings['currency_symbol'] }})</label>
                        <input type="number" step="0.01" name="auspost_default_shipping_cost" class="form-control" value="{{ $settings['auspost_default_shipping_cost'] }}" placeholder="12.00" style="max-width:180px;">
                    </div>
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

{{-- Danger Zone: Reset All Website Data --}}
<div class="card mt-4" style="border: 1px solid #fecaca; background: #fffafb; border-radius: 8px; margin-top: 36px;">
    <div class="card-header" style="background: transparent; border-bottom: 1px solid #fee2e2; padding: 22px 26px;">
        <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:16px;">
            <div style="max-width:760px;">
                <span class="badge" style="background:#dc2626; color:#ffffff; font-size:0.7rem; font-weight:700; letter-spacing:0.08em; padding:4px 8px; border-radius:4px; text-transform:uppercase;">Danger Zone</span>
                <h3 style="font-size:1.15rem; font-weight:700; margin:8px 0 4px 0; color:#991b1b; letter-spacing:0.02em;">RESET ALL STORE DATA</h3>
                <p style="font-size:0.86rem; color:#7f1d1d; margin:0; line-height:1.5;">
                    Permanently wipe every item of store data on the website (Products, Categories, Orders, Customers, Carts, Hero Slides, Size Guides, and Sync Logs).
                    <strong style="display:block; margin-top:6px; color:#15803d;">✓ Your administrator login accounts will NOT be deleted.</strong>
                </p>
            </div>
            <button type="button" class="btn btn-danger" onclick="openResetDataModal()" style="background:#dc2626; color:#ffffff; border:none; padding:12px 24px; font-weight:700; font-size:0.85rem; letter-spacing:0.05em; border-radius:6px; cursor:pointer; display:inline-flex; align-items:center; gap:8px; box-shadow:0 2px 8px rgba(220,38,38,0.25);">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                CLEAR ALL STORE DATA
            </button>
        </div>
    </div>
</div>

{{-- Reset Store Data Confirmation Modal --}}
<div id="resetDataModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.65); backdrop-filter:blur(3px); z-index:9999; align-items:center; justify-content:center; padding:16px;">
    <div style="background:#ffffff; border-radius:12px; max-width:540px; width:100%; box-shadow:0 20px 40px rgba(0,0,0,0.25); overflow:hidden; border:1px solid #fecaca;">
        {{-- Modal Header --}}
        <div style="padding:20px 24px; background:#fef2f2; border-bottom:1px solid #fee2e2; display:flex; justify-content:space-between; align-items:center;">
            <div style="display:flex; align-items:center; gap:10px;">
                <div style="width:36px; height:36px; border-radius:50%; background:#fee2e2; color:#dc2626; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                </div>
                <div>
                    <h4 style="margin:0; font-size:1.1rem; font-weight:700; color:#991b1b;">Confirm Store Data Reset</h4>
                    <span style="font-size:0.75rem; color:#b91c1c;">Destructive Administrative Action</span>
                </div>
            </div>
            <button type="button" onclick="closeResetDataModal()" style="background:none; border:none; color:#991b1b; font-size:1.5rem; cursor:pointer; line-height:1;">&times;</button>
        </div>

        {{-- Modal Body --}}
        <form action="{{ route('admin.settings.wipe-data') }}" method="POST" id="wipeDataForm" style="margin:0;">
            @csrf
            <div style="padding:24px; max-height:75vh; overflow-y:auto;">
                <div style="padding:14px; background:#fff1f2; border-radius:8px; border:1px solid #fecdd3; margin-bottom:18px;">
                    <p style="margin:0 0 8px 0; font-size:0.85rem; font-weight:700; color:#9f1239;">This action will permanently delete:</p>
                    <ul style="margin:0; padding-left:18px; font-size:0.82rem; color:#881337; line-height:1.6;">
                        <li>All Products, variants, and specifications</li>
                        <li>All Categories and hierarchy trees</li>
                        <li>All Customer accounts, orders, and shopping carts</li>
                        <li>All Hero slider banners and Size guide charts</li>
                        <li>All Shopify synchronization logs</li>
                    </ul>
                </div>

                <div style="padding:12px 14px; background:#f0fdf4; border-radius:8px; border:1px solid #bbf7d0; margin-bottom:20px; display:flex; align-items:center; gap:10px;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    <span style="font-size:0.83rem; font-weight:600; color:#166534;">
                        Your Administrator account and login access are protected and will NOT be deleted.
                    </span>
                </div>

                <div class="form-group mb-3">
                    <label style="display:flex; align-items:center; gap:8px; font-size:0.85rem; color:#374151; cursor:pointer; font-weight:500;">
                        <input type="checkbox" name="delete_uploaded_media" value="1" style="accent-color:#dc2626;">
                        Also delete uploaded product/hero image files from server storage
                    </label>
                </div>

                <div class="form-group mb-4">
                    <label style="display:flex; align-items:center; gap:8px; font-size:0.85rem; color:#374151; cursor:pointer; font-weight:500;">
                        <input type="checkbox" name="reset_settings" value="1" style="accent-color:#dc2626;">
                        Also reset store policies & settings to initial defaults (Brand logo is preserved)
                    </label>
                </div>

                <hr style="border:0; border-top:1px solid #e5e5e5; margin:16px 0;">

                <div class="form-group mb-3">
                    <label class="form-label" style="font-weight:700; font-size:0.85rem; color:#18181b;">
                        Type <span style="background:#fee2e2; color:#b91c1c; padding:2px 6px; border-radius:4px; font-family:monospace;">RESET</span> to confirm:
                    </label>
                    <input type="text" name="confirmation_text" id="resetConfirmationInput" class="form-control" placeholder="Type RESET" required autocomplete="off" style="font-weight:600; letter-spacing:0.05em;">
                </div>

                <div class="form-group mb-2">
                    <label class="form-label" style="font-weight:700; font-size:0.85rem; color:#18181b;">
                        Enter your Admin Password:
                    </label>
                    <input type="password" name="password" id="resetPasswordInput" class="form-control" placeholder="Your current login password" required autocomplete="current-password">
                </div>
            </div>

            {{-- Modal Footer --}}
            <div style="padding:16px 24px; background:#f9fafb; border-top:1px solid #e5e7eb; display:flex; justify-content:flex-end; gap:12px;">
                <button type="button" onclick="closeResetDataModal()" class="btn btn-secondary" style="padding:10px 18px; font-size:0.85rem; font-weight:600;">
                    Cancel
                </button>
                <button type="submit" id="confirmResetBtn" class="btn btn-danger" style="background:#dc2626; color:#ffffff; border:none; padding:10px 20px; font-size:0.85rem; font-weight:700; letter-spacing:0.04em; border-radius:6px; cursor:pointer;">
                    Permanently Clear All Data
                </button>
            </div>
        </form>
    </div>
</div>

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

    function openResetDataModal() {
        const modal = document.getElementById('resetDataModal');
        if (modal) {
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            setTimeout(() => {
                const input = document.getElementById('resetConfirmationInput');
                if (input) input.focus();
            }, 60);
        }
    }

    function closeResetDataModal() {
        const modal = document.getElementById('resetDataModal');
        if (modal) {
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }
    }

    window.addEventListener('click', function(e) {
        const modal = document.getElementById('resetDataModal');
        if (modal && e.target === modal) {
            closeResetDataModal();
        }
    });

    document.getElementById('wipeDataForm')?.addEventListener('submit', function(e) {
        const confirmVal = document.getElementById('resetConfirmationInput')?.value?.trim();
        if (confirmVal !== 'RESET') {
            e.preventDefault();
            alert('Please type "RESET" in all caps into the confirmation field.');
            return false;
        }
        if (!confirm('FINAL WARNING: This cannot be undone. Are you sure you want to permanently clear all store data?')) {
            e.preventDefault();
            return false;
        }
        const btn = document.getElementById('confirmResetBtn');
        if (btn) {
            btn.disabled = true;
            btn.innerText = 'Clearing Store Data...';
        }
    });

    // ─── Floating Social Media Admin Preview ─────────────────────────
    function updateLiveAdminPreview() {
        const handleInput = document.getElementById('admin_primary_handle');
        const previewHandle = document.getElementById('preview_handle');
        if (handleInput && previewHandle) {
            const val = handleInput.value.trim();
            previewHandle.textContent = val !== '' ? val : '@pistisofficial';
        }
    }

    function toggleSocialPreview(isChecked) {
        const previewBox = document.getElementById('admin-preview-box');
        const previewStatus = document.getElementById('admin-preview-status');
        if (previewBox) {
            previewBox.style.opacity = isChecked ? '1' : '0.4';
        }
        if (previewStatus) {
            previewStatus.innerHTML = isChecked 
                ? '<span style="color:#10b981;">● Active Preview</span>' 
                : '<span style="color:#ef4444;">○ Feature Disabled</span>';
        }
    }
</script>

<style>
.switch { position: relative; display: inline-block; width: 44px; height: 24px; }
.switch input { opacity: 0; width: 0; height: 0; }
.slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #cbd5e1; transition: .3s; border-radius: 24px; }
.slider:before { position: absolute; content: ""; height: 18px; width: 18px; left: 3px; bottom: 3px; background-color: white; transition: .3s; border-radius: 50%; }
input:checked + .slider { background-color: #0f172a; }
input:checked + .slider:before { transform: translateX(20px); }
</style>
@endsection
