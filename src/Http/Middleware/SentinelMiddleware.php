<?php

namespace Laravel\Sentinel\Http\Middleware;

class SentinelMiddleware
{
    public function handle(Request $request, Closure $next, ?string $driver = null)
    {
        return $next($request);
    }
}
