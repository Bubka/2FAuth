<?php

namespace Tests\Feature\Http\Auth;

use App\Models\User;
use Tests\FeatureTestCase;

/**
 * @covers  \App\Http\Controllers\Auth\PasswordController
 */
class PasswordControllerTest extends FeatureTestCase
{
    /**
     * @var \App\Models\User|\Illuminate\Contracts\Auth\Authenticatable
     */
    protected $user;

    private const PASSWORD = 'password';

    private const NEW_PASSWORD = 'newPassword';

    /**
     * @test
     */
    public function setUp() : void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    /**
     * @test
     */
    public function test_update_return_success()
    {
        $response = $this->actingAs($this->user, 'web-guard')
            ->json('PATCH', '/user/password', [
                'currentPassword'       => self::PASSWORD,
                'password'              => self::NEW_PASSWORD,
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
        $response = $this->actingAs($this->user, 'web-guard')
            ->json('PATCH', '/user/password', [
                'currentPassword'       => self::NEW_PASSWORD,
                'password'              => self::NEW_PASSWORD,
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
        $response = $this->actingAs($this->user, 'web-guard')
            ->json('PATCH', '/user/password', [
                'currentPassword'       => self::PASSWORD,
                'password'              => null,
                'password_confirmation' => self::NEW_PASSWORD,
            ])
            ->assertStatus(422);
    }
}
