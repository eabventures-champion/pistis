@if(!empty($socialSettings['floating_enabled']) && !request()->routeIs('cart.*') && !request()->routeIs('checkout.*'))
@php
    $position = $socialSettings['position'] ?? 'bottom-left';
    $primaryHandle = $socialSettings['primary_handle'] ?? '@pistisofficial';
    $tagline = $socialSettings['tagline'] ?? 'Official Atelier & Runway Archive';

    // Helper to format platform URLs
    $formatUrl = function($val, $prefix) {
        if (empty($val)) return null;
        $val = trim($val);
        if (str_starts_with($val, 'http://') || str_starts_with($val, 'https://')) {
            return $val;
        }
        return $prefix . ltrim($val, '@');
    };

    $platforms = [
        [
            'key' => 'instagram',
            'name' => 'Instagram',
            'handle' => $socialSettings['instagram'] ?? '',
            'url' => $formatUrl($socialSettings['instagram'] ?? '', 'https://instagram.com/'),
            'label' => 'Runway & Capsule Drops',
            'icon' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>',
        ],
        [
            'key' => 'tiktok',
            'name' => 'TikTok',
            'handle' => $socialSettings['tiktok'] ?? '',
            'url' => $formatUrl($socialSettings['tiktok'] ?? '', 'https://tiktok.com/@'),
            'label' => 'Behind the Scenes & Motion',
            'icon' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64c.29 0 .58.04.86.12V9.42a6.37 6.37 0 0 0-.86-.06 6.34 6.34 0 0 0-6.34 6.34 6.34 6.34 0 0 0 6.34 6.34 6.34 6.34 0 0 0 6.34-6.34V8.58a8.28 8.28 0 0 0 4.84 1.56V6.69z"/></svg>',
        ],
        [
            'key' => 'twitter',
            'name' => 'X (Twitter)',
            'handle' => $socialSettings['twitter'] ?? '',
            'url' => $formatUrl($socialSettings['twitter'] ?? '', 'https://x.com/'),
            'label' => 'Atelier Dispatch & Press',
            'icon' => '<svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>',
        ],
        [
            'key' => 'youtube',
            'name' => 'YouTube',
            'handle' => $socialSettings['youtube'] ?? '',
            'url' => $formatUrl($socialSettings['youtube'] ?? '', 'https://youtube.com/@'),
            'label' => 'Seasonal Runway Films',
            'icon' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z"/><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"/></svg>',
        ],
        [
            'key' => 'pinterest',
            'name' => 'Pinterest',
            'handle' => $socialSettings['pinterest'] ?? '',
            'url' => $formatUrl($socialSettings['pinterest'] ?? '', 'https://pinterest.com/'),
            'label' => 'Moodboards & Textures',
            'icon' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0a12 12 0 0 0-4.37 23.18c-.06-.98-.12-2.48.02-3.56.13-1 .85-6.52.85-6.52s-.22-.44-.22-1.08c0-1.02.59-1.78 1.33-1.78.63 0 .93.47.93 1.04 0 .63-.4 1.58-.61 2.45-.17.74.37 1.35 1.1 1.35 1.32 0 2.34-1.39 2.34-3.4 0-1.78-1.28-3.02-3.1-3.02-2.27 0-3.6 1.7-3.6 3.46 0 .68.26 1.42.59 1.82.07.08.08.15.06.23-.06.27-.2.83-.23.95-.04.16-.13.2-.3.12-1.12-.52-1.82-2.16-1.82-3.48 0-2.83 2.06-5.43 5.94-5.43 3.12 0 5.54 2.22 5.54 5.19 0 3.1-1.95 5.59-4.66 5.59-.91 0-1.77-.47-2.06-1.03l-.56 2.14c-.2 1.79-.75 2.01-.78 2.07A12 12 0 1 0 12 0z"/></svg>',
        ],
        [
            'key' => 'whatsapp',
            'name' => 'WhatsApp Concierge',
            'handle' => $socialSettings['whatsapp'] ?? '',
            'url' => !empty($socialSettings['whatsapp']) ? (str_starts_with($socialSettings['whatsapp'], 'http') ? $socialSettings['whatsapp'] : 'https://wa.me/' . preg_replace('/[^0-9]/', '', $socialSettings['whatsapp'])) : null,
            'label' => 'Direct Client Liaison',
            'icon' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>',
        ],
    ];

    // Filter to only platforms with configured links/handles
    $activePlatforms = array_filter($platforms, fn($p) => !empty($p['url']));
    $hasCampaignFilm = !empty($campaignVideo['enabled']) && !empty($campaignVideo['video_url']);
@endphp

{{-- Floating Luxury Social Widget Container --}}
<div id="pistis-floating-social" class="pistis-social-wrap pos-{{ $position }}">
    
    {{-- Expanded Luxury Flyout Tray --}}
    <div id="pistis-social-flyout" class="pistis-social-flyout" role="dialog" aria-label="Social Channels">
        {{-- Flyout Header --}}
        <div class="flyout-header">
            <div class="flyout-brand">
                <div class="brand-monogram">
                    <span>P</span>
                </div>
                <div class="brand-details">
                    <div class="brand-handle-row">
                        <span class="brand-handle" id="copy-handle-text">{{ $primaryHandle }}</span>
                        <span class="verified-badge" title="Official Verified Handle">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="#10b981"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                        </span>
                    </div>
                    <span class="brand-tagline">{{ $tagline }}</span>
                </div>
            </div>
            <button type="button" class="flyout-close-btn" onclick="toggleFloatingSocial(false)" aria-label="Close social flyout">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>

        {{-- Quick Copy Bar --}}
        <div class="flyout-copy-bar">
            <div class="copy-handle-info">
                <span class="copy-hint">Official Handle:</span>
                <strong class="copy-target">{{ $primaryHandle }}</strong>
            </div>
            <button type="button" class="copy-action-btn" id="copy-handle-btn" onclick="copySocialHandle('{{ addslashes($primaryHandle) }}')">
                <span id="copy-btn-icon">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                </span>
                <span id="copy-btn-text">Copy</span>
            </button>
        </div>

        {{-- Direct Campaign Film Launcher inside Flyout if available --}}
        @if(!empty($campaignVideo['enabled']) && !empty($campaignVideo['video_url']))
            <button type="button" 
                    onclick="toggleFloatingSocial(false); if(typeof openCampaignVideoModal === 'function') openCampaignVideoModal(true);"
                    class="channel-item campaign-channel-item"
                    style="all:unset;cursor:pointer;width:100%;box-sizing:border-box;display:flex;align-items:center;gap:12px;padding:10px 12px;background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.22);border-radius:8px;color:#ffffff;transition:all 0.2s;margin-bottom:10px;"
                    onmouseover="this.style.background='rgba(255,255,255,0.18)';this.style.borderColor='#ffffff';"
                    onmouseout="this.style.background='rgba(255,255,255,0.08)';this.style.borderColor='rgba(255,255,255,0.22)';">
                <div class="channel-icon-wrap" style="background:#ffffff;color:#000000;display:flex;align-items:center;justify-content:center;border-radius:7px;width:32px;height:32px;font-size:1rem;">
                    🎬
                </div>
                <div class="channel-info" style="flex:1;">
                    <span class="channel-name" style="font-size:0.84rem;font-weight:700;display:block;">Runway Campaign Film</span>
                    <span class="channel-sub" style="font-size:0.68rem;color:#cbd5e1;display:block;">Watch Lookbook Premiere in Motion</span>
                </div>
                <div class="channel-arrow" style="color:#ffffff;font-size:0.75rem;">
                    ▶
                </div>
            </button>
        @endif

        {{-- Active Channels List --}}
        <div class="flyout-channels-list">
            @forelse($activePlatforms as $plat)
                <a href="{{ $plat['url'] }}" target="_blank" rel="noopener noreferrer" class="channel-item">
                    <div class="channel-icon-wrap channel-{{ $plat['key'] }}">
                        {!! $plat['icon'] !!}
                    </div>
                    <div class="channel-info">
                        <span class="channel-name">{{ $plat['name'] }}</span>
                        <span class="channel-sub">{{ $plat['label'] }}</span>
                    </div>
                    <div class="channel-arrow">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg>
                    </div>
                </a>
            @empty
                <div class="channels-empty">
                    <span>Social handles can be linked from the Admin Settings dashboard.</span>
                </div>
            @endforelse
        </div>

        {{-- Flyout Footer --}}
        <div class="flyout-footer">
            <span class="footer-tag">#PistisArchive</span>
            <span class="footer-text">Tag our atelier in your silhouettes to be featured</span>
        </div>
    </div>

    {{-- Main Floating Trigger Pill --}}
    <button type="button" 
            id="pistis-social-pill" 
            class="pistis-social-pill" 
            onclick="toggleFloatingSocial()" 
            aria-expanded="false" 
            aria-haspopup="dialog"
            title="Connect with Pistis on Social Media">
        
        {{-- Pulsing Live Atelier Dot --}}
        <div class="pill-indicator-wrap">
            <span class="pill-pulse-ring"></span>
            <span class="pill-live-dot"></span>
        </div>

        {{-- Primary Handle & Label --}}
        <div class="pill-text-content">
            <span class="pill-handle">{{ $primaryHandle }}</span>
            <span class="pill-sub">ATELIER CONNECT</span>
        </div>

        {{-- Interactive Expand Arrow --}}
        <div class="pill-action-icon">
            <svg id="pill-arrow-svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="18 15 12 9 6 15"></polyline>
            </svg>
        </div>
    </button>
</div>

<style>
/* ─── Floating Social Luxury Styling ──────────────────────────────────── */
.pistis-social-wrap {
    position: fixed;
    z-index: 9992;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    user-select: none;
    transition: bottom 0.28s ease, left 0.28s ease;
}

/* Positions */
.pistis-social-wrap.pos-bottom-left {
    bottom: 24px;
    left: 24px;
}
.pistis-social-wrap.pos-bottom-right {
    bottom: 24px;
    right: 24px;
}
.pistis-social-wrap.pos-left-center {
    top: 50%;
    left: 22px;
    transform: translateY(-50%);
}
.pistis-social-wrap.pos-right-center {
    top: 50%;
    right: 22px;
    transform: translateY(-50%);
}

/* The Trigger Capsule Pill */
.pistis-social-pill {
    all: unset;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 12px;
    padding: 10px 18px 10px 14px;
    background: rgba(13, 13, 13, 0.92);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.16);
    border-radius: 999px;
    color: #ffffff;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5), 0 2px 8px rgba(0, 0, 0, 0.4);
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}

.pistis-social-pill:hover {
    background: rgba(22, 22, 22, 0.98);
    border-color: rgba(255, 255, 255, 0.32);
    transform: translateY(-3px);
    box-shadow: 0 16px 38px rgba(0, 0, 0, 0.65), 0 0 20px rgba(255, 255, 255, 0.08);
}

.pistis-social-pill.is-active {
    background: #000000;
    border-color: #ffffff;
}

.pistis-social-pill.is-active #pill-arrow-svg {
    transform: rotate(180deg);
}

/* Pulsing Atelier Dot */
.pill-indicator-wrap {
    position: relative;
    width: 10px;
    height: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.pill-live-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #10b981;
    display: block;
    box-shadow: 0 0 8px rgba(16, 185, 129, 0.8);
}

.pill-pulse-ring {
    position: absolute;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    background: rgba(16, 185, 129, 0.35);
    animation: pillPulse 2.4s infinite ease-out;
}

@keyframes pillPulse {
    0% { transform: scale(0.6); opacity: 1; }
    100% { transform: scale(1.6); opacity: 0; }
}

/* Pill Typography */
.pill-text-content {
    display: flex;
    flex-direction: column;
    line-height: 1.15;
}

.pill-handle {
    font-size: 0.84rem;
    font-weight: 700;
    letter-spacing: 0.04em;
    color: #ffffff;
}

.pill-sub {
    font-size: 0.6rem;
    font-weight: 600;
    letter-spacing: 0.14em;
    color: #9ca3af;
    text-transform: uppercase;
    margin-top: 2px;
}

.pill-action-icon {
    width: 22px;
    height: 22px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.08);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ffffff;
    transition: transform 0.25s ease, background 0.2s ease;
    margin-left: 2px;
}

.pistis-social-pill:hover .pill-action-icon {
    background: rgba(255, 255, 255, 0.18);
}

#pill-arrow-svg {
    transition: transform 0.3s ease;
}

