<?php

namespace Tests\Api\v1\Requests;

use App\Api\v1\Requests\TwoFAccountUpdateRequest;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class TwoFAccountUpdateRequestTest extends TestCase
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

        $request = new TwoFAccountUpdateRequest();
    
        $this->assertTrue($request->authorize());
    }

    /**
     * @dataProvider provideValidData
     */
    public function test_valid_data(array $data) : void
    {
        $request = new TwoFAccountUpdateRequest();
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
                'service' => 'MyService',
                'account' => 'MyAccount',
                'icon' => 'icon.png',
                'otp_type' => 'totp',
                'secret' => 'A4GRFHZVRBGY7UIW',
                'digits' => 6,
                'algorithm' => 'sha1',
                'period' => 30,
            ]],
            [[
                'service' => 'MyService',
                'account' => 'MyAccount',
                'icon' => 'icon.png',
                'otp_type' => 'hotp',
                'secret' => 'A4GRFHZVRBGY7UIW',
                'digits' => 6,
                'algorithm' => 'sha1',
                'counter' => 10,
            ]],
            [[
                'service' => null,
                'account' => 'MyAccount',
                'icon' => null,
                'otp_type' => 'hotp',
                'secret' => 'A4GRFHZVRBGY7UIW',
                'digits' => 6,
                'algorithm' => 'sha1',
                'period' => null,
                'counter' => 15,
            ]],
        ];
    }

    /**
     * @dataProvider provideInvalidData
     */
    public function test_invalid_data(array $data) : void
    {
        $request = new TwoFAccountUpdateRequest();
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
                'service' => null,
                'account' => 'My:Account',
                'icon' => null,
                'otp_type' => 'hotp',
                'secret' => 'A4GRFHZVRBGY7UIW',
                'digits' => 6,
                'algorithm' => 'sha1',
                'period' => null,
                'counter' => 15,
            ]],
            [[
                'service' => 'My:Service',
                'account' => 'MyAccount',
                'icon' => null,
                'otp_type' => 'hotp',
                'secret' => 'A4GRFHZVRBGY7UIW',
                'digits' => 6,
                'algorithm' => 'sha1',
                'period' => null,
                'counter' => 15,
            ]],
            [[
                'service' => null,
                'account' => 'My:Account',
                'icon' => null,
                'otp_type' => 'Xotp',
                'secret' => 'A4GRFHZVRBGY7UIW',
                'digits' => 6,
                'algorithm' => 'sha1',
                'period' => null,
                'counter' => 15,
            ]],
            [[
                'service' => null,
                'account' => 'MyAccount',
                'icon' => null,
                'otp_type' => 'hotp',
                'secret' => 'notaBase32String',
                'digits' => 6,
                'algorithm' => 'sha1',
                'period' => null,
                'counter' => 15,
            ]],
            [[
                'service' => 'MyService',
                'account' => 'MyAccount',
                'icon' => null,
                'otp_type' => 'totp',
                'secret' => 'A4GRFHZVRBGY7UIW',
                'digits' => 5,
                'algorithm' => 'sha1',
                'period' => null,
                'counter' => 15,
            ]],
            [[
                'service' => 'MyService',
                'account' => 'MyAccount',
                'icon' => null,
                'otp_type' => 'totp',
                'secret' => 'A4GRFHZVRBGY7UIW',
                'digits' => 11,
                'algorithm' => 'sha1',
                'period' => null,
                'counter' => 15,
            ]],
            [[
                'service' => 'MyService',
                'account' => 'MyAccount',
                'icon' => null,
                'otp_type' => 'totp',
                'secret' => 'A4GRFHZVRBGY7UIW',
                'digits' => 6,
                'algorithm' => 'Xsha1',
                'period' => null,
                'counter' => 15,
            ]],
            [[
                'service' => 'MyService',
                'account' => 'MyAccount',
                'icon' => null,
                'otp_type' => 'totp',
                'secret' => 'A4GRFHZVRBGY7UIW',
                'digits' => 6,
                'algorithm' => 'sha1',
                'period' => 0,
                'counter' => 15,
            ]],
            [[
                'service' => 'MyService',
                'account' => 'MyAccount',
                'icon' => null,
                'otp_type' => 'totp',
                'secret' => 'A4GRFHZVRBGY7UIW',
                'digits' => 5,
                'algorithm' => 'sha1',
                'period' => null,
                'counter' => -1,
            ]],
            [[
                'service' => 'MyService',
                'account' => 'MyAccount',
                'icon' => null,
                'otp_type' => 'totp',
                'secret' => 'A4GRFHZVRBGY7UIW',
                'digits' => null,
                'algorithm' => 'sha1',
                'period' => null,
                'counter' => 15,
            ]],
            [[
                'service' => 'MyService',
                'account' => 'MyAccount',
                'icon' => null,
                'otp_type' => 'totp',
                'secret' => 'A4GRFHZVRBGY7UIW',
                'digits' => 6,
                'algorithm' => null,
                'period' => null,
                'counter' => 15,
            ]],
        ];
    }
    
}