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
        \Illuminate\Support\Facades\View::composer('frontend.layouts.master', function ($view) {
            $view->with('menuCategories', \App\Models\Category::where('status', true)
                ->with(['children' => fn ($query) => $query->where('status', true)->where('show_on_menu', true)
                    ->orderBy('menu_serial')->orderBy('id')])
                ->where('show_on_menu', true)->orderBy('menu_serial')->orderBy('id')->get());
        });
        \Illuminate\Support\Facades\View::composer('frontend.partials.popup-ad', function ($view) {
            // Use the same record managed by the admin settings screen.
            $popup = \App\Models\PopupAds::orderBy('id')->first();
            $view->with('popupAd', $popup?->status ? $popup : null);
        });
    }
}
