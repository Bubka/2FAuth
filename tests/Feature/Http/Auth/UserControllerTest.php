<?php

namespace Tests\Feature\Http\Auth;

use App\Models\User;
use Tests\FeatureTestCase;
use Illuminate\Support\Facades\Config;

class UserControllerTest extends FeatureTestCase
{
    /**
     * @var \App\Models\User
    */
    protected $user;

    private const NEW_USERNAME = 'Jane DOE';
    private const NEW_EMAIL = 'janedoe@example.org';
    private const PASSWORD =  'password';

    /**
     * @test
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }
    

    /**
     * @test
     */
    public function test_update_user_returns_success()
    {
        $response = $this->actingAs($this->user, 'web-guard')
            ->json('PUT', '/user', [
                'name' => self::NEW_USERNAME,
                'email' => self::NEW_EMAIL,
                'password' => self::PASSWORD,
            ])
            ->assertOk()
            ->assertExactJson([
                'name' => self::NEW_USERNAME,
                'id'    => $this->user->id,
                'email' => self::NEW_EMAIL,
            ]);
    }
    

    /**
     * @test
     */
    public function test_update_user_in_demo_mode_returns_unchanged_user()
    {
        $settingService = resolve('App\Services\SettingService');
        $settingService->set('isDemoApp', true);

        $response = $this->actingAs($this->user, 'web-guard')
            ->json('PUT', '/user', [
                'name' => self::NEW_USERNAME,
                'email' => self::NEW_EMAIL,
                'password' => self::PASSWORD,
            ])
            ->assertOk()
            ->assertExactJson([
                'name' => $this->user->name,
                'id'    => $this->user->id,
                'email' => $this->user->email,
            ]);
    }
    

    /**
     * @test
     */
    public function test_update_user_passing_wrong_password_returns_bad_request()
    {
        $response = $this->actingAs($this->user, 'web-guard')
            ->json('PUT', '/user', [
                'name' => self::NEW_USERNAME,
                'email' => self::NEW_EMAIL,
                'password' => 'wrongPassword',
            ])
            ->assertStatus(400);
    }
    

    /**
     * @test
     */
    public function test_update_user_with_invalid_data_returns_validation_error()
    {
        $response = $this->actingAs($this->user, 'web-guard')
            ->json('PUT', '/user', [
                'name' => '',
                'email' => '',
                'password' => self::PASSWORD,
            ])
            ->assertStatus(422);
    }
    

    /**
     * @test
     */
    public function test_delete_user_returns_success()
    {
        $response = $this->actingAs($this->user, 'web-guard')
            ->json('DELETE', '/user', [
                'password' => self::PASSWORD,
            ])
            ->assertNoContent();
    }
    

    /**
     * @test
     */
    public function test_delete_user_in_demo_mode_returns_unauthorized()
    {
        Config::set('2fauth.config.isDemoApp', true);

        $settingService = resolve('App\Services\SettingService');
        $settingService->set('isDemoApp', true);

        $response = $this->actingAs($this->user, 'web-guard')
            ->json('DELETE', '/user', [
                'password' => self::PASSWORD,
            ])
            ->assertUnauthorized()
            ->assertJsonStructure([
                'message'
            ]);
    }
    

    /**
     * @test
     */
    public function test_delete_user_passing_wrong_password_returns_bad_request()
    {
        $response = $this->actingAs($this->user, 'web-guard')
            ->json('DELETE', '/user', [
                'password' => 'wrongPassword',
            ])
            ->assertStatus(400);
    }

}