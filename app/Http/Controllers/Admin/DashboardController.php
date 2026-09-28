<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\ShopifySyncLog;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_products' => Product::count(),
            'active_products' => Product::active()->count(),
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
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

        return view('admin.dashboard', compact('stats', 'recentOrders', 'recentSyncLogs'));
    }
}
