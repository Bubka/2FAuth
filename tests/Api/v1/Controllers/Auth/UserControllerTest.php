<?php

namespace Tests\Api\v1\Controllers\Auth;

use App\Api\v1\Controllers\UserController;
use App\Api\v1\Resources\UserResource;
use App\Models\User;
use PHPUnit\Framework\Attributes\CoversClass;
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
    ];

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

    /**
     * @test
     */
    public function test_allPreferences_returns_consistent_json_structure()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('GET', '/api/v1/user/preferences')
            ->assertOk()
            ->assertJsonStructure([
                '*' => self::PREFERENCE_JSON_STRUCTURE,
            ]);
    }

    /**
     * @test
     */
    public function test_allPreferences_returns_preferences_with_default_values()
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

    /**
     * @test
     */
    public function test_allPreferences_returns_preferences_with_user_values()
    {
        $userPrefs = [];

        foreach (config('2fauth.preferences') as $pref => $value) {
            if (is_numeric($value)) {
                $userPrefs[$pref] = $value + 1;
            } else if (is_string($value)) {
                $userPrefs[$pref] = $value . '_';
            } else if (is_bool($value)) {
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

    /**
     * @test
     */
    public function test_showPreference_returns_preference_with_default_value()
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
            ]);
    }

    /**
     * @test
     */
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

    /**
     * @test
     */
    public function test_showPreference_for_missing_preference_returns_not_found()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('GET', '/api/v1/user/preferences/unknown')
            ->assertNotFound();
    }

    /**
     * @test
     */
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

    /**
     * @test
     */
    public function test_setPreference_for_missing_preference_returns_not_found()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('PUT', '/api/v1/user/preferences/unknown', [
                'key'   => 'showOtpAsDot',
                'value' => true,
            ])
            ->assertNotFound();
    }

    /**
     * @test
     */
    public function test_setPreference_with_invalid_data_returns_validation_error()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('PUT', '/api/v1/user/preferences/showOtpAsDot', [
                'key'   => 'showOtpAsDot',
                'value' => null,
            ])
            ->assertStatus(422);
    }
}
