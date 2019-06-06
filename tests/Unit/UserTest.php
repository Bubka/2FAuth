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
     * test User creation via API
     *
     * @test
     */
    public function testUserCreation()
    {
        $response = $this->json('POST', '/api/register', [
            'name' => 'testCreate',
            'email' => 'testCreate@example.org',
            'password' => 'test',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success' => ['token', 'name']
            ]);
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
                'success' => ['token']
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
                'success' => 'signed out',
            ]);
    }


    /**
     * test User logout via API
     *
     * @test
     */
    public function testGetUserDetails()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('GET', '/api/user')
            ->assertStatus(200)
            ->assertJsonStructure(['id', 'name', 'email']);
    }

}
