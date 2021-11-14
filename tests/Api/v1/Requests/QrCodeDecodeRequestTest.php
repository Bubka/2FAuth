<?php

namespace Tests\Api\v1\Requests;

use App\Api\v1\Requests\QrCodeDecodeRequest;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tests\Classes\LocalFile;
use Tests\TestCase;

class QrCodeDecodeRequestTest extends TestCase
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

        $request = new QrCodeDecodeRequest();
    
        $this->assertTrue($request->authorize());
    }

    /**
     * @dataProvider provideValidData
     */
    public function test_valid_data(array $data) : void
    {
        $request = new QrCodeDecodeRequest();
        $validator = Validator::make($data, $request->rules());

        $this->assertFalse($validator->fails());
    }

    /**
     * Provide Valid data for validation test
     */
    public function provideValidData() : array
    {
        $file = LocalFile::fake()->validQrcode();

        return [
            [[
                'qrcode' => $file
            ]],
        ];
    }

    /**
     * @dataProvider provideInvalidData
     */
    public function test_invalid_data(array $data) : void
    {
        $request = new QrCodeDecodeRequest();
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
                'qrcode' => null // required
            ]],
            [[
                'qrcode' => true // image
            ]],
            [[
                'qrcode' => 8 // image
            ]],
            [[
                'qrcode' => 'string' // image
            ]],
        ];
    }

}