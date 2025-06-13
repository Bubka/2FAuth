<?php

namespace Tests\Api\v1\Requests;

use App\Api\v1\Requests\TwoFAccountImportRequest;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * TwoFAccountImportRequestTest test class
 */
#[CoversClass(TwoFAccountImportRequest::class)]
class TwoFAccountImportRequestTest extends TestCase
{
    use WithoutMiddleware;

    #[Test]
    public function test_user_is_authorized()
    {
        Auth::shouldReceive('check')
            ->once()
            ->andReturn(true);

        $request = new TwoFAccountImportRequest;

        $this->assertTrue($request->authorize());
    }

    #[Test]
    #[DataProvider('provideValidData')]
    public function test_valid_data(array $data) : void
    {
        $request = new TwoFAccountImportRequest;
        $request->merge($data);
        
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
                'payload' => 'otpauth-migration://offline?data=AEoATACEAEYASAA',
            ]],
        ];
    }

    #[Test]
    #[DataProvider('provideInvalidData')]
    public function test_invalid_data(array $data) : void
    {
        $request = new TwoFAccountImportRequest;
        $request->merge($data);
        
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
