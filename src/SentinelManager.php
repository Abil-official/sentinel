<?php

namespace Laravel\Sentinel;

use Illuminate\Support\Manager;

class SentinelManager extends Manager
{
    /**
     * Create default "laravel" driver.
     *
     * @return \Laravel\Sentinel\Drivers\Laravel
     */
    public function createLaravelDriver()
    {
        /** @var \Illuminate\Contracts\Container\Container&\Illuminate\Contracts\Foundation\Application $app */
        $app = $this->getContainer();

        return new Drivers\Laravel($app);
    }

    /**
     * Get the default driver name.
     *
     * @return string|null
     */
    public function getDefaultDriver()
    {
        return 'laravel';
    }
}
