<?php

namespace Tests\Unit;

use Zxing\QrReader;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class QrcodeTest extends TestCase
{

    use WithoutMiddleware;


    /**
     * test upload icon with no missing image resource via API
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
     * test Qrcode decode with an invalid image resource via API
     *
     * @test
     */
    // public function testQrcodeDecodeWithInvalidImage()
    // {

    //     Storage::fake('qrcodes');

    //     $file = UploadedFile::fake()->image('qrcode.jpg');

    //     $this->expectException(\Illuminate\Validation\ValidationException::class);

    //     $response = $this->json('POST', '/api/qrcode/decode', [
    //                     'qrcode' => $file,
    //                 ]);
    // }


    /**
     * test delete an uploaded icon via API
     *
     * @test
     */
    public function testDecodeQrcode()
    {
        //Storage::fake('qrcodes');

        $image = 'data:image/png;base64,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';


        //Storage::put('tests/qrcodeTest.png', base64_decode($image));

        //$this->assertFileExists('storage/app/tests/qrcodeTest.png');

        $response = $this->withHeaders([
                                'Content-Type' => 'multipart/form-data',
                            ])
                            ->json('POST', '/api/qrcode/decode', [
                                'qrcode' => $image
                            ]);

        $response->dump();
        $response->dumpHeaders();

        $response->assertStatus(200);
    }

}