<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Classes\OTP;
use OTPHP\TOTP;
use OTPHP\HOTP;

class OtpTest extends TestCase
{

    /**
     * @test
     */
    public function setUp(): void
    {
        parent::setUp();
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
     * test HOTP generation (with $isPreview = false to prevent db update)
     *
     * @test
     */
    public function testHOTPGeneration()
    {
        $uri = 'otpauth://hotp/test:test@test.com?counter=16&secret=A4GRFHVIRBGY7UIW';

        $result = OTP::generate($uri, true);

        $this->assertArrayHasKey('otp', $result);
        $this->assertArrayHasKey('counter', $result);
        $this->assertArrayHasKey('nextUri', $result);
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
