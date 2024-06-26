<?php

namespace Tests\Api\v1\Controllers;

use App\Api\v1\Controllers\SettingController;
use App\Facades\Settings;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;

/**
 * SettingController test class
 */
#[CoversClass(SettingController::class)]
class SettingControllerTest extends FeatureTestCase
{
    /**
     * @var \App\Models\User|\Illuminate\Contracts\Auth\Authenticatable
     */
    protected $user;

    protected $admin;

    private const SETTING_JSON_STRUCTURE = [
        'key',
        'value',
    ];

    private const TWOFAUTH_NATIVE_SETTING = 'checkForUpdate';

    private const TWOFAUTH_NATIVE_SETTING_DEFAULT_VALUE = true;

    private const TWOFAUTH_NATIVE_SETTING_CHANGED_VALUE = false;

    private const USER_DEFINED_SETTING = 'mySetting';

    private const USER_DEFINED_SETTING_VALUE = 'mySetting';

    private const USER_DEFINED_SETTING_CHANGED_VALUE = 'mySetting';

    public function setUp() : void
    {
        parent::setUp();

        $this->user  = User::factory()->create();
        $this->admin = User::factory()->administrator()->create();
    }

    #[Test]
    public function test_all_controller_routes_are_protected_by_admin_middleware()
    {
        $routes = Route::getRoutes()->getRoutes();

        $controllerRoutes = Arr::where($routes, function (\Illuminate\Routing\Route $route, int $key) {
            if (Str::startsWith($route->getActionName(), SettingController::class)) {
                return $route;
            }
        });

        foreach ($controllerRoutes as $controllerRoute) {
            $this->assertContains('admin', $controllerRoute->middleware());
        }
    }

    #[Test]
    public function test_index_returns_setting_collection()
    {
        $response = $this->actingAs($this->admin, 'api-guard')
            ->json('GET', '/api/v1/settings')
            ->assertOk()
            ->assertJsonStructure([
                '*' => self::SETTING_JSON_STRUCTURE,
            ]);
    }

