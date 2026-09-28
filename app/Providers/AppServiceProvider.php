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
            }
        } catch (\Exception $e) {
            // Avoid failing during migrations
            \Illuminate\Support\Facades\View::share('currency_symbol', '$');
        }
    }

}
