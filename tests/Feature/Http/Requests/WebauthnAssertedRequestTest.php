<?php

namespace Tests\Feature\Http\Requests;

use App\Http\Requests\WebauthnAssertedRequest;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * WebauthnAssertedRequestTest test class
 */
#[CoversClass(WebauthnAssertedRequest::class)]
class WebauthnAssertedRequestTest extends TestCase
{
    use WithoutMiddleware;

    #[Test]
    #[DataProvider('provideValidData')]
    public function test_valid_data(array $data) : void
    {
        $request   = new WebauthnAssertedRequest();
        $validator = Validator::make($data, $request->rules());

        $this->assertFalse($validator->fails());
    }

    /**
     * Provide Valid data for validation test
     */
    public static function provideValidData() : array
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

    #[Test]
    #[DataProvider('provideInvalidData')]
    public function test_invalid_data(array $data) : void
    {
        $request   = new WebauthnAssertedRequest();
        $validator = Validator::make($data, $request->rules());

        $this->assertTrue($validator->fails());
    }

    /**
     * Provide invalid data for validation test
     */
    public static function provideInvalidData() : array
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
