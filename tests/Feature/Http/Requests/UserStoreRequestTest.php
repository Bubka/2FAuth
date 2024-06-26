<?php

namespace Tests\Feature\Http\Requests;

use App\Http\Requests\UserStoreRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;

/**
 * UserStoreRequestTest test class
 */
#[CoversClass(UserStoreRequest::class)]
class UserStoreRequestTest extends FeatureTestCase
{
    use WithoutMiddleware;

    #[Test]
    public function test_user_is_authorized()
    {
        $request = new UserStoreRequest();

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

        $request   = new UserStoreRequest();
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
            ]],
            [[
                'name'                  => 'John',
                'email'                 => 'JOHN@example.com',
                'password'              => 'MyPassword',
                'password_confirmation' => 'MyPassword',
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

        $request   = new UserStoreRequest();
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
            ]],
            [[
                'name'                  => '', // required
                'email'                 => 'john@example.com',
                'password'              => 'MyPassword',
                'password_confirmation' => 'MyPassword',
            ]],
            [[
                'name'                  => 'John',
                'email'                 => '', // required
                'password'              => 'MyPassword',
                'password_confirmation' => 'MyPassword',
            ]],
            [[
                'name'                  => 'abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz', // max:255
                'email'                 => 'john@example.com',
                'password'              => 'MyPassword',
                'password_confirmation' => 'MyPassword',
            ]],
            [[
                'name'                  => 'John',
                'email'                 => 'abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz@example.com', // max:255
                'password'              => 'MyPassword',
                'password_confirmation' => 'MyPassword',
            ]],
            [[
                'name'                  => 'John',
                'email'                 => 'johnexample.com', // email
                'password'              => 'MyPassword',
                'password_confirmation' => 'MyPassword',
            ]],
            [[
                'name'                  => 'John',
                'email'                 => 'john@example.com',
                'password'              => '', // required
                'password_confirmation' => '', // required
            ]],
            [[
                'name'                  => 'John',
                'email'                 => 'john@example.com',
                'password'              => 'MyPassword',
                'password_confirmation' => 'anotherPassword', // confirmed
            ]],
            [[
                'name'                  => 'John',
                'email'                 => 'john@example.com',
                'password'              => 'pwd', // min:8
                'password_confirmation' => 'pwd',
            ]],
        ];
    }
}
