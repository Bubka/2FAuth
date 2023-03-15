<?php

namespace Tests\Api\v1\Controllers\Auth;

use App\Models\User;
use Tests\FeatureTestCase;

/**
 * @covers \App\Api\v1\Controllers\UserController
 * @covers \App\Api\v1\Resources\UserResource
 */
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
            ->assertExactJson([
                'name'     => $this->user->name,
                'id'       => $this->user->id,
                'email'    => $this->user->email,
                'is_admin' => $this->user->is_admin,
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
        $userPrefs = [
            'showTokenAsDot'       => true,
            'closeOtpOnCopy'       => true,
            'copyOtpOnDisplay'     => true,
            'useBasicQrcodeReader' => true,
            'displayMode'          => 'grid',
            'showAccountsIcons'    => false,
            'kickUserAfter'        => 5,
            'activeGroup'          => 1,
            'rememberActiveGroup'  => false,
            'defaultGroup'         => 1,
            'defaultCaptureMode'   => 'advancedForm',
            'useDirectCapture'     => true,
            'useWebauthnOnly'      => true,
            'getOfficialIcons'     => false,
            'theme'                => 'dark',
            'formatPassword'       => false,
            'formatPasswordBy'     => 1,
            'lang'                 => 'fr',
        ];

        $this->user['preferences->showTokenAsDot']       = $userPrefs['showTokenAsDot'];
        $this->user['preferences->closeOtpOnCopy']       = $userPrefs['closeOtpOnCopy'];
        $this->user['preferences->copyOtpOnDisplay']     = $userPrefs['copyOtpOnDisplay'];
        $this->user['preferences->useBasicQrcodeReader'] = $userPrefs['useBasicQrcodeReader'];
        $this->user['preferences->displayMode']          = $userPrefs['displayMode'];
        $this->user['preferences->showAccountsIcons']    = $userPrefs['showAccountsIcons'];
        $this->user['preferences->kickUserAfter']        = $userPrefs['kickUserAfter'];
        $this->user['preferences->activeGroup']          = $userPrefs['activeGroup'];
        $this->user['preferences->rememberActiveGroup']  = $userPrefs['rememberActiveGroup'];
        $this->user['preferences->defaultGroup']         = $userPrefs['defaultGroup'];
        $this->user['preferences->defaultCaptureMode']   = $userPrefs['defaultCaptureMode'];
        $this->user['preferences->useDirectCapture']     = $userPrefs['useDirectCapture'];
        $this->user['preferences->useWebauthnOnly']      = $userPrefs['useWebauthnOnly'];
        $this->user['preferences->getOfficialIcons']     = $userPrefs['getOfficialIcons'];
        $this->user['preferences->theme']                = $userPrefs['theme'];
        $this->user['preferences->formatPassword']       = $userPrefs['formatPassword'];
        $this->user['preferences->formatPasswordBy']     = $userPrefs['formatPasswordBy'];
        $this->user['preferences->lang']                 = $userPrefs['lang'];
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
            ->json('GET', '/api/v1/user/preferences/showTokenAsDot')
            ->assertOk()
            ->assertExactJson([
                'key'   => 'showTokenAsDot',
                'value' => config('2fauth.preferences.showTokenAsDot'),
            ]);
    }

    /**
     * @test
     */
    public function test_showPreference_returns_preference_with_custom_value()
    {
        $showTokenAsDot                            = ! config('2fauth.preferences.showTokenAsDot');
        $this->user['preferences->showTokenAsDot'] = $showTokenAsDot;
        $this->user->save();

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('GET', '/api/v1/user/preferences/showTokenAsDot')
            ->assertJsonFragment([
                'key'   => 'showTokenAsDot',
                'value' => $showTokenAsDot,
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

        $showTokenAsDot = ! config('2fauth.preferences.showTokenAsDot');

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('PUT', '/api/v1/user/preferences/showTokenAsDot', [
                'key'   => 'showTokenAsDot',
                'value' => $showTokenAsDot,
            ])
            ->assertCreated()
            ->assertExactJson([
                'key'   => 'showTokenAsDot',
                'value' => $showTokenAsDot,
            ]);
    }

    /**
     * @test
     */
    public function test_setPreference_for_missing_preference_returns_not_found()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('PUT', '/api/v1/user/preferences/unknown', [
                'key'   => 'showTokenAsDot',
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
            ->json('PUT', '/api/v1/user/preferences/showTokenAsDot', [
                'key'   => 'showTokenAsDot',
                'value' => null,
            ])
            ->assertStatus(422);
    }
}
