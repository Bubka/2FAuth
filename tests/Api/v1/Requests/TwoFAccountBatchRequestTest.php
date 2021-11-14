<?php

namespace Tests\Api\v1\Requests;

use App\Api\v1\Requests\TwoFAccountBatchRequest;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class TwoFAccountBatchRequestTest extends TestCase
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

        $request = new TwoFAccountBatchRequest();
    
        $this->assertTrue($request->authorize());
    }

    /**
     * @dataProvider provideValidData
     */
    public function test_valid_data(array $data) : void
    {
        $request = new TwoFAccountBatchRequest();
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
                'ids' => '1'
            ]],
            [[
                'ids' => '1,2,5'
            ]],
        ];
    }

    /**
     * @dataProvider provideInvalidData
     */
    public function test_invalid_data(array $data) : void
    {
        $request = new TwoFAccountBatchRequest();
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
                'ids' => '' // required
            ]],
            [[
                'ids' => null // required
            ]],
            [[
                'ids' => true // string
            ]],
            [[
                'ids' => 10 // string
            ]],
            [[
                'ids' => 'notaCommaSeparatedList' // regex
            ]],
            [[
                'ids' => 'a,b' // regex
            ]],
            [[
                'ids' => 'a,1' // regex
            ]],
            [[
                'ids' => ',1,2' // regex
            ]],
            [[
                'ids' => '1,,2' // regex
            ]],
            [[
                'ids' => '1,2,' // regex
            ]],
            [[
                'ids' => ',1,2,' // regex
            ]],
            [[
                'ids' => '1;2' // regex
            ]],
        ];
    }

}