<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — Pistis Admin</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ file_exists(public_path('css/app.css')) ? filemtime(public_path('css/app.css')) : time() }}">
    @stack('styles')
</head>
<body>
    <div class="admin-layout">
        {{-- Mobile Overlay Backdrop --}}
        <div class="sidebar-backdrop" id="sidebar-backdrop" onclick="toggleMobileSidebar()"></div>

        {{-- Sidebar --}}
        <aside class="admin-sidebar" id="admin-sidebar">
            <div class="sidebar-brand-wrapper">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
                    @if(!empty($store_logo))
                        <img src="{{ $store_logo }}" alt="{{ $store_name ?? 'Pistis' }}" class="brand-logo-img" style="max-height: 26px; width: auto; object-fit: contain; display: block; border-radius: 50%; aspect-ratio: 1/1;">
                    @else
                        <span class="brand-dot">P</span>
                    @endif
                    <span class="brand-text">Pistis Admin</span>
                </a>
                <button type="button" class="sidebar-toggle-btn" id="sidebar-toggle-btn" title="Toggle Sidebar (Collapse/Expand)" onclick="toggleAdminSidebar()">
                    <span class="toggle-icon">◀</span>
                </button>
            </div>

            <ul class="sidebar-menu">
                <li class="sidebar-section"><span class="section-label">Main</span></li>
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" title="Dashboard">
                        <span class="icon">📊</span> <span class="menu-label">Dashboard</span>
                    </a>
                </li>

                <li class="sidebar-section"><span class="section-label">Catalog & Content</span></li>
                <li>
                    <a href="{{ route('admin.hero-slides.index') }}" class="{{ request()->routeIs('admin.hero-slides.*') ? 'active' : '' }}" title="Hero Slider">
                        <span class="icon">🖼️</span> <span class="menu-label">Hero Slider</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.campaign-video.index') }}" class="{{ request()->routeIs('admin.campaign-video.*') ? 'active' : '' }}" title="Campaign Video">
                        <span class="icon">🎬</span> <span class="menu-label">Campaign Video</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}" title="Products">
                        <span class="icon">📦</span> <span class="menu-label">Products</span>
                        <span class="sidebar-badge black-badge" title="{{ $sidebarProductsCount ?? 0 }} Products">{{ $sidebarProductsCount ?? 0 }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" title="Categories">
                        <span class="icon">🏷️</span> <span class="menu-label">Categories</span>
                        <span class="sidebar-badge black-badge" title="{{ $sidebarCategoriesCount ?? 0 }} Categories">{{ $sidebarCategoriesCount ?? 0 }}</span>
                    </a>
                </li>

                <li class="sidebar-section"><span class="section-label">Sales</span></li>
                <li>
                    <a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}" title="Orders">
                        <span class="icon">🛍️</span>
                        <span class="menu-label">Orders</span>
                        @if(($pendingOrdersCount ?? 0) > 0)
                            <span class="sidebar-badge black-badge has-pending" title="{{ $pendingOrdersCount }} Pending / Processing Orders">{{ $pendingOrdersCount }}</span>
                        @elseif(($totalOrdersCount ?? 0) > 0)
                            <span class="sidebar-badge black-badge" title="{{ $totalOrdersCount }} Total Orders">{{ $totalOrdersCount }}</span>
                        @endif
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.customers.index') }}" class="{{ request()->routeIs('admin.customers.*') ? 'active' : '' }}" title="Customers">
                        <span class="icon">👥</span> <span class="menu-label">Customers</span>
                        <span class="sidebar-badge black-badge" title="{{ $sidebarCustomersCount ?? 0 }} Customers">{{ $sidebarCustomersCount ?? 0 }}</span>
                    </a>
                </li>

                <li class="sidebar-section"><span class="section-label">Shopify</span></li>
                <li>
                    <a href="{{ route('admin.shopify.logs') }}" class="{{ request()->routeIs('admin.shopify.*') ? 'active' : '' }}" title="Sync & Logs">
                        <span class="icon">🔄</span> <span class="menu-label">Sync & Logs</span>
                    </a>
                </li>

                <li class="sidebar-section"><span class="section-label">System</span></li>
                <li>
                    <a href="{{ route('admin.settings.index') }}" class="{{ request()->routeIs('admin.settings.*') ? 'active' : '' }}" title="Settings">
                        <span class="icon">⚙️</span> <span class="menu-label">Settings</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('home') }}" target="_blank" title="View Store">
                        <span class="icon">🌐</span> <span class="menu-label">View Store</span>
                    </a>
                </li>
                <li>
                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <a href="#" onclick="this.closest('form').submit(); return false;" title="Logout">
                            <span class="icon">🚪</span> <span class="menu-label">Logout</span>
                        </a>
                    </form>
                </li>
            </ul>
        </aside>

        {{-- Main Content --}}
        <main class="admin-main" id="admin-main">
            {{-- Luxury Top Navigation Bar --}}
            <header class="admin-topbar">
                <div class="topbar-left">
                    <button type="button" class="admin-mobile-toggle" onclick="toggleMobileSidebar()" aria-label="Open sidebar menu">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
                        <span>Menu</span>
                    </button>
                    <div class="topbar-brand-indicator">
                        <span class="store-dot"></span>
                        <span class="store-name">{{ $store_name ?? 'Pistis' }}</span>
                        <span class="store-divider">/</span>
                        <span class="topbar-page-label">Admin Console</span>
                    </div>
                </div>

                <div class="topbar-right">
                    {{-- Quick Store Link --}}
                    <a href="{{ route('home') }}" target="_blank" class="topbar-btn topbar-view-store" title="View live storefront">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                        <span>Live Store</span>
                    </a>

                    {{-- Bell Notification Dropdown --}}
                    <div class="topbar-dropdown" id="topbar-notifications-dropdown">
                        <button type="button" class="topbar-bell-btn {{ (($pendingOrdersCount ?? 0) > 0 || ($unviewedOrdersCount ?? 0) > 0) ? 'has-notifications' : '' }}" id="notifications-toggle-btn" onclick="toggleNotificationsMenu(event)" title="Notifications" aria-expanded="false">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                                <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                            </svg>
                            @if(($pendingOrdersCount ?? 0) > 0)
                                <span class="bell-badge-count">{{ $pendingOrdersCount }}</span>
                            @elseif(($unviewedOrdersCount ?? 0) > 0)
                                <span class="bell-badge-count">{{ $unviewedOrdersCount }}</span>
                            @endif
                        </button>

                        <div class="notifications-menu" id="notifications-menu">
                            <div class="notifications-header">
                                <div class="notifications-title">
                                    <span>Order Notifications</span>
                                    @if(($pendingOrdersCount ?? 0) > 0)
                                        <span class="badge badge-warning" style="font-size:0.68rem;padding:3px 8px;border-radius:10px;">{{ $pendingOrdersCount }} Pending</span>
                                    @endif
                                </div>
                                <span class="notifications-sub">Recent customer orders</span>
                            </div>

                            <div class="notifications-list">
                                @forelse($recentNotifications ?? [] as $notifOrder)
                                    <a href="{{ route('admin.orders.show', $notifOrder) }}" class="notification-item {{ $notifOrder->isUnviewedByAdmin() ? 'is-unread' : '' }}">
                                        <div class="notif-icon-box">
                                            <span class="notif-icon">🛍️</span>
                                        </div>
                                        <div class="notif-content">
                                            <div class="notif-top-row">
                                                <span class="notif-order-num">{{ $notifOrder->order_number }}</span>
                                                <span class="badge badge-{{ $notifOrder->status_badge }}">{{ ucfirst($notifOrder->status) }}</span>
                                            </div>
                                            <div class="notif-customer">
                                                {{ $notifOrder->customer_name }}
                                                <span class="notif-amount">• {{ $currency_symbol }}{{ number_format($notifOrder->total, 2) }}</span>
                                            </div>
                                            <div class="notif-time">{{ $notifOrder->created_at->diffForHumans() }}</div>
                                        </div>
                                        @if($notifOrder->isUnviewedByAdmin())
                                            <span class="notif-unread-dot" title="New unviewed order"></span>
                                        @endif
                                    </a>
                                @empty
                                    <div class="notifications-empty">
                                        <div style="font-size:1.4rem;margin-bottom:6px;">✨</div>
                                        <div>No new orders yet</div>
                                        <span style="font-size:0.75rem;color:#737373;">Confirmed orders will appear here automatically.</span>
                                    </div>
                                @endforelse
                            </div>

                            <div class="notifications-footer">
                                <a href="{{ route('admin.orders.index') }}" class="view-all-orders-link">
                                    View All Orders ({{ $totalOrdersCount ?? 0 }}) →
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Admin Profile Pill --}}
                    <div class="topbar-user">
                        <div class="topbar-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</div>
                        <span class="topbar-username">{{ auth()->user()->name ?? 'Admin' }}</span>
                    </div>
                </div>
            </header>

            @if(session('success'))
                <div class="alert alert-success">✓ {{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">✕ {{ session('error') }}</div>
            @endif

            @yield('content')
        </main>
    </div>

    <script>
        // Sidebar Collapse/Expand Persistence
        function initSidebarState() {
            const isCollapsed = localStorage.getItem('pistis_admin_sidebar_collapsed') === 'true';
            if (isCollapsed) {
                document.body.classList.add('sidebar-collapsed');
                const icon = document.querySelector('.toggle-icon');
                if (icon) icon.textContent = '▶';
            }
        }

        function toggleAdminSidebar() {
            const isCollapsed = document.body.classList.toggle('sidebar-collapsed');
            localStorage.setItem('pistis_admin_sidebar_collapsed', isCollapsed ? 'true' : 'false');
            const icon = document.querySelector('.toggle-icon');
            if (icon) {
                icon.textContent = isCollapsed ? '▶' : '◀';
            }
        }

        function toggleMobileSidebar() {
            const sidebar = document.getElementById('admin-sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            if (sidebar) sidebar.classList.toggle('mobile-open');
            if (backdrop) backdrop.classList.toggle('active');
        }

        // Topbar Notifications Dropdown
        function toggleNotificationsMenu(event) {
            if (event) event.stopPropagation();
            const menu = document.getElementById('notifications-menu');
            const btn = document.getElementById('notifications-toggle-btn');
            if (menu) {
                const isOpen = menu.classList.toggle('open');
                if (btn) {
                    btn.classList.toggle('active', isOpen);
                    btn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                }
            }
        }

        // Close notifications dropdown when clicking outside
        window.addEventListener('click', function(e) {
            const menu = document.getElementById('notifications-menu');
            const btn = document.getElementById('notifications-toggle-btn');
            if (menu && menu.classList.contains('open')) {
                if (!menu.contains(e.target) && !btn.contains(e.target)) {
                    menu.classList.remove('open');
                    if (btn) {
                        btn.classList.remove('active');
                        btn.setAttribute('aria-expanded', 'false');
                    }
                }
            }
        });

        initSidebarState();
    </script>
    @stack('scripts')
</body>
</html>



