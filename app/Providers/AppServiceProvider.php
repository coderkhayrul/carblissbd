<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->scoped(\App\Services\FrontendLayoutData::class);
        $this->app->scoped('company.branding', fn() => \App\Models\CompanySetting::orderBy('id')->first() ?? new \App\Models\CompanySetting);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Support\Facades\View::composer('admin.partials.order-notifications', function ($view) {
            $view->with('orderNoticeFeed', app(\App\Services\AdminOrderNotificationService::class)->feed((int) Auth::id()));
        });
        \Illuminate\Support\Facades\View::composer(['admin.partials.header', 'admin.auth.login'], function ($view) {
            $view->with('brandCompany', app('company.branding'));
        });
        \Illuminate\Support\Facades\View::composer('frontend.layouts.master', function ($view) {
            $layout = app(\App\Services\FrontendLayoutData::class)->get();
            $view->with($layout);
            $view->with('seo', app(\App\Services\SeoService::class)->build($view->getData(), $layout['footerCompany']));
        });
        \Illuminate\Support\Facades\View::composer('frontend.partials.popup-ad', function ($view) {
            $view->with('popupAd', app(\App\Services\FrontendLayoutData::class)->get()['popupAd']);
        });
        \Illuminate\Support\Facades\View::composer(['frontend.partials.recommended-products', 'frontend.partials.catalog-listing'], function ($view) {
            $view->with('footerCompany', app(\App\Services\FrontendLayoutData::class)->get()['footerCompany']);
        });
    }
}
