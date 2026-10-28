<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\Setting;
use App\Models\ShopifySyncLog;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_products' => Product::count(),
            'active_products' => Product::active()->count(),
            'total_orders' => Order::count(),
            'pending_orders' => Order::whereIn('status', ['pending', 'processing'])->active()->count(),
            'delivered_orders' => Order::where('status', 'delivered')->count(),
            'total_customers' => Customer::count(),
            'total_revenue' => Order::where('payment_status', 'paid')->sum('total'),
            'synced_products' => Product::whereNotNull('shopify_product_id')->count(),
            'failed_syncs' => ShopifySyncLog::where('status', 'failed')
                ->where('created_at', '>=', now()->subDays(7))->count(),
        ];

        $recentOrders = Order::with('customer')
            ->latest()
            ->take(10)
            ->get();

        $recentSyncLogs = ShopifySyncLog::with('product')
            ->latest()
            ->take(10)
            ->get();

        $socialInfo = [
            'store_name' => Setting::get('store_name', 'Pistis'),
            'primary_handle' => Setting::get('social_primary_handle', '@wearpistis'),
            'tagline' => Setting::get('social_floating_tagline', 'Official Atelier & Runway Archive'),
            'instagram' => Setting::get('social_instagram', ''),
            'tiktok' => Setting::get('social_tiktok', ''),
            'whatsapp' => Setting::get('social_whatsapp', ''),
            'connect_url' => route('social.connect'),
        ];

        return view('admin.dashboard', compact('stats', 'recentOrders', 'recentSyncLogs', 'socialInfo'));
    }
}
