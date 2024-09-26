<?php

namespace Tests\Api\v1\Requests;

use App\Api\v1\Requests\UserManagerPromoteRequest;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * UserManagerPromoteRequestTest test class
 */
#[CoversClass(UserManagerPromoteRequest::class)]
class UserManagerPromoteRequestTest extends TestCase
{
    use WithoutMiddleware;

    #[Test]
    public function test_user_is_authorized()
    {
        Auth::shouldReceive('user->isAdministrator')
            ->once()
            ->andReturn(true);

        $request = new UserManagerPromoteRequest;

        $this->assertTrue($request->authorize());
    }

    #[Test]
    #[DataProvider('provideValidData')]
    public function test_valid_data(array $data) : void
    {
        $request   = new UserManagerPromoteRequest;
        $validator = Validator::make($data, $request->rules());

        $this->assertFalse($validator->fails());
    }

    /**
     * Provide Valid data for validation test
     */
    public static function provideValidData() : array
    {
        return [
            [[
                'is_admin' => true,
            ]],
            [[
                'is_admin' => false,
            ]],
            [[
                'is_admin' => 0,
            ]],
            [[
                'is_admin' => 1,
            ]],
        ];
    }

    #[Test]
    #[DataProvider('provideInvalidData')]
    public function test_invalid_data(array $data) : void
    {
        $request   = new UserManagerPromoteRequest;
        $validator = Validator::make($data, $request->rules());

        $this->assertTrue($validator->fails());
    }

    /**
     * Provide invalid data for validation test
     */
    public static function provideInvalidData() : array
    {
        return [
            [[
                'is_admin' => [],
            ]],
            [[
                'is_admin' => null,
            ]],
            [[
                'is_admin' => 'string',
            ]],
            [[
                'is_admin' => '',
            ]],
            [[
                'is_admin' => 5,
            ]],
        ];
    }
}