/* ─── Expanded Luxury Flyout Tray ────────────────────────────────────── */
.pistis-social-flyout {
    position: absolute;
    width: 330px;
    background: rgba(12, 12, 12, 0.96);
    backdrop-filter: blur(28px);
    -webkit-backdrop-filter: blur(28px);
    border: 1px solid rgba(255, 255, 255, 0.14);
    border-radius: 16px;
    box-shadow: 0 24px 60px rgba(0, 0, 0, 0.75), 0 4px 16px rgba(0, 0, 0, 0.5);
    padding: 20px;
    color: #ffffff;
    opacity: 0;
    visibility: hidden;
    pointer-events: none;
    transform: translateY(12px) scale(0.97);
    transition: opacity 0.28s cubic-bezier(0.16, 1, 0.3, 1), transform 0.28s cubic-bezier(0.16, 1, 0.3, 1), visibility 0.28s;
}

/* Position adjustments for flyout */
.pos-bottom-left .pistis-social-flyout {
    bottom: calc(100% + 14px);
    left: 0;
    transform-origin: bottom left;
}
.pos-bottom-right .pistis-social-flyout {
    bottom: calc(100% + 14px);
    right: 0;
    transform-origin: bottom right;
}
.pos-left-center .pistis-social-flyout {
    top: 50%;
    left: calc(100% + 14px);
    transform: translateY(-50%) scale(0.97);
    transform-origin: center left;
}
.pos-right-center .pistis-social-flyout {
    top: 50%;
    right: calc(100% + 14px);
    transform: translateY(-50%) scale(0.97);
    transform-origin: center right;
}

.pistis-social-flyout.is-open {
    opacity: 1;
    visibility: visible;
    pointer-events: auto;
    transform: translateY(0) scale(1);
}

.pos-left-center .pistis-social-flyout.is-open,
.pos-right-center .pistis-social-flyout.is-open {
    transform: translateY(-50%) scale(1);
}

/* Flyout Header */
.flyout-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding-bottom: 14px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
}

.flyout-brand {
    display: flex;
    align-items: center;
    gap: 12px;
}

.brand-monogram {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: #ffffff;
    color: #000000;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: 'Cormorant Garamond', Georgia, serif;
    font-size: 1.35rem;
    font-weight: 700;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(255, 255, 255, 0.2);
}

.brand-details {
    display: flex;
    flex-direction: column;
}

.brand-handle-row {
    display: flex;
    align-items: center;
    gap: 6px;
}

.brand-handle {
    font-size: 0.95rem;
    font-weight: 800;
    letter-spacing: 0.02em;
    color: #ffffff;
}

.verified-badge {
    display: inline-flex;
    align-items: center;
}

.brand-tagline {
    font-size: 0.72rem;
    color: #9ca3af;
    letter-spacing: 0.03em;
    margin-top: 2px;
}

.flyout-close-btn {
    all: unset;
    cursor: pointer;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.08);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #9ca3af;
    transition: all 0.2s ease;
}

.flyout-close-btn:hover {
    background: rgba(255, 255, 255, 0.2);
    color: #ffffff;
}

/* Quick Copy Bar */
.flyout-copy-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: rgba(255, 255, 255, 0.04);
    border: 1px dashed rgba(255, 255, 255, 0.15);
    border-radius: 8px;
    padding: 8px 12px;
    margin: 14px 0 12px 0;
}

.copy-handle-info {
    display: flex;
    flex-direction: column;
    font-size: 0.75rem;
}

.copy-hint {
    font-size: 0.64rem;
    color: #71717a;
    text-transform: uppercase;
    letter-spacing: 0.08em;
}

.copy-target {
    font-family: monospace;
    font-size: 0.82rem;
    color: #e4e4e7;
    margin-top: 1px;
}

.copy-action-btn {
    all: unset;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 0.72rem;
    font-weight: 600;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    background: #ffffff;
    color: #000000;
    padding: 5px 11px;
    border-radius: 4px;
    transition: all 0.2s ease;
}

.copy-action-btn:hover {
    background: #e5e5e5;
    transform: scale(1.03);
}

.copy-action-btn.copied {
    background: #10b981;
    color: #ffffff;
}

/* Channels List */
.flyout-channels-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-bottom: 14px;
}

.channel-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 9px 12px;
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.06);
    border-radius: 8px;
    text-decoration: none;
    color: #ffffff;
    transition: all 0.22s cubic-bezier(0.16, 1, 0.3, 1);
}

.channel-item:hover {
    background: rgba(255, 255, 255, 0.09);
    border-color: rgba(255, 255, 255, 0.22);
    transform: translateX(4px);
    color: #ffffff;
}

