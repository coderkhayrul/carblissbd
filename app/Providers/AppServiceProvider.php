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
        \Illuminate\Support\Facades\View::composer('frontend.partials.popup-ad', function ($view) {
            // Use the same record managed by the admin settings screen.
            $popup = \App\Models\PopupAds::orderBy('id')->first();
            $view->with('popupAd', $popup?->status ? $popup : null);
        });
    }
}
