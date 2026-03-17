<?php

namespace Tests\Api\v1\Requests;

use App\Api\v1\Requests\TwoFAccountTransferOwnershipRequest;
use App\Models\TwoFAccount;
use App\Models\User;
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
        $newOwner = User::factory()->create();
        $data = [
            'new_owner_id' => $newOwner->id,
        ];

        $request = new TwoFAccountTransferOwnershipRequest;
        $request->merge($data);

        $validator = Validator::make($data, $request->rules());

        $this->assertFalse($validator->fails());
    }

    #[Test]
    #[DataProvider('provideInvalidData')]
    public function test_invalid_data(array $data) : void
    {
        $request = new TwoFAccountTransferOwnershipRequest;
        $request->merge($data);

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
            [['new_owner_id' => 'abc']],
            [['new_owner_id' => 999_999_999]],
        ];
    }

    #[Test]
    public function test_new_owner_must_be_different_from_current_owner() : void
    {
        $owner = User::factory()->create();
        $twofaccount = TwoFAccount::factory()->for($owner)->create();

        $data = [
            'new_owner_id' => $owner->id,
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
        ];

        $request = new TwoFAccountTransferOwnershipRequest;
        $request->merge($data);

        $validator = Validator::make($data, $request->rules());
        $request->withValidator($validator);

        $this->assertFalse($validator->fails());
    }
}
