<?php

namespace Laravel\Sentinel\Drivers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use RuntimeException;
use Symfony\Component\HttpFoundation\IpUtils;

abstract class Driver
{
    /**
     * Construct a new driver.
     *
     * @param  \Illuminate\Contracts\Foundation\Application&\Illuminate\Foundation\Application  $app
     */
    public function __construct(protected Application $app)
    {
        //
    }

    /**
     * Authorize access the request.
     */
    abstract public function authorize(Request $request): bool;

    /**
     * Authorize access from local environment.
     */
    protected function authorizeLocalEnvironmentAccessedFromReverseProxies(Request $request): bool
    {
        if (! $this->app->isLocal()) {
            return true;
        }

        $isPrivateIp = IpUtils::isPrivateIp($request->ip());
        $isFromTrustedProxy = $request->isFromTrustedProxy();

        if (
            $isPrivateIp
            && ! $isFromTrustedProxy
            && Str::endsWith($request->host(), ['.sharedwithexpose.com', '.ngrok-free.app', '.ngrok.io'])
        ) {
            throw new RuntimeException(
                sprintf('Unable to access "%s /%s" using "local" environment, please change the environment or configure Trusted Proxies: https://laravel.com/docs/requests#configuring-trusted-proxies', $request->method(), $request->path())
            );
        }

        if (! $isPrivateIp && $isFromTrustedProxy) {
            return false;
        }

        return true;
    }
}
