<?php

namespace Tests\Feature;

use Laravel\Sentinel\Drivers\Driver;
use Laravel\Sentinel\Drivers\Laravel;
use Laravel\Sentinel\SentinelManager;
use Tests\TestCase;

class SentinelManagerTest extends TestCase
{
    public function test_it_can_be_resolved()
    {
        $manager = app(SentinelManager::class);

        $this->assertInstanceOf(SentinelManager::class, $manager);
        $this->assertSame('laravel', $manager->getDefaultDriver());

        tap($manager->driver(), function ($driver) use ($manager) {
            $this->assertInstanceOf(Laravel::class, $driver);
            $this->assertInstanceOf(Driver::class, $driver);

            $this->assertSame($driver, $manager->driver('laravel'));
        });
    }

    public function test_it_can_fallback_to_default_driver()
    {
        $manager = app(SentinelManager::class);

        $this->assertInstanceOf(Laravel::class, $manager->driverOrFallback('foobar'));
    }
}
