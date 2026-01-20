<?php

namespace Laravel\Sentinel;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Illuminate\Contracts\Container\Container getContainer()
 * @method static \Laravel\Sentinel\SentinelManager extend(string $driver, \Closure $callback)
 * @method static \Laravel\Sentinel\SentinelManager forgetDrivers()
 * @method static \Laravel\Sentinel\SentinelManager setContainer(\Illuminate\Contracts\Container\Container $container)
 * @method static array getDrivers()
 * @method static mixed driver(string|null $driver = null)
 * @method static mixed driverOrFallback(string|null $driver)
 * @method static string|null getDefaultDriver()
 *
 * @see \Laravel\Sentinel\SentinelManager
 */
class Sentinel extends Facade
{
    /** {@inheritdoc} */
    protected static function getFacadeAccessor()
    {
        return SentinelManager::class;
    }
}
