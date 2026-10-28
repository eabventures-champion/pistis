<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, viewport-fit=cover">
    <title>{{ $primaryHandle }} — {{ $storeName }} Official Social Hub</title>
    <meta name="description" content="Connect with {{ $storeName }} on official social media and VIP concierge channels. {{ $tagline }}">
    
    <!-- Open Graph / Meta -->
    <meta property="og:title" content="{{ $primaryHandle }} — {{ $storeName }} Official Hub">
    <meta property="og:description" content="{{ $tagline }}">
    <meta property="og:type" content="website">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            -webkit-tap-highlight-color: transparent;
        }

        :root {
            --bg-color: #070709;
            --card-bg: rgba(20, 20, 24, 0.78);
            --card-border: rgba(255, 255, 255, 0.08);
            --card-hover-border: rgba(255, 255, 255, 0.28);
            --text-primary: #f8fafc;
            --text-secondary: #94a3b8;
            --gold-accent: #d4af37;
            --gold-glow: rgba(212, 175, 55, 0.2);
        }

        html {
            font-size: 16px;
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: var(--bg-color);
            background-image: 
                radial-gradient(circle at 50% -10%, rgba(212, 175, 55, 0.12) 0%, transparent 55%),
                radial-gradient(circle at 85% 85%, rgba(30, 41, 59, 0.35) 0%, transparent 60%);
            background-attachment: fixed;
            color: var(--text-primary);
            min-height: 100vh;
            min-height: 100dvh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 24px 14px 32px 14px;
            -webkit-font-smoothing: antialiased;
            touch-action: manipulation;
        }

        .hub-container {
            width: 100%;
            max-width: 440px;
            display: flex;
            flex-direction: column;
            align-items: center;
            z-index: 1;
        }

        /* Top Brand Profile */
        .brand-header {
            text-align: center;
            margin-bottom: 16px;
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
        }

        .brand-logo-frame {
            position: relative;
            width: 72px;
            height: 72px;
            border-radius: 50%;
            padding: 2.5px;
            background: linear-gradient(135deg, rgba(212, 175, 55, 0.7), rgba(255, 255, 255, 0.15), rgba(212, 175, 55, 0.5));
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.6), 0 0 16px var(--gold-glow);
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
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
            border: 2px solid rgba(0, 0, 0, 0.85);
        }

        .brand-logo-inner img {
            max-width: 80%;
            max-height: 80%;
            object-fit: contain;
        }

        .brand-monogram {
            font-family: 'Cinzel', serif;
            font-size: 1.8rem;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: 0.05em;
        }

        .verified-badge {
            position: absolute;
            bottom: 0px;
            right: 0px;
            width: 22px;
            height: 22px;
            background: #0f172a;
            border: 2px solid var(--bg-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #38bdf8;
            box-shadow: 0 2px 6px rgba(0,0,0,0.5);
        }

        .brand-title {
            font-family: 'Cinzel', serif;
            font-size: clamp(1.15rem, 5.2vw, 1.45rem);
            font-weight: 700;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            color: #ffffff;
            margin-bottom: 4px;
            line-height: 1.25;
            text-align: center;
        }

        .brand-handle-pill {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.12);
            padding: 4px 12px;
            border-radius: 999px;
            font-size: 0.8rem;
            font-weight: 600;
            color: #e2e8f0;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-bottom: 6px;
        }

        .brand-handle-pill:active {
            transform: scale(0.97);
            background: rgba(255, 255, 255, 0.12);
        }

        .brand-tagline {
            font-size: 0.74rem;
            color: var(--text-secondary);
            max-width: 300px;
            line-height: 1.35;
            font-weight: 400;
            letter-spacing: 0.02em;
            text-align: center;
        }

        /* Scan Notice Banner for Events */
        .event-banner {
            width: 100%;
            background: rgba(212, 175, 55, 0.07);
            border: 1px solid rgba(212, 175, 55, 0.22);
            border-radius: 10px;
            padding: 7px 12px;
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
        }

        .event-banner-text {
            font-size: 0.7rem;
            color: #fef08a;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .event-banner-pulse {
            width: 6px;
            height: 6px;
            background: #10b981;
            border-radius: 50%;
            box-shadow: 0 0 6px #10b981;
            animation: pulse 2s infinite;
            flex-shrink: 0;
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
            gap: 10px;
            margin-bottom: 20px;
        }

        .channel-card {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 14px;
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 14px;
            text-decoration: none;
            color: var(--text-primary);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.2s ease, background 0.2s ease;
            overflow: hidden;
            width: 100%;
        }

        .channel-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 3px;
            height: 100%;
            background: var(--indicator-color, #ffffff);
            opacity: 0.85;
            transition: width 0.2s ease;
        }

        .channel-card:active {
            transform: scale(0.985);
            background: rgba(30, 30, 36, 0.95);
        }

        @media (hover: hover) {
            .channel-card:hover {
                transform: translateY(-2px);
                border-color: var(--card-hover-border);
                background: rgba(28, 28, 34, 0.9);
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            }
            .channel-card:hover::before {
                width: 5px;
            }
            .channel-card:hover .channel-arrow-box {
                background: #ffffff;
                color: #000000;
                transform: translateX(2px);
            }
        }

        .channel-left {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
            flex: 1;
        }

        .channel-icon-box {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 3px 10px rgba(0,0,0,0.3);
            color: #ffffff;
        }

        .channel-content {
            display: flex;
            flex-direction: column;
            min-width: 0;
            flex: 1;
        }

        .channel-title-row {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
            line-height: 1.2;
        }

        .channel-title {
            font-size: 0.9rem;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: 0.01em;
        }

        .channel-badge {
            font-size: 0.58rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            padding: 1.5px 6px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.12);
            color: #f1f5f9;
            white-space: nowrap;
        }

        .channel-subtitle {
            font-size: 0.7rem;
            color: var(--text-secondary);
            margin-top: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            line-height: 1.25;
        }

        .channel-arrow-box {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.05);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #94a3b8;
            flex-shrink: 0;
            margin-left: 8px;
            transition: all 0.2s ease;
        }

        /* Optional Storefront CTA Card */
        .store-cta-card {
            width: 100%;
            background: linear-gradient(135deg, #18181b 0%, #09090b 100%);
            border: 1px solid rgba(212, 175, 55, 0.4);
            border-radius: 14px;
            padding: 13px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            text-decoration: none;
            color: #ffffff;
            margin-bottom: 18px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.4), inset 0 0 14px rgba(212, 175, 55, 0.04);
            transition: all 0.2s ease;
        }

        .store-cta-card:active {
            transform: scale(0.985);
        }

        @media (hover: hover) {
            .store-cta-card:hover {
                border-color: rgba(212, 175, 55, 0.8);
                transform: translateY(-2px);
                box-shadow: 0 10px 24px rgba(0, 0, 0, 0.5), 0 0 16px rgba(212, 175, 55, 0.18);
            }
        }

        .store-cta-text {
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .store-cta-heading {
            font-family: 'Cinzel', serif;
            font-size: 0.88rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #ffffff;
        }

        .store-cta-sub {
            font-size: 0.7rem;
            color: #cbd5e1;
            margin-top: 2px;
        }

        /* Action Buttons Row */
        .actions-row {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 24px;
            width: 100%;
        }

        .action-pill-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #94a3b8;
            font-size: 0.75rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .action-pill-btn:active {
            background: rgba(255, 255, 255, 0.12);
            color: #ffffff;
            transform: scale(0.96);
        }

        @media (hover: hover) {
            .action-pill-btn:hover {
                background: rgba(255, 255, 255, 0.1);
                color: #ffffff;
                border-color: rgba(255, 255, 255, 0.25);
            }
        }

        /* Footer */
        .hub-footer {
            text-align: center;
            font-size: 0.68rem;
            color: #64748b;
            letter-spacing: 0.04em;
            line-height: 1.5;
            width: 100%;
            padding-bottom: env(safe-area-inset-bottom, 8px);
        }

        .hub-footer-brand {
            font-family: 'Cinzel', serif;
            font-weight: 600;
            letter-spacing: 0.12em;
            color: #94a3b8;
            text-transform: uppercase;
            display: block;
            margin-bottom: 2px;
        }

        /* Toast Alert */
        #toast-alert {
            position: fixed;
            bottom: calc(20px + env(safe-area-inset-bottom, 0px));
            left: 50%;
            transform: translateX(-50%) translateY(100px);
            background: #ffffff;
            color: #000000;
            padding: 8px 18px;
            border-radius: 999px;
            font-size: 0.78rem;
            font-weight: 700;
            box-shadow: 0 10px 25px rgba(0,0,0,0.5);
            transition: transform 0.28s cubic-bezier(0.16, 1, 0.3, 1);
            z-index: 9999;
            pointer-events: none;
            display: flex;
            align-items: center;
            gap: 7px;
        }

        #toast-alert.show {
            transform: translateX(-50%) translateY(0);
        }

        /* Small screen adjustments (< 360px) */
        @media (max-width: 360px) {
            body {
                padding: 16px 10px 24px 10px;
            }
            .brand-logo-frame {
                width: 62px;
                height: 62px;
            }
            .channel-card {
                padding: 10px 12px;
            }
            .channel-icon-box {
                width: 34px;
                height: 34px;
            }
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
                <svg width="11" height="11" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                </svg>
            </div>
        </div>

        <h1 class="brand-title">{{ $storeName }}</h1>

        <button type="button" class="brand-handle-pill" onclick="copyText('{{ $primaryHandle }}', 'Handle copied!')">
            <span>{{ $primaryHandle }}</span>
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
            Media Connect • Live Handles
        </span>
        <span style="font-size:0.65rem;color:#a1a1aa;letter-spacing:0.04em;">Official Link</span>
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
                            <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
                        @elseif($channel['icon'] === 'tiktok')
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64c.29 0 .58.04.86.12V9.42a6.37 6.37 0 0 0-.86-.06 6.34 6.34 0 0 0-6.34 6.34 6.34 6.34 0 0 0 6.34 6.34 6.34 6.34 0 0 0 6.34-6.34V8.58a8.28 8.28 0 0 0 4.84 1.56V6.69z"/></svg>
                        @elseif($channel['icon'] === 'whatsapp')
                            <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
                        @elseif($channel['icon'] === 'twitter')
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        @elseif($channel['icon'] === 'youtube')
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z"/><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"/></svg>
                        @elseif($channel['icon'] === 'pinterest')
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0a12 12 0 0 0-4.37 23.18c-.06-.98-.12-2.48.02-3.56.13-1 .85-6.52.85-6.52s-.22-.44-.22-1.08c0-1.02.59-1.78 1.33-1.78.63 0 .93.47.93 1.04 0 .63-.4 1.58-.61 2.45-.17.74.37 1.35 1.1 1.35 1.32 0 2.34-1.39 2.34-3.4 0-1.78-1.28-3.02-3.1-3.02-2.27 0-3.6 1.7-3.6 3.46 0 .68.26 1.42.59 1.82.07.08.08.15.06.23-.06.27-.2.83-.23.95-.04.16-.13.2-.3.12-1.12-.52-1.82-2.16-1.82-3.48 0-2.83 2.06-5.43 5.94-5.43 3.12 0 5.54 2.22 5.54 5.19 0 3.1-1.95 5.59-4.66 5.59-.91 0-1.77-.47-2.06-1.03l-.56 2.14c-.2 1.79-.75 2.01-.78 2.07A12 12 0 1 0 12 0z"/></svg>
                        @elseif($channel['icon'] === 'facebook')
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        @else
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"/><path d="M2 12h20"/></svg>
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
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </div>
            </a>
        @empty
            <div style="text-align:center;padding:20px;background:rgba(255,255,255,0.03);border-radius:12px;color:#94a3b8;font-size:0.8rem;">
                No active social channels at the moment.
            </div>
        @endforelse
    </main>

    <!-- Optional Visit Storefront CTA (controllable via admin settings, hidden by default) -->
    @if(!empty($showStorefront))
        <a href="{{ route('shop.index') }}" class="store-cta-card">
            <div class="store-cta-text">
                <span class="store-cta-heading">Explore Official Storefront</span>
                <span class="store-cta-sub">Shop the Latest Runway & Editorial Drops</span>
            </div>
            <div class="channel-arrow-box" style="background:#ffffff;color:#000000;">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                    <polyline points="12 5 19 12 12 19"></polyline>
                </svg>
            </div>
        </a>
    @endif

    <!-- Action Buttons -->
    <div class="actions-row">
        <button type="button" class="action-pill-btn" onclick="shareHub()">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="18" cy="5" r="3"></circle>
                <circle cx="6" cy="12" r="3"></circle>
                <circle cx="18" cy="19" r="3"></circle>
                <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line>
                <line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line>
            </svg>
            <span>Share Hub</span>
        </button>

        <button type="button" class="action-pill-btn" onclick="copyText(window.location.href, 'Hub link copied!')">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
        <div style="margin-top:4px;opacity:0.6;">© {{ date('Y') }} {{ $storeName }}. All rights reserved.</div>
    </footer>

</div>

<!-- Toast notification element -->
<div id="toast-alert">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
        <polyline points="20 6 9 17 4 12"></polyline>
    </svg>
    <span id="toast-message">Copied!</span>
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
    }, 2200);
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
        copyText(window.location.href, 'Hub link copied!');
    }
}

function trackClick(channel) {
    // Analytics hook
}
</script>

</body>
</html>
