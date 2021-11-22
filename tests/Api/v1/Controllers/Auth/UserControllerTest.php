<?php

namespace Tests\Api\v1\Controllers\Auth;

use App\User;
use Tests\FeatureTestCase;

class UserControllerTest extends FeatureTestCase
{
    /**
     * @var \App\User
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

        $this->user = factory(User::class)->create();
    }


    /**
     * @test
     */
    public function test_show_existing_user_when_authenticated_returns_success()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('GET', '/api/v1/user')
            ->assertOk()
            ->assertExactJson([
                'name'  => $this->user->name,
                'email' => $this->user->email,
            ]);
    }


    /**
     * @test
     */
    public function test_show_existing_user_when_anonymous_returns_success()
    {
        $response = $this->json('GET', '/api/v1/user/name')
            ->assertOk()
            ->assertExactJson([
                'name'  => $this->user->name,
            ]);
    }


    /**
     * @test
     */
    public function test_show_missing_user_returns_success_with_null_name()
    {
        User::destroy($this->user->id);

        $response = $this->actingAs($this->user, 'api')
            ->json('GET', '/api/v1/user')
            ->assertOk()
            ->assertExactJson([
                'name'  => null,
            ]);
    }
    

    /**
     * @test
     */
    public function test_update_user_returns_success()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('PUT', '/api/v1/user', [
                'name' => self::NEW_USERNAME,
                'email' => self::NEW_EMAIL,
                'password' => self::PASSWORD,
            ])
            ->assertOk()
            ->assertExactJson([
                'name' => self::NEW_USERNAME,
                'email' => self::NEW_EMAIL,
            ]);
    }
    

    /**
     * @test
     */
    public function test_update_user_in_demo_mode_returns_unchanged_user()
    {
        $settingService = resolve('App\Services\SettingServiceInterface');
        $settingService->set('isDemoApp', true);

        $response = $this->actingAs($this->user, 'api')
            ->json('PUT', '/api/v1/user', [
                'name' => self::NEW_USERNAME,
                'email' => self::NEW_EMAIL,
                'password' => self::PASSWORD,
            ])
            ->assertOk()
            ->assertExactJson([
                'name' => $this->user->name,
                'email' => $this->user->email,
            ]);
    }
    

    /**
     * @test
     */
    public function test_update_user_passing_wrong_password_returns_bad_request()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('PUT', '/api/v1/user', [
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
        $response = $this->actingAs($this->user, 'api')
            ->json('PUT', '/api/v1/user', [
                'name' => '',
                'email' => '',
                'password' => self::PASSWORD,
            ])
            ->assertStatus(422);
    }

}