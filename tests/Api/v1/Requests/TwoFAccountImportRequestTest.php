<?php

namespace Tests\Api\v1\Requests;

use App\Api\v1\Requests\TwoFAccountImportRequest;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class TwoFAccountImportRequestTest extends TestCase
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

        $request = new TwoFAccountImportRequest();
    
        $this->assertTrue($request->authorize());
    }

    /**
     * @dataProvider provideValidData
     */
    public function test_valid_data(array $data) : void
    {
        $request = new TwoFAccountImportRequest();
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
                'uri' => 'otpauth-migration://offline?data=AEoATACEAEYASAA'
            ]],
        ];
    }

    /**
     * @dataProvider provideInvalidData
     */
    public function test_invalid_data(array $data) : void
    {
        $request = new TwoFAccountImportRequest();
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
                'uri' => null // required
            ]],
            [[
                'uri' => '' // required
            ]],
            [[
                'uri' => true // string
            ]],
            [[
                'uri' => 8 // string
            ]],
            [[
                'uri' => 'otpXauth-migration://offline?data=fYmlzIAEoATACEAEYASAA' // regex
            ]],
            [[
                'uri' => 'otpauth-migration:/offline?data=fYmlzIAEoATACEAEYASAA' // regex
            ]],
            [[
                'uri' => 'otpauth-migration://offlinedata=fYmlzIAEoATACEAEYASAA' // regex
            ]],
            [[
                'uri' => 'otpauth-migration://offline?dat=fYmlzIAEoATACEAEYASAA' // regex
            ]],
        ];
    }

}