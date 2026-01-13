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

    /**
     * Authorize access from local environment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function authorize(Request $request): bool
    {
        if ($this->app->isLocal() && ! IpUtils::isPrivateIp($request->ip()) && $request->isFromTrustedProxy()) {
            return false;
        }

        return true;
    }
}
