@php
    $hubUrl = route('social.connect');
    $storeName = $storeName ?? ($socialInfo['store_name'] ?? ($settings['store_name'] ?? 'Pistis'));
    $primaryHandle = $primaryHandle ?? ($socialInfo['primary_handle'] ?? ($settings['social_primary_handle'] ?? '@wearpistis'));
    $tagline = $tagline ?? ($socialInfo['tagline'] ?? ($settings['social_floating_tagline'] ?? 'Official Atelier & Runway Archive'));
    $instagram = $instagram ?? ($socialInfo['instagram'] ?? ($settings['social_instagram'] ?? ''));
    $tiktok = $tiktok ?? ($socialInfo['tiktok'] ?? ($settings['social_tiktok'] ?? ''));
    $whatsapp = $whatsapp ?? ($socialInfo['whatsapp'] ?? ($settings['social_whatsapp'] ?? ''));
    $instagramEnabled = $instagramEnabled ?? ($socialInfo['instagram_enabled'] ?? ($settings['social_instagram_enabled'] ?? true));
    $tiktokEnabled = $tiktokEnabled ?? ($socialInfo['tiktok_enabled'] ?? ($settings['social_tiktok_enabled'] ?? true));
    $whatsappEnabled = $whatsappEnabled ?? ($socialInfo['whatsapp_enabled'] ?? ($settings['social_whatsapp_enabled'] ?? true));
    $prefix = $prefix ?? 'dash';
@endphp

