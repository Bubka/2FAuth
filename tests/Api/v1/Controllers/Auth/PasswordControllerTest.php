<?php

namespace Tests\Api\v1\Controllers\Auth;

use App\User;
use App\Group;
use Tests\FeatureTestCase;
use App\TwoFAccount;

class PasswordControllerTest extends FeatureTestCase
{
    /**
     * @var \App\User
    */
    protected $user;

    private const PASSWORD =  'password';
    private const NEW_PASSWORD =  'newPassword';

    /**
     * @test
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }


    /**
     * @test
     */
    public function test_update_return_success()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('PATCH', '/api/v1/user/password', [
                'currentPassword' => self::PASSWORD,
                'password' => self::NEW_PASSWORD,
                'password_confirmation' => self::NEW_PASSWORD,
            ])
            ->assertOk()
            ->assertJsonStructure([
                'message',
            ]);
    }


    /**
     * @test
     */
    public function test_update_passing_bad_current_pwd_return_bad_request()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('PATCH', '/api/v1/user/password', [
                'currentPassword' => self::NEW_PASSWORD,
                'password' => self::NEW_PASSWORD,
                'password_confirmation' => self::NEW_PASSWORD,
            ])
            ->assertStatus(400)
            ->assertJsonStructure([
                'message',
            ]);
    }


    /**
     * @test
     */
    public function test_update_passing_invalid_data_return_validation_error()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('PATCH', '/api/v1/user/password', [
                'currentPassword' => self::PASSWORD,
                'password' => null,
                'password_confirmation' => self::NEW_PASSWORD,
            ])
            ->assertStatus(422);
    }

}