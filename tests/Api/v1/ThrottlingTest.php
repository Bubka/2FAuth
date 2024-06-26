<?php

namespace Tests\Api\v1;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Config;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;

/**
 * ThrottlingTest test class
 */
#[CoversClass(RouteServiceProvider::class)]
class ThrottlingTest extends FeatureTestCase
{
    #[Test]
    public function test_api_calls_are_throttled_using_config()
    {
        /**
         * @var \App\Models\User|\Illuminate\Contracts\Auth\Authenticatable
         */
        $user     = User::factory()->create();
        $throttle = 5;

        Config::set('2fauth.api.throttle', $throttle);

        $this->actingAs($user, 'api-guard');

        for ($i = 0; $i < $throttle - 1; $i++) {
            $this->json('GET', '/api/v1/twofaccounts/count');
        }

        $this->json('GET', '/api/v1/twofaccounts/count')
            ->assertOk();

        $this->json('GET', '/api/v1/twofaccounts/count')
            ->assertStatus(429);
    }
}