.channel-icon-wrap {
    width: 32px;
    height: 32px;
    border-radius: 7px;
    background: rgba(255, 255, 255, 0.08);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ffffff;
    flex-shrink: 0;
    transition: background 0.2s ease, transform 0.2s ease;
}

.channel-item:hover .channel-icon-wrap {
    background: #ffffff;
    color: #000000;
    transform: scale(1.05);
}

.channel-info {
    flex: 1;
    display: flex;
    flex-direction: column;
    min-width: 0;
}

.channel-name {
    font-size: 0.84rem;
    font-weight: 700;
    letter-spacing: 0.02em;
    color: #ffffff;
}

.channel-sub {
    font-size: 0.68rem;
    color: #71717a;
    letter-spacing: 0.02em;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    margin-top: 1px;
}

.channel-arrow {
    color: #52525b;
    transition: transform 0.2s ease, color 0.2s ease;
}

.channel-item:hover .channel-arrow {
    color: #ffffff;
    transform: translate(2px, -2px);
}

.channels-empty {
    padding: 16px;
    text-align: center;
    font-size: 0.76rem;
    color: #71717a;
    background: rgba(255, 255, 255, 0.02);
    border-radius: 8px;
}

/* Flyout Footer */
.flyout-footer {
    display: flex;
    flex-direction: column;
    gap: 3px;
    padding-top: 12px;
    border-top: 1px solid rgba(255, 255, 255, 0.08);
    text-align: center;
}

.footer-tag {
    font-size: 0.72rem;
    font-weight: 800;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: #ffffff;
}

.footer-text {
    font-size: 0.68rem;
    color: #71717a;
    letter-spacing: 0.02em;
}

/* Mobile Responsiveness */
@media (max-width: 768px) {
    .pistis-social-wrap.pos-bottom-left,
    .pistis-social-wrap.pos-bottom-right {
        bottom: 76px;
        left: 14px;
        right: auto;
    }

    .pistis-social-pill {
        padding: 7px 12px !important;
        gap: 8px !important;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.4) !important;
    }

    .pistis-social-pill .pill-sub {
        display: none !important;
    }

    .pistis-social-pill .pill-handle {
        font-size: 0.72rem !important;
        font-weight: 700 !important;
    }

    .pistis-social-flyout {
        bottom: 50px !important;
        left: 0 !important;
        width: calc(100vw - 28px) !important;
        max-width: 310px !important;
        max-height: 80vh !important;
        overflow-y: auto !important;
    }
}
</style>

<script>
function toggleFloatingSocial(forceState) {
    const wrap = document.getElementById('pistis-floating-social');
    const pill = document.getElementById('pistis-social-pill');
    const flyout = document.getElementById('pistis-social-flyout');
    if (!flyout || !pill) return;

    const isOpen = flyout.classList.contains('is-open');
    const shouldOpen = forceState !== undefined ? forceState : !isOpen;

    if (shouldOpen) {
        flyout.classList.add('is-open');
        pill.classList.add('is-active');
        pill.setAttribute('aria-expanded', 'true');
    } else {
        flyout.classList.remove('is-open');
        pill.classList.remove('is-active');
        pill.setAttribute('aria-expanded', 'false');
    }
}

function copySocialHandle(handle) {
    if (!handle) return;
    navigator.clipboard.writeText(handle).then(() => {
        const btn = document.getElementById('copy-handle-btn');
        const text = document.getElementById('copy-btn-text');
        const icon = document.getElementById('copy-btn-icon');
        if (!btn || !text) return;

        btn.classList.add('copied');
        text.textContent = 'Copied!';
        if (icon) {
            icon.innerHTML = '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>';
        }

        setTimeout(() => {
            btn.classList.remove('copied');
            text.textContent = 'Copy';
            if (icon) {
                icon.innerHTML = '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>';
            }
        }, 2200);
    }).catch(err => {
        console.error('Failed to copy: ', err);
    });
}

// Close when clicking outside of the floating widget
document.addEventListener('click', function(e) {
    const wrap = document.getElementById('pistis-floating-social');
    if (!wrap) return;
    if (!wrap.contains(e.target)) {
        toggleFloatingSocial(false);
    }
});

// Close on Escape key press
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        toggleFloatingSocial(false);
    }
});
</script>
@endif
