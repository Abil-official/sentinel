<?php

namespace Laravel\Sentinel;

use Illuminate\Support\Facades\Facade;

class Sentinel extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return SentinelManager::class;
    }
}
