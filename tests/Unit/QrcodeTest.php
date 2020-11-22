<?php

namespace Tests\Unit;

use App\User;
use Zxing\QrReader;
use Tests\TestCase;
use App\TwoFAccount;
use App\Classes\Options;
use Tests\Classes\LocalFile;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class QrcodeTest extends TestCase
{

    use WithoutMiddleware;


    /**
     * test Decode a qrcode without providing a file resource via API
     *
     * @test
     */
    public function testQrcodeDecodeWithMissingImage()
    {

        $response = $this->json('POST', '/api/qrcode/decode', [
                    'qrcode' => '',
                ])
            ->assertStatus(422);
    }


    /**
     * test decode an invalid QR code uplloaded via API
     *
     * @test
     */
    // public function testDecodeInvalidQrcode()
    // {
    //     $file = LocalFile::fake()->invalidQrcode();

    //     $response = $this->withHeaders([
    //                             'Content-Type' => 'multipart/form-data',
    //                         ])
    //                     ->json('POST', '/api/qrcode/decode', [
    //                         'qrcode' => $file,
    //                         'inputFormat' => 'fileUpload'
    //                     ]);

    //     $response->assertStatus(422);
    // }


    /**
     * test Decode a qrcode via API
     *
     * @test
     */
    public function testDecodeValidQrcode()
    {    
        Options::store(array('useBasicQrcodeReader' => true));

        $file = LocalFile::fake()->validQrcode();

        $response = $this->withHeaders(['Content-Type' => 'multipart/form-data'])
                          ->json('POST', '/api/qrcode/decode', [
                                'qrcode' => $file,
                                'inputFormat' => 'fileUpload'
                          ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                    'uri',
                 ]);
    }

}