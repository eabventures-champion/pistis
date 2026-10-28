<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $primaryHandle }} — {{ $storeName }} Official Social Hub</title>
    <meta name="description" content="Connect with {{ $storeName }} on Instagram, TikTok, and direct channels. Official Atelier & Runway Archive.">
    
    <!-- Open Graph / Meta -->
    <meta property="og:title" content="{{ $primaryHandle }} — {{ $storeName }} Official Hub">
    <meta property="og:description" content="{{ $tagline }}">
    <meta property="og:type" content="website">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --bg-color: #08080a;
            --card-bg: rgba(22, 22, 26, 0.75);
            --card-border: rgba(255, 255, 255, 0.1);
            --card-hover-border: rgba(255, 255, 255, 0.35);
            --text-primary: #f8fafc;
            --text-secondary: #94a3b8;
            --gold-accent: #d4af37;
            --gold-glow: rgba(212, 175, 55, 0.25);
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: var(--bg-color);
            background-image: 
                radial-gradient(circle at 50% 0%, rgba(212, 175, 55, 0.08) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(30, 41, 59, 0.4) 0%, transparent 60%);
            background-attachment: fixed;
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 40px 20px 48px 20px;
            -webkit-font-smoothing: antialiased;
        }

        .hub-container {
            width: 100%;
            max-width: 480px;
            display: flex;
            flex-direction: column;
            align-items: center;
            z-index: 1;
        }

        /* Top Brand Profile */
        .brand-header {
            text-align: center;
            margin-bottom: 28px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .brand-logo-frame {
            position: relative;
            width: 88px;
            height: 88px;
            border-radius: 50%;
            padding: 3px;
            background: linear-gradient(135deg, rgba(212, 175, 55, 0.6), rgba(255, 255, 255, 0.1), rgba(212, 175, 55, 0.4));
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5), 0 0 20px var(--gold-glow);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .brand-logo-inner {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: #000000;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border: 2px solid rgba(0, 0, 0, 0.8);
        }

        .brand-logo-inner img {
            max-width: 78%;
            max-height: 78%;
            object-fit: contain;
        }

        .brand-monogram {
            font-family: 'Cinzel', serif;
            font-size: 2.2rem;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: 0.05em;
        }

        .verified-badge {
            position: absolute;
            bottom: 2px;
            right: 2px;
            width: 24px;
            height: 24px;
            background: #0f172a;
            border: 2px solid var(--bg-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #38bdf8;
            box-shadow: 0 2px 8px rgba(0,0,0,0.4);
        }

        .brand-title {
            font-family: 'Cinzel', serif;
            font-size: 1.45rem;
            font-weight: 700;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: #ffffff;
            margin-bottom: 6px;
        }

        .brand-handle-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.12);
            padding: 6px 14px;
            border-radius: 999px;
            font-size: 0.85rem;
            font-weight: 600;
            color: #e2e8f0;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-bottom: 8px;
        }

        .brand-handle-pill:hover {
            background: rgba(255, 255, 255, 0.12);
            border-color: rgba(255, 255, 255, 0.25);
            transform: translateY(-1px);
        }

        .brand-tagline {
            font-size: 0.82rem;
            color: var(--text-secondary);
            max-width: 320px;
            line-height: 1.45;
            font-weight: 400;
            letter-spacing: 0.02em;
        }

        /* Scan Notice Banner for Events */
        .event-banner {
            width: 100%;
            background: rgba(212, 175, 55, 0.08);
            border: 1px solid rgba(212, 175, 55, 0.25);
            border-radius: 12px;
            padding: 10px 16px;
            margin-bottom: 22px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .event-banner-text {
            font-size: 0.74rem;
            color: #fef08a;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .event-banner-pulse {
            width: 7px;
            height: 7px;
            background: #10b981;
            border-radius: 50%;
            box-shadow: 0 0 8px #10b981;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { opacity: 0.4; }
            50% { opacity: 1; }
            100% { opacity: 0.4; }
        }

        /* Channels List */
        .channels-list {
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 13px;
            margin-bottom: 28px;
        }

        .channel-card {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px 18px;
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 16px;
            text-decoration: none;
            color: var(--text-primary);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            overflow: hidden;
        }

        .channel-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 3px;
            height: 100%;
            background: var(--indicator-color, #ffffff);
            opacity: 0.8;
            transition: width 0.25s ease;
        }

        .channel-card:hover {
            transform: translateY(-2px);
            border-color: var(--card-hover-border);
            background: rgba(30, 30, 36, 0.85);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.35);
        }

        .channel-card:hover::before {
            width: 5px;
        }

        .channel-left {
            display: flex;
            align-items: center;
            gap: 14px;
            min-width: 0;
        }

        .channel-icon-box {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            color: #ffffff;
        }

        .channel-content {
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .channel-title-row {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .channel-title {
            font-size: 0.96rem;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: 0.02em;
        }

        .channel-badge {
            font-size: 0.62rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            padding: 2px 7px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.12);
            color: #f1f5f9;
        }

        .channel-subtitle {
            font-size: 0.74rem;
            color: var(--text-secondary);
            margin-top: 3px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .channel-arrow-box {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.05);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #94a3b8;
            flex-shrink: 0;
            transition: all 0.2s ease;
        }

        .channel-card:hover .channel-arrow-box {
            background: #ffffff;
            color: #000000;
            transform: translateX(2px);
        }

        /* Storefront CTA Button */
        .store-cta-card {
            width: 100%;
            background: linear-gradient(135deg, #18181b 0%, #09090b 100%);
            border: 1px solid rgba(212, 175, 55, 0.4);
            border-radius: 16px;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            text-decoration: none;
            color: #ffffff;
            margin-bottom: 24px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.4), inset 0 0 16px rgba(212, 175, 55, 0.05);
            transition: all 0.25s ease;
        }

        .store-cta-card:hover {
            border-color: rgba(212, 175, 55, 0.8);
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.6), 0 0 20px rgba(212, 175, 55, 0.2);
        }

        .store-cta-text {
            display: flex;
            flex-direction: column;
        }

        .store-cta-heading {
            font-family: 'Cinzel', serif;
            font-size: 0.95rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #ffffff;
        }

        .store-cta-sub {
            font-size: 0.74rem;
            color: #cbd5e1;
            margin-top: 3px;
        }

        /* Action Buttons Row */
        .actions-row {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-bottom: 32px;
            width: 100%;
        }

        .action-pill-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #94a3b8;
            font-size: 0.78rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .action-pill-btn:hover {
            background: rgba(255, 255, 255, 0.12);
            color: #ffffff;
            border-color: rgba(255, 255, 255, 0.25);
        }

        /* Footer */
        .hub-footer {
            text-align: center;
            font-size: 0.7rem;
            color: #64748b;
            letter-spacing: 0.05em;
            line-height: 1.6;
        }

        .hub-footer-brand {
            font-family: 'Cinzel', serif;
            font-weight: 600;
            letter-spacing: 0.14em;
            color: #94a3b8;
            text-transform: uppercase;
            display: block;
            margin-bottom: 2px;
        }

        /* Toast Alert */
        #toast-alert {
            position: fixed;
            bottom: 24px;
            left: 50%;
            transform: translateX(-50%) translateY(100px);
            background: #ffffff;
            color: #000000;
            padding: 10px 20px;
            border-radius: 999px;
            font-size: 0.82rem;
            font-weight: 700;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            z-index: 9999;
            pointer-events: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        #toast-alert.show {
            transform: translateX(-50%) translateY(0);
        }
    </style>
</head>
<body>

<div class="hub-container">
    
    <!-- Brand Header -->
    <header class="brand-header">
        <div class="brand-logo-frame">
            <div class="brand-logo-inner">
                @if(!empty($logoUrl))
                    <img src="{{ $logoUrl }}" alt="{{ $storeName }}">
                @else
                    <span class="brand-monogram">{{ substr($storeName, 0, 1) }}</span>
                @endif
            </div>
            <div class="verified-badge" title="Official Verified Handle">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                </svg>
            </div>
        </div>

        <h1 class="brand-title">{{ $storeName }}</h1>

        <button type="button" class="brand-handle-pill" onclick="copyText('{{ $primaryHandle }}', 'Handle copied to clipboard!')">
            <span>{{ $primaryHandle }}</span>
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
            </svg>
        </button>

        <p class="brand-tagline">{{ $tagline }}</p>
    </header>

    <!-- Event Live Connect Notice -->
    <div class="event-banner">
        <span class="event-banner-text">
            <span class="event-banner-pulse"></span>
            Event Media Connect • Live Channels
        </span>
        <span style="font-size:0.68rem;color:#a1a1aa;letter-spacing:0.04em;">Official Link</span>
    </div>

    <!-- Active Social Channels -->
    <main class="channels-list">
        @forelse($channels as $channel)
            <a href="{{ $channel['url'] }}" 
               target="_blank" 
               rel="noopener noreferrer" 
               class="channel-card" 
               style="--indicator-color: {{ $channel['color'] }};"
               onclick="trackClick('{{ $channel['key'] }}')">
                <div class="channel-left">
                    <div class="channel-icon-box" style="background: {{ $channel['gradient'] }};">
                        @if($channel['icon'] === 'instagram')
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
                        @elseif($channel['icon'] === 'tiktok')
                            <svg width="21" height="21" viewBox="0 0 24 24" fill="currentColor"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64c.29 0 .58.04.86.12V9.42a6.37 6.37 0 0 0-.86-.06 6.34 6.34 0 0 0-6.34 6.34 6.34 6.34 0 0 0 6.34 6.34 6.34 6.34 0 0 0 6.34-6.34V8.58a8.28 8.28 0 0 0 4.84 1.56V6.69z"/></svg>
                        @elseif($channel['icon'] === 'whatsapp')
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
                        @elseif($channel['icon'] === 'twitter')
                            <svg width="19" height="19" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        @elseif($channel['icon'] === 'youtube')
                            <svg width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z"/><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"/></svg>
                        @elseif($channel['icon'] === 'pinterest')
                            <svg width="21" height="21" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0a12 12 0 0 0-4.37 23.18c-.06-.98-.12-2.48.02-3.56.13-1 .85-6.52.85-6.52s-.22-.44-.22-1.08c0-1.02.59-1.78 1.33-1.78.63 0 .93.47.93 1.04 0 .63-.4 1.58-.61 2.45-.17.74.37 1.35 1.1 1.35 1.32 0 2.34-1.39 2.34-3.4 0-1.78-1.28-3.02-3.1-3.02-2.27 0-3.6 1.7-3.6 3.46 0 .68.26 1.42.59 1.82.07.08.08.15.06.23-.06.27-.2.83-.23.95-.04.16-.13.2-.3.12-1.12-.52-1.82-2.16-1.82-3.48 0-2.83 2.06-5.43 5.94-5.43 3.12 0 5.54 2.22 5.54 5.19 0 3.1-1.95 5.59-4.66 5.59-.91 0-1.77-.47-2.06-1.03l-.56 2.14c-.2 1.79-.75 2.01-.78 2.07A12 12 0 1 0 12 0z"/></svg>
                        @elseif($channel['icon'] === 'facebook')
                            <svg width="21" height="21" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        @else
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"/><path d="M2 12h20"/></svg>
                        @endif
                    </div>

                    <div class="channel-content">
                        <div class="channel-title-row">
                            <span class="channel-title">{{ $channel['name'] }}</span>
                            @if(!empty($channel['badge']))
                                <span class="channel-badge">{{ $channel['badge'] }}</span>
                            @endif
                        </div>
                        <span class="channel-subtitle">{{ $channel['subtitle'] }}</span>
                    </div>
                </div>

                <div class="channel-arrow-box">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </div>
            </a>
        @empty
            <div style="text-align:center;padding:24px;background:rgba(255,255,255,0.03);border-radius:12px;color:#94a3b8;font-size:0.85rem;">
                No social channels published yet. Please configure links in the Pistis dashboard.
            </div>
        @endforelse
    </main>

    <!-- Visit Official Storefront CTA -->
    <a href="{{ route('shop.index') }}" class="store-cta-card">
        <div class="store-cta-text">
            <span class="store-cta-heading">Explore Official Storefront</span>
            <span class="store-cta-sub">Shop the Latest Runway & Editorial Drops</span>
        </div>
        <div class="channel-arrow-box" style="background:#ffffff;color:#000000;">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="5" y1="12" x2="19" y2="12"></line>
                <polyline points="12 5 19 12 12 19"></polyline>
            </svg>
        </div>
    </a>

    <!-- Action Buttons -->
    <div class="actions-row">
        <button type="button" class="action-pill-btn" onclick="shareHub()">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="18" cy="5" r="3"></circle>
                <circle cx="6" cy="12" r="3"></circle>
                <circle cx="18" cy="19" r="3"></circle>
                <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line>
                <line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line>
            </svg>
            <span>Share Hub</span>
        </button>

        <button type="button" class="action-pill-btn" onclick="copyText(window.location.href, 'Hub link copied to clipboard!')">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
            </svg>
            <span>Copy Link</span>
        </button>
    </div>

    <!-- Hub Footer -->
    <footer class="hub-footer">
        <span class="hub-footer-brand">{{ $storeName }}</span>
        <span>Architectural silhouettes and timeless luxury essentials.</span>
        <div style="margin-top:6px;opacity:0.6;">© {{ date('Y') }} {{ $storeName }}. All rights reserved.</div>
    </footer>

</div>

<!-- Toast notification element -->
<div id="toast-alert">
    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
        <polyline points="20 6 9 17 4 12"></polyline>
    </svg>
    <span id="toast-message">Copied to clipboard!</span>
</div>

<script>
function showToast(msg) {
    const toast = document.getElementById('toast-alert');
    const label = document.getElementById('toast-message');
    if (!toast || !label) return;
    label.textContent = msg;
    toast.classList.add('show');
    setTimeout(() => {
        toast.classList.remove('show');
    }, 2400);
}

function copyText(text, successMsg) {
    if (!text) return;
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(text).then(() => {
            showToast(successMsg || 'Copied!');
        }).catch(() => fallbackCopy(text, successMsg));
    } else {
        fallbackCopy(text, successMsg);
    }
}

function fallbackCopy(text, successMsg) {
    const input = document.createElement('input');
    input.value = text;
    document.body.appendChild(input);
    input.select();
    try {
        document.execCommand('copy');
        showToast(successMsg || 'Copied!');
    } catch (e) {
        alert(text);
    }
    document.body.removeChild(input);
}

function shareHub() {
    if (navigator.share) {
        navigator.share({
            title: "{{ $storeName }} Official Social Hub",
            text: "{{ $tagline }}",
            url: window.location.href,
        }).catch(() => {});
    } else {
        copyText(window.location.href, 'Hub link copied! Share it anywhere.');
    }
}

function trackClick(channel) {
    // Optional click analytics logging hook
}
</script>

</body>
</html>
