<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;

class UserTest extends TestCase
{
    /**
     * test User creation via API
     *
     * @return void
     */
    public function testUserCreation()
    {
        $response = $this->json('POST', '/api/register', [
            'name' => 'testCreate',
            'email' => str_random(10) . '@test.com',
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
     * @return void
     */
    public function testUserLogin()
    {
        $response = $this->json('POST', '/api/login', [
            'email' => 'test@test.com',
            'password' => 'test'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success' => ['token']
            ]);
    }


    /**
     * test User logout via API
     *
     * @return void
     */
    public function testUserLogout()
    {
        $user = ['email' => 'test@test.com',
            'password' => 'test'
        ];

        Auth::attempt($user);
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
     * @return void
     */
    public function testGetUserDetails()
    {
        $user = \App\User::find(1);

        $response = $this->actingAs($user, 'api')
            ->json('GET', '/api/user')
            ->assertStatus(200)
            ->assertJsonFragment([
                'id' => 1,
                'name' => 'testLogin',
                'email' => 'test@test.com',
            ]);
    }

}
