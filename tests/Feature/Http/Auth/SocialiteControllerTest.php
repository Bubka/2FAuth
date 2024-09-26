<?php

namespace Tests\Feature\Http\Auth;

use App\Facades\Settings;
use App\Http\Controllers\Auth\SocialiteController;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;

/**
 * SocialiteControllerTest test class
 */
#[CoversClass(SocialiteController::class)]
class SocialiteControllerTest extends FeatureTestCase
{
    /**
     * @var \App\Models\User|\Illuminate\Contracts\Auth\Authenticatable
     */
    protected $user;

    /**
     * @var \Laravel\Socialite\Two\User
     */
    protected $socialiteUser;

    private const USER_OAUTH_ID = '12345';

    private const USER_OAUTH_PROVIDER = 'github';

    private const USER_NAME = 'John';

    private const USER_NICKNAME = 'Jo';

    private const USER_EMAIL = 'john@provider.com';

    public function setUp() : void
    {
        parent::setUp();

        DB::table('users')->delete();
        $this->user = User::factory()->create([
            'name'           => self::USER_NAME,
            'email'          => self::USER_EMAIL,
            'password'       => 'password',
            'is_admin'       => 1,
            'oauth_id'       => self::USER_OAUTH_ID,
            'oauth_provider' => self::USER_OAUTH_PROVIDER,
        ]);

        $this->socialiteUser           = new \Laravel\Socialite\Two\User;
        $this->socialiteUser->id       = self::USER_OAUTH_ID;
        $this->socialiteUser->name     = self::USER_NAME;
        $this->socialiteUser->email    = self::USER_EMAIL;
        $this->socialiteUser->nickname = self::USER_NICKNAME;
    }

    #[Test]
    public function test_redirect_redirects_to_provider_url()
    {
        Settings::set('enableSso', true);

        $response = $this->get('/socialite/redirect/github');

        $response->assertRedirectContains('https://github.com/login/oauth/authorize');
    }

    #[Test]
    public function test_redirect_returns_error_when_registrations_are_disabled()
    {
        Settings::set('enableSso', false);

        $response = $this->get('/socialite/redirect/github');

        $response->assertRedirect('/error?err=sso_disabled');
    }

    #[Test]
    public function test_redirect_returns_error_when_sso_provider_client_id_is_missing()
    {
        //Settings::set('enableSso', true);
        config(['services.github.client_id' => null], true);

        $response = $this->get('/socialite/redirect/github');

        $response->assertRedirect('/error?err=sso_bad_provider_setup');
    }

    #[Test]
    public function test_redirect_returns_error_when_sso_provider_client_secret_is_missing()
    {
        config(['services.github.client_secret' => null]);

        $response = $this->get('/socialite/redirect/github');

        $response->assertRedirect('/error?err=sso_bad_provider_setup');
    }

    #[Test]
    #[DataProvider('ssoConfigVarProvider')]
    public function test_redirect_returns_error_when_openid_provider_client_secret_is_missing($ssoConfigVar)
    {
        config(['services.openid.' . $ssoConfigVar => null]);

        $response = $this->get('/socialite/redirect/openid');

        $response->assertRedirect('/error?err=sso_bad_provider_setup');
    }

    public static function ssoConfigVarProvider()
    {
        return [
            'TOKEN_URL' => [
                'token_url',
            ],
            'AUTHORIZE_URL' => [
                'authorize_url',
            ],
            'USERINFO_URL' => [
                'userinfo_url',
            ],
        ];
    }

    #[Test]
    public function test_callback_authenticates_the_user()
    {
        Socialite::shouldReceive('driver->user')
            ->andReturn($this->socialiteUser);

        $response = $this->get('/socialite/callback/github', ['driver' => 'github']);

        $this->assertAuthenticatedAs($this->user, 'web-guard');
    }

    #[Test]
    public function test_callback_redirects_authenticated_user_to_accounts()
    {
        Socialite::shouldReceive('driver->user')
            ->andReturn($this->socialiteUser);

        $response = $this->get('/socialite/callback/github', ['driver' => 'github']);

        $response->assertRedirect('/accounts');
    }

