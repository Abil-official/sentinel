<?php

namespace Laravel\Sentinel;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Laravel\Sentinel\Http\Middleware\SentinelMiddleware;

class SentinelServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(SentinelManager::class, fn ($app) => new SentinelManager($app));

        $this->callAfterResolving('router', function (Router $router) {
            $router->aliasMiddleware('sentinel', SentinelMiddleware::class);
        });
    }
}
