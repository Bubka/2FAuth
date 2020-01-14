<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;
use Illuminate\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;

class UserTest extends TestCase
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
     * test creation of another user via API
     *
     * @test
     */
    public function testMoreThanOneUserCreation()
    {
        $response = $this->json('POST', '/api/register', [
            'name' => 'testCreate',
            'email' => 'testCreate@example.org',
            'password' => 'test',
            'password_confirmation' => 'test',
        ]);

        $response->assertStatus(400);
    }


    /**
     * test User creation with missing values via API
     *
     * @test
     */
    public function testUserCreationWithMissingValues()
    {
        $response = $this->json('POST', '/api/register', [
            'name' => '',
            'email' => '',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertStatus(400);
    }


    /**
     * test User creation with invalid values via API
     *
     * @test
     */
    public function testUserCreationWithInvalidData()
    {
        $response = $this->json('POST', '/api/register', [
            'name' => 'testInvalid',
            'email' => 'email',
            'password' => 'test',
            'password_confirmation' => 'tset',
        ]);

        $response->assertStatus(400);
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

        $response->assertStatus(400);
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
     * test User logout via API
     *
     * @test
     */
    public function testUserLogout()
    {
        $credentials = [
            'email' => $this->user->email,
            'password' => 'password'
        ];

        Auth::attempt($credentials);
        $token = Auth::user()->createToken('testToken')->accessToken;
        $headers = ['Authorization' => "Bearer $token"];

        $response = $this->json('POST', '/api/logout', [], $headers)
            ->assertStatus(200)
            ->assertJson([
                'message' => 'signed out',
            ]);
    }


    /**
     * test User logout via API
     *
     * @test
     */
    public function testGetUserDetails()
    {
        $user = User::find(1);

        $response = $this->actingAs($user, 'api')
            ->json('GET', '/api/user')
            ->assertStatus(200)
            ->assertJsonStructure(['id', 'name', 'email']);
    }


    /**
     * test User creation via API
     *
     * @test
     */
    public function testUserCreation()
    {

        // we delete the existing user
        User::destroy(1);

        $response = $this->json('POST', '/api/register', [
            'name' => 'newUser',
            'email' => 'newUser@example.org',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message' => ['token', 'name']
            ]);
    }

}
