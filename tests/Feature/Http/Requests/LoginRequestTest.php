<?php

namespace Tests\Feature\Http\Requests;

use App\Models\User;
use App\Http\Requests\LoginRequest;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tests\FeatureTestCase;


/**
 * @covers \App\Http\Requests\LoginRequest
 */
class LoginRequestTest extends FeatureTestCase
{

    use WithoutMiddleware;

    /**
     * @test
     */
    public function test_user_is_authorized()
    {
        $request = new LoginRequest();
    
        $this->assertTrue($request->authorize());
    }


    /**
     * @dataProvider provideValidData
     */
    public function test_valid_data(array $data) : void
    {
        User::factory()->create([
            'email' => 'JOHN.DOE@example.com'
        ]);

        $request = new LoginRequest();
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
                'email'     => 'john.doe@example.com',
                'password'  => 'MyPassword'
            ]],
            [[
                'email'     => 'JOHN.doe@example.com',
                'password'  => 'MyPassword'
            ]],
        ];
    }


    /**
     * @dataProvider provideInvalidData
     */
    public function test_invalid_data(array $data) : void
    {      
        User::factory()->create([
            'email' => 'JOHN.DOE@example.com'
        ]);

        $request = new LoginRequest();
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
                'email'     => '', // required
                'password'  => 'MyPassword',
            ]],
            [[
                'email'     => 'john', // email
                'password'  => 'MyPassword',
            ]],
            [[
                'email'     => 'john@example.com', // exists
                'password'  => 'MyPassword',
            ]],
            [[
                'email'     => 'john.doe@example.com',
                'password'  => '', // required
            ]],
            [[
                'email'     => 'john.doe@example.com',
                'password'  => true, // string
            ]],
        ];
    }
}