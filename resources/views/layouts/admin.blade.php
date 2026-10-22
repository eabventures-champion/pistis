<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — Pistis Admin</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ file_exists(public_path('css/app.css')) ? filemtime(public_path('css/app.css')) : time() }}">
    <style>
        /* ─── Admin Top Navigation Bar ────────────────────────────────────────── */
        .admin-topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 56px;
            padding: 0 32px;
            margin: 0 -32px 20px -32px;
            background: #ffffff;
            border-bottom: 1px solid #e5e5e5;
            position: sticky;
            top: 0;
            z-index: 95;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.02);
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .admin-mobile-toggle {
            display: none;
            align-items: center;
            gap: 6px;
            background: #000000;
            color: #ffffff;
            border: none;
            padding: 7px 12px;
            border-radius: 4px;
            font-size: 0.78rem;
            font-weight: 600;
            cursor: pointer;
        }

        .topbar-brand-indicator {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.85rem;
            color: #475569;
        }

        .topbar-brand-indicator .store-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #22c55e;
            box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.15);
        }

        .topbar-brand-indicator .store-name {
            font-weight: 700;
            color: #0f172a;
            letter-spacing: 0.02em;
        }

        .topbar-brand-indicator .store-divider {
            color: #cbd5e1;
        }

        .topbar-page-label {
            color: #64748b;
            font-size: 0.8rem;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .topbar-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.78rem;
            font-weight: 500;
            color: #475569;
            padding: 6px 12px;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            text-decoration: none;
            transition: all 0.15s ease;
        }

        .topbar-btn:hover {
            color: #0f172a;
            background: #f1f5f9;
            border-color: #cbd5e1;
        }

        /* Bell Notification Container */
        .topbar-dropdown {
            position: relative;
        }

        .topbar-bell-btn {
            position: relative;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            border: 1px solid #e2e8f0;
            background: #ffffff;
            color: #334155;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.15s ease;
            padding: 0;
        }

        .topbar-bell-btn:hover,
        .topbar-bell-btn.active {
            background: #f8fafc;
            color: #0f172a;
            border-color: #cbd5e1;
        }

        .bell-badge-count {
            position: absolute;
            top: -4px;
            right: -4px;
            min-width: 18px;
            height: 18px;
            padding: 0 4px;
            border-radius: 9px;
            background: #ef4444;
            color: #ffffff;
            font-size: 0.65rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #ffffff;
            box-shadow: 0 2px 4px rgba(239, 68, 68, 0.35);
            animation: bellPulse 2s infinite;
        }

        @keyframes bellPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.12); }
        }

        /* Dropdown Menu */
        .notifications-menu {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            width: 380px;
            max-width: calc(100vw - 32px);
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.12), 0 8px 10px -6px rgba(0, 0, 0, 0.08);
            z-index: 120;
            display: none;
            flex-direction: column;
            overflow: hidden;
        }

        .notifications-menu.open {
            display: flex;
            animation: fadeInDown 0.15s ease-out;
        }

        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .notifications-header {
            padding: 14px 18px;
            border-bottom: 1px solid #f1f5f9;
            background: #f8fafc;
        }

        .notifications-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.9rem;
            font-weight: 700;
            color: #0f172a;
        }

        .notifications-sub {
            font-size: 0.75rem;
            color: #64748b;
            display: block;
            margin-top: 2px;
        }

        .notifications-list {
            max-height: 380px;
            overflow-y: auto;
        }

        .notification-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 12px 18px;
            border-bottom: 1px solid #f1f5f9;
            text-decoration: none;
            color: inherit;
            transition: background 0.15s ease;
            position: relative;
        }

        .notification-item:hover {
            background: #f8fafc;
        }

        .notification-item.is-unread {
            background: #f0fdf4;
        }

        .notification-item.is-unread:hover {
            background: #e6f9ed;
        }

        .notif-icon-box {
            width: 34px;
            height: 34px;
            border-radius: 6px;
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .notif-content {
            flex: 1;
            min-width: 0;
        }

        .notif-top-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 6px;
            margin-bottom: 2px;
        }

        .notif-order-num {
            font-weight: 700;
            font-size: 0.82rem;
            color: #0f172a;
        }

        .notif-customer {
            font-size: 0.78rem;
            color: #334155;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .notif-amount {
            font-weight: 600;
            color: #0f172a;
        }

        .notif-time {
            font-size: 0.7rem;
            color: #94a3b8;
            margin-top: 4px;
        }

        .notif-unread-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #22c55e;
            position: absolute;
            top: 16px;
            right: 14px;
        }

        .notifications-empty {
            padding: 32px 20px;
            text-align: center;
            color: #64748b;
            font-size: 0.85rem;
        }

        .notifications-footer {
            padding: 10px 18px;
            background: #f8fafc;
            border-top: 1px solid #f1f5f9;
            text-align: center;
        }

        .view-all-orders-link {
            font-size: 0.8rem;
            font-weight: 600;
            color: #0f172a;
            text-decoration: none;
            display: block;
        }

        .view-all-orders-link:hover {
            text-decoration: underline;
        }

        .topbar-user {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 3px 10px 3px 3px;
            border-radius: 20px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
        }

        .topbar-avatar {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: #0f172a;
            color: #ffffff;
            font-size: 0.72rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .topbar-username {
            font-size: 0.8rem;
            font-weight: 600;
            color: #0f172a;
        }

        .topbar-user-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            background: none;
            border: 1px solid transparent;
            cursor: pointer;
            padding: 4px 8px;
            border-radius: 8px;
            transition: all 0.15s ease;
        }
        .topbar-user-btn:hover,
        .topbar-user-btn.active {
            background: #f8fafc;
            border-color: #e2e8f0;
        }

        .profile-menu {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            width: 260px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.12), 0 8px 10px -6px rgba(0, 0, 0, 0.08);
            z-index: 120;
            display: none;
            flex-direction: column;
            overflow: hidden;
        }
        .profile-menu.open {
            display: flex;
            animation: fadeInDown 0.15s ease-out;
        }
        .profile-menu-header {
            padding: 14px 16px;
            background: #f8fafc;
            border-bottom: 1px solid #f1f5f9;
        }
        .profile-menu-body {
            padding: 6px;
        }
        .profile-menu-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 12px;
            border-radius: 6px;
            color: #1e293b;
            text-decoration: none;
            font-size: 0.82rem;
            font-weight: 500;
            transition: all 0.15s ease;
        }
        .profile-menu-item:hover {
            background: #f1f5f9;
            color: #0f172a;
        }
        .profile-menu-logout {
            color: #dc2626;
        }
        .profile-menu-logout:hover {
            background: #fef2f2;
            color: #b91c1c;
        }

        @media (max-width: 900px) {
            .admin-topbar {
                margin: 0 -16px 16px -16px;
                padding: 0 16px;
            }
            .admin-mobile-toggle {
                display: inline-flex !important;
            }
            .topbar-brand-indicator .store-divider,
            .topbar-brand-indicator .topbar-page-label,
            .topbar-username {
                display: none;
            }
            .topbar-view-store span {
                display: none;
            }
            .notifications-menu,
            .profile-menu {
                position: fixed;
                top: 60px;
                right: 12px;
                left: 12px;
                width: auto;
                max-width: none;
            }
        }
    </style>
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

                @if(auth()->user()->hasPermission('hero_slides') || auth()->user()->hasPermission('campaign_video') || auth()->user()->hasPermission('social_media') || auth()->user()->hasPermission('products') || auth()->user()->hasPermission('categories') || auth()->user()->hasPermission('size_guides'))
                    <li class="sidebar-section"><span class="section-label">Catalog & Content</span></li>
                    @if(auth()->user()->hasPermission('hero_slides'))
                        <li>
                            <a href="{{ route('admin.hero-slides.index') }}" class="{{ request()->routeIs('admin.hero-slides.*') ? 'active' : '' }}" title="Hero Slider">
                                <span class="icon">🖼️</span> <span class="menu-label">Hero Slider</span>
                            </a>
                        </li>
                    @endif
                    @if(auth()->user()->hasPermission('campaign_video'))
                        <li>
                            <a href="{{ route('admin.campaign-video.index') }}" class="{{ request()->routeIs('admin.campaign-video.*') ? 'active' : '' }}" title="Campaign Video">
                                <span class="icon">🎬</span> <span class="menu-label">Campaign Video</span>
                            </a>
                        </li>
                    @endif
                    @if(auth()->user()->hasPermission('social_media'))
                        <li>
                            <a href="{{ route('admin.settings.index') }}#social-settings" title="Floating Social Media & Handles">
                                <span class="icon">📱</span> <span class="menu-label">Social Media</span>
                                <span class="sidebar-badge" style="background:#0f172a;color:#ffffff;font-size:0.6rem;padding:2px 6px;border-radius:10px;font-weight:700;letter-spacing:0.04em;">FLOATING</span>
                            </a>
                        </li>
                    @endif
                    @if(auth()->user()->hasPermission('products'))
                        <li>
                            <a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}" title="Products">
                                <span class="icon">📦</span> <span class="menu-label">Products</span>
                                <span class="sidebar-badge black-badge" title="{{ $sidebarProductsCount ?? 0 }} Products">{{ $sidebarProductsCount ?? 0 }}</span>
                            </a>
                        </li>
                    @endif
                    @if(auth()->user()->hasPermission('categories'))
                        <li>
                            <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" title="Categories">
                                <span class="icon">🏷️</span> <span class="menu-label">Categories</span>
                                <span class="sidebar-badge black-badge" title="{{ $sidebarCategoriesCount ?? 0 }} Categories">{{ $sidebarCategoriesCount ?? 0 }}</span>
                            </a>
                        </li>
                    @endif
                    @if(auth()->user()->hasPermission('size_guides'))
                        <li>
                            <a href="{{ route('admin.size-guides.index') }}" class="{{ request()->routeIs('admin.size-guides.*') ? 'active' : '' }}" title="Size Guides">
                                <span class="icon">📏</span> <span class="menu-label">Size Guides</span>
                                <span class="sidebar-badge black-badge" title="{{ $sidebarSizeGuidesCount ?? 0 }} Size Guides">{{ $sidebarSizeGuidesCount ?? 0 }}</span>
                            </a>
                        </li>
                    @endif
                @endif

                @if(auth()->user()->hasPermission('orders') || auth()->user()->hasPermission('customers') || auth()->user()->hasPermission('subscribers'))
                    <li class="sidebar-section"><span class="section-label">Sales</span></li>
                    @if(auth()->user()->hasPermission('orders'))
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
                    @endif
                    @if(auth()->user()->hasPermission('customers'))
                        <li>
                            <a href="{{ route('admin.customers.index') }}" class="{{ request()->routeIs('admin.customers.*') ? 'active' : '' }}" title="Customers">
                                <span class="icon">👥</span> <span class="menu-label">Customers</span>
                                <span class="sidebar-badge black-badge" title="{{ $sidebarCustomersCount ?? 0 }} Customers">{{ $sidebarCustomersCount ?? 0 }}</span>
                            </a>
                        </li>
                    @endif
                    @if(auth()->user()->hasPermission('subscribers'))
                        <li>
                            <a href="{{ route('admin.subscribers.index') }}" class="{{ request()->routeIs('admin.subscribers.*') ? 'active' : '' }}" title="Inner Circle Subscribers">
                                <span class="icon">✉️</span> <span class="menu-label">Inner Circle</span>
                                <span class="sidebar-badge black-badge" title="{{ $sidebarSubscribersCount ?? 0 }} Subscribers">{{ $sidebarSubscribersCount ?? 0 }}</span>
                            </a>
                        </li>
                    @endif
                @endif

                @if(auth()->user()->hasPermission('shopify'))
                    <li class="sidebar-section"><span class="section-label">Shopify</span></li>
                    <li>
                        <a href="{{ route('admin.shopify.logs') }}" class="{{ request()->routeIs('admin.shopify.*') ? 'active' : '' }}" title="Sync & Logs">
                            <span class="icon">🔄</span> <span class="menu-label">Sync & Logs</span>
                        </a>
                    </li>
                @endif

                <li class="sidebar-section"><span class="section-label">System</span></li>
                <li>
                    <a href="{{ route('admin.profile.index') }}" class="{{ request()->routeIs('admin.profile.*') ? 'active' : '' }}" title="My Profile & Security">
                        <span class="icon">👤</span> <span class="menu-label">My Profile</span>
                    </a>
                </li>
                @if(auth()->user()->isSuperAdmin())
                    <li>
                        <a href="{{ route('admin.team.index') }}" class="{{ request()->routeIs('admin.team.*') ? 'active' : '' }}" title="Admin Team & Roles">
                            <span class="icon">👥</span> <span class="menu-label">Team & Roles</span>
                            <span class="sidebar-badge" style="background:#09090b;color:#ffffff;font-size:0.6rem;padding:2px 6px;border-radius:10px;font-weight:700;">SUPER</span>
                        </a>
                    </li>
                @endif
                @if(auth()->user()->hasPermission('settings'))
                    <li>
                        <a href="{{ route('admin.settings.index') }}" class="{{ request()->routeIs('admin.settings.*') ? 'active' : '' }}" title="Settings">
                            <span class="icon">⚙️</span> <span class="menu-label">Settings</span>
                        </a>
                    </li>
                @endif
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
                        <button type="button" class="topbar-bell-btn {{ ($unviewedOrdersCount ?? 0) > 0 ? 'has-notifications' : '' }}" id="notifications-toggle-btn" onclick="toggleNotificationsMenu(event)" title="Notifications" aria-expanded="false">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                                <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                            </svg>
                            @if(($unviewedOrdersCount ?? 0) > 0)
                                <span class="bell-badge-count" id="bell-badge-count">{{ $unviewedOrdersCount }}</span>
                            @endif
                        </button>

                        <div class="notifications-menu" id="notifications-menu">
                            <div class="notifications-header">
                                <div class="notifications-title">
                                    <span>Order Notifications</span>
                                    @if(($unviewedOrdersCount ?? 0) > 0)
                                        <span class="badge badge-warning" id="notif-header-badge" style="font-size:0.68rem;padding:3px 8px;border-radius:10px;">{{ $unviewedOrdersCount }} New</span>
                                    @else
                                        <span class="badge" id="notif-header-badge" style="background:#e5e7eb;color:#475569;font-size:0.65rem;padding:2px 7px;border-radius:10px;">All caught up</span>
                                    @endif
                                </div>
                                <div style="display:flex;align-items:center;justify-content:space-between;margin-top:4px;">
                                    <span class="notifications-sub" style="margin:0;">Recent customer orders</span>
                                    @if(($unviewedOrdersCount ?? 0) > 0)
                                        <button type="button" id="mark-all-read-btn" onclick="markAllNotificationsRead(event)" style="background:none;border:none;padding:0;color:#2563eb;font-size:0.72rem;cursor:pointer;font-weight:600;text-decoration:underline;">
                                            Mark all read
                                        </button>
                                    @endif
                                </div>
                            </div>

                            <div class="notifications-list">
                                @forelse($recentNotifications ?? [] as $notifOrder)
                                    <a href="{{ route('admin.orders.show', $notifOrder) }}" class="notification-item {{ $notifOrder->isUnviewedByAdmin() ? 'is-unread' : '' }}">
                                        @php
                                            $firstItem = $notifOrder->items->first();
                                            $notifThumb = $firstItem?->image_url;
                                        @endphp
                                        <div class="notif-icon-box" style="overflow:hidden;padding:0;background:#f5f5f5;border:1px solid #e5e5e5;width:38px;height:46px;border-radius:4px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                            @if($notifThumb)
                                                <img src="{{ $notifThumb }}" alt="" style="width:100%;height:100%;object-fit:cover;display:block;">
                                            @else
                                                <span class="notif-icon" style="font-size:1.1rem;">🛍️</span>
                                            @endif
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

                    {{-- Admin Profile Menu Dropdown --}}
                    <div class="topbar-dropdown" id="topbar-profile-dropdown">
                        <button type="button" class="topbar-user-btn" id="profile-toggle-btn" onclick="toggleProfileMenu(event)" title="Account & Security" aria-expanded="false">
                            <div class="topbar-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</div>
                            <div style="display:flex;flex-direction:column;align-items:flex-start;line-height:1.2;">
                                <span class="topbar-username" style="font-weight:700;">{{ auth()->user()->name ?? 'Admin' }}</span>
                                <span style="font-size:0.65rem;color:#71717a;letter-spacing:0.04em;">{{ auth()->user()->role_title ?? 'Admin' }}</span>
                            </div>
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#71717a" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-left:2px;">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </button>

                        <div class="profile-menu" id="profile-menu">
                            <div class="profile-menu-header">
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <div class="topbar-avatar" style="width:36px;height:36px;font-size:0.9rem;">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</div>
                                    <div style="min-width:0;flex:1;">
                                        <div style="font-weight:700;font-size:0.85rem;color:#0f172a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ auth()->user()->name }}</div>
                                        <div style="font-size:0.75rem;color:#64748b;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ auth()->user()->email }}</div>
                                    </div>
                                </div>
                                <div style="margin-top:8px;">
                                    <span style="display:inline-block;font-size:0.65rem;font-weight:700;letter-spacing:0.05em;padding:2px 8px;border-radius:12px;background:#09090b;color:#ffffff;">
                                        {{ strtoupper(auth()->user()->role_title ?? 'ADMIN') }}
                                    </span>
                                </div>
                            </div>

                            <div class="profile-menu-body">
                                <a href="{{ route('admin.profile.index') }}" class="profile-menu-item">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                    <span>My Profile</span>
                                </a>

                                <a href="{{ route('admin.profile.index') }}#password-section" class="profile-menu-item">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                                    <span>Change Password</span>
                                </a>

                                @if(auth()->user()->isSuperAdmin())
                                    <a href="{{ route('admin.team.index') }}" class="profile-menu-item">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                        <span>Team & Roles</span>
                                    </a>
                                @endif

                                @if(auth()->user()->hasPermission('settings'))
                                    <a href="{{ route('admin.settings.index') }}" class="profile-menu-item">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                                        <span>Store Settings</span>
                                    </a>
                                @endif

                                <div style="height:1px;background:#f1f5f9;margin:4px 0;"></div>

                                <form action="{{ route('admin.logout') }}" method="POST" style="margin:0;">
                                    @csrf
                                    <button type="submit" class="profile-menu-item profile-menu-logout" style="width:100%;background:none;border:none;cursor:pointer;text-align:left;">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                                        <span>Sign Out</span>
                                    </button>
                                </form>
                            </div>
                        </div>
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

        // Topbar Profile Dropdown
        function toggleProfileMenu(event) {
            if (event) event.stopPropagation();
            const menu = document.getElementById('profile-menu');
            const btn = document.getElementById('profile-toggle-btn');
            // Close notifications menu if open
            const notifMenu = document.getElementById('notifications-menu');
            const notifBtn = document.getElementById('notifications-toggle-btn');
            if (notifMenu && notifMenu.classList.contains('open')) {
                notifMenu.classList.remove('open');
                if (notifBtn) {
                    notifBtn.classList.remove('active');
                    notifBtn.setAttribute('aria-expanded', 'false');
                }
            }

            if (menu) {
                const isOpen = menu.classList.toggle('open');
                if (btn) {
                    btn.classList.toggle('active', isOpen);
                    btn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                }
            }
        }

        // Topbar Notifications Dropdown
        function toggleNotificationsMenu(event) {
            if (event) event.stopPropagation();
            const menu = document.getElementById('notifications-menu');
            const btn = document.getElementById('notifications-toggle-btn');
            // Close profile menu if open
            const profMenu = document.getElementById('profile-menu');
            const profBtn = document.getElementById('profile-toggle-btn');
            if (profMenu && profMenu.classList.contains('open')) {
                profMenu.classList.remove('open');
                if (profBtn) {
                    profBtn.classList.remove('active');
                    profBtn.setAttribute('aria-expanded', 'false');
                }
            }

            if (menu) {
                const isOpen = menu.classList.toggle('open');
                if (btn) {
                    btn.classList.toggle('active', isOpen);
                    btn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                }
            }
        }

        // Close dropdowns when clicking outside
        window.addEventListener('click', function(e) {
            const notifMenu = document.getElementById('notifications-menu');
            const notifBtn = document.getElementById('notifications-toggle-btn');
            if (notifMenu && notifMenu.classList.contains('open')) {
                if (!notifMenu.contains(e.target) && !notifBtn.contains(e.target)) {
                    notifMenu.classList.remove('open');
                    if (notifBtn) {
                        notifBtn.classList.remove('active');
                        notifBtn.setAttribute('aria-expanded', 'false');
                    }
                }
            }

            const profMenu = document.getElementById('profile-menu');
            const profBtn = document.getElementById('profile-toggle-btn');
            if (profMenu && profMenu.classList.contains('open')) {
                if (!profMenu.contains(e.target) && !profBtn.contains(e.target)) {
                    profMenu.classList.remove('open');
                    if (profBtn) {
                        profBtn.classList.remove('active');
                        profBtn.setAttribute('aria-expanded', 'false');
                    }
                }
            }
        });

        // Mark all notifications as read
        function markAllNotificationsRead(event) {
            if (event) event.stopPropagation();
            fetch('{{ route('admin.orders.mark-all-read') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                }
            }).then(res => res.json()).then(data => {
                if (data.success) {
                    const bellBadge = document.getElementById('bell-badge-count');
                    if (bellBadge) bellBadge.remove();
                    const bellBtn = document.getElementById('notifications-toggle-btn');
                    if (bellBtn) bellBtn.classList.remove('has-notifications');
                    const headerBadge = document.getElementById('notif-header-badge');
                    if (headerBadge) {
                        headerBadge.className = 'badge';
                        headerBadge.style = 'background:#e5e7eb;color:#475569;font-size:0.65rem;padding:2px 7px;border-radius:10px;';
                        headerBadge.textContent = 'All caught up';
                    }
                    const markBtn = document.getElementById('mark-all-read-btn');
                    if (markBtn) markBtn.remove();
                    document.querySelectorAll('.notif-unread-dot').forEach(el => el.remove());
                    document.querySelectorAll('.notification-item.is-unread').forEach(el => el.classList.remove('is-unread'));
                }
            }).catch(err => console.error(err));
        }

        initSidebarState();
    </script>
    @stack('scripts')
</body>
</html>



