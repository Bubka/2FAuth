<?php

namespace Tests\Api\v1\Requests;

use App\Api\v1\Requests\QrCodeDecodeRequest;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\Classes\LocalFile;
use Tests\TestCase;

/**
 * QrCodeDecodeRequestTest test class
 */
#[CoversClass(QrCodeDecodeRequest::class)]
class QrCodeDecodeRequestTest extends TestCase
{
    use WithoutMiddleware;

    #[Test]
    public function test_user_is_authorized()
    {
        Auth::shouldReceive('check')
            ->once()
            ->andReturn(true);

        $request = new QrCodeDecodeRequest();

        $this->assertTrue($request->authorize());
    }

    #[Test]
    #[DataProvider('provideValidData')]
    public function test_valid_data(array $data) : void
    {
        $request   = new QrCodeDecodeRequest();
        $validator = Validator::make($data, $request->rules());

        $this->assertFalse($validator->fails());
    }

    /**
     * Provide Valid data for validation test
     */
    public static function provideValidData() : array
    {
        $file = LocalFile::fake()->validQrcode();

        return [
            [[
                'qrcode' => $file,
            ]],
        ];
    }

    #[Test]
    #[DataProvider('provideInvalidData')]
    public function test_invalid_data(array $data) : void
    {
        $request   = new QrCodeDecodeRequest();
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
                'qrcode' => null, // required
            ]],
            [[
                'qrcode' => true, // image
            ]],
            [[
                'qrcode' => 8, // image
            ]],
            [[
                'qrcode' => 'string', // image
            ]],
        ];
    }
}
