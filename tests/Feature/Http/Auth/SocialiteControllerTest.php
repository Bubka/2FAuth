<?php

namespace Tests\Feature\Http\Auth;

use App\Facades\Settings;
use App\Http\Controllers\Auth\SocialiteController;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;
use PHPUnit\Framework\Attributes\CoversClass;
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

    /**
     * @test
     */
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

        
        $this->socialiteUser = new \Laravel\Socialite\Two\User;
        $this->socialiteUser->id = self::USER_OAUTH_ID;
        $this->socialiteUser->name = self::USER_NAME;
        $this->socialiteUser->email = self::USER_EMAIL;
        $this->socialiteUser->nickname = self::USER_NICKNAME;
    }
    
    /**
     * @test
     */
    public function test_redirect_redirects_to_provider_url()
    {
        Settings::set('enableSso', true);

        $response = $this->get('/socialite/redirect/github');

        $response->assertRedirectContains('https://github.com/login/oauth/authorize');
    }

    /**
     * @test
     */
    public function test_redirect_returns_error_when_registrations_are_disabled()
    {
        Settings::set('enableSso', false);

        $response = $this->get('/socialite/redirect/github');

        $response->assertRedirect('/error?err=sso_disabled');
    }

    /**
     * @test
     */
    public function test_callback_authenticates_the_user()
    {
        Socialite::shouldReceive('driver->user')
            ->andReturn($this->socialiteUser);

        $response = $this->get('/socialite/callback/github', ['driver' => 'github']);

        $this->assertAuthenticatedAs($this->user, 'web-guard');
    }

    /**
     * @test
     */
    public function test_callback_redirects_authenticated_user_to_accounts()
    {
        Socialite::shouldReceive('driver->user')
            ->andReturn($this->socialiteUser);

        $response = $this->get('/socialite/callback/github', ['driver' => 'github']);

        $response->assertRedirect('/accounts');
    }

    /**
     * @test
     */
    public function test_callback_updates_user_informations()
    {
        $socialiteUpdatedUser = new \Laravel\Socialite\Two\User;
        $socialiteUpdatedUser->id = self::USER_OAUTH_ID;
        $socialiteUpdatedUser->email = 'new_email';
        $socialiteUpdatedUser->nickname = 'new_nickname';

        Socialite::shouldReceive('driver->user')
            ->andReturn($socialiteUpdatedUser);

        $response = $this->get('/socialite/callback/github', ['driver' => 'github']);

        $this->assertDatabaseHas('users', [
            'oauth_id'       => self::USER_OAUTH_ID,
            'oauth_provider' => self::USER_OAUTH_PROVIDER,
            'name'           => 'new_nickname',
            'email'          => 'new_email',
        ]);
    }

    /**
     * @test
     */
    public function test_callback_updates_username_with_fallback_value()
    {
        $socialiteUpdatedUser = new \Laravel\Socialite\Two\User;
        $socialiteUpdatedUser->id = self::USER_OAUTH_ID;
        $socialiteUpdatedUser->name = 'new_name';
        $socialiteUpdatedUser->email = 'new_email';

        Socialite::shouldReceive('driver->user')
            ->andReturn($socialiteUpdatedUser);

        $response = $this->get('/socialite/callback/github', ['driver' => 'github']);

        $this->assertDatabaseHas('users', [
            'oauth_id'       => self::USER_OAUTH_ID,
            'oauth_provider' => self::USER_OAUTH_PROVIDER,
            'name'           => 'new_name',
            'email'          => 'new_email',
        ]);
    }

    /**
     * @test
     */
    public function test_callback_registers_new_user()
    {
        $newSocialiteUser = new \Laravel\Socialite\Two\User;
        $newSocialiteUser->id = 'new_id';
        $newSocialiteUser->name = 'jane';
        $newSocialiteUser->email = 'jane@provider.com';

        Socialite::shouldReceive('driver->user')
            ->andReturn($newSocialiteUser);

        $response = $this->get('/socialite/callback/github', ['driver' => 'github']);

        $this->assertDatabaseHas('users', [
            'oauth_id'       => 'new_id',
            'oauth_provider' => self::USER_OAUTH_PROVIDER,
            'name'           => 'jane',
            'email'          => 'jane@provider.com',
            'is_admin'       => 0,
        ]);
    }

    /**
     * @test
     */
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

    /**
     * @test
     */
    public function test_callback_returns_error_when_registrations_are_closed()
    {
        Settings::set('disableRegistration', true);

        $newSocialiteUser = new \Laravel\Socialite\Two\User;
        $newSocialiteUser->id = 'rejected_id';
        $newSocialiteUser->name = 'jane';
        $newSocialiteUser->email = 'jane@provider.com';

        Socialite::shouldReceive('driver->user')
            ->andReturn($newSocialiteUser);

        $response = $this->get('/socialite/callback/github', ['driver' => 'github']);

        $response->assertRedirect('/error?err=no_register');
    }

    /**
     * @test
     */
    public function test_callback_skips_registration_when_registrations_are_closed()
    {
        Settings::set('disableRegistration', true);

        $newSocialiteUser = new \Laravel\Socialite\Two\User;
        $newSocialiteUser->id = 'rejected_id';
        $newSocialiteUser->name = 'jane';
        $newSocialiteUser->email = 'jane@provider.com';

        Socialite::shouldReceive('driver->user')
            ->andReturn($newSocialiteUser);

        $response = $this->get('/socialite/callback/github', ['driver' => 'github']);

        $this->assertDatabaseMissing('users', [
            'oauth_id'       => 'rejected_id',
            'oauth_provider' => self::USER_OAUTH_PROVIDER,
        ]);
    }

}
