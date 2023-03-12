<?php

namespace Tests\Feature\Http\Middlewares;

use Illuminate\Support\Facades\Config;
use Tests\FeatureTestCase;

class AuthenticateMiddlewareTest extends FeatureTestCase
{
    private const USER_NAME = 'John';

    private const USER_EMAIL = 'john@example.com';

    /**
     * @test
     */
    public function test_it_always_authenticates_with_reverse_proxy_guard()
    {
        Config::set('auth.auth_proxy_headers.user', 'HTTP_REMOTE_USER');

        $this->app['auth']->shouldUse('reverse-proxy-guard');

        $this->json('GET', '/api/v1/groups', [], ['HTTP_REMOTE_USER' => self::USER_NAME]);
        $this->assertAuthenticated('reverse-proxy-guard');
    }

    /**
     * @test
     */
    public function test_it_does_not_authenticate_with_empty_header()
    {
        Config::set('auth.auth_proxy_headers.user', 'HTTP_REMOTE_USER');
        Config::set('auth.auth_proxy_headers.email', 'HTTP_REMOTE_EMAIL');

        $this->app['auth']->shouldUse('reverse-proxy-guard');

        $this->json('GET', '/api/v1/groups', [], [
            'HTTP_REMOTE_USER'  => '',
            'HTTP_REMOTE_EMAIL' => '',
        ])->assertStatus(407);
    }

    /**
     * @test
     */
    public function test_it_does_not_authenticate_with_missing_header()
    {
        $this->app['auth']->shouldUse('reverse-proxy-guard');

        $this->json('GET', '/api/v1/groups', [], [])
            ->assertStatus(407);
    }
}