    #[Test]
    public function test_index_is_forbidden_to_users()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('GET', '/api/v1/settings')
            ->assertForbidden()
            ->assertJsonStructure([
                'message',
            ]);
    }

    #[Test]
    public function test_show_native_unchanged_setting_returns_consistent_value()
    {
        $response = $this->actingAs($this->admin, 'api-guard')
            ->json('GET', '/api/v1/settings/' . self::TWOFAUTH_NATIVE_SETTING)
            ->assertOk()
            ->assertExactJson([
                'key'   => self::TWOFAUTH_NATIVE_SETTING,
                'value' => self::TWOFAUTH_NATIVE_SETTING_DEFAULT_VALUE,
            ]);
    }

    #[Test]
    public function test_show_native_changed_setting_returns_consistent_value()
    {
        Settings::set(self::TWOFAUTH_NATIVE_SETTING, self::TWOFAUTH_NATIVE_SETTING_CHANGED_VALUE);

        $response = $this->actingAs($this->admin, 'api-guard')
            ->json('GET', '/api/v1/settings/' . self::TWOFAUTH_NATIVE_SETTING)
            ->assertOk()
            ->assertExactJson([
                'key'   => self::TWOFAUTH_NATIVE_SETTING,
                'value' => self::TWOFAUTH_NATIVE_SETTING_CHANGED_VALUE,
            ]);
    }

    #[Test]
    public function test_show_custom_user_setting_returns_consistent_value()
    {
        Settings::set(self::USER_DEFINED_SETTING, self::USER_DEFINED_SETTING_VALUE);

        $response = $this->actingAs($this->admin, 'api-guard')
            ->json('GET', '/api/v1/settings/' . self::USER_DEFINED_SETTING)
            ->assertOk()
            ->assertExactJson([
                'key'   => self::USER_DEFINED_SETTING,
                'value' => self::USER_DEFINED_SETTING_VALUE,
            ]);
    }

    #[Test]
    public function test_show_missing_setting_returns_not_found()
    {
        $response = $this->actingAs($this->admin, 'api-guard')
            ->json('GET', '/api/v1/settings/missing')
            ->assertNotFound();
    }

    #[Test]
    public function test_show_setting_is_forbidden_to_users()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('GET', '/api/v1/settings/' . self::TWOFAUTH_NATIVE_SETTING)
            ->assertForbidden()
            ->assertJsonStructure([
                'message',
            ]);
    }

    #[Test]
    public function test_store_custom_user_setting_returns_success()
    {
        $response = $this->actingAs($this->admin, 'api-guard')
            ->json('POST', '/api/v1/settings', [
                'key'   => self::USER_DEFINED_SETTING,
                'value' => self::USER_DEFINED_SETTING_VALUE,
            ])
            ->assertCreated()
            ->assertExactJson([
                'key'   => self::USER_DEFINED_SETTING,
                'value' => self::USER_DEFINED_SETTING_VALUE,
            ]);
    }

    #[Test]
    public function test_store_invalid_custom_user_setting_returns_validation_error()
    {
        $response = $this->actingAs($this->admin, 'api-guard')
            ->json('POST', '/api/v1/settings', [
                'key'   => null,
                'value' => null,
            ])
            ->assertStatus(422);
    }

    #[Test]
    public function test_store_existing_custom_user_setting_returns_validation_error()
    {
        Settings::set(self::USER_DEFINED_SETTING, self::USER_DEFINED_SETTING_VALUE);

        $response = $this->actingAs($this->admin, 'api-guard')
            ->json('POST', '/api/v1/settings', [
                'key'   => self::USER_DEFINED_SETTING,
                'value' => self::USER_DEFINED_SETTING_VALUE,
            ])
            ->assertStatus(422);
    }

    #[Test]
    public function test_update_unchanged_native_setting_returns_updated_setting()
    {
        $response = $this->actingAs($this->admin, 'api-guard')
            ->json('PUT', '/api/v1/settings/' . self::TWOFAUTH_NATIVE_SETTING, [
                'value' => self::TWOFAUTH_NATIVE_SETTING_CHANGED_VALUE,
            ])
            ->assertOk()
            ->assertExactJson([
                'key'   => self::TWOFAUTH_NATIVE_SETTING,
                'value' => self::TWOFAUTH_NATIVE_SETTING_CHANGED_VALUE,
            ]);
    }

    #[Test]
    public function test_update_custom_user_setting_returns_updated_setting()
    {
        Settings::set(self::USER_DEFINED_SETTING, self::USER_DEFINED_SETTING_VALUE);

        $response = $this->actingAs($this->admin, 'api-guard')
            ->json('PUT', '/api/v1/settings/' . self::USER_DEFINED_SETTING, [
                'value' => self::USER_DEFINED_SETTING_CHANGED_VALUE,
            ])
            ->assertOk()
            ->assertExactJson([
                'key'   => self::USER_DEFINED_SETTING,
                'value' => self::USER_DEFINED_SETTING_CHANGED_VALUE,
            ]);
    }

    #[Test]
    public function test_update_missing_user_setting_returns_created_setting()
    {
        $response = $this->actingAs($this->admin, 'api-guard')
            ->json('PUT', '/api/v1/settings/' . self::USER_DEFINED_SETTING, [
                'value' => self::USER_DEFINED_SETTING_CHANGED_VALUE,
            ])
            ->assertOk()
            ->assertExactJson([
                'key'   => self::USER_DEFINED_SETTING,
                'value' => self::USER_DEFINED_SETTING_CHANGED_VALUE,
            ]);
    }

    #[Test]
    public function test_destroy_user_setting_returns_success()
    {
        Settings::set(self::USER_DEFINED_SETTING, self::USER_DEFINED_SETTING_VALUE);

        $response = $this->actingAs($this->admin, 'api-guard')
            ->json('DELETE', '/api/v1/settings/' . self::USER_DEFINED_SETTING)
            ->assertNoContent();
    }

    #[Test]
    public function test_destroy_native_setting_returns_bad_request()
    {
        $response = $this->actingAs($this->admin, 'api-guard')
            ->json('DELETE', '/api/v1/settings/' . self::TWOFAUTH_NATIVE_SETTING)
            ->assertStatus(400)
            ->assertJsonStructure([
                'message',
                'reason',
            ]);
    }

    #[Test]
    public function test_destroy_missing_user_setting_returns_not_found()
    {
        $response = $this->actingAs($this->admin, 'api-guard')
            ->json('DELETE', '/api/v1/settings/' . self::USER_DEFINED_SETTING)
            ->assertNotFound();
    }

    #[Test]
    public function test_destroy_is_forbidden_to_users()
    {
        Settings::set(self::USER_DEFINED_SETTING, self::USER_DEFINED_SETTING_VALUE);

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('DELETE', '/api/v1/settings/' . self::USER_DEFINED_SETTING)
            ->assertForbidden()
            ->assertJsonStructure([
                'message',
            ]);
    }
}
