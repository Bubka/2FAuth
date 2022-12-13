<?php

namespace Tests\Api\v1\Requests;

use App\Api\v1\Requests\TwoFAccountImportRequest;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

/**
 * @covers \App\Api\v1\Requests\TwoFAccountImportRequest
 */
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
        $request   = new TwoFAccountImportRequest();
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
                'payload' => 'otpauth-migration://offline?data=AEoATACEAEYASAA',
            ]],
        ];
    }

    /**
     * @dataProvider provideInvalidData
     */
    public function test_invalid_data(array $data) : void
    {
        $request   = new TwoFAccountImportRequest();
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
                'payload' => null, // required
            ]],
            [[
                'payload' => '', // required
            ]],
            [[
                'payload' => true, // string
            ]],
            [[
                'payload' => 8, // string
            ]],
        ];
    }
}
