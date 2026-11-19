<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();

        if ($this->app->environment('production') || env('FORCE_HTTPS', false)) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        RateLimiter::for('outbound-mail', function ($job) {
            return Limit::perMinute(max(1, (int) config('mail.outbound_rate_limit_per_minute', 10)))
                ->by('outbound-mail');
        });

        // Global Blade Component Aliases for Modularized Component Categories
        \Illuminate\Support\Facades\Blade::component('components.cards.kpi-card', 'kpi-card');
        \Illuminate\Support\Facades\Blade::component('components.cards.table-card', 'table-card');
        \Illuminate\Support\Facades\Blade::component('components.badges.status-badge', 'status-badge');
        \Illuminate\Support\Facades\Blade::component('components.badges.stock-badge', 'stock-badge');
        \Illuminate\Support\Facades\Blade::component('components.avatars.entity-avatar', 'entity-avatar');
        \Illuminate\Support\Facades\Blade::component('components.layout.page-header', 'page-header');
    }
}
