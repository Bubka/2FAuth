<?php

namespace Tests\Feature\Http\Auth;

use App\Models\User;
use Illuminate\Support\Carbon;
use Tests\FeatureTestCase;

/**
 * @covers  \App\Http\Controllers\Auth\LoginController
 * @covers  \App\Http\Middleware\RejectIfAuthenticated
 * @covers  \App\Http\Middleware\RejectIfReverseProxy
 * @covers  \App\Http\Middleware\RejectIfDemoMode
 * @covers  \App\Http\Middleware\SkipIfAuthenticated
 */
class LoginTest extends FeatureTestCase
{
    /**
     * @var \App\Models\User|\Illuminate\Contracts\Auth\Authenticatable
     */
    protected $user;

    private const PASSWORD = 'password';

    private const WRONG_PASSWORD = 'wrong_password';

    /**
     * @test
     */
    public function setUp() : void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    /**
     * @test
     */
    public function test_user_login_returns_success()
    {
        $response = $this->json('POST', '/user/login', [
            'email'    => $this->user->email,
            'password' => self::PASSWORD,
        ])
            ->assertOk()
            ->assertExactJson([
                'message' => 'authenticated',
                'name'    => $this->user->name,
            ]);
    }

    /**
     * @test
     *
     * @covers  \App\Rules\CaseInsensitiveEmailExists
     */
    public function test_user_login_with_uppercased_email_returns_success()
    {
        $response = $this->json('POST', '/user/login', [
            'email'    => strtoupper($this->user->email),
            'password' => self::PASSWORD,
        ])
            ->assertOk()
            ->assertExactJson([
                'message' => 'authenticated',
                'name'    => $this->user->name,
            ]);
    }

    /**
     * @test
     *
     * @covers  \App\Http\Middleware\SkipIfAuthenticated
     */
    public function test_user_login_already_authenticated_returns_bad_request()
    {
        $response = $this->json('POST', '/user/login', [
            'email'    => $this->user->email,
            'password' => self::PASSWORD,
        ]);

        $response = $this->actingAs($this->user, 'web-guard')
            ->json('POST', '/user/login', [
                'email'    => $this->user->email,
                'password' => self::PASSWORD,
            ])
            ->assertStatus(200)
            ->assertJson([
                'message' => 'authenticated',
                'name'    => $this->user->name,
            ]);
    }

    /**
     * @test
     */
    public function test_user_login_with_missing_data_returns_validation_error()
    {
        $response = $this->json('POST', '/user/login', [
            'email'    => '',
            'password' => '',
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'email',
                'password',
            ]);
    }

    /**
     * @test
     *
     * @covers  \App\Exceptions\Handler
     */
    public function test_user_login_with_invalid_credentials_returns_authentication_error()
    {
        $response = $this->json('POST', '/user/login', [
            'email'    => $this->user->email,
            'password' => self::WRONG_PASSWORD,
        ])
            ->assertStatus(401)
            ->assertJson([
                'message' => 'unauthorised',
            ]);
    }

    /**
     * @test
     */
    public function test_too_many_login_attempts_with_invalid_credentials_returns_too_many_request_error()
    {
        $response = $this->json('POST', '/user/login', [
            'email'    => $this->user->email,
            'password' => self::WRONG_PASSWORD,
        ]);

        $response = $this->json('POST', '/user/login', [
            'email'    => $this->user->email,
            'password' => self::WRONG_PASSWORD,
        ]);

        $response = $this->json('POST', '/user/login', [
            'email'    => $this->user->email,
            'password' => self::WRONG_PASSWORD,
        ]);

        $response = $this->json('POST', '/user/login', [
            'email'    => $this->user->email,
            'password' => self::WRONG_PASSWORD,
        ]);

        $response = $this->json('POST', '/user/login', [
            'email'    => $this->user->email,
            'password' => self::WRONG_PASSWORD,
        ]);

        $response = $this->json('POST', '/user/login', [
            'email'    => $this->user->email,
            'password' => self::WRONG_PASSWORD,
        ]);

        $response->assertStatus(429);
    }

    /**
     * @test
     */
    public function test_user_logout_returns_validation_success()
    {
        $response = $this->json('POST', '/user/login', [
            'email'    => $this->user->email,
            'password' => self::PASSWORD,
        ]);

        $response = $this->actingAs($this->user, 'web-guard')
            ->json('GET', '/user/logout')
            ->assertOk()
            ->assertExactJson([
                'message' => 'signed out',
            ]);
    }

    /**
     * @test
     *
     * @covers  \App\Http\Middleware\KickOutInactiveUser
     * @covers  \App\Http\Middleware\LogUserLastSeen
     */
    public function test_user_logout_after_inactivity_returns_teapot()
    {
        // Set the autolock period to 1 minute
        $this->user['preferences->kickUserAfter'] = 1;
        $this->user->save();

        $response = $this->json('POST', '/user/login', [
            'email'    => $this->user->email,
            'password' => self::PASSWORD,
        ]);

        // Ping a protected endpoint to log last_seen_at time
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('GET', '/api/v1/twofaccounts');

        $this->travelTo(Carbon::now()->addMinutes(2));

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('GET', '/api/v1/twofaccounts')
            ->assertStatus(418);
    }
}
