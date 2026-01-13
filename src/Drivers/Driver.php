<?php

namespace Laravel\Sentinel\Drivers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\IpUtils;

abstract class Driver
{
    /**
     * Construct a new driver.
     *
     * @param \Illuminate\Contracts\Foundation\Application&\Illuminate\Foundation\Application  $app
     */
    public function __construct(protected Application $app)
    {
        //
    }

    public function attempt(Request $request): bool
    {
        if ($app->isLocal() && ! IpUtils::isPrivateIp($request->ip()) && $request->isFromTrustedProxies()) {
            return false;
        }

        return true;
    }
}
