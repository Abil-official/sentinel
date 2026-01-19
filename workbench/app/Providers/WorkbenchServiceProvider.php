<?php

namespace App\Providers;

use Illuminate\Contracts\Http\Kernel as HttpKernel;
use Illuminate\Http\Middleware\TrustProxies;
use Illuminate\Support\ServiceProvider;

use function Orchestra\Testbench\laravel_version_compare;

class WorkbenchServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        if (laravel_version_compare('11.0.0', '<')) {
            $this->callAfterResolving(HttpKernel::class, function ($kernel) {
                $kernel->prependMiddleware(TrustProxies::class);
            });
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
