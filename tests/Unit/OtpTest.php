<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;
use App\Classes\OTP;
use OTPHP\TOTP;
use OTPHP\HOTP;
use App\TwoFAccount;

class OtpTest extends TestCase
{

    /** @var \App\User */
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
     * test TOTP generation (with $isPreview = false to prevent db update)
     *
     * @test
     */
    public function testTOTPGeneration()
    {
        $uri = 'otpauth://totp/test@test.com?secret=A4GRFHVIRBGY7UIW&issuer=test';

        $result = OTP::generate($uri, true);

        $this->assertArrayHasKey('otp', $result);
        $this->assertArrayHasKey('position', $result);
    }


    /**
     * test HOTP generation for Preview
     *
     * @test
     */
    public function testHOTPGenerationforPreview()
    {
        $uri = 'otpauth://hotp/test:test@test.com?counter=16&secret=A4GRFHVIRBGY7UIW';

        $result = OTP::generate($uri, true);

        $this->assertArrayHasKey('otp', $result);
        $this->assertArrayHasKey('counter', $result);
        $this->assertArrayHasKey('nextUri', $result);
    }


    /**
     * test HOTP generation for existing HOTP account
     *
     * @test
     */
    public function testHOTPGenerationForExistingAccount()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/twofaccounts', [
                    'service' => 'hotp',
                    'account' => 'test.com',
                    'uri' => 'otpauth://hotp/test@test.com?counter=1&secret=A4GRFHZVRBGY7UIW',
                    'icon' => 'test.png',
                ]);

        $testedAccount = TwoFAccount::where('service', 'hotp')->first();

        $result = OTP::generate($testedAccount->uri, false);

        $testedAccount->refresh();

        $this->assertEquals($testedAccount->uri, 'otpauth://hotp/test@test.com?counter=2&secret=A4GRFHZVRBGY7UIW');
    }


    /**
     * test if provided TOTP uri is valid
     *
     * @test
     */
    public function testTOTPUriIsValid()
    {
        $uri = 'otpauth://totp/test@test.com?secret=A4GRFHVIRBGY7UIW&issuer=test';

        $result = OTP::get($uri);

        $this->assertInstanceOf(TOTP::class, $result);
    }


    /**
     * test if provided HOTP uri is valid
     *
     * @test
     */
    public function testHOTPUriIsValid()
    {
        $uri = 'otpauth://hotp/test:test@test.com?counter=16&secret=A4GRFHVIRBGY7UIW';

        $result = OTP::get($uri);

        $this->assertInstanceOf(HOTP::class, $result);
    }


    /**
     * test invalid privded uri returns a ValidationException exception
     *
     * @test
     */
    public function testInvalidUriReturnValidationException()
    {
        $uri = 'otpauth://totp/';

        $this->expectException(\Illuminate\Validation\ValidationException::class);

        OTP::get($uri);
    }

}
