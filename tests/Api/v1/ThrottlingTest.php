<?php

namespace Tests\Api\v1;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Config;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\Data\OtpTestData;
use Tests\FeatureTestCase;

/**
 * ThrottlingTest test class
 */
#[CoversClass(RouteServiceProvider::class)]
class ThrottlingTest extends FeatureTestCase
{
    #[Test]
    public function test_api_calls_are_throttled()
    {
        /**
         * @var \App\Models\User|\Illuminate\Contracts\Auth\Authenticatable
         */
        $user     = User::factory()->create();
        $throttle = 5;

        Config::set('2fauth.api.throttle', $throttle);

        $this->actingAs($user, 'api-guard');

        for ($i = 0; $i < $throttle; $i++) {
            $this->json('GET', '/api/v1/twofaccounts/count');
        }

        $this->json('GET', '/api/v1/twofaccounts/count')
            ->assertStatus(429);
    }
    #[Test]
    #[DataProvider('DisabledThrottlingProvider')]
    public function test_api_calls_are_not_throttled(mixed $throttlingStatus)
    {
        /**
         * @var \App\Models\User|\Illuminate\Contracts\Auth\Authenticatable
         */
        $user     = User::factory()->create();
        $throttle = 5;

        Config::set('2fauth.api.throttle', $throttle);
        Config::set('2fauth.api.throttle', $throttlingStatus);

        $this->actingAs($user, 'api-guard');

        for ($i = 0; $i < $throttle; $i++) {
            $this->json('GET', '/api/v1/twofaccounts/count');
        }

        $this->json('GET', '/api/v1/twofaccounts/count')
            ->assertOk();
    }

    /**
     * Provide various disabled status for throttling
     */
    public static function DisabledThrottlingProvider()
    {
        return [
            'NULL_CASE' => [null],
            'FALSE_CASE' => [false],
            'ZERO_CASE' => [0],
            'EMPTY_CASE' => [""],
        ];
    }

    #[Test]
    public function test_api_calls_for_import_are_throttled_specifically()
    {
        /**
         * @var \App\Models\User|\Illuminate\Contracts\Auth\Authenticatable
         */
        $user     = User::factory()->create();
        $throttle = 5;
        $throttleImport = 10;

        Config::set('2fauth.api.throttle', $throttle);
        Config::set('2fauth.api.throttleImport', $throttleImport);

        $this->actingAs($user, 'api-guard');
        
        for ($i = 0; $i < $throttle; $i++) {
            $this->json('GET', '/api/v1/twofaccounts/count');
        }

        $this->json('GET', '/api/v1/twofaccounts/count')
            ->assertStatus(429);
        
        for ($i = 0; $i < $throttleImport; $i++) {
            $this->json('POST', '/api/v1/twofaccounts', [
                'uri' => OtpTestData::TOTP_SHORT_URI,
            ],
            [
                'referer' => 'test.local/account/import'
            ])
            ->assertCreated();
        }

        $this->json('POST', '/api/v1/twofaccounts', [
            'uri' => OtpTestData::TOTP_SHORT_URI,
        ],
        [
            'referer' => 'test.local/account/import'
        ])
        ->assertStatus(429);
    }

    #[Test]
    public function test_api_calls_for_import_are_throttled_specifically_before_others()
    {
        /**
         * @var \App\Models\User|\Illuminate\Contracts\Auth\Authenticatable
         */
        $user     = User::factory()->create();
        $throttle = 5;
        $throttleImport = 10;

        Config::set('2fauth.api.throttle', $throttle);
        Config::set('2fauth.api.throttleImport', $throttleImport);

        $this->actingAs($user, 'api-guard');

        for ($i = 0; $i < $throttleImport; $i++) {
            $this->json('POST', '/api/v1/twofaccounts', [
                'uri' => OtpTestData::TOTP_SHORT_URI,
            ],
            [
                'referer' => 'test.local/account/import'
            ])
            ->assertCreated();
        }
        
        for ($i = 0; $i < $throttle; $i++) {
            $this->json('GET', '/api/v1/twofaccounts/count');
        }

        $this->json('GET', '/api/v1/twofaccounts/count')
            ->assertStatus(429);
    }

    #[Test]
    public function test_api_calls_for_import_are_not_throttled()
    {
        /**
         * @var \App\Models\User|\Illuminate\Contracts\Auth\Authenticatable
         */
        $user     = User::factory()->create();
        $throttle = 5;
        $throttleImport = 10;

        Config::set('2fauth.api.throttle', $throttle);
        Config::set('2fauth.api.throttleImport', $throttleImport);
        Config::set('2fauth.api.throttleImport', null);

        $this->actingAs($user, 'api-guard');
        
        for ($i = 0; $i < $throttle; $i++) {
            $this->json('GET', '/api/v1/twofaccounts/count');
        }

        $this->json('GET', '/api/v1/twofaccounts/count')
            ->assertStatus(429);
        
        for ($i = 0; $i < $throttleImport + 1; $i++) {
            $this->json('POST', '/api/v1/twofaccounts', [
                'uri' => OtpTestData::TOTP_SHORT_URI,
            ],
            [
                'referer' => 'test.local/account/import'
            ])
            ->assertCreated();
        }
    }

    #[Test]
    public function test_api_calls_for_import_has_no_effect_if_global_throttling_is_disabled()
    {
        /**
         * @var \App\Models\User|\Illuminate\Contracts\Auth\Authenticatable
         */
        $user     = User::factory()->create();
        $throttle = null;
        $throttleImport = 5;

        Config::set('2fauth.api.throttle', $throttle);
        Config::set('2fauth.api.throttleImport', $throttleImport);

        $this->actingAs($user, 'api-guard');
        
        for ($i = 0; $i < $throttleImport + 1; $i++) {
            $this->json('POST', '/api/v1/twofaccounts', [
                'uri' => OtpTestData::TOTP_SHORT_URI,
            ],
            [
                'referer' => 'test.local/account/import'
            ])
            ->assertCreated();
        }
    }
}
