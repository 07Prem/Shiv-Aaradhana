<?php

namespace App\Providers;

use App\Services\Search\DatabaseSearchService;
use App\Services\Search\SearchServiceInterface;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

use App\Services\Settings\BrandingService;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(SearchServiceInterface::class, DatabaseSearchService::class);
        $this->app->singleton(BrandingService::class, fn () => new BrandingService());
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useTailwind();

        // Share branding settings globally with all layouts and storefront views
        View::composer(['layouts.storefront', 'layouts.admin', 'storefront.*', 'admin.*'], function ($view) {
            $view->with('branding', app(BrandingService::class)->getBranding());
        });
    }
}
