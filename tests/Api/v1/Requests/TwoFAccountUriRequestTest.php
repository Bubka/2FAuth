<?php

namespace Tests\Api\v1\Requests;

use App\Api\v1\Requests\TwoFAccountUriRequest;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class TwoFAccountUriRequestTest extends TestCase
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

        $request = new TwoFAccountUriRequest();
    
        $this->assertTrue($request->authorize());
    }

    /**
     * @dataProvider provideValidData
     */
    public function test_valid_data(array $data) : void
    {
        $request = new TwoFAccountUriRequest();
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
                'uri' => 'otpauth://totp/test@test.com?secret=A4GRFHZVRBGY7UIW&issuer=test'
            ]],
            [[
                'uri' => 'otpauth://hotp/test@test.com?secret=A4GRFHZVRBGY7UIW&issuer=test'
            ]],
        ];
    }

    /**
     * @dataProvider provideInvalidData
     */
    public function test_invalid_data(array $data) : void
    {
        $request = new TwoFAccountUriRequest();
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
                'uri' => 'otpXauth://totp/test@test.com?secret=A4GRFHZVRBGY7UIW&issuer=test' // regex
            ]],
        ];
    }

}