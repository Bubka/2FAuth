<?php

namespace Tests\Feature\Http\Requests;

use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Mockery;
use Tests\FeatureTestCase;

/**
 * @covers \App\Http\Requests\UserUpdateRequest
 */
class UserUpdateRequestTest extends FeatureTestCase
{
    use WithoutMiddleware;

    /**
     * @test
     */
    public function test_user_is_authorized()
    {
        Auth::shouldReceive('check')
        ->once()
        ->andReturn(true);

        $request = new UserUpdateRequest();

        $this->assertTrue($request->authorize());
    }

    /**
     * @dataProvider provideValidData
     */
    public function test_valid_data(array $data) : void
    {
        /**
         * @var \App\Models\User|\Illuminate\Contracts\Auth\Authenticatable
         */
        $user = User::factory()->create([
            'name'  => 'Jane',
            'email' => 'jane@example.com',
        ]);

        $request = Mockery::mock(UserUpdateRequest::class)->makePartial();
        $request->shouldReceive('user')
            ->andReturn($user);

        $validator = Validator::make($data, $request->rules());

        $this->assertFalse($validator->fails());
    }

    /**
     * Provide Valid data for validation test
     */
    public function provideValidData() : array
    {
        return [
            [[
                'name'     => 'John',
                'email'    => 'john@example.com',
                'password' => 'MyPassword',
            ]],
            [[
                'name'     => 'John',
                'email'    => 'jane@example.com',
                'password' => 'MyPassword',
            ]],
            [[
                'name'     => 'Jane',
                'email'    => 'john@example.com',
                'password' => 'MyPassword',
            ]],
        ];
    }

    /**
     * @dataProvider provideInvalidData
     */
    public function test_invalid_data(array $data) : void
    {
        /**
         * @var \App\Models\User|\Illuminate\Contracts\Auth\Authenticatable
         */
        $user = User::factory()->create([
            'name'  => 'Jane',
            'email' => 'jane@example.com',
        ]);

        User::factory()->create([
            'name'  => 'Bob',
            'email' => 'bob@example.com',
        ]);

        $request = Mockery::mock(UserUpdateRequest::class)->makePartial();
        $request->shouldReceive('user')
            ->andReturn($user);

        $validator = Validator::make($data, $request->rules());

        $this->assertTrue($validator->fails());
    }

    /**
     * Provide invalid data for validation test
     */
    public function provideInvalidData() : array
    {
        return [
            [[
                'name'     => 'Jane',
                'email'    => 'bob@example.com',  // unique
                'password' => 'MyPassword',
            ]],
            [[
                'name'     => 'Bob',  // unique
                'email'    => 'jane@example.com',
                'password' => 'MyPassword',
            ]],
            [[
                'name'     => '', // required
                'email'    => 'john@example.com',
                'password' => 'MyPassword',
            ]],
            [[
                'name'     => 'abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz', // max:255
                'email'    => 'john@example.com',
                'password' => 'MyPassword',
            ]],
            [[
                'name'     => true, // string
                'email'    => 'john@example.com',
                'password' => 'MyPassword',
            ]],
            [[
                'name'     => 'John',
                'email'    => '', // required
                'password' => 'MyPassword',
            ]],
            [[
                'name'     => 'John',
                'email'    => 0, // string
                'password' => 'MyPassword',
            ]],
            [[
                'name'     => 'John',
                'email'    => 'johnexample.com', // email
                'password' => 'MyPassword',
            ]],
            [[
                'name'     => 'John',
                'email'    => 'abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz@example.com', // max:255
                'password' => 'MyPassword',
            ]],
            [[
                'name'     => 'John',
                'email'    => 'john@example.com',
                'password' => '', // required
            ]],
        ];
    }
}