    #[Test]
    public function test_callback_updates_user_informations()
    {
        $socialiteUpdatedUser           = new \Laravel\Socialite\Two\User;
        $socialiteUpdatedUser->id       = self::USER_OAUTH_ID;
        $socialiteUpdatedUser->email    = 'new_email';
        $socialiteUpdatedUser->nickname = 'new_nickname';

        Socialite::shouldReceive('driver->user')
            ->andReturn($socialiteUpdatedUser);

        $response = $this->get('/socialite/callback/github', ['driver' => 'github']);

        $this->assertDatabaseHas('users', [
            'oauth_id'       => self::USER_OAUTH_ID,
            'oauth_provider' => self::USER_OAUTH_PROVIDER,
            'email'          => 'new_email',
        ]);
    }

    #[Test]
    public function test_callback_updates_username_with_fallback_value()
    {
        $socialiteUpdatedUser        = new \Laravel\Socialite\Two\User;
        $socialiteUpdatedUser->id    = self::USER_OAUTH_ID;
        $socialiteUpdatedUser->name  = 'new_name';
        $socialiteUpdatedUser->email = 'new_email';

        Socialite::shouldReceive('driver->user')
            ->andReturn($socialiteUpdatedUser);

        $response = $this->get('/socialite/callback/github', ['driver' => 'github']);

        $this->assertDatabaseHas('users', [
            'oauth_id'       => self::USER_OAUTH_ID,
            'oauth_provider' => self::USER_OAUTH_PROVIDER,
            'email'          => 'new_email',
        ]);
    }

    #[Test]
    public function test_callback_registers_new_user()
    {
        $newSocialiteUser        = new \Laravel\Socialite\Two\User;
        $newSocialiteUser->id    = 'new_id';
        $newSocialiteUser->name  = 'jane';
        $newSocialiteUser->email = 'jane@provider.com';

        Socialite::shouldReceive('driver->user')
            ->andReturn($newSocialiteUser);

        $response = $this->get('/socialite/callback/github', ['driver' => 'github']);

        $this->assertDatabaseHas('users', [
            'oauth_id'       => 'new_id',
            'oauth_provider' => self::USER_OAUTH_PROVIDER,
            'email'          => 'jane@provider.com',
            'is_admin'       => 0,
        ]);
    }

    #[Test]
    public function test_callback_registers_new_user_with_existing_name()
    {
        $socialiteUserWithSameName           = new \Laravel\Socialite\Two\User;
        $socialiteUserWithSameName->id       = 'socialiteUserWithSameNameId';
        $socialiteUserWithSameName->name     = self::USER_NAME;
        $socialiteUserWithSameName->email    = 'socialiteuserwithsamename@example.com';
        $socialiteUserWithSameName->nickname = self::USER_NICKNAME;

        Socialite::shouldReceive('driver->user')
            ->andReturn($socialiteUserWithSameName);

        $response = $this->get('/socialite/callback/github', ['driver' => 'github']);

        $this->assertDatabaseHas('users', [
            'oauth_id'       => 'socialiteUserWithSameNameId',
            'oauth_provider' => self::USER_OAUTH_PROVIDER,
            'email'          => 'socialiteuserwithsamename@example.com',
        ]);
    }

    #[Test]
    public function test_callback_always_registers_first_user_as_admin()
    {
        DB::table('users')->delete();
        Settings::set('disableRegistration', true);
        Settings::set('enableSso', false);

        Socialite::shouldReceive('driver->user')
            ->andReturn($this->socialiteUser);

        $response = $this->get('/socialite/callback/github', ['driver' => 'github']);

        $this->assertDatabaseHas('users', [
            'oauth_id'       => self::USER_OAUTH_ID,
            'oauth_provider' => self::USER_OAUTH_PROVIDER,
            'is_admin'       => 1,
        ]);
    }

