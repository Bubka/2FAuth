<?php

namespace Tests\Api\v1\Requests;

use App\Api\v1\Requests\UserManagerStoreRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;

/**
 * UserManagerStoreRequestTest test class
 */
#[CoversClass(UserManagerStoreRequest::class)]
class UserManagerStoreRequestTest extends FeatureTestCase
{
    use WithoutMiddleware;

    #[Test]
    public function test_admin_is_authorized()
    {
        Auth::shouldReceive('user->isAdministrator')
            ->once()
            ->andReturn(true);

        $request = new UserManagerStoreRequest();

        $this->assertTrue($request->authorize());
    }

    #[Test]
    #[DataProvider('provideValidData')]
    public function test_valid_data(array $data) : void
    {
        User::factory()->create([
            'name'  => 'Jane',
            'email' => 'jane@example.com',
        ]);
        
        $request   = new UserManagerStoreRequest();
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
                'name'                  => 'John',
                'email'                 => 'john@example.com',
                'password'              => 'MyPassword',
                'password_confirmation' => 'MyPassword',
                'is_admin'              => true,
            ]],
            [[
                'name'                  => 'John',
                'email'                 => 'JOHN@example.com',
                'password'              => 'MyPassword',
                'password_confirmation' => 'MyPassword',
                'is_admin'              => 0,
            ]],
        ];
    }

    #[Test]
    #[DataProvider('provideInvalidData')]
    public function test_invalid_data(array $data) : void
    {
        User::factory()->create([
            'name'  => 'John',
            'email' => 'john@example.com',
        ]);

        $request   = new UserManagerStoreRequest();
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
                'name'                  => 'John',
                'email'                 => 'john@example.com', // unique
                'password'              => 'MyPassword',
                'password_confirmation' => 'MyPassword',
                'is_admin'              => true,
            ]],
            [[
                'name'                  => '', // required
                'email'                 => 'john@example.com',
                'password'              => 'MyPassword',
                'password_confirmation' => 'MyPassword',
                'is_admin'              => true,
            ]],
            [[
                'name'                  => 'John',
                'email'                 => '', // required
                'password'              => 'MyPassword',
                'password_confirmation' => 'MyPassword',
                'is_admin'              => true,
            ]],
            [[
                'name'                  => 'abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz', // max:255
                'email'                 => 'john@example.com',
                'password'              => 'MyPassword',
                'password_confirmation' => 'MyPassword',
                'is_admin'              => true,
            ]],
            [[
                'name'                  => 'John',
                'email'                 => 'abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz@example.com', // max:255
                'password'              => 'MyPassword',
                'password_confirmation' => 'MyPassword',
                'is_admin'              => true,
            ]],
            [[
                'name'                  => 'John',
                'email'                 => 'johnexample.com', // email
                'password'              => 'MyPassword',
                'password_confirmation' => 'MyPassword',
                'is_admin'              => true,
            ]],
            [[
                'name'                  => 'John',
                'email'                 => 'john@example.com',
                'password'              => '', // required
                'password_confirmation' => '', // required
                'is_admin'              => true,
            ]],
            [[
                'name'                  => 'John',
                'email'                 => 'john@example.com',
                'password'              => 'MyPassword',
                'password_confirmation' => 'anotherPassword', // confirmed
                'is_admin'              => true,
            ]],
            [[
                'name'                  => 'John',
                'email'                 => 'john@example.com',
                'password'              => 'pwd', // min:8
                'password_confirmation' => 'pwd',
                'is_admin'              => true,
            ]],
            [[
                'name'                  => 'John',
                'email'                 => 'john@example.com',
                'password'              => 'MyPassword',
                'password_confirmation' => 'MyPassword',
                'is_admin'              => null, // required
            ]],
            [[
                'name'                  => 'John',
                'email'                 => 'john@example.com',
                'password'              => 'MyPassword',
                'password_confirmation' => 'MyPassword',
                'is_admin'              => '', // required
            ]],
            [[
                'name'                  => 'John',
                'email'                 => 'john@example.com',
                'password'              => 'MyPassword',
                'password_confirmation' => 'MyPassword',
                'is_admin'              => 'string', // boolean
            ]],
        ];
    }
}
