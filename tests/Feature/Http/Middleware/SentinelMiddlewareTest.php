<?php

namespace Tests\Feature\Http\Middleware;

use Illuminate\Http\Request;
use Laravel\Sentinel\Drivers\Driver;
use Laravel\Sentinel\SentinelManager;
use Tests\TestCase;

class SentinelMiddlewareTest extends TestCase
{
    /** {@inheritdoc} */
    protected function defineEnvironment($app): void
    {
        $app->make(SentinelManager::class)->extend('testing', function ($app) {
            return new class(fn () => $app) extends Driver
            {
                public function authorize(Request $request): bool
                {
                    return $this->authorizeAccessingViaReverseProxies($request);
                }
            };
        });
    }

    /** {@inheritdoc} */
    protected function defineRoutes($router)
    {
        $router->get('debug', function () {
            return app()->version();
        })->middleware('sentinel:testing');
    }

    public function test_it_can_authorize_a_request_using_middleware()
    {
        $this->get('debug')
            ->assertSee(app()->version())
            ->assertOk();
    }

    public function test_it_can_authorize_a_request_using_middleware_and_prevent_reverse_proxy_access()
    {
        $this->withHeaders([
            'REMOTE_ADDR' => '127.0.0.1',
            'HOST' => 'laravel.ngrok.io',
            'X-FORWARDED-FOR' => '202.168.65.217',
            'X-FORWARDED-HOST' => 'laravel.ngrok.io',
            'X-FORWARDED-PROTO' => 'https',
        ])->get('debug')
            ->assertUnauthorized();
    }
}
