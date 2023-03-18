<?php

namespace Tests\Feature\Http\Requests;

use App\Http\Requests\WebauthnAssertedRequest;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

/**
 * @covers \App\Http\Requests\WebauthnAssertedRequest
 */
class WebauthnAssertedRequestTest extends TestCase
{
    use WithoutMiddleware;

    /**
     * @dataProvider provideValidData
     */
    public function test_valid_data(array $data) : void
    {
        $request   = new WebauthnAssertedRequest();
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
                'id'       => 'string',
                'rawId'    => 'string',
                'type'     => 'string',
                'response' => [
                    'clientDataJSON'    => 'string',
                    'authenticatorData' => 'string',
                    'signature'         => 'string',
                    'userHandle'        => null,
                ],
                'email' => 'valid@email.com',
            ]],
        ];
    }

    /**
     * @dataProvider provideInvalidData
     */
    public function test_invalid_data(array $data) : void
    {
        $request   = new WebauthnAssertedRequest();
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
                'email' => '', // required
            ]],
            [[
                'email' => true, // email
            ]],
            [[
                'email' => 0, // email
            ]],
            [[
                'email' => 'sdfsdf@', // email
            ]],
        ];
    }
}
