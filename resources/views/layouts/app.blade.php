<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Pistis — Premium ecommerce store. Discover quality products at great prices.">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('meta')
    <title>@yield('title', 'Pistis — Premium Store')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400;1,600&family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ file_exists(public_path('css/app.css')) ? filemtime(public_path('css/app.css')) : time() }}">
    @php
        $navHeight = max(76, (($store_logo_height ?? 32) > 52 ? (($store_logo_height ?? 32) + 24) : 76));
    @endphp
    <style>
        :root {
            --nav-height: {{ $navHeight }}px;
        }
        .navbar-brand {
            line-height: 1;
        }
        .brand-logo-img {
            transition: max-height 0.2s ease, transform 0.2s ease;
            image-rendering: auto;
            border-radius: 50%;
            aspect-ratio: 1 / 1;
            object-fit: contain;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }
        .brand-logo-img:hover {
            transform: scale(1.03);
        }
        @media (max-width: 768px) {
            :root {
                --nav-height: {{ min($navHeight, 72) }}px;
            }
            .brand-logo-img {
                max-height: {{ min($store_logo_height ?? 32, 50) }}px !important;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="page-wrapper">
        {{-- Navigation --}}
        <nav class="navbar" id="main-navbar">
            <div class="navbar-inner">
                <a href="{{ route('home') }}" class="navbar-brand">
                    @if(!empty($store_logo))
                        <img src="{{ $store_logo }}" alt="{{ $store_name ?? 'PISTIS' }}" class="brand-logo-img" style="max-height: {{ $store_logo_height ?? 32 }}px; width: auto; object-fit: contain; display: block;">
                    @else
                        <span class="brand-icon">P</span>
                    @endif
                    @if(empty($store_hide_brand_text))
                        <span>{{ $store_name ?? 'PISTIS' }}</span>
                    @endif
                </a>

                <ul class="navbar-links" id="nav-links">
                    <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Collection</a></li>
                    <li><a href="{{ route('shop.index') }}" class="{{ request()->routeIs('shop.*') ? 'active' : '' }}">Shop</a></li>
                    @if(config('services.shopify.store_url'))
                        <li><a href="{{ config('services.shopify.store_url') }}" target="_blank">Shopify Store ↗</a></li>
                    @endif
                    <li class="nav-divider-mobile"></li>
                    @auth('customer')
                        <li class="mobile-nav-auth"><a href="{{ route('account.index') }}">My Account</a></li>
                        <li class="mobile-nav-auth">
                            <form action="{{ route('customer.logout') }}" method="POST" style="margin:0;">
                                @csrf
                                <button type="submit" class="mobile-nav-logout-btn">Logout</button>
                            </form>
                        </li>
                    @else
                        <li class="mobile-nav-auth"><a href="{{ route('customer.login') }}">Login</a></li>
                        <li class="mobile-nav-auth"><a href="{{ route('customer.register') }}" class="mobile-join-link">Join Pistis</a></li>
                    @endauth
                </ul>

                <div class="navbar-actions">
                    @php
                        $cartCount = 0;
                        try {
                            $cartCount = app(\App\Services\CartService::class)->getCart()->items->sum('quantity');
                        } catch (\Exception $e) {}
                    @endphp
                    <a href="{{ route('cart.index') }}" class="cart-badge" aria-label="Shopping Bag">
                        <span>BAG</span>
                        @if($cartCount > 0)
                            <span class="cart-count">{{ $cartCount }}</span>
                        @endif
                    </a>

                    @auth('customer')
                        <a href="{{ route('account.index') }}" class="btn btn-sm btn-secondary desktop-auth-btn">Account</a>
                        <form action="{{ route('customer.logout') }}" method="POST" class="desktop-auth-btn" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-secondary">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('customer.login') }}" class="btn btn-sm btn-secondary desktop-auth-btn">Login</a>
                        <a href="{{ route('customer.register') }}" class="btn btn-sm btn-primary desktop-auth-btn">Join</a>
                    @endauth

                    <button class="mobile-toggle" aria-label="Toggle navigation" onclick="document.getElementById('nav-links').classList.toggle('open')">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="3" y1="12" x2="21" y2="12"></line>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <line x1="3" y1="18" x2="21" y2="18"></line>
                        </svg>
                    </button>
                </div>
            </div>
        </nav>

        {{-- Main Content --}}
        <main class="main-content">
            @if(session('success'))
                <div class="container" style="padding-top: 24px;">
                    <div class="alert alert-success" style="background:#000000;color:#ffffff;border:none;border-radius:0;font-size:0.8rem;letter-spacing:0.05em;text-transform:uppercase;">
                        <span>✓ {{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="container" style="padding-top: 24px;">
                    <div class="alert alert-danger" style="background:#dc2626;color:#ffffff;border:none;border-radius:0;font-size:0.8rem;letter-spacing:0.05em;text-transform:uppercase;">
                        <span>✕ {{ session('error') }}</span>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>

        {{-- Luxury Editorial Footer --}}
        <footer class="footer">
            <div class="container">
                <div class="footer-grid">
                    <div class="footer-brand">
                        <a href="{{ route('home') }}" class="footer-brand-header" style="display:inline-flex;align-items:center;gap:14px;text-decoration:none;margin-bottom:16px;">
                            @if(!empty($store_logo))
                                <img src="{{ $store_logo }}" alt="{{ $store_name ?? 'PISTIS' }}" class="footer-logo-img" style="height:44px;width:44px;object-fit:contain;border-radius:50%;border:1px solid rgba(255,255,255,0.25);background:#000000;display:block;flex-shrink:0;">
                            @else
                                <span class="brand-icon" style="width:40px;height:40px;border-radius:50%;background:#ffffff;color:#000000;display:inline-flex;align-items:center;justify-content:center;font-family:'Cormorant Garamond',serif;font-weight:700;font-size:1.2rem;flex-shrink:0;">P</span>
                            @endif
                            <span class="brand-title" style="font-family:'Inter',sans-serif;font-size:1.25rem;font-weight:800;letter-spacing:0.28em;color:#ffffff !important;-webkit-text-fill-color:#ffffff !important;text-transform:uppercase;margin:0;">{{ $store_name ?? 'PISTIS' }}</span>
                        </a>
                        <p style="font-size:0.85rem;line-height:1.8;color:#a3a3a3 !important;-webkit-text-fill-color:#a3a3a3 !important;max-width:320px;margin:0 0 14px 0;">Architectural silhouettes and timeless luxury essentials designed for modern permanence.</p>
                        @if(!empty($socialSettings['show_in_footer']))
                            <div class="footer-social-links" style="display:flex;align-items:center;gap:12px;margin-top:14px;flex-wrap:wrap;">
                                @if(!empty($socialSettings['instagram']))
                                    <a href="{{ str_starts_with($socialSettings['instagram'], 'http') ? $socialSettings['instagram'] : 'https://instagram.com/' . ltrim($socialSettings['instagram'], '@') }}" target="_blank" rel="noopener noreferrer" style="display:inline-flex;align-items:center;justify-content:center;width:34px;height:34px;border-radius:50%;background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.15);color:#ffffff;transition:all 0.2s;" onmouseover="this.style.background='#ffffff';this.style.color='#000000';" onmouseout="this.style.background='rgba(255,255,255,0.08)';this.style.color='#ffffff';" title="Instagram: {{ $socialSettings['instagram'] }}">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
                                    </a>
                                @endif
                                @if(!empty($socialSettings['tiktok']))
                                    <a href="{{ str_starts_with($socialSettings['tiktok'], 'http') ? $socialSettings['tiktok'] : 'https://tiktok.com/@' . ltrim($socialSettings['tiktok'], '@') }}" target="_blank" rel="noopener noreferrer" style="display:inline-flex;align-items:center;justify-content:center;width:34px;height:34px;border-radius:50%;background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.15);color:#ffffff;transition:all 0.2s;" onmouseover="this.style.background='#ffffff';this.style.color='#000000';" onmouseout="this.style.background='rgba(255,255,255,0.08)';this.style.color='#ffffff';" title="TikTok: {{ $socialSettings['tiktok'] }}">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64c.29 0 .58.04.86.12V9.42a6.37 6.37 0 0 0-.86-.06 6.34 6.34 0 0 0-6.34 6.34 6.34 6.34 0 0 0 6.34 6.34 6.34 6.34 0 0 0 6.34-6.34V8.58a8.28 8.28 0 0 0 4.84 1.56V6.69z"/></svg>
                                    </a>
                                @endif
                                @if(!empty($socialSettings['twitter']))
                                    <a href="{{ str_starts_with($socialSettings['twitter'], 'http') ? $socialSettings['twitter'] : 'https://x.com/' . ltrim($socialSettings['twitter'], '@') }}" target="_blank" rel="noopener noreferrer" style="display:inline-flex;align-items:center;justify-content:center;width:34px;height:34px;border-radius:50%;background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.15);color:#ffffff;transition:all 0.2s;" onmouseover="this.style.background='#ffffff';this.style.color='#000000';" onmouseout="this.style.background='rgba(255,255,255,0.08)';this.style.color='#ffffff';" title="X (Twitter): {{ $socialSettings['twitter'] }}">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                                    </a>
                                @endif
                                @if(!empty($socialSettings['whatsapp']))
                                    <a href="{{ str_starts_with($socialSettings['whatsapp'], 'http') ? $socialSettings['whatsapp'] : 'https://wa.me/' . preg_replace('/[^0-9]/', '', $socialSettings['whatsapp']) }}" target="_blank" rel="noopener noreferrer" style="display:inline-flex;align-items:center;justify-content:center;width:34px;height:34px;border-radius:50%;background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.15);color:#ffffff;transition:all 0.2s;" onmouseover="this.style.background='#ffffff';this.style.color='#000000';" onmouseout="this.style.background='rgba(255,255,255,0.08)';this.style.color='#ffffff';" title="WhatsApp Concierge">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                    <div class="footer-col">
                        <h4>Collection</h4>
                        <ul>
                            <li><a href="{{ route('shop.index') }}">All Pieces</a></li>
                            <li><a href="{{ route('shop.index', ['sort' => 'newest']) }}">New Arrivals</a></li>
                            <li><a href="{{ route('shop.index', ['sort' => 'featured']) }}">Editorial Curations</a></li>
                        </ul>
                    </div>
                    <div class="footer-col">
                        <h4>Client Services</h4>
                        <ul>
                            <li><a href="{{ route('account.index') }}">Client Account</a></li>
                            <li><a href="{{ route('cart.index') }}">Shopping Bag</a></li>
                            @if(config('services.shopify.store_url'))
                                <li><a href="{{ config('services.shopify.store_url') }}" target="_blank">Shopify Portal ↗</a></li>
                            @endif
                        </ul>
                    </div>
                    <div class="footer-col">
                        <h4>Inner Circle</h4>
                        <p style="font-size:0.8rem;color:#737373;margin-bottom:12px;">Subscribe to receive private capsule previews and editorial lookbooks.</p>
                        <form class="newsletter-form" onsubmit="event.preventDefault(); alert('Thank you for joining Pistis.');">
                            <input type="email" placeholder="ENTER YOUR EMAIL" required>
                            <button type="submit" aria-label="Subscribe">→</button>
                        </form>
                    </div>
                </div>
                <div class="footer-bottom">
                    <span>&copy; {{ date('Y') }} PISTIS MAISON. ALL RIGHTS RESERVED.</span>
                    <span>EDITION 2026 · MONOCHROME ARCHIVE</span>
                </div>
            </div>
    </div>

    {{-- Floating Luxury Social Media Widget --}}
    @include('components.floating-social')

    {{-- Luxury Campaign & Lookbook Video Intro --}}
    @include('components.campaign-video-modal')

    @stack('scripts')
    <script>
        document.addEventListener('click', function(e) {
            const navLinks = document.getElementById('nav-links');
            const toggle = document.querySelector('.mobile-toggle');
            if (navLinks && navLinks.classList.contains('open')) {
                if (!navLinks.contains(e.target) && !toggle.contains(e.target)) {
                    navLinks.classList.remove('open');
                }
            }
        });
    </script>
</body>
</html>



