<?php

namespace Tests\Api\v1\Requests;

use App\Api\v1\Requests\TwoFAccountReorderRequest;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class TwoFAccountReorderRequestTest extends TestCase
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

        $request = new TwoFAccountReorderRequest();
    
        $this->assertTrue($request->authorize());
    }

    /**
     * @dataProvider provideValidData
     */
    public function test_valid_data(array $data) : void
    {
        $request = new TwoFAccountReorderRequest();
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
                'orderedIds' => [1,2,5]
            ]],
            [[
                'orderedIds' => [5]
            ]],
        ];
    }

    /**
     * @dataProvider provideInvalidData
     */
    public function test_invalid_data(array $data) : void
    {
        $request = new TwoFAccountReorderRequest();
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
                'orderedIds' => [] // required
            ]],
            [[
                'orderedIds' => null // required
            ]],
            [[
                'orderedIds' => 0 // array
            ]],
            [[
                'orderedIds' => 'string' // array
            ]],
            [[
                'orderedIds' => true // array
            ]],
        ];
    }

}