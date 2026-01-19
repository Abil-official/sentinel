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
    protected function authorizeAccessingViaReverseProxies(Request $request): bool
    {
        $isPrivateIp = $this->isPrivateIp($request->ip());
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

    /**
     * Checks if an IPv4 or IPv6 address is contained in the list of private IP subnets.
     */
    protected function isPrivateIp(string $requestIp): bool
    {
        /** @phpstan-ignore function.alreadyNarrowedType */
        if (method_exists(IpUtils::class, 'isPrivateIp')) {
            return IpUtils::isPrivateIp($requestIp);
        }

        return IpUtils::checkIp($requestIp, [
            '127.0.0.0/8',    // RFC1700 (Loopback)
            '10.0.0.0/8',     // RFC1918
            '192.168.0.0/16', // RFC1918
            '172.16.0.0/12',  // RFC1918
            '169.254.0.0/16', // RFC3927
            '0.0.0.0/8',      // RFC5735
            '240.0.0.0/4',    // RFC1112
            '::1/128',        // Loopback
            'fc00::/7',       // Unique Local Address
            'fe80::/10',      // Link Local Address
            '::ffff:0:0/96',  // IPv4 translations
            '::/128',         // Unspecified address
        ]);
    }
}
