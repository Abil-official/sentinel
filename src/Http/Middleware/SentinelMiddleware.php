<?php

namespace Laravel\Sentinel\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sentinel\SentinelManager;

class SentinelMiddleware
{
    public function handle(Request $request, Closure $next, ?string $driver = null)
    {
        abort_unless(app(SentinelManager::class)->driver($driver)->authorize($request), 401);

        return $next($request);
    }
}
