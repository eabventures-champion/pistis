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
                        <img src="{{ $store_logo }}" alt="{{ $store_name ?? 'Pistis' }}" style="max-height: 26px; width: auto; object-fit: contain; display: block;">
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
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" title="Categories">
                        <span class="icon">🏷️</span> <span class="menu-label">Categories</span>
                    </a>
                </li>

                <li class="sidebar-section"><span class="section-label">Sales</span></li>
                <li>
                    <a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}" title="Orders">
                        <span class="icon">🛍️</span> <span class="menu-label">Orders</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.customers.index') }}" class="{{ request()->routeIs('admin.customers.*') ? 'active' : '' }}" title="Customers">
                        <span class="icon">👥</span> <span class="menu-label">Customers</span>
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
            {{-- Mobile Navbar Trigger Bar --}}
            <div class="admin-mobile-bar">
                <button type="button" class="admin-mobile-toggle" onclick="toggleMobileSidebar()">
                    ☰ <span>Menu</span>
                </button>
                <div class="mobile-bar-brand">Pistis Admin</div>
            </div>

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

        initSidebarState();
    </script>
    @stack('scripts')
</body>
</html>