<div class="card event-qr-module-card" id="{{ $prefix }}_qr_card" style="border:1px solid #e2e8f0;background:#ffffff;box-shadow:0 4px 16px rgba(0,0,0,0.03);border-radius:12px;overflow:hidden;margin-bottom:28px;">
    {{-- Card Header --}}
    <div style="background:#0f172a;color:#ffffff;padding:18px 24px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;">
        <div style="display:flex;align-items:center;gap:12px;">
            <div style="width:36px;height:36px;border-radius:8px;background:rgba(255,255,255,0.1);display:flex;align-items:center;justify-content:center;font-size:1.1rem;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="7" height="7"></rect>
                    <rect x="14" y="3" width="7" height="7"></rect>
                    <rect x="14" y="14" width="7" height="7"></rect>
                    <rect x="3" y="14" width="7" height="7"></rect>
                </svg>
            </div>
            <div>
                <h3 style="font-size:1.05rem;font-weight:700;margin:0;color:#ffffff;letter-spacing:0.02em;">
                    Event Social Media QR Code & Connect Hub
                </h3>
                <p style="font-size:0.75rem;color:#94a3b8;margin:2px 0 0 0;">
                    Dynamic QR code for runway shows, pop-ups & events. Updates automatically when handles are modified.
                </p>
            </div>
        </div>
        <div style="display:flex;align-items:center;gap:8px;">
            <span class="badge" style="background:#10b981;color:#ffffff;font-size:0.7rem;padding:4px 10px;border-radius:12px;font-weight:700;letter-spacing:0.04em;">
                ● REAL-TIME SYNC
            </span>
            <a href="{{ route('admin.settings.index') }}#social-settings" class="btn btn-sm" style="background:rgba(255,255,255,0.12);color:#ffffff;font-size:0.72rem;padding:4px 10px;border-radius:6px;text-decoration:none;border:1px solid rgba(255,255,255,0.2);">
                Manage Handles ↗
            </a>
        </div>
    </div>

    {{-- Card Body --}}
    <div style="padding:24px;">
        <div style="display:grid;grid-template-columns:1fr 280px;gap:28px;align-items:center;">
            
            {{-- Left Column: Explanation, Controls & Action Buttons --}}
            <div>
                {{-- Dynamic explanation --}}
                <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:16px;margin-bottom:20px;">
                    <div style="font-size:0.85rem;font-weight:700;color:#0f172a;margin-bottom:6px;display:flex;align-items:center;gap:6px;">
                        <span>💡</span> How the Dynamic Event QR Code Works
                    </div>
                    <p style="font-size:0.8rem;color:#475569;line-height:1.55;margin:0 0 10px 0;">
                        When attendees scan this QR code with their phone camera at an event, it opens your dedicated 
                        <strong>Pistis Connect Hub</strong> (<code>/connect</code>). Attendees can follow you on Instagram, TikTok, chat on WhatsApp, or shop your collection in 1 tap.
                    </p>
                    <div style="font-size:0.75rem;color:#059669;font-weight:600;display:flex;align-items:center;gap:6px;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        <span>Zero reprinting needed: If you change or add new handles in settings, anyone scanning this same QR code will immediately see the updated links!</span>
                    </div>
                </div>

                {{-- Mode Selector Tabs --}}
                <div style="margin-bottom:18px;">
                    <label style="font-size:0.76rem;font-weight:700;text-transform:uppercase;letter-spacing:0.04em;color:#64748b;display:block;margin-bottom:8px;">
                        Select QR Target Destination
                    </label>
                    <div style="display:flex;gap:8px;flex-wrap:wrap;">
                        <button type="button" 
                                class="btn btn-sm {{ $prefix }}_mode_btn active" 
                                onclick="setQrMode('{{ $prefix }}', 'hub', '{{ $hubUrl }}')"
                                style="font-size:0.78rem;font-weight:600;padding:6px 14px;border-radius:20px;border:1px solid #0f172a;background:#0f172a;color:#ffffff;cursor:pointer;">
                            ★ All-in-One Social Hub (Recommended)
                        </button>
                        
                        @if(!empty($instagram) && !empty($instagramEnabled))
                            <button type="button" 
                                    class="btn btn-sm {{ $prefix }}_mode_btn" 
                                    onclick="setQrMode('{{ $prefix }}', 'instagram', '{{ str_starts_with($instagram, 'http') ? $instagram : 'https://instagram.com/' . ltrim($instagram, '@') }}')"
                                    style="font-size:0.78rem;font-weight:600;padding:6px 14px;border-radius:20px;border:1px solid #cbd5e1;background:#ffffff;color:#334155;cursor:pointer;">
                                Direct Instagram Only
                            </button>
                        @endif

                        @if(!empty($tiktok) && !empty($tiktokEnabled))
                            <button type="button" 
                                    class="btn btn-sm {{ $prefix }}_mode_btn" 
                                    onclick="setQrMode('{{ $prefix }}', 'tiktok', '{{ str_starts_with($tiktok, 'http') ? $tiktok : 'https://tiktok.com/@' . ltrim($tiktok, '@') }}')"
                                    style="font-size:0.78rem;font-weight:600;padding:6px 14px;border-radius:20px;border:1px solid #cbd5e1;background:#ffffff;color:#334155;cursor:pointer;">
                                Direct TikTok Only
                            </button>
                        @endif
                    </div>
                </div>

                {{-- Active Channels Pill List --}}
                <div style="margin-bottom:20px;">
                    <span style="font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.04em;color:#64748b;display:block;margin-bottom:6px;">
                        Active Channels Linked to QR Code:
                    </span>
                    <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                        <span style="display:inline-flex;align-items:center;gap:6px;background:#f1f5f9;border:1px solid #e2e8f0;padding:4px 10px;border-radius:6px;font-size:0.76rem;font-weight:600;color:#0f172a;">
                            <span style="color:#d4af37;">👑</span> {{ $primaryHandle }}
                        </span>
                        @if(!empty($instagram) && !empty($instagramEnabled))
                            <span style="display:inline-flex;align-items:center;gap:6px;background:#fdf2f8;border:1px solid #fbcfe8;padding:4px 10px;border-radius:6px;font-size:0.76rem;font-weight:600;color:#db2777;">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/></svg>
                                Instagram Linked
                            </span>
                        @endif
                        @if(!empty($tiktok) && !empty($tiktokEnabled))
                            <span style="display:inline-flex;align-items:center;gap:6px;background:#f0fdfa;border:1px solid #ccfbf1;padding:4px 10px;border-radius:6px;font-size:0.76rem;font-weight:600;color:#0d9488;">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64c.29 0 .58.04.86.12V9.42a6.37 6.37 0 0 0-.86-.06 6.34 6.34 0 0 0-6.34 6.34 6.34 6.34 0 0 0 6.34 6.34 6.34 6.34 0 0 0 6.34-6.34V8.58a8.28 8.28 0 0 0 4.84 1.56V6.69z"/></svg>
                                TikTok Linked
                            </span>
                        @endif
                        @if(!empty($whatsapp) && !empty($whatsappEnabled))
                            <span style="display:inline-flex;align-items:center;gap:6px;background:#f0fdf4;border:1px solid #bbf7d0;padding:4px 10px;border-radius:6px;font-size:0.76rem;font-weight:600;color:#16a34a;">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
                                WhatsApp Concierge
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Action Buttons Toolbar --}}
                <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                    {{-- Download High-Res PNG --}}
                    <button type="button" 
                            class="btn btn-primary" 
                            onclick="downloadQrCodeImage('{{ $prefix }}', '{{ $storeName }}-Social-QR')"
                            style="font-size:0.82rem;font-weight:700;padding:8px 18px;letter-spacing:0.02em;display:inline-flex;align-items:center;gap:8px;">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="7 10 12 15 17 10"></polyline>
                            <line x1="12" y1="15" x2="12" y2="3"></line>
                        </svg>
                        Download High-Res QR (PNG)
                    </button>

                    {{-- Print Display Standee / Table Sign --}}
                    <button type="button" 
                            class="btn btn-secondary" 
                            onclick="printEventStandee('{{ $prefix }}')"
                            style="font-size:0.82rem;font-weight:600;padding:8px 16px;display:inline-flex;align-items:center;gap:8px;">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 6 2 18 2 18 9"></polyline>
                            <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                            <rect x="6" y="14" width="12" height="8"></rect>
                        </svg>
                        Print Event Standee Card
                    </button>

                    {{-- Copy Link --}}
                    <button type="button" 
                            class="btn btn-secondary" 
                            onclick="copyTargetUrl('{{ $prefix }}')"
                            style="font-size:0.82rem;font-weight:600;padding:8px 14px;display:inline-flex;align-items:center;gap:6px;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path></svg>
                        <span id="{{ $prefix }}_copy_label">Copy Link</span>
                    </button>

                    {{-- Preview Live Hub in new window --}}
                    <a href="{{ $hubUrl }}" 
                       target="_blank" 
                       id="{{ $prefix }}_preview_link"
                       class="btn btn-secondary" 
                       style="font-size:0.82rem;font-weight:600;padding:8px 14px;display:inline-flex;align-items:center;gap:6px;text-decoration:none;">
                        <span>Preview Hub</span>
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                    </a>
                </div>
            </div>

            {{-- Right Column: Live QR Code Stand Display --}}
            <div style="display:flex;flex-direction:column;align-items:center;">
                <div style="background:#ffffff;border:2px solid #0f172a;border-radius:16px;padding:18px;box-shadow:0 12px 30px rgba(0,0,0,0.08);text-align:center;width:100%;max-width:240px;position:relative;">
                    
                    {{-- Mini Header on Frame --}}
                    <div style="font-size:0.65rem;font-weight:800;letter-spacing:0.14em;text-transform:uppercase;color:#0f172a;margin-bottom:8px;">
                        {{ $storeName }} COLLECTIONS
                    </div>

                    {{-- QR Code Container --}}
                    <div id="{{ $prefix }}_qrcode_box" 
                         style="background:#ffffff;display:flex;align-items:center;justify-content:center;margin:0 auto;width:190px;height:190px;">
                        {{-- QR Code renders here via JS --}}
                    </div>

                    {{-- Mini Handle Caption --}}
                    <div style="margin-top:10px;padding-top:8px;border-top:1px dashed #e2e8f0;">
                        <span style="font-size:0.75rem;font-weight:700;color:#0f172a;display:block;" id="{{ $prefix }}_display_handle">
                            {{ $primaryHandle }}
                        </span>
                        <span style="font-size:0.62rem;color:#64748b;text-transform:uppercase;letter-spacing:0.06em;font-weight:600;">
                            Scan to Follow & Shop
                        </span>
                    </div>
                </div>

                {{-- Encoded Target URL display --}}
                <div style="margin-top:8px;font-size:0.7rem;color:#64748b;max-width:230px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;text-align:center;" id="{{ $prefix }}_url_display" title="{{ $hubUrl }}">
                    {{ $hubUrl }}
                </div>
            </div>

        </div>
    </div>
</div>
