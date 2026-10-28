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
            $view->with('footerSocialLinks', \App\Models\WebSocialLink::where('status', true)->orderBy('id')->get());
            $view->with('footerCompany', \App\Models\CompanySetting::orderBy('id')->first());
            $view->with('informationPages', \App\Models\Page::where('status', true)
                ->where('group_name', 'Information')->where('show_in_quick_links', true)
                ->orderBy('orderBy')->orderBy('id')->get());
            $view->with('customerCarePages', \App\Models\Page::where('status', true)
                ->where('group_name', 'Customer Care')->where('show_in_quick_links', true)
                ->orderBy('orderBy')->orderBy('id')->get());
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
