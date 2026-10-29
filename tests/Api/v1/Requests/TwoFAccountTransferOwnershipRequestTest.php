<?php

namespace Tests\Api\v1\Requests;

use App\Api\v1\Requests\TwoFAccountTransferOwnershipRequest;
use App\Models\TwoFAccount;
use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * TwoFAccountTransferOwnershipRequestTest test class
 */
#[CoversClass(TwoFAccountTransferOwnershipRequest::class)]
class TwoFAccountTransferOwnershipRequestTest extends TestCase
{
    use RefreshDatabase;
    use WithoutMiddleware;

    #[Test]
    public function test_user_is_authorized() : void
    {
        Auth::shouldReceive('check')
            ->once()
            ->andReturn(true);

        $request = new TwoFAccountTransferOwnershipRequest;

        $this->assertTrue($request->authorize());
    }

    #[Test]
    public function test_valid_data() : void
    {
        $user = User::factory()->create();
        $data = [
            'new_owner_id' => 1,
            'confirm_password' => UserFactory::USER_PASSWORD,
        ];

        $request = new TwoFAccountTransferOwnershipRequest;
        $request->merge($data);
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $validator = Validator::make($data, $request->rules());

        $this->assertFalse($validator->fails());
    }

    #[Test]
    #[DataProvider('provideInvalidData')]
    public function test_invalid_data(array $data) : void
    {
        $user = User::factory()->create();
        $request = new TwoFAccountTransferOwnershipRequest;
        $request->merge($data);
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $validator = Validator::make($data, $request->rules());

        $this->assertTrue($validator->fails());
    }

    /**
     * Provide invalid data for validation test
     */
    public static function provideInvalidData() : array
    {
        return [
            [[]],
            [[
                'new_owner_id' => 'abc',
                'confirm_password' => UserFactory::USER_PASSWORD,
            ]],
            [[
                'new_owner_id' => 999_999_999,
                'confirm_password' => UserFactory::USER_PASSWORD,
            ]],
            [[
                'new_owner_id' => '1',
            ]],
        ];
    }

    #[Test]
    public function test_password_is_not_required_for_auth_proxy_user() : void
    {
        Auth::shouldReceive('getDefaultDriver')
            ->once()
            ->andReturn('reverse-proxy-guard');

        $user = User::factory()->create();
        $data = [
            'new_owner_id' => '1',
        ];

        $request = new TwoFAccountTransferOwnershipRequest;
        $request->merge($data);
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $validator = Validator::make($data, $request->rules());

        $this->assertFalse($validator->fails());
    }

    #[Test]
    public function test_password_is_not_required_for_sso_user() : void
    {
        $user = User::factory()->ssoUser()->create();

        $data = [
            'new_owner_id' => '1',
        ];

        $request = new TwoFAccountTransferOwnershipRequest;
        $request->merge($data);
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $validator = Validator::make($data, $request->rules());

        $this->assertFalse($validator->fails());
    }

    #[Test]
    public function test_new_owner_must_be_different_from_current_owner() : void
    {
        $owner = User::factory()->create();
        $twofaccount = TwoFAccount::factory()->for($owner)->create();

        $data = [
            'new_owner_id' => $owner->id,
            'confirm_password' => UserFactory::USER_PASSWORD,
        ];

        $request = new TwoFAccountTransferOwnershipRequest;
        $request->merge($data);

        $route = new Route('PATCH', '/api/v1/twofaccounts/{twofaccount}/owner', []);
        $route->bind($request);
        $route->setParameter('twofaccount', $twofaccount);
        $request->setRouteResolver(fn () => $route);

        $validator = Validator::make($data, $request->rules());
        $request->withValidator($validator);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('new_owner_id', $validator->errors()->toArray());
    }

    #[Test]
    public function test_with_validator_ignores_different_check_when_route_has_no_twofaccount() : void
    {
        $newOwner = User::factory()->create();
        $data = [
            'new_owner_id' => $newOwner->id,
            'confirm_password' => UserFactory::USER_PASSWORD,
        ];

        $request = new TwoFAccountTransferOwnershipRequest;
        $request->merge($data);

        $validator = Validator::make($data, $request->rules());
        $request->withValidator($validator);

        $this->assertFalse($validator->fails());
    }
}
