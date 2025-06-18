<?php

namespace Tests\Feature\Http\Requests;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;

/**
 * LoginRequestTest test class
 */
#[CoversClass(LoginRequest::class)]
class LoginRequestTest extends FeatureTestCase
{
    use WithoutMiddleware;

    #[Test]
    public function test_user_is_authorized()
    {
        $request = new LoginRequest;

        $this->assertTrue($request->authorize());
    }

    #[Test]
    #[DataProvider('provideValidData')]
    public function test_valid_data(array $data) : void
    {
        User::factory()->create([
            'email' => 'JOHN.DOE@example.com',
        ]);

        $request = new LoginRequest;
        $request->merge($data);

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
                'email'    => 'john.doe@example.com',
                'password' => 'MyPassword',
            ]],
            [[
                'email'    => 'JOHN.doe@example.com',
                'password' => 'MyPassword',
            ]],
        ];
    }

    #[Test]
    #[DataProvider('provideInvalidData')]
    public function test_invalid_data(array $data) : void
    {
        User::factory()->create([
            'email' => 'JOHN.DOE@example.com',
        ]);

        $request = new LoginRequest;
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
            [[
                'email'    => '', // required
                'password' => 'MyPassword',
            ]],
            [[
                'email'    => 'john', // email
                'password' => 'MyPassword',
            ]],
            [[
                'email'    => 'john@example.com', // exists
                'password' => 'MyPassword',
            ]],
            [[
                'email'    => 'john.doe@example.com',
                'password' => '', // required
            ]],
            [[
                'email'    => 'john.doe@example.com',
                'password' => true, // string
            ]],
        ];
    }
}
