<?php

namespace Tests\Api\v1\Requests;

use App\Api\v1\Requests\UserStoreRequest;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Validator;
use Tests\FeatureTestCase;

class UserStoreRequestTest extends FeatureTestCase
{

    use WithoutMiddleware;

    /**
     * @test
     */
    public function test_user_is_authorized()
    {
        $request = new UserStoreRequest();
    
        $this->assertTrue($request->authorize());
    }

    /**
     * @dataProvider provideValidData
     */
    public function test_valid_data(array $data) : void
    {
        $request = new UserStoreRequest();
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
                'name'      => 'John',
                'email'     => 'john@example.com',
                'password'  => 'MyPassword',
                'password_confirmation'  => 'MyPassword',
            ]],
        ];
    }

    /**
     * @dataProvider provideInvalidData
     */
    public function test_invalid_data(array $data) : void
    {
        $user = new \App\User(
            [
                'name'      => 'John',
                'email'     => 'john@example.com',
                'password'  => 'MyPassword',
                'password_confirmation'  => 'MyPassword',
            ]
        );
        $user->save();
        
        $request = new UserStoreRequest();
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
                'name'      => 'John', // unique
                'email'     => 'john@example.com',
                'password'  => 'MyPassword',
                'password_confirmation'  => 'MyPassword',
            ]],
            [[
                'name'      => '', // required
                'email'     => 'john@example.com',
                'password'  => 'MyPassword',
                'password_confirmation'  => 'MyPassword',
            ]],
            [[
                'name'      => 'John',
                'email'     => '', // required
                'password'  => 'MyPassword',
                'password_confirmation'  => 'MyPassword',
            ]],
            [[
                'name'      => 'abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz', // max:255
                'email'     => 'john@example.com',
                'password'  => 'MyPassword',
                'password_confirmation'  => 'MyPassword',
            ]],
            [[
                'name'      => 'John',
                'email'     => 'abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz@example.com', // max:255
                'password'  => 'MyPassword',
                'password_confirmation'  => 'MyPassword',
            ]],
            [[
                'name'      => 'John',
                'email'     => 'johnexample.com', // email
                'password'  => 'MyPassword',
                'password_confirmation'  => 'MyPassword',
            ]],
            [[
                'name'      => 'John',
                'email'     => 'john@example.com',
                'password'  => '', // required
                'password_confirmation'  => '', // required
            ]],
            [[
                'name'      => 'John',
                'email'     => 'john@example.com',
                'password'  => 'MyPassword',
                'password_confirmation'  => 'anotherPassword', // confirmed
            ]],
            [[
                'name'      => 'John',
                'email'     => 'john@example.com',
                'password'  => 'pwd', // min:8
                'password_confirmation'  => 'pwd',
            ]],
        ];
    }

}