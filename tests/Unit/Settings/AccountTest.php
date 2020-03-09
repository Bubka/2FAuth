<?php

namespace Tests\Unit\Settings;

use App\User;
use Tests\TestCase;

class AccountTest extends TestCase
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
     * test Get user infos via API
     *
     * @test
     */
    public function testGetUserDetails()
    {
        $user = User::find(1);

        $response = $this->actingAs($user, 'api')
            ->json('GET', '/api/settings/account')
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
            ->json('PATCH', '/api/settings/account', [
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
            ->json('PATCH', '/api/settings/account', [
                'name' => 'userUpdated',
                'email' => 'userUpdated@example.org',
                'password' => 'password',
            ]);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'username' => 'userUpdated'
            ]);
    }

}