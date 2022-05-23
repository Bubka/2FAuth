<?php

namespace Tests\Feature\Http\Middlewares;

use App\Models\User;
use Tests\FeatureTestCase;
use Illuminate\Support\Facades\Config;


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
    public function test_user_is_set_from_reverse_proxy_info()
    {
        Config::set('auth.auth_proxy_headers.user', 'HTTP_REMOTE_USER');
        Config::set('auth.auth_proxy_headers.email', 'HTTP_REMOTE_EMAIL');

        $this->app['auth']->shouldUse('reverse-proxy-guard');

        $this->json('GET', '/api/v1/groups', [], [
            'HTTP_REMOTE_USER' => self::USER_NAME,
            'HTTP_REMOTE_EMAIL' => self::USER_EMAIL
        ]);
        $this->assertAuthenticated('reverse-proxy-guard');

        $user = $this->app->make('auth')->guard('reverse-proxy-guard')->user();
        $this->assertEquals(self::USER_NAME, $user->name);
        $this->assertEquals(self::USER_EMAIL, $user->email);
    }


    /**
     * @test
     */
    public function test_user_is_set_from_reverse_proxy_without_email()
    {
        Config::set('auth.auth_proxy_headers.user', 'HTTP_REMOTE_USER');

        $this->app['auth']->shouldUse('reverse-proxy-guard');

        $this->json('GET', '/api/v1/groups', [], [
            'HTTP_REMOTE_USER' => self::USER_NAME
        ]);
        $this->assertAuthenticated('reverse-proxy-guard');

        $user = $this->app->make('auth')->guard('reverse-proxy-guard')->user();
        $this->assertEquals('fake.email@do.not.use', $user->email);
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
            'HTTP_REMOTE_USER' => '',
            'HTTP_REMOTE_EMAIL' => ''
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