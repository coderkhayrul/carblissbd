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
        $this->app->scoped(\App\Services\FrontendLayoutData::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Support\Facades\View::composer('frontend.layouts.master', function ($view) {
            $view->with(app(\App\Services\FrontendLayoutData::class)->get());
        });
        \Illuminate\Support\Facades\View::composer('frontend.partials.popup-ad', function ($view) {
            $view->with('popupAd', app(\App\Services\FrontendLayoutData::class)->get()['popupAd']);
        });
    }
}