    #[Test]
    public function test_callback_returns_error_when_email_is_already_used()
    {
        $userWithSameEmail = User::factory()->create([
            'name'     => 'userWithSameEmail',
            'email'    => 'other@example.com',
            'password' => 'password',
        ]);

        $socialiteUserWithSameEmail           = new \Laravel\Socialite\Two\User;
        $socialiteUserWithSameEmail->id       = '666';
        $socialiteUserWithSameEmail->name     = 'socialiteUserWithSameEmail';
        $socialiteUserWithSameEmail->email    = 'other@example.com';
        $socialiteUserWithSameEmail->nickname = self::USER_NICKNAME;

        Socialite::shouldReceive('driver->user')
            ->andReturn($socialiteUserWithSameEmail);

        $response = $this->get('/socialite/callback/github', ['driver' => 'github']);

        $response->assertRedirect('/error?err=sso_email_already_used');
        $this->assertDatabaseMissing('users', [
            'oauth_id'       => '666',
            'oauth_provider' => self::USER_OAUTH_PROVIDER,
        ]);
    }

    #[Test]
    public function test_callback_redirects_to_error_when_sso_provider_reject_auth()
    {
        $newSocialiteUser        = new \Laravel\Socialite\Two\User;
        $newSocialiteUser->id    = 'rejected_id';
        $newSocialiteUser->name  = 'jane';
        $newSocialiteUser->email = 'jane@provider.com';

        Socialite::shouldReceive('driver->user')
            ->andThrow(new Exception);

        $response = $this->get('/socialite/callback/github', ['driver' => 'github']);

        $response->assertRedirect('/error?err=sso_failed');
    }

    #[Test]
    public function test_callback_redirects_to_error_when_registrations_are_closed()
    {
        Settings::set('disableRegistration', true);
        Settings::set('keepSsoRegistrationEnabled', false);

        $newSocialiteUser        = new \Laravel\Socialite\Two\User;
        $newSocialiteUser->id    = 'rejected_id';
        $newSocialiteUser->name  = 'jane';
        $newSocialiteUser->email = 'jane@provider.com';

        Socialite::shouldReceive('driver->user')
            ->andReturn($newSocialiteUser);

        $response = $this->get('/socialite/callback/github', ['driver' => 'github']);

        $response->assertRedirect('/error?err=sso_no_register');
    }

    #[Test]
    public function test_callback_skips_registration_when_all_registrations_are_closed()
    {
        Settings::set('disableRegistration', true);
        Settings::set('keepSsoRegistrationEnabled', false);

        $newSocialiteUser        = new \Laravel\Socialite\Two\User;
        $newSocialiteUser->id    = 'rejected_id';
        $newSocialiteUser->name  = 'jane';
        $newSocialiteUser->email = 'jane@provider.com';

        Socialite::shouldReceive('driver->user')
            ->andReturn($newSocialiteUser);

        $response = $this->get('/socialite/callback/github', ['driver' => 'github']);

        $this->assertDatabaseMissing('users', [
            'oauth_id'       => 'rejected_id',
            'oauth_provider' => self::USER_OAUTH_PROVIDER,
        ]);
    }

    #[Test]
    public function test_callback_registers_new_user_when_sso_registrations_are_enabled()
    {
        Settings::set('disableRegistration', true);
        Settings::set('keepSsoRegistrationEnabled', true);

        $newSocialiteUser        = new \Laravel\Socialite\Two\User;
        $newSocialiteUser->id    = 'new_id';
        $newSocialiteUser->name  = 'jane';
        $newSocialiteUser->email = 'jane@provider.com';

        Socialite::shouldReceive('driver->user')
            ->andReturn($newSocialiteUser);

        $response = $this->get('/socialite/callback/github', ['driver' => 'github']);

        $this->assertDatabaseHas('users', [
            'oauth_id'       => 'new_id',
            'oauth_provider' => self::USER_OAUTH_PROVIDER,
            'email'          => 'jane@provider.com',
            'is_admin'       => 0,
        ]);
    }
}
