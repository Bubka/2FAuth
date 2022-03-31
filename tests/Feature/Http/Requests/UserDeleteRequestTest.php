<?php

namespace Tests\Feature\Http\Requests;

use App\Http\Requests\UserDeleteRequest;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tests\FeatureTestCase;

/**
 * @covers \App\Http\Requests\UserDeleteRequest
 */
class UserDeleteRequestTest extends FeatureTestCase
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

        $request = new UserDeleteRequest();
    
        $this->assertTrue($request->authorize());
    }

    /**
     * @dataProvider provideValidData
     */
    public function test_valid_data(array $data) : void
    {
        $request = new UserDeleteRequest();
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
                'password'  => 'Yubikey',
            ]],
        ];
    }

    /**
     * @dataProvider provideInvalidData
     */
    public function test_invalid_data(array $data) : void
    {        
        $request = new UserDeleteRequest();
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
                'password'  => '', // required
            ]],
            [[
                'password'  => true, // string
            ]],
            [[
                'password'  => 0, // string
            ]],
        ];
    }

}