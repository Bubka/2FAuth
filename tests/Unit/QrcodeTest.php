<?php

namespace Tests\Unit;

use Zxing\QrReader;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use Tests\Classes\LocalFile;
use App\Classes\Options;

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
     * test delete an uploaded icon via API
     *
     * @test
     */
    public function testDecodeInvalidQrcode()
    {
        $file = LocalFile::fake()->invalidQrcode();

        $response = $this->withHeaders([
                                'Content-Type' => 'multipart/form-data',
                            ])
                            ->json('POST', '/api/qrcode/decode', [
                                'qrcode' => $file
                            ]);

        $response->assertStatus(422);
    }


    /**
     * test Decode a qrcode via API
     *
     * @test
     */
    public function testDecodeValidUri()
    {
        $response = $this->json('POST', '/api/qrcode/decode', [
                                'uri' => 'otpauth://totp/test@test.com?secret=A4GRFHVIRBGY7UIW'
                          ]);

        $response->assertStatus(200)
                 ->assertJsonFragment([
                    'service' => 'test@test.com',
                    'account' => '',
                    'options' => [
                        'algorithm' => 'sha1',
                        'digits' => 6,
                        'epoch' => 0,
                        'period' => 30,
                        'secret' => 'A4GRFHVIRBGY7UIW'
                    ],
                    'uri' => 'otpauth://totp/test@test.com?secret=A4GRFHVIRBGY7UIW'
                 ]);
    }


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
                                'qrcode' => $file
                          ]);

        $response->assertStatus(200)
                 ->assertJsonFragment([
                    'service' => 'test@test.com',
                    'account' => '',
                    'options' => [
                        'algorithm' => 'sha1',
                        'digits' => 6,
                        'epoch' => 0,
                        'period' => 30,
                        'secret' => 'A4GRFHVIRBGY7UIW'
                    ],
                    'uri' => 'otpauth://totp/test@test.com?secret=A4GRFHVIRBGY7UIW'
                 ]);
    }

}