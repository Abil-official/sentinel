<?php

namespace App\Providers;

use Illuminate\Http\Middleware\TrustProxies;
use Illuminate\Support\ServiceProvider;

class WorkbenchServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        TrustProxies::at('*');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
