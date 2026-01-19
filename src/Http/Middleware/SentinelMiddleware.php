<?php

namespace Laravel\Sentinel\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sentinel\SentinelManager;

class SentinelMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ?string $driver = null)
    {
        $sentinel = app(SentinelManager::class)->driverOrFallback($driver);

        abort_unless($sentinel->authorize($request), 401);

        return $next($request);
    }
}
