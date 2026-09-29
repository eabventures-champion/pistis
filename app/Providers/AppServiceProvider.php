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
            if (!app()->runningInConsole() || app()->runningUnitTests()) {
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
                \Illuminate\Support\Facades\View::share('store_hide_brand_text', (bool) \App\Models\Setting::get('store_hide_brand_text', false));
            }
        } catch (\Exception $e) {
            // Avoid failing during migrations
            \Illuminate\Support\Facades\View::share('currency_symbol', '$');
            \Illuminate\Support\Facades\View::share('store_name', 'Pistis');
            \Illuminate\Support\Facades\View::share('store_logo', null);
            \Illuminate\Support\Facades\View::share('store_logo_height', 32);
            \Illuminate\Support\Facades\View::share('store_hide_brand_text', false);
            \Illuminate\Support\Facades\View::share('campaignVideo', ['enabled' => false, 'video_url' => null]);
        }
    }

}
