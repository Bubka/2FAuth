<?php

namespace Tests\Api\v1\Controllers;

use App\User;
use App\Group;
use Tests\FeatureTestCase;
use App\TwoFAccount;


/**
 * @covers \App\Api\v1\Controllers\SettingController
 */
class SettingControllerTest extends FeatureTestCase
{
    /**
     * @var \App\User
    */
    protected $user;

    private const SETTING_JSON_STRUCTURE = [
        'key',
        'value'
    ];
    private const TWOFAUTH_NATIVE_SETTING = 'showTokenAsDot';
    private const TWOFAUTH_NATIVE_SETTING_DEFAULT_VALUE = false;
    private const TWOFAUTH_NATIVE_SETTING_CHANGED_VALUE = true;
    private const USER_DEFINED_SETTING = 'mySetting';
    private const USER_DEFINED_SETTING_VALUE = 'mySetting';
    private const USER_DEFINED_SETTING_CHANGED_VALUE = 'mySetting';

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
    public function test_index_returns_setting_collection()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('GET', '/api/v1/settings')
            ->assertOk()
            ->assertJsonStructure([
                '*' => self::SETTING_JSON_STRUCTURE
            ]);
    }


    /**
     * @test
     */
    public function test_show_native_unchanged_setting_returns_consistent_value()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('GET', '/api/v1/settings/' . self::TWOFAUTH_NATIVE_SETTING)
            ->assertOk()
            ->assertExactJson([
                'key' => self::TWOFAUTH_NATIVE_SETTING,
                'value' => self::TWOFAUTH_NATIVE_SETTING_DEFAULT_VALUE,
            ]);
    }


    /**
     * @test
     */
    public function test_show_native_changed_setting_returns_consistent_value()
    {
        $settingService = resolve('App\Services\SettingServiceInterface');
        $settingService->set(self::TWOFAUTH_NATIVE_SETTING, self::TWOFAUTH_NATIVE_SETTING_CHANGED_VALUE);

        $response = $this->actingAs($this->user, 'api')
            ->json('GET', '/api/v1/settings/' . self::TWOFAUTH_NATIVE_SETTING)
            ->assertOk()
            ->assertExactJson([
                'key' => self::TWOFAUTH_NATIVE_SETTING,
                'value' => self::TWOFAUTH_NATIVE_SETTING_CHANGED_VALUE,
            ]);
    }


    /**
     * @test
     */
    public function test_show_custom_user_setting_returns_consistent_value()
    {
        $settingService = resolve('App\Services\SettingServiceInterface');
        $settingService->set(self::USER_DEFINED_SETTING, self::USER_DEFINED_SETTING_VALUE);

        $response = $this->actingAs($this->user, 'api')
            ->json('GET', '/api/v1/settings/' . self::USER_DEFINED_SETTING)
            ->assertOk()
            ->assertExactJson([
                'key' => self::USER_DEFINED_SETTING,
                'value' => self::USER_DEFINED_SETTING_VALUE,
            ]);
    }


    /**
     * @test
     */
    public function test_show_missing_setting_returns_not_found()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('GET', '/api/v1/settings/missing')
            ->assertNotFound();
    }


    /**
     * @test
     */
    public function test_store_custom_user_setting_returns_success()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/v1/settings', [
                'key' => self::USER_DEFINED_SETTING,
                'value' => self::USER_DEFINED_SETTING_VALUE,
            ])
            ->assertCreated()
            ->assertExactJson([
                'key' => self::USER_DEFINED_SETTING,
                'value' => self::USER_DEFINED_SETTING_VALUE,
            ]);
    }


    /**
     * @test
     */
    public function test_store_invalid_custom_user_setting_returns_validation_error()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/v1/settings', [
                'key' => null,
                'value' => null,
            ])
            ->assertStatus(422);
    }


    /**
     * @test
     */
    public function test_store_existing_custom_user_setting_returns_validation_error()
    {
        $settingService = resolve('App\Services\SettingServiceInterface');
        $settingService->set(self::USER_DEFINED_SETTING, self::USER_DEFINED_SETTING_VALUE);

        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/v1/settings', [
                'key' => self::USER_DEFINED_SETTING,
                'value' => self::USER_DEFINED_SETTING_VALUE,
            ])
            ->assertStatus(422);
    }


    /**
     * @test
     */
    public function test_update_unchanged_native_setting_returns_updated_setting()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('PUT', '/api/v1/settings/' . self::TWOFAUTH_NATIVE_SETTING, [
                'key' => self::TWOFAUTH_NATIVE_SETTING,
                'value' => self::TWOFAUTH_NATIVE_SETTING_CHANGED_VALUE,
            ])
            ->assertOk()
            ->assertExactJson([
                'key' => self::TWOFAUTH_NATIVE_SETTING,
                'value' => self::TWOFAUTH_NATIVE_SETTING_CHANGED_VALUE,
            ]);
    }


    /**
     * @test
     */
    public function test_update_custom_user_setting_returns_updated_setting()
    {
        $settingService = resolve('App\Services\SettingServiceInterface');
        $settingService->set(self::USER_DEFINED_SETTING, self::USER_DEFINED_SETTING_VALUE);

        $response = $this->actingAs($this->user, 'api')
            ->json('PUT', '/api/v1/settings/' . self::USER_DEFINED_SETTING, [
                'key' => self::USER_DEFINED_SETTING,
                'value' => self::USER_DEFINED_SETTING_CHANGED_VALUE,
            ])
            ->assertOk()
            ->assertExactJson([
                'key' => self::USER_DEFINED_SETTING,
                'value' => self::USER_DEFINED_SETTING_CHANGED_VALUE,
            ]);
    }


    /**
     * @test
     */
    public function test_update_missing_user_setting_returns_created_setting()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('PUT', '/api/v1/settings/' . self::USER_DEFINED_SETTING, [
                'key' => self::USER_DEFINED_SETTING,
                'value' => self::USER_DEFINED_SETTING_CHANGED_VALUE,
            ])
            ->assertOk()
            ->assertExactJson([
                'key' => self::USER_DEFINED_SETTING,
                'value' => self::USER_DEFINED_SETTING_CHANGED_VALUE,
            ]);
    }


    /**
     * @test
     */
    public function test_destroy_user_setting_returns_success()
    {
        $settingService = resolve('App\Services\SettingServiceInterface');
        $settingService->set(self::USER_DEFINED_SETTING, self::USER_DEFINED_SETTING_VALUE);

        $response = $this->actingAs($this->user, 'api')
            ->json('DELETE', '/api/v1/settings/' . self::USER_DEFINED_SETTING)
            ->assertNoContent();
    }


    /**
     * @test
     */
    public function test_destroy_native_setting_returns_bad_request()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('DELETE', '/api/v1/settings/' . self::TWOFAUTH_NATIVE_SETTING)
            ->assertStatus(400)
            ->assertJsonStructure([
                'message',
                'reason',
            ]);
    }


    /**
     * @test
     */
    public function test_destroy_missing_user_setting_returns_not_found()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('DELETE', '/api/v1/settings/' . self::USER_DEFINED_SETTING)
            ->assertNotFound();
    }


}