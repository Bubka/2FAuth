<?php

namespace Tests\Api\v1\Requests;

use App\Api\v1\Requests\TwoFAccountExportRequest;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * TwoFAccountExportRequestTest test class
 */
#[CoversClass(TwoFAccountExportRequest::class)]
class TwoFAccountExportRequestTest extends TestCase
{
    use WithoutMiddleware;

    #[Test]
    public function test_user_is_authorized()
    {
        Auth::shouldReceive('check')
            ->once()
            ->andReturn(true);

        $request = new TwoFAccountExportRequest;

        $this->assertTrue($request->authorize());
    }

    #[Test]
    #[DataProvider('provideValidData')]
    public function test_valid_data(array $data) : void
    {
        $request = new TwoFAccountExportRequest;
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
                'ids'     => '1',
                'otpauth' => '1',
            ]],
            [[
                'ids'     => '1',
                'otpauth' => 1,
            ]],
            [[
                'ids'     => '1',
                'otpauth' => true,
            ]],
            [[
                'ids' => '1',
            ]],
            [[
                'ids'     => '1',
                'otpauth' => '0',
            ]],
            [[
                'ids'     => '1',
                'otpauth' => 0,
            ]],
            [[
                'ids'     => '1',
                'otpauth' => false,
            ]],
        ];
    }

    #[Test]
    #[DataProvider('provideInvalidData')]
    public function test_invalid_data(array $data) : void
    {
        $request = new TwoFAccountExportRequest;
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
                'ids'     => '1',
                'otpauth' => null,
            ]],
            [[
                'ids'     => '1',
                'otpauth' => '',
            ]],
            [[
                'ids'     => '1',
                'otpauth' => 2,
            ]],
            [[
                'ids'     => '1',
                'otpauth' => 'string',
            ]],
            [[
                'ids'     => '1',
                'otpauth' => 0.1,
            ]],
            [[
                'ids'     => '1',
                'otpauth' => '01/01/2020',
            ]],
            [[
                'ids'     => '1',
                'otpauth' => '01',
            ]],
        ];
    }
}
