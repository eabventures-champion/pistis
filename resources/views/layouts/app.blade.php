<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Pistis — Premium ecommerce store. Discover quality products at great prices.">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                            $cartCount = app(\App\Services\CartService::class)->getCart()->items->count();
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
                        <div class="brand-title">PISTIS</div>
                        <p>Architectural silhouettes and timeless luxury essentials designed for modern permanence.</p>
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



