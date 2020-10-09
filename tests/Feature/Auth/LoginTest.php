<?php

namespace Tests\Feature\Auth;

use App\User;
use Tests\TestCase;
use Illuminate\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\RequestGuard;
use Illuminate\Support\Facades\Config;

class LoginTest extends TestCase
{
    /** @var \App\User */
    protected $user;


    /**
     * @test
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }


    /**
     * test User login via API
     *
     * @test
     */
    public function testUserLogin()
    {

        $response = $this->json('POST', '/api/login', [
            'email' => $this->user->email,
            'password' => 'password'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message' => ['token']
            ]);
    }


    /**
     * test User login via API
     *
     * @test
     */
    public function testUserLoginAlreadyAuthenticated()
    {

        $response = $this->json('POST', '/api/login', [
            'email' => $this->user->email,
            'password' => 'password'
        ]);

        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/login', [
                'email' => $this->user->email,
                'password' => 'password'
            ]);

        $response->assertStatus(400)
            ->assertJson([
                'message' => __('auth.already_authenticated')
            ]);
    }


    /**
     * test User login with missing values via API
     *
     * @test
     */
    public function testUserLoginWithMissingValues()
    {
        $response = $this->json('POST', '/api/login', [
            'email' => '',
            'password' => ''
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'email',
                'password'
            ]);
    }


    /**
     * test User login with invalid credentials via API
     *
     * @test
     */
    public function testUserLoginWithInvalidCredential()
    {
        $response = $this->json('POST', '/api/login', [
            'email' => $this->user->email,
            'password' => 'badPassword'
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'unauthorised'
            ]);
    }


    /**
     * test User login with invalid credentials via API
     *
     * @test
     */
    public function testTooManyAttempsWithInvalidCredential()
    {
        $response = $this->json('POST', '/api/login', [
            'email' => $this->user->email,
            'password' => 'badPassword'
        ]);

        $response = $this->json('POST', '/api/login', [
            'email' => $this->user->email,
            'password' => 'badPassword'
        ]);

        $response = $this->json('POST', '/api/login', [
            'email' => $this->user->email,
            'password' => 'badPassword'
        ]);

        $response = $this->json('POST', '/api/login', [
            'email' => $this->user->email,
            'password' => 'badPassword'
        ]);

        $response = $this->json('POST', '/api/login', [
            'email' => $this->user->email,
            'password' => 'badPassword'
        ]);

        $response = $this->json('POST', '/api/login', [
            'email' => $this->user->email,
            'password' => 'badPassword'
        ]);

        $response->assertStatus(429);
    }


    /**
     * test User logout via API
     *
     * @test
     */
    public function testUserLogout()
    {
        $response = $this->json('POST', '/api/login', [
            'email' => $this->user->email,
            'password' => 'password'
        ]);

        $headers = ['Authorization' => "Bearer " . $response->original['message']['token']];

        $response = $this->json('POST', '/api/logout', [], $headers)
            ->assertStatus(200)
            ->assertJson([
                'message' => 'signed out',
            ]);
    }


    /**
     * test User logout after inactivity via API
     *
     * @test
     */
    public function testUserLogoutAfterInactivity()
    {
        // Set the autolock period to 1 minute
        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/settings/options', [
                    'kickUserAfter' => '1'])
            ->assertStatus(200);

        sleep(61);

        // Ping a restricted endpoint to log last_seen_at time
        $response = $this->actingAs($this->user, 'api')
            ->json('GET', '/api/settings/account')
            ->assertStatus(401);
    }

}