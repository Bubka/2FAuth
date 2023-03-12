<?php

namespace Tests\Unit\Extensions;

use App\Extensions\RemoteUserProvider;
use App\Models\User;
use Illuminate\Support\Facades\Config;
use Tests\FeatureTestCase;

/**
 * @covers \App\Extensions\RemoteUserProvider
 */
class RemoteUserProviderTest extends FeatureTestCase
{
    private const USER_NAME = 'John';

    private const USER_EMAIL = 'john@example.com';

    public function test_user_is_retreived_from_db()
    {
        $dbUser = User::factory()->create([
            'name'  => self::USER_NAME,
            'email' => self::USER_EMAIL,
        ]);

        $provider = new RemoteUserProvider;

        $user = $provider->retrieveById([
            'id'    => self::USER_NAME,
            'email' => null,
        ]);

        $this->assertEquals($dbUser->id, $user->id);
    }

    /**
     * @test
     */
    public function test_user_is_set_from_reverse_proxy_info_with_provided_email()
    {
        Config::set('auth.auth_proxy_headers.user', 'HTTP_REMOTE_USER');
        Config::set('auth.auth_proxy_headers.email', 'HTTP_REMOTE_EMAIL');

        $this->app['auth']->shouldUse('reverse-proxy-guard');

        $this->json('GET', '/api/v1/groups', [], [
            'HTTP_REMOTE_USER'  => self::USER_NAME,
            'HTTP_REMOTE_EMAIL' => self::USER_EMAIL,
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
            'HTTP_REMOTE_USER' => self::USER_NAME,
        ]);
        $this->assertAuthenticated('reverse-proxy-guard');

        $user = $this->app->make('auth')->guard('reverse-proxy-guard')->user();
        $this->assertEquals(self::USER_NAME, $user->name);
        $this->assertEquals(strtolower(self::USER_NAME) . '@remote', $user->email);
    }

    /**
     * @test
     */
    public function test_user_is_set_from_reverse_proxy_even_if_identifier_is_long()
    {
        Config::set('auth.auth_proxy_headers.user', 'HTTP_REMOTE_USER');
        Config::set('auth.auth_proxy_headers.email', 'HTTP_REMOTE_EMAIL');

        $this->app['auth']->shouldUse('reverse-proxy-guard');

        $name = str_pad('john', 300, '_');

        $this->json('GET', '/api/v1/groups', [], [
            'HTTP_REMOTE_USER' => $name,
        ]);
        $this->assertAuthenticated('reverse-proxy-guard');

        $user = $this->app->make('auth')->guard('reverse-proxy-guard')->user();
        $this->assertLessThan(192, strlen($user->name));
        $this->assertLessThan(192, strlen($user->email));
    }

    /**
     * @test
     */
    public function test_user_email_is_sync_with_email_proxy_header()
    {
        Config::set('auth.auth_proxy_headers.user', 'HTTP_REMOTE_USER');
        Config::set('auth.auth_proxy_headers.email', 'HTTP_REMOTE_EMAIL');

        $dbUser = User::factory()->create([
            'name'  => self::USER_NAME,
            'email' => strtolower(self::USER_NAME) . '@remote',
        ]);

        $this->app['auth']->shouldUse('reverse-proxy-guard');

        $this->json('GET', '/api/v1/groups', [], [
            'HTTP_REMOTE_USER'  => self::USER_NAME,
            'HTTP_REMOTE_EMAIL' => self::USER_EMAIL,
        ]);

        $this->assertAuthenticated('reverse-proxy-guard');

        $this->assertDatabaseHas('users', [
            'id'    => $dbUser->id,
            'email' => self::USER_EMAIL,
        ]);
    }

    /**
     * @test
     */
    public function test_user_email_is_not_sync_with_invalid_email_proxy_header()
    {
        Config::set('auth.auth_proxy_headers.user', 'HTTP_REMOTE_USER');
        Config::set('auth.auth_proxy_headers.email', 'HTTP_REMOTE_EMAIL');

        $dbUser = User::factory()->create([
            'name'  => self::USER_NAME,
            'email' => strtolower(self::USER_NAME) . '@remote',
        ]);

        $this->app['auth']->shouldUse('reverse-proxy-guard');

        $this->json('GET', '/api/v1/groups', [], [
            'HTTP_REMOTE_USER'  => self::USER_NAME,
            'HTTP_REMOTE_EMAIL' => 'bad[at]email',
        ]);

        $this->assertAuthenticated('reverse-proxy-guard');

        $this->assertDatabaseHas('users', [
            'id'    => $dbUser->id,
            'email' => $dbUser->email,
        ]);
    }

    /**
     * @test
     */
    public function test_user_email_is_not_sync_with_already_used_email_proxy_header()
    {
        Config::set('auth.auth_proxy_headers.user', 'HTTP_REMOTE_USER');
        Config::set('auth.auth_proxy_headers.email', 'HTTP_REMOTE_EMAIL');

        $dbUser = User::factory()->create([
            'name'  => self::USER_NAME,
            'email' => strtolower(self::USER_NAME) . '@remote',
        ]);

        $otherUser = User::factory()->create([
            'email' => 'other@example.com',
        ]);

        $this->app['auth']->shouldUse('reverse-proxy-guard');

        $this->json('GET', '/api/v1/groups', [], [
            'HTTP_REMOTE_USER'  => self::USER_NAME,
            'HTTP_REMOTE_EMAIL' => $otherUser->email,
        ]);

        $this->assertAuthenticated('reverse-proxy-guard');

        $this->assertDatabaseHas('users', [
            'id'    => $dbUser->id,
            'email' => $dbUser->email,
        ]);
    }
}
