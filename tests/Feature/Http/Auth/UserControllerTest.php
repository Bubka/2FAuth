<?php

namespace Tests\Feature\Http\Auth;

use App\Http\Controllers\Auth\UserController;
use App\Http\Middleware\RejectIfDemoMode;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use App\Observers\UserObserver;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Config;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;

/**
 * UserControllerTest test class
 */
#[CoversClass(UserController::class)]
#[CoversClass(UserObserver::class)]
#[CoversClass(UserPolicy::class)]
#[CoversClass(RejectIfDemoMode::class)]
#[CoversClass(UserUpdateRequest::class)]
class UserControllerTest extends FeatureTestCase
{
    /**
     * @var \App\Models\User|\Illuminate\Contracts\Auth\Authenticatable
     */
    protected $user;

    private const NEW_USERNAME = 'Jane DOE';

    private const NEW_EMAIL = 'janedoe@example.org';

    private const PASSWORD = 'password';

    protected function setUp() : void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    #[Test]
    public function test_update_user_returns_success()
    {
        $response = $this->actingAs($this->user, 'web-guard')
            ->json('PUT', '/user', [
                'name'     => self::NEW_USERNAME,
                'email'    => self::NEW_EMAIL,
                'password' => self::PASSWORD,
            ])
            ->assertOk()
            ->assertJsonFragment([
                'name'     => self::NEW_USERNAME,
                'id'       => $this->user->id,
                'email'    => self::NEW_EMAIL,
                'is_admin' => false,
            ])
            ->assertJsonStructure([
                'preferences',
            ]);

        $this->assertDatabaseHas('users', [
            'name'     => self::NEW_USERNAME,
            'id'       => $this->user->id,
            'email'    => self::NEW_EMAIL,
            'is_admin' => false,
        ]);
    }

    #[Test]
    public function test_update_user_without_changing_email_returns_success()
    {
        $response = $this->actingAs($this->user, 'web-guard')
            ->json('PUT', '/user', [
                'name'     => self::NEW_USERNAME,
                'email'    => $this->user->email,
                'password' => self::PASSWORD,
            ])
            ->assertOk()
            ->assertJsonFragment([
                'name'     => self::NEW_USERNAME,
                'id'       => $this->user->id,
                'email'    => $this->user->email,
                'is_admin' => false,
            ]);

        $this->assertDatabaseHas('users', [
            'name'     => self::NEW_USERNAME,
            'id'       => $this->user->id,
            'email'    => $this->user->email,
            'is_admin' => false,
        ]);
    }

    #[Test]
    public function test_update_user_without_changing_name_returns_success()
    {
        $response = $this->actingAs($this->user, 'web-guard')
            ->json('PUT', '/user', [
                'name'     => $this->user->name,
                'email'    => self::NEW_EMAIL,
                'password' => self::PASSWORD,
            ])
            ->assertOk()
            ->assertJsonFragment([
                'name'     => $this->user->name,
                'id'       => $this->user->id,
                'email'    => self::NEW_EMAIL,
                'is_admin' => false,
            ]);

        $this->assertDatabaseHas('users', [
            'name'     => $this->user->name,
            'id'       => $this->user->id,
            'email'    => self::NEW_EMAIL,
            'is_admin' => false,
        ]);
    }

    #[Test]
    public function test_update_user_with_uppercased_email_returns_success()
    {
        $response = $this->actingAs($this->user, 'web-guard')
            ->json('PUT', '/user', [
                'name'     => self::NEW_USERNAME,
                'email'    => strtoupper(self::NEW_EMAIL),
                'password' => self::PASSWORD,
            ])
            ->assertOk()
            ->assertJsonFragment([
                'name'     => self::NEW_USERNAME,
                'id'       => $this->user->id,
                'email'    => self::NEW_EMAIL,
                'is_admin' => false,
            ]);

        $this->assertDatabaseHas('users', [
            'name'     => self::NEW_USERNAME,
            'id'       => $this->user->id,
            'email'    => self::NEW_EMAIL,
            'is_admin' => false,
        ]);
    }

    #[Test]
    public function test_update_user_in_demo_mode_returns_unchanged_user()
    {
        Config::set('2fauth.config.isDemoApp', true);

        $name  = $this->user->name;
        $email = $this->user->email;

        $response = $this->actingAs($this->user, 'web-guard')
            ->json('PUT', '/user', [
                'name'     => self::NEW_USERNAME,
                'email'    => self::NEW_EMAIL,
                'password' => self::PASSWORD,
            ])
            ->assertOk()
            ->assertJsonFragment([
                'name'     => $name,
                'id'       => $this->user->id,
                'email'    => $email,
                'is_admin' => $this->user->is_admin,
            ]);

        $this->assertDatabaseHas('users', [
            'name'  => $name,
            'id'    => $this->user->id,
            'email' => $email,
        ]);
    }

    #[Test]
    public function test_update_user_passing_wrong_password_returns_bad_request()
    {
        $response = $this->actingAs($this->user, 'web-guard')
            ->json('PUT', '/user', [
                'name'     => self::NEW_USERNAME,
                'email'    => self::NEW_EMAIL,
                'password' => 'wrongPassword',
            ])
            ->assertStatus(400);
    }

    #[Test]
    public function test_update_user_with_invalid_data_returns_validation_error()
    {
        $response = $this->actingAs($this->user, 'web-guard')
            ->json('PUT', '/user', [
                'name'     => '',
                'email'    => '',
                'password' => self::PASSWORD,
            ])
            ->assertStatus(422);
    }

    #[Test]
    public function test_delete_user_returns_success()
    {
        $this->actingAs($this->user, 'web-guard')
            ->json('DELETE', '/user', [
                'password' => self::PASSWORD,
            ])
            ->assertNoContent();
    }

    #[Test]
    public function test_delete_user_in_demo_mode_returns_unauthorized()
    {
        Config::set('2fauth.config.isDemoApp', true);

        $response = $this->actingAs($this->user, 'web-guard')
            ->json('DELETE', '/user', [
                'password' => self::PASSWORD,
            ])
            ->assertUnauthorized()
            ->assertJsonStructure([
                'message',
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
        ]);
    }

    #[Test]
    public function test_delete_user_passing_wrong_password_returns_bad_request()
    {
        $response = $this->actingAs($this->user, 'web-guard')
            ->json('DELETE', '/user', [
                'password' => 'wrongPassword',
            ])
            ->assertStatus(400);

        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
        ]);
    }

    #[Test]
    public function test_delete_the_only_admin_returns_bad_request()
    {
        /**
         * @var \App\Models\User|\Illuminate\Contracts\Auth\Authenticatable
         */
        $admin = User::factory()->administrator()->create();

        $this->assertDatabaseCount('users', 2);
        $this->assertEquals(1, User::admins()->count());

        $response = $this->actingAs($admin, 'web-guard')
            ->json('DELETE', '/user', [
                'password' => self::PASSWORD,
            ])
            ->assertStatus(400);

        $this->assertDatabaseHas('users', [
            'id' => $admin->id,
        ]);
    }
}
