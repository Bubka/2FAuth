<?php

namespace Tests\Feature\Extensions;

use App\Extensions\RemoteUserProvider;
use App\Models\User;
use App\Services\Auth\ReverseProxyGuard;
use Illuminate\Support\Facades\Config;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;

/**
 * RemoteUserProviderTest test class
 */
#[CoversClass(RemoteUserProvider::class)]
#[CoversClass(ReverseProxyGuard::class)]
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

    #[Test]
    public function test_user_is_set_from_proxy_headers()
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
        $this->assertDatabaseHas('users', [
            'name'  => self::USER_NAME,
            'email' => strtolower(self::USER_NAME) . '@remote',
        ]);
    }

    #[Test]
    public function test_user_is_set_from_proxy_headers_with_an_email()
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
        $this->assertDatabaseHas('users', [
            'name'  => self::USER_NAME,
            'email' => self::USER_EMAIL,
        ]);
    }

    #[Test]
    public function test_user_is_set_from_proxy_headers_even_if_name_is_long()
    {
        Config::set('auth.auth_proxy_headers.user', 'HTTP_REMOTE_USER');
        Config::set('auth.auth_proxy_headers.email', 'HTTP_REMOTE_EMAIL');

        $this->app['auth']->shouldUse('reverse-proxy-guard');

        $name      = str_pad('john', 300, '_');
        $inDbName  = substr($name, 0, 191);
        $inDbEmail = substr($name, 0, 184) . '@remote';

        $this->json('GET', '/api/v1/groups', [], [
            'HTTP_REMOTE_USER' => $name,
        ]);
        $this->assertAuthenticated('reverse-proxy-guard');

        $user = $this->app->make('auth')->guard('reverse-proxy-guard')->user();

        $this->assertEquals($inDbName, $user->name);
        $this->assertEquals($inDbEmail, $user->email);
        $this->assertDatabaseHas('users', [
            'name'  => $inDbName,
            'email' => $inDbEmail,
        ]);
    }

    #[Test]
    public function test_user_is_not_set_from_proxy_headers_when_name_is_missing()
    {
        Config::set('auth.auth_proxy_headers.user', 'HTTP_REMOTE_USER');
        Config::set('auth.auth_proxy_headers.email', 'HTTP_REMOTE_EMAIL');

        $this->app['auth']->shouldUse('reverse-proxy-guard');

        $this->json('GET', '/api/v1/groups', [], [
            'HTTP_REMOTE_USER' => '',
        ]);
        $this->assertGuest('reverse-proxy-guard');

        $user = $this->app->make('auth')->guard('reverse-proxy-guard')->user();

        $this->assertNull($user);
        $this->assertDatabaseCount('users', 0);
    }

    #[Test]
    public function test_user_email_is_synced_with_email_from_proxy_headers()
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

    #[Test]
    public function test_user_email_is_not_synced_when_email_from_proxy_headers_is_missing()
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
            'HTTP_REMOTE_EMAIL' => '',
        ]);

        $this->assertAuthenticated('reverse-proxy-guard');
        $this->assertDatabaseHas('users', [
            'id'    => $dbUser->id,
            'email' => strtolower(self::USER_NAME) . '@remote',
        ]);
    }

    #[Test]
    public function test_user_email_is_not_synced_when_email_from_proxy_headers_is_invalid()
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

    #[Test]
    public function test_user_email_is_not_sync_when_email_from_proxy_headers_is_already_in_use()
    {
        Config::set('auth.auth_proxy_headers.user', 'HTTP_REMOTE_USER');
        Config::set('auth.auth_proxy_headers.email', 'HTTP_REMOTE_EMAIL');

        $dbUser = User::factory()->create([
            'name'  => self::USER_NAME,
            'email' => self::USER_EMAIL,
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
