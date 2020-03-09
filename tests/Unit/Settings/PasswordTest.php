<?php

namespace Tests\Unit\Settings;

use App\User;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

class PasswordTest extends TestCase
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
     * test User password update with wrong current password via API
     *
     * @test
     */
    public function testPasswordUpdateWithWrongCurrentPassword()
    {        
        $response = $this->actingAs($this->user, 'api')
            ->json('PATCH', '/api/settings/password', [
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
    public function testPasswordUpdate()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('PATCH', '/api/settings/password', [
                'currentPassword' => 'password',
                'password' => 'passwordUpdated',
                'password_confirmation' => 'passwordUpdated',
            ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['message']);

        $this->user->refresh();

        $this->assertTrue(Hash::check('passwordUpdated', $this->user->password));
    }

}
