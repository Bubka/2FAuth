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
     * test Existing user count via API
     *
     * @test
     */
    public function testExistingUserCount()
    {
        $response = $this->json('POST', '/api/checkuser')
            ->assertStatus(200)
            ->assertJson([
                'userCount' => '1',
            ]);
    }


    /**
     * test creation of another user via API
     *
     * @test
     */
    public function testUserCreationWithAnExistingUser()
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
        // we delete the existing user
        User::destroy(1);

        $response = $this->json('POST', '/api/register', [
            'name' => '',
            'email' => '',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertStatus(422);
    }


    /**
     * test User creation with invalid values via API
     *
     * @test
     */
    public function testUserCreationWithInvalidData()
    {
        // we delete the existing user
        User::destroy(1);

        $response = $this->json('POST', '/api/register', [
            'name' => 'testInvalid',
            'email' => 'email',
            'password' => 'test',
            'password_confirmation' => 'tset',
        ]);

        $response->assertStatus(422);
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

        $response->assertStatus(422);
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
            ->assertJsonStructure(['name', 'email']);
    }


    /**
     * test User update with wrong current password via API
     *
     * @test
     */
    public function testUserUpdateWithWrongCurrentPassword()
    {
        $user = User::find(1);

        $response = $this->actingAs($user, 'api')
            ->json('PATCH', '/api/user', [
                'name' => 'userUpdated',
                'email' => 'userUpdated@example.org',
                'password' => 'wrongPassword',
            ]);

        $response->assertStatus(400)
            ->assertJsonStructure(['message']);
    }


    /**
     * test User update via API
     *
     * @test
     */
    public function testUserUpdate()
    {
        $user = User::find(1);

        $response = $this->actingAs($user, 'api')
            ->json('PATCH', '/api/user', [
                'name' => 'userUpdated',
                'email' => 'userUpdated@example.org',
                'password' => 'password',
            ]);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'username' => 'userUpdated'
            ]);
    }


    /**
     * test User password update with wrong current password via API
     *
     * @test
     */
    public function testUserPasswordUpdateWithWrongCurrentPassword()
    {
        $user = User::find(1);
        
        $response = $this->actingAs($user, 'api')
            ->json('PATCH', '/api/password', [
                'currentPassword' => 'wrongPassword',
                'password' => 'passwordUpdated',
                'password_confirmation' => 'passwordUpdated',
            ]);

        $response->assertStatus(400)
            ->assertJsonStructure(['message']);
    }


    /**
     * test User password update via API
     *
     * @test
     */
    public function testUserPasswordUpdate()
    {
        $user = User::find(1);
        
        $response = $this->actingAs($user, 'api')
            ->json('PATCH', '/api/password', [
                'currentPassword' => 'password',
                'password' => 'passwordUpdated',
                'password_confirmation' => 'passwordUpdated',
            ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['message']);
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
