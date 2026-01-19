<?php

namespace Tests\Feature\Drivers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Laravel\Sentinel\Drivers\Driver;
use Laravel\Sentinel\Sentinel;
use Laravel\Sentinel\SentinelManager;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Tests\TestCase;

class DriverTest extends TestCase
{
    /** {@inheritdoc} */
    protected function defineEnvironment($app): void
    {
        Request::setTrustedProxies(['127.0.0.1'], SymfonyRequest::HEADER_X_FORWARDED_FOR | SymfonyRequest::HEADER_X_FORWARDED_HOST | SymfonyRequest::HEADER_X_FORWARDED_PORT | SymfonyRequest::HEADER_X_FORWARDED_PROTO);

        $app->make(SentinelManager::class)->extend('testing', function ($app) {
            return new class($app) extends Driver
            {
                public function authorize(Request $request): bool
                {
                    return $this->authorizeAccessingViaReverseProxies($request);
                }
            };
        });
    }

    public function test_it_can_authorize_local_request()
    {
        $request = Request::create('GET', '/', [
            'REMOTE_ADDR' => '127.0.0.1',
        ]);

        tap(Sentinel::driver('testing'), function ($driver) use ($request) {
            $this->assertTrue($driver->authorize($request));
        });
    }

    public function test_it_can_authorize_reverse_proxy_request()
    {
        $request = Request::create('GET', '/', [
            'REMOTE_ADDR' => '127.0.0.1',
            'HOST' => 'laravel.ngrok.io',
            'X-FORWARDED-FOR' => '127.0.0.1',
            'X-FORWARDED-HOST' => 'laravel.ngrok.io',
            'X-FORWARDED-PROTO' => 'https',
        ]);

        tap(Sentinel::driver('testing'), function ($driver) use ($request) {
            $this->assertTrue($driver->authorize($request));
        });
    }

    public function test_it_can_authorize_reverse_proxy_request_when_forwarding_for_public_ips()
    {
        $request = Request::create('/', 'GET', [], [], [], $this->transformHeadersToServerVars([
            'REMOTE_ADDR' => '127.0.0.1',
            'HOST' => 'laravel.ngrok.io',
            'X-FORWARDED-FOR' => '202.168.65.217',
            'X-FORWARDED-HOST' => 'laravel.ngrok.io',
            'X-FORWARDED-PROTO' => 'https',
        ]));

        tap(Sentinel::driver('testing'), function ($driver) use ($request) {
            $this->assertFalse($driver->authorize($request));
        });
    }

    public function test_it_can_authorize_or_fail_reverse_proxy_request_when_forwarding_for_public_ips()
    {
        $this->expectException(AuthorizationException::class);
        $this->expectExceptionMessage('This action is unauthorized.');

        $request = Request::create('/', 'GET', [], [], [], $this->transformHeadersToServerVars([
            'REMOTE_ADDR' => '127.0.0.1',
            'HOST' => 'laravel.ngrok.io',
            'X-FORWARDED-FOR' => '202.168.65.217',
            'X-FORWARDED-HOST' => 'laravel.ngrok.io',
            'X-FORWARDED-PROTO' => 'https',
        ]));

        Sentinel::driver('testing')->authorizeOrFail($request);
    }
}
