<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        try {
            $symbol = \App\Models\Setting::get('currency_symbol', '$');
            \Illuminate\Support\Facades\View::share('currency_symbol', $symbol);

                $videoPath = \App\Models\Setting::get('campaign_video_path');
                $videoUrl = $videoPath ? asset('storage/' . $videoPath) : \App\Models\Setting::get('campaign_video_url');

                $campaignVideo = [
                    'enabled' => (bool) \App\Models\Setting::get('campaign_video_enabled', false),
                    'video_url' => $videoUrl,
                    'delay' => (int) \App\Models\Setting::get('campaign_video_delay', 5),
                    'badge' => \App\Models\Setting::get('campaign_video_badge', 'PISTIS EDITORIAL · RUNWAY PREMIERE'),
                    'title' => \App\Models\Setting::get('campaign_video_title', 'THE NEW LOOKBOOK IN MOTION'),
                    'description' => \App\Models\Setting::get('campaign_video_description', 'Discover the drape, texture, and refined silhouettes of our latest luxury clothing collection.'),
                    'cta_text' => \App\Models\Setting::get('campaign_video_cta_text', 'SHOP THE COLLECTION'),
                    'cta_url' => \App\Models\Setting::get('campaign_video_cta_url', '/shop'),
                    'target_page' => \App\Models\Setting::get('campaign_video_target_page', 'homepage'),
                    'frequency' => \App\Models\Setting::get('campaign_video_frequency', 'once_per_session'),
                ];
                \Illuminate\Support\Facades\View::share('campaignVideo', $campaignVideo);

                $storeName = \App\Models\Setting::get('store_name', 'Pistis');
                \Illuminate\Support\Facades\View::share('store_name', $storeName);

                $logoPath = \App\Models\Setting::get('store_logo_path');
                if ($logoPath) {
                    $fullPath = storage_path('app/public/' . $logoPath);
                    $v = file_exists($fullPath) ? filemtime($fullPath) : time();
                    $logoUrl = asset('storage/' . $logoPath) . '?v=' . $v;
                } else {
                    $logoUrl = \App\Models\Setting::get('store_logo_url');
                }
                \Illuminate\Support\Facades\View::share('store_logo', $logoUrl);
                \Illuminate\Support\Facades\View::share('store_logo_height', (int) \App\Models\Setting::get('store_logo_height', 32));
                $storeHideBrandText = (bool) \App\Models\Setting::get('store_hide_brand_text', false);
                \Illuminate\Support\Facades\View::share('store_hide_brand_text', $storeHideBrandText);
                \Illuminate\Support\Facades\View::share('homepage_show_categories', (bool) \App\Models\Setting::get('homepage_show_categories', '1'));
                \Illuminate\Support\Facades\View::share('homepage_show_editorial_categories', (bool) \App\Models\Setting::get('homepage_show_editorial_categories', '1'));

                // Share admin notification stats & counts across all admin views
                \Illuminate\Support\Facades\View::composer(['layouts.admin', 'admin.*'], function ($view) {
                    try {
                        $pendingOrdersCount = \App\Models\Order::whereIn('status', ['pending', 'processing'])->active()->count();
                        $totalOrdersCount = \App\Models\Order::count();
                        $sidebarProductsCount = \App\Models\Product::count();
                        $sidebarCategoriesCount = \App\Models\Category::count();
                        $sidebarCustomersCount = \App\Models\Customer::count();
                        $sidebarSizeGuidesCount = \App\Models\SizeGuide::count();
                        $recentNotifications = \App\Models\Order::with(['customer', 'items.product'])
                            ->latest()
                            ->take(6)
                            ->get();
                        $unviewedOrdersCount = \App\Models\Order::whereNull('admin_viewed_at')->count();

                        $view->with([
                            'pendingOrdersCount' => $pendingOrdersCount,
                            'totalOrdersCount' => $totalOrdersCount,
                            'sidebarProductsCount' => $sidebarProductsCount,
                            'sidebarCategoriesCount' => $sidebarCategoriesCount,
                            'sidebarCustomersCount' => $sidebarCustomersCount,
                            'sidebarSizeGuidesCount' => $sidebarSizeGuidesCount,
                            'recentNotifications' => $recentNotifications,
                            'unviewedOrdersCount' => $unviewedOrdersCount,
                        ]);
                    } catch (\Throwable $e) {
                        $view->with([
                            'pendingOrdersCount' => 0,
                            'totalOrdersCount' => 0,
                            'sidebarProductsCount' => 0,
                            'sidebarCategoriesCount' => 0,
                            'sidebarCustomersCount' => 0,
                            'sidebarSizeGuidesCount' => 0,
                            'recentNotifications' => collect(),
                            'unviewedOrdersCount' => 0,
                        ]);
                    }
                });
        } catch (\Throwable $e) {
            // Avoid failing during migrations
            \Illuminate\Support\Facades\View::share('currency_symbol', '$');
            \Illuminate\Support\Facades\View::share('store_name', 'Pistis');
            \Illuminate\Support\Facades\View::share('store_logo', null);
            \Illuminate\Support\Facades\View::share('store_logo_height', 32);
            \Illuminate\Support\Facades\View::share('store_hide_brand_text', false);
            \Illuminate\Support\Facades\View::share('homepage_show_categories', true);
            \Illuminate\Support\Facades\View::share('homepage_show_editorial_categories', true);
            \Illuminate\Support\Facades\View::share('campaignVideo', ['enabled' => false, 'video_url' => null]);
        }
    }

}
