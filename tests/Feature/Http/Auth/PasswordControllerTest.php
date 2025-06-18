<?php

namespace Tests\Feature\Http\Auth;

use App\Http\Controllers\Auth\PasswordController;
use App\Models\User;
use Illuminate\Support\Facades\Config;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;

/**
 * PasswordControllerTest test class
 */
#[CoversClass(PasswordController::class)]
class PasswordControllerTest extends FeatureTestCase
{
    /**
     * @var \App\Models\User|\Illuminate\Contracts\Auth\Authenticatable
     */
    protected $user;

    private const PASSWORD = 'password';

    private const NEW_PASSWORD = 'newPassword';

    private const USER_NAME = 'John';

    private const USER_EMAIL = 'john@example.com';

    private const REVERSE_PROXY_GUARD = 'reverse-proxy-guard';

    protected function setUp() : void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    #[Test]
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

    #[Test]
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

    #[Test]
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

    #[Test]
    public function test_update_pwd_of_reverse_proxy_user_return_bad_request()
    {
        Config::set('auth.auth_proxy_headers.user', 'HTTP_REMOTE_USER');

        $user = User::factory()->create([
            'name'  => self::USER_NAME,
            'email' => self::USER_EMAIL,
        ]);

        $this->app['auth']->shouldUse(self::REVERSE_PROXY_GUARD);

        $response = $this->json('PATCH', '/user/password', [
            'currentPassword'       => self::NEW_PASSWORD,
            'password'              => self::NEW_PASSWORD,
            'password_confirmation' => self::NEW_PASSWORD,
        ], [
            'HTTP_REMOTE_USER' => self::USER_NAME,
        ])
            ->assertStatus(405)
            ->assertJsonStructure([
                'message',
            ]);
    }

    #[Test]
    public function test_update_pwd_of_oauth_user_return_bad_request()
    {
        $this->user = User::factory()->create([
            'name'           => self::USER_NAME,
            'email'          => self::USER_EMAIL,
            'password'       => 'password',
            'is_admin'       => 1,
            'oauth_id'       => '12345',
            'oauth_provider' => 'github',
        ]);

        $this->actingAs($this->user, 'web-guard')
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
}
