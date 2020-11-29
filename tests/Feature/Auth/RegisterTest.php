<?php

namespace Tests\Feature\Auth;

use App\User;
use Tests\TestCase;

class RegisterTest extends TestCase
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
                'username' => $this->user->name,
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

        $response->assertStatus(422);
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
