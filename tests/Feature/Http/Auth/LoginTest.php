<?php

namespace Tests\Feature\Http\Auth;

use App\Models\User;
use Tests\FeatureTestCase;

class LoginTest extends FeatureTestCase
{
    /**
     * @var \App\Models\User
     */
    protected $user;

    private const PASSWORD = 'password';
    private const WRONG_PASSWORD = 'wrong_password';

    /**
     * @test
     */
    public function setUp(): void
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
            'email' => $this->user->email,
            'password' => self::PASSWORD
        ])
        ->assertOk()
        ->assertExactJson([
            'message' => 'authenticated',
            'name' => $this->user->name,
        ]);
    }


    /**
     * @test
     */
    public function test_user_login_already_authenticated_returns_bad_request()
    {
        $response = $this->json('POST', '/user/login', [
            'email' => $this->user->email,
            'password' => self::PASSWORD
        ]);

        $response = $this->actingAs($this->user, 'web-guard')
            ->json('POST', '/user/login', [
                'email' => $this->user->email,
                'password' => self::PASSWORD
            ])
            ->assertStatus(200)
            ->assertJson([
                'message' => 'authenticated',
                'name' => $this->user->name,
            ]);
    }


    /**
     * @test
     */
    public function test_user_login_with_missing_data_returns_validation_error()
    {
        $response = $this->json('POST', '/user/login', [
            'email' => '',
            'password' => ''
        ])
        ->assertStatus(422)
        ->assertJsonValidationErrors([
            'email',
            'password'
        ]);
    }


    /**
     * @test
     */
    public function test_user_login_with_invalid_credentials_returns_validation_error()
    {
        $response = $this->json('POST', '/user/login', [
            'email' => $this->user->email,
            'password' => self::WRONG_PASSWORD
        ])
        ->assertStatus(401)
        ->assertJson([
            'message' => 'unauthorised'
        ]);
    }


    /**
     * @test
     */
    public function test_too_many_login_attempts_with_invalid_credentials_returns_too_many_request_error()
    {
        $response = $this->json('POST', '/user/login', [
            'email' => $this->user->email,
            'password' => self::WRONG_PASSWORD
        ]);

        $response = $this->json('POST', '/user/login', [
            'email' => $this->user->email,
            'password' => self::WRONG_PASSWORD
        ]);

        $response = $this->json('POST', '/user/login', [
            'email' => $this->user->email,
            'password' => self::WRONG_PASSWORD
        ]);

        $response = $this->json('POST', '/user/login', [
            'email' => $this->user->email,
            'password' => self::WRONG_PASSWORD
        ]);

        $response = $this->json('POST', '/user/login', [
            'email' => $this->user->email,
            'password' => self::WRONG_PASSWORD
        ]);

        $response = $this->json('POST', '/user/login', [
            'email' => $this->user->email,
            'password' => self::WRONG_PASSWORD
        ]);

        $response->assertStatus(429);
    }


    /**
     * @test
     */
    public function test_user_logout_returns_validation_success()
    {
        $response = $this->json('POST', '/user/login', [
            'email' => $this->user->email,
            'password' => self::PASSWORD
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
     */
    public function test_user_logout_after_inactivity_returns_teapot()
    {
        // Set the autolock period to 1 minute
        $settingService = resolve('App\Services\SettingService');
        $settingService->set('kickUserAfter', 1);

        $response = $this->json('POST', '/user/login', [
            'email' => $this->user->email,
            'password' => self::PASSWORD
        ]);

        // Ping a protected endpoint to log last_seen_at time
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('GET', '/api/v1/twofaccounts');

        sleep(61);

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('GET', '/api/v1/twofaccounts')
            ->assertStatus(418);
    }

}