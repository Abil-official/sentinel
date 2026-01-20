<?php

namespace Laravel\Sentinel;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class SentinelServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(SentinelManager::class, fn ($app) => new SentinelManager($app));
    }
}
