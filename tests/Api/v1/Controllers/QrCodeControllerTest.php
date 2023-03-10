<?php

namespace Tests\Api\v1\Controllers;

use App\Models\TwoFAccount;
use App\Models\User;
use Tests\Classes\LocalFile;
use Tests\FeatureTestCase;

/**
 * @covers \App\Api\v1\Controllers\QrCodeController
 */
class QrCodeControllerTest extends FeatureTestCase
{
    /**
     * @var \App\Models\User|\Illuminate\Contracts\Auth\Authenticatable
     */
    protected $user;

    protected $anotherUser;

    /**
     * @var App\Models\TwoFAccount
     */
    protected $twofaccount;

    /**
     * @test
     */
    public function setUp() : void
    {
        parent::setUp();

        $this->user        = User::factory()->create();
        $this->anotherUser = User::factory()->create();

        $this->twofaccount = TwoFAccount::factory()->for($this->user)->create([
            'otp_type'   => 'totp',
            'account'    => 'account',
            'service'    => 'service',
            'secret'     => 'A4GRFHZVRBGY7UIW',
            'algorithm'  => 'sha1',
            'digits'     => 6,
            'period'     => 30,
            'legacy_uri' => 'otpauth://hotp/service:account?secret=A4GRFHZVRBGY7UIW&issuer=service',
        ]);
    }

    /**
     * @test
     */
    public function test_show_qrcode_returns_base64_image()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('GET', '/api/v1/twofaccounts/' . $this->twofaccount->id . '/qrcode')
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
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('GET', '/api/v1/twofaccounts/1000/qrcode')
            ->assertNotFound()
            ->assertJsonStructure([
                'message',
            ]);
    }

    /**
     * @test
     */
    public function test_show_qrcode_of_another_user_is_forbidden()
    {
        $response = $this->actingAs($this->anotherUser, 'api-guard')
            ->json('GET', '/api/v1/twofaccounts/' . $this->twofaccount->id . '/qrcode')
            ->assertForbidden()
            ->assertJsonStructure([
                'message',
            ]);
    }

    /**
     * @test
     */
    public function test_decode_qrcode_return_success()
    {
        $file = LocalFile::fake()->validQrcode();

        $response = $this->withHeaders(['Content-Type' => 'multipart/form-data'])
            ->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/qrcode/decode', [
                'qrcode'      => $file,
                'inputFormat' => 'fileUpload',
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
        $response = $this->actingAs($this->user, 'api-guard')
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
            ->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/qrcode/decode', [
                'qrcode'      => $file,
                'inputFormat' => 'fileUpload',
            ])
            ->assertStatus(400)
            ->assertJsonStructure([
                'message',
            ]);
    }
}
