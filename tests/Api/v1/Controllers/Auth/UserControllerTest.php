<?php

namespace Tests\Api\v1\Controllers\Auth;

use App\Api\v1\Controllers\UserController;
use App\Api\v1\Resources\UserResource;
use App\Models\User;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;

/**
 * UserControllerTest test class
 */
#[CoversClass(UserController::class)]
#[CoversClass(UserResource::class)]
class UserControllerTest extends FeatureTestCase
{
    /**
     * @var \App\Models\User|\Illuminate\Contracts\Auth\Authenticatable
     */
    protected $user;

    private const PREFERENCE_JSON_STRUCTURE = [
        'key',
        'value',
        'locked',
    ];

    public function setUp() : void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    #[Test]
    public function test_show_existing_user_when_authenticated_returns_success()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('GET', '/api/v1/user')
            ->assertOk()
            ->assertJsonFragment([
                'name'     => $this->user->name,
                'id'       => $this->user->id,
                'email'    => $this->user->email,
                'is_admin' => $this->user->is_admin,
            ])
            ->assertJsonStructure([
                'preferences',
            ]);
    }

    #[Test]
    public function test_allPreferences_returns_consistent_json_structure()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('GET', '/api/v1/user/preferences')
            ->assertOk()
            ->assertJsonStructure([
                '*' => self::PREFERENCE_JSON_STRUCTURE,
            ]);
    }

    #[Test]
    public function test_allPreferences_returns_preferences_with_default_config_values()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('GET', '/api/v1/user/preferences')
            ->assertJsonCount(count(config('2fauth.preferences')), $key = null);

        foreach (config('2fauth.preferences') as $pref => $value) {
            $response->assertJsonFragment([
                'key'   => $pref,
                'value' => $value,
            ]);
        }
    }

    #[Test]
    public function test_allPreferences_returns_preferences_with_user_values()
    {
        $userPrefs = [];

        foreach (config('2fauth.preferences') as $pref => $value) {
            if (is_numeric($value)) {
                $userPrefs[$pref] = $value + 1;
            } elseif (is_string($value)) {
                $userPrefs[$pref] = $value . '_';
            } elseif (is_bool($value)) {
                $userPrefs[$pref] = ! $value;
            }

            $this->user['preferences->' . $pref] = $userPrefs[$pref];
        }

        $this->user->save();

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('GET', '/api/v1/user/preferences')
            ->assertJsonCount(count(config('2fauth.preferences')), $key = null);

        foreach ($userPrefs as $pref => $value) {
            $response->assertJsonFragment([
                'key'   => $pref,
                'value' => $value,
            ]);
        }
    }

    #[Test]
    public function test_showPreference_returns_preference_with_default_config_value()
    {
        /**
         * @var \App\Models\User|\Illuminate\Contracts\Auth\Authenticatable
         */
        $this->user = User::factory()->create();

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('GET', '/api/v1/user/preferences/showOtpAsDot')
            ->assertOk()
            ->assertExactJson([
                'key'   => 'showOtpAsDot',
                'value' => config('2fauth.preferences.showOtpAsDot'),
                'locked' => false,
            ]);
    }

    #[Test]
    public function test_showPreference_returns_preference_with_locked_default_env_value()
    {
        // See .env.testing which sets USERPREF_DEFAULT__THEME=light
        // while config/2fauth.php sets the default value to 'system' 

        /**
         * @var \App\Models\User|\Illuminate\Contracts\Auth\Authenticatable
         */
        $this->user = User::factory()->create();

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('GET', '/api/v1/user/preferences/theme')
            ->assertOk()
            ->assertExactJson([
                'key'   => 'theme',
                'value' => 'light',
                'locked' => true,
            ]);
    }

    #[Test]
    public function test_showPreference_returns_preference_with_custom_value()
    {
        $showOtpAsDot                            = ! config('2fauth.preferences.showOtpAsDot');
        $this->user['preferences->showOtpAsDot'] = $showOtpAsDot;
        $this->user->save();

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('GET', '/api/v1/user/preferences/showOtpAsDot')
            ->assertJsonFragment([
                'key'   => 'showOtpAsDot',
                'value' => $showOtpAsDot,
            ]);
    }

    #[Test]
    public function test_showPreference_for_missing_preference_returns_not_found()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('GET', '/api/v1/user/preferences/unknown')
            ->assertNotFound();
    }

    #[Test]
    public function test_setPreference_returns_updated_preference()
    {
        /**
         * @var \App\Models\User|\Illuminate\Contracts\Auth\Authenticatable
         */
        $this->user = User::factory()->create();

        $showOtpAsDot = ! config('2fauth.preferences.showOtpAsDot');

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('PUT', '/api/v1/user/preferences/showOtpAsDot', [
                'key'   => 'showOtpAsDot',
                'value' => $showOtpAsDot,
            ])
            ->assertCreated()
            ->assertExactJson([
                'key'   => 'showOtpAsDot',
                'value' => $showOtpAsDot,
            ]);
    }

    #[Test]
    public function test_setPreference_for_missing_preference_returns_not_found()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('PUT', '/api/v1/user/preferences/unknown', [
                'key'   => 'showOtpAsDot',
                'value' => true,
            ])
            ->assertNotFound();
    }

    #[Test]
    public function test_setPreference_with_invalid_data_returns_validation_error()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('PUT', '/api/v1/user/preferences/showOtpAsDot', [
                'key'   => 'showOtpAsDot',
                'value' => null,
            ])
            ->assertStatus(422);
    }

    #[Test]
    public function test_setPreference_on_locked_preference_returns_forbidden()
    {
        // See .env.testing which sets USERPREF_LOCKED__THEME=true
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('PUT', '/api/v1/user/preferences/theme', [
                'key'   => 'theme',
                'value' => 'system',
            ])
            ->assertStatus(403);
    }
}
