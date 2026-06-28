<?php

namespace Tests\Feature\Http\Middlewares;

use App\Http\Middleware\Authenticate;
use App\Models\User;
use App\Providers\AuthServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;

#[CoversClass(Authenticate::class)]
#[CoversMethod(AuthServiceProvider::class, 'boot')]
class AuthenticateMiddlewareTest extends FeatureTestCase
{
    private const USER_NAME = 'John';

    private const USER_EMAIL = 'john@example.com';

    protected function setUp() : void
    {
        parent::setUp();

        Config::set('2fauth.config.trustedProxies', '127.0.0.1');
    }

    #[Test]
    public function test_it_always_authenticates_with_reverse_proxy_guard()
    {
        Config::set('auth.auth_proxy_headers.user', 'HTTP_REMOTE_USER');

        $this->app['auth']->shouldUse('reverse-proxy-guard');

        $this->json('GET', '/api/v1/groups', [], ['HTTP_REMOTE_USER' => self::USER_NAME]);
        $this->assertAuthenticated('reverse-proxy-guard');
    }

    #[Test]
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

    #[Test]
    public function test_it_does_not_authenticate_with_missing_header()
    {
        $this->app['auth']->shouldUse('reverse-proxy-guard');

        $this->json('GET', '/api/v1/groups', [], [])
            ->assertStatus(407);
    }

    #[Test]
    public function test_it_does_not_authenticate_without_trusted_proxy_configuration()
    {
        Config::set('auth.auth_proxy_headers.user', 'HTTP_REMOTE_USER');
        Config::set('2fauth.config.trustedProxies', null);

        $this->app['auth']->shouldUse('reverse-proxy-guard');

        $this->json('GET', '/api/v1/groups', [], [
            'HTTP_REMOTE_USER' => self::USER_NAME,
        ])->assertStatus(407);
    }

    #[Test]
    public function test_it_overrides_locale_when_auth_is_successful()
    {
        Config::set('auth.auth_proxy_headers.user', 'HTTP_REMOTE_USER');
        Config::set('auth.auth_proxy_headers.email', 'HTTP_REMOTE_EMAIL');

        $this->app['auth']->shouldUse('reverse-proxy-guard');

        $lang = 'fr';
        $user = User::factory()->create([
            'name'  => self::USER_NAME,
            'email' => self::USER_EMAIL,
        ]);
        $user['preferences->lang'] = $lang;
        $user->save();

        $this->json('GET', '/api/v1/groups', [], [
            'HTTP_REMOTE_USER' => self::USER_NAME,
        ]);

        $this->assertEquals($lang, App::getLocale());
    }

    #[Test]
    public function test_it_rejects_reverse_proxy_headers_from_untrusted_remote_addresses()
    {
        Config::set('auth.auth_proxy_headers.user', 'HTTP_REMOTE_USER');

        $this->app['auth']->shouldUse('reverse-proxy-guard');

        $this->json('GET', '/api/v1/groups', [], [
            'HTTP_REMOTE_USER' => self::USER_NAME,
            'REMOTE_ADDR' => '10.0.0.25',
        ])->assertStatus(407);
    }
}
