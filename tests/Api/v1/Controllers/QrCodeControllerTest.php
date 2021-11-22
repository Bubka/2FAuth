<?php

namespace Tests\Api\v1\Controllers;

use App\User;
use Tests\FeatureTestCase;
use App\TwoFAccount;
use Tests\Classes\LocalFile;


/**
 * @covers \App\Api\v1\Controllers\QrCodeController
 */
class QrCodeControllerTest extends FeatureTestCase
{

    /**
     * @var \App\User
    */
    protected $user;


    /**
     * @test
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }


    /**
     * @test
     */
    public function test_show_qrcode_returns_base64_image()
    {
        $twofaccount = factory(TwoFAccount::class)->create([
            'otp_type' => 'totp',
            'account' => 'account',
            'service' => 'service',
            'secret' => 'A4GRFHZVRBGY7UIW',
            'algorithm' => 'sha1',
            'digits' => 6,
            'period' => 30,
            'legacy_uri' => 'otpauth://hotp/service:account?secret=A4GRFHZVRBGY7UIW&issuer=service',
        ]);

        $response = $this->actingAs($this->user, 'api')
            ->json('GET', '/api/v1/twofaccounts/' . $twofaccount->id . '/qrcode')
            ->assertJsonStructure([
                'qrcode',
            ])
            ->assertOk();
            
        $this->assertStringStartsWith('data:image/png;base64', $response->getData()->qrcode);
    }


    /**
     * @test
     */
    public function test_show_missing_qrcode_returns_not_found()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('GET', '/api/v1/twofaccounts/1000/qrcode')
            ->assertNotFound()
            ->assertJsonStructure([
                'message'
            ]);
    }


    /**
     * @test
     */
    public function test_decode_qrcode_return_success()
    {
        $file = LocalFile::fake()->validQrcode();

        $response = $this->withHeaders(['Content-Type' => 'multipart/form-data'])
            ->actingAs($this->user, 'api')
            ->json('POST', '/api/v1/qrcode/decode', [
                'qrcode' => $file,
                'inputFormat' => 'fileUpload'
            ])
            ->assertOk()
            ->assertExactJson([
                'data' => 'otpauth://totp/test@test.com?secret=A4GRFHVIRBGY7UIW',
            ]);
    }


    /**
     * @test
     */
    public function test_decode_missing_qrcode_return_validation_error()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/v1/qrcode/decode', [
                'qrcode' => '',
            ])
            ->assertStatus(422);
    }


    /**
     * @test
     */
    public function test_decode_invalid_qrcode_return_bad_request()
    {
        $file = LocalFile::fake()->invalidQrcode();

        $response = $this->withHeaders(['Content-Type' => 'multipart/form-data'])
            ->actingAs($this->user, 'api')
            ->json('POST', '/api/v1/qrcode/decode', [
                'qrcode' => $file,
                'inputFormat' => 'fileUpload'
            ])
            ->assertStatus(400)
            ->assertJsonStructure([
                'message',
            ]);
    }
}