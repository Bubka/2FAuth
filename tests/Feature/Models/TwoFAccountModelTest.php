<?php

namespace Tests\Feature\Models;

use App\Models\TwoFAccount;
use Tests\FeatureTestCase;
use Tests\Classes\OtpTestData;

/**
 * @covers \App\Models\TwoFAccount
 */
class TwoFAccountModelTest extends FeatureTestCase
{
    /**
     * App\Models\TwoFAccount $customTotpTwofaccount
     */
    protected $customTotpTwofaccount;


    /**
     * App\Models\TwoFAccount $customTotpTwofaccount
     */
    protected $customHotpTwofaccount;


    /**
     * @test
     */
    public function setUp() : void
    {
        parent::setUp();

        $this->customTotpTwofaccount = new TwoFAccount;
        $this->customTotpTwofaccount->legacy_uri = OtpTestData::TOTP_FULL_CUSTOM_URI;
        $this->customTotpTwofaccount->service = OtpTestData::SERVICE;
        $this->customTotpTwofaccount->account = OtpTestData::ACCOUNT;
        $this->customTotpTwofaccount->icon = OtpTestData::ICON;
        $this->customTotpTwofaccount->otp_type = 'totp';
        $this->customTotpTwofaccount->secret = OtpTestData::SECRET;
        $this->customTotpTwofaccount->digits = OtpTestData::DIGITS_CUSTOM;
        $this->customTotpTwofaccount->algorithm = OtpTestData::ALGORITHM_CUSTOM;
        $this->customTotpTwofaccount->period = OtpTestData::PERIOD_CUSTOM;
        $this->customTotpTwofaccount->counter = null;
        $this->customTotpTwofaccount->save();

        $this->customHotpTwofaccount = new TwoFAccount;
        $this->customHotpTwofaccount->legacy_uri = OtpTestData::HOTP_FULL_CUSTOM_URI;
        $this->customHotpTwofaccount->service = OtpTestData::SERVICE;
        $this->customHotpTwofaccount->account = OtpTestData::ACCOUNT;
        $this->customHotpTwofaccount->icon = OtpTestData::ICON;
        $this->customHotpTwofaccount->otp_type = 'hotp';
        $this->customHotpTwofaccount->secret = OtpTestData::SECRET;
        $this->customHotpTwofaccount->digits = OtpTestData::DIGITS_CUSTOM;
        $this->customHotpTwofaccount->algorithm = OtpTestData::ALGORITHM_CUSTOM;
        $this->customHotpTwofaccount->period = null;
        $this->customHotpTwofaccount->counter = OtpTestData::COUNTER_CUSTOM;
        $this->customHotpTwofaccount->save();

        $this->customSteamTotpTwofaccount = new TwoFAccount;
        $this->customSteamTotpTwofaccount->legacy_uri = OtpTestData::STEAM_TOTP_URI;
        $this->customSteamTotpTwofaccount->service = OtpTestData::STEAM;
        $this->customSteamTotpTwofaccount->account = OtpTestData::ACCOUNT;
        $this->customSteamTotpTwofaccount->otp_type = 'steamtotp';
        $this->customSteamTotpTwofaccount->secret = OtpTestData::STEAM_SECRET;
        $this->customSteamTotpTwofaccount->digits = OtpTestData::DIGITS_STEAM;
        $this->customSteamTotpTwofaccount->algorithm = OtpTestData::ALGORITHM_DEFAULT;
        $this->customSteamTotpTwofaccount->period = OtpTestData::PERIOD_DEFAULT;
        $this->customSteamTotpTwofaccount->counter = null;
        $this->customSteamTotpTwofaccount->save();
    }


    /**
    * @test
    */
    public function test_fill_with_custom_totp_uri_returns_correct_value()
    {
        $twofaccount = new TwoFAccount;
        $twofaccount->fillWithURI(OtpTestData::TOTP_FULL_CUSTOM_URI);

        $this->assertEquals('totp', $twofaccount->otp_type);
        $this->assertEquals(OtpTestData::TOTP_FULL_CUSTOM_URI, $twofaccount->legacy_uri);
        $this->assertEquals(OtpTestData::SERVICE, $twofaccount->service);
        $this->assertEquals(OtpTestData::ACCOUNT, $twofaccount->account);
        $this->assertEquals(OtpTestData::SECRET, $twofaccount->secret);
        $this->assertEquals(OtpTestData::DIGITS_CUSTOM, $twofaccount->digits);
        $this->assertEquals(OtpTestData::PERIOD_CUSTOM, $twofaccount->period);
        $this->assertEquals(null, $twofaccount->counter);
        $this->assertEquals(OtpTestData::ALGORITHM_CUSTOM, $twofaccount->algorithm);
        $this->assertStringEndsWith('.png',$twofaccount->icon);
    }


    /**
     * @test
     */
    public function test_fill_with_basic_totp_uri_returns_default_value()
    {
        $twofaccount = new TwoFAccount;
        $twofaccount->fillWithURI(OtpTestData::TOTP_SHORT_URI);

        $this->assertEquals('totp', $twofaccount->otp_type);
        $this->assertEquals(OtpTestData::TOTP_SHORT_URI, $twofaccount->legacy_uri);
        $this->assertEquals(OtpTestData::ACCOUNT, $twofaccount->account);
        $this->assertEquals(null, $twofaccount->service);
        $this->assertEquals(OtpTestData::SECRET, $twofaccount->secret);
        $this->assertEquals(OtpTestData::DIGITS_DEFAULT, $twofaccount->digits);
        $this->assertEquals(OtpTestData::PERIOD_DEFAULT, $twofaccount->period);
        $this->assertEquals(null, $twofaccount->counter);
        $this->assertEquals(OtpTestData::ALGORITHM_DEFAULT, $twofaccount->algorithm);
        $this->assertEquals(null, $twofaccount->icon);
    }


    /**
     * @test
     */
    public function test_fill_with_custom_hotp_uri_returns_correct_value()
    {
        $twofaccount = new TwoFAccount;
        $twofaccount->fillWithURI(OtpTestData::HOTP_FULL_CUSTOM_URI);

        $this->assertEquals('hotp', $twofaccount->otp_type);
        $this->assertEquals(OtpTestData::HOTP_FULL_CUSTOM_URI, $twofaccount->legacy_uri);
        $this->assertEquals(OtpTestData::SERVICE, $twofaccount->service);
        $this->assertEquals(OtpTestData::ACCOUNT, $twofaccount->account);
        $this->assertEquals(OtpTestData::SECRET, $twofaccount->secret);
        $this->assertEquals(OtpTestData::DIGITS_CUSTOM, $twofaccount->digits);
        $this->assertEquals(null, $twofaccount->period);
        $this->assertEquals(OtpTestData::COUNTER_CUSTOM, $twofaccount->counter);
        $this->assertEquals(OtpTestData::ALGORITHM_CUSTOM, $twofaccount->algorithm);
        $this->assertStringEndsWith('.png',$twofaccount->icon);
    }


    /**
     * @test
     */
    public function test_fill_with_basic_hotp_uri_returns_default_value()
    {
        $twofaccount = new TwoFAccount;
        $twofaccount->fillWithURI(OtpTestData::HOTP_SHORT_URI);

        $this->assertEquals('hotp', $twofaccount->otp_type);
        $this->assertEquals(OtpTestData::HOTP_SHORT_URI, $twofaccount->legacy_uri);
        $this->assertEquals(null, $twofaccount->service);
        $this->assertEquals(OtpTestData::ACCOUNT, $twofaccount->account);
        $this->assertEquals(OtpTestData::SECRET, $twofaccount->secret);
        $this->assertEquals(OtpTestData::DIGITS_DEFAULT, $twofaccount->digits);
        $this->assertEquals(null, $twofaccount->period);
        $this->assertEquals(OtpTestData::COUNTER_DEFAULT, $twofaccount->counter);
        $this->assertEquals(OtpTestData::ALGORITHM_DEFAULT, $twofaccount->algorithm);
        $this->assertEquals(null, $twofaccount->icon);
    }


    /**
     * @test
     */
    public function test_filled_with_uri_persists_correct_values_to_db()
    {
        $twofaccount = new TwoFAccount;
        $twofaccount->fillWithURI(OtpTestData::TOTP_SHORT_URI);
        $twofaccount->save();

        $this->assertDatabaseHas('twofaccounts', [
            'otp_type'      => 'totp',
            'legacy_uri'    => OtpTestData::TOTP_SHORT_URI,
            'service'       => null,
            'account'       => OtpTestData::ACCOUNT,
            'secret'        => OtpTestData::SECRET,
            'digits'        => OtpTestData::DIGITS_DEFAULT,
            'period'        => OtpTestData::PERIOD_DEFAULT,
            'counter'       => null,
            'algorithm'     => OtpTestData::ALGORITHM_DEFAULT,
            'icon'          => null,
        ]);
    }


    /**
     * @test
     */
    public function test_fill_with_invalid_uri_returns_ValidationException()
    {
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $twofaccount = new TwoFAccount;
        $twofaccount->fillWithURI(OtpTestData::INVALID_OTPAUTH_URI);
    }


    /**
     * @test
     */
    public function test_fill_with_uri_without_label_returns_ValidationException()
    {
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $twofaccount = new TwoFAccount;
        $twofaccount->fillWithURI('otpauth://totp/?secret='.OtpTestData::SECRET);
    }


    /**
     * @test
     */
    public function test_create_custom_totp_from_parameters_returns_correct_value()
    {
        $twofaccount = new TwoFAccount;
        $twofaccount->fillWithOtpParameters(OtpTestData::ARRAY_OF_FULL_VALID_PARAMETERS_FOR_CUSTOM_TOTP);

        $this->assertEquals('totp', $twofaccount->otp_type);
        $this->assertEquals(OtpTestData::SERVICE, $twofaccount->service);
        $this->assertEquals(OtpTestData::ACCOUNT, $twofaccount->account);
        $this->assertEquals(OtpTestData::SECRET, $twofaccount->secret);
        $this->assertEquals(OtpTestData::DIGITS_CUSTOM, $twofaccount->digits);
        $this->assertEquals(OtpTestData::PERIOD_CUSTOM, $twofaccount->period);
        $this->assertEquals(null, $twofaccount->counter);
        $this->assertEquals(OtpTestData::ALGORITHM_CUSTOM, $twofaccount->algorithm);
        $this->assertStringEndsWith('.png',$twofaccount->icon);
    }


    /**
     * @test
     */
    public function test_create_basic_totp_from_parameters_returns_correct_value()
    {
        $twofaccount = new TwoFAccount;
        $twofaccount->fillWithOtpParameters(OtpTestData::ARRAY_OF_MINIMUM_VALID_PARAMETERS_FOR_TOTP);

        $this->assertEquals('totp', $twofaccount->otp_type);
        $this->assertEquals(null, $twofaccount->service);
        $this->assertEquals(OtpTestData::ACCOUNT, $twofaccount->account);
        $this->assertEquals(OtpTestData::SECRET, $twofaccount->secret);
        $this->assertEquals(OtpTestData::DIGITS_DEFAULT, $twofaccount->digits);
        $this->assertEquals(OtpTestData::PERIOD_DEFAULT, $twofaccount->period);
        $this->assertEquals(null, $twofaccount->counter);
        $this->assertEquals(OtpTestData::ALGORITHM_DEFAULT, $twofaccount->algorithm);
        $this->assertEquals(null, $twofaccount->icon);
    }


    /**
     * @test
     */
    public function test_create_custom_hotp_from_parameters_returns_correct_value()
    {
        $twofaccount = new TwoFAccount;
        $twofaccount->fillWithOtpParameters(OtpTestData::ARRAY_OF_FULL_VALID_PARAMETERS_FOR_CUSTOM_HOTP);

        $this->assertEquals('hotp', $twofaccount->otp_type);
        $this->assertEquals(OtpTestData::SERVICE, $twofaccount->service);
        $this->assertEquals(OtpTestData::ACCOUNT, $twofaccount->account);
        $this->assertEquals(OtpTestData::SECRET, $twofaccount->secret);
        $this->assertEquals(OtpTestData::DIGITS_CUSTOM, $twofaccount->digits);
        $this->assertEquals(null, $twofaccount->period);
        $this->assertEquals(OtpTestData::COUNTER_CUSTOM, $twofaccount->counter);
        $this->assertEquals(OtpTestData::ALGORITHM_CUSTOM, $twofaccount->algorithm);
        $this->assertStringEndsWith('.png',$twofaccount->icon);
    }


    /**
     * @test
     */
    public function test_create_basic_hotp_from_parameters_returns_correct_value()
    {
        $twofaccount = new TwoFAccount;
        $twofaccount->fillWithOtpParameters(OtpTestData::ARRAY_OF_MINIMUM_VALID_PARAMETERS_FOR_HOTP);

        $this->assertEquals('hotp', $twofaccount->otp_type);
        $this->assertEquals(null, $twofaccount->service);
        $this->assertEquals(OtpTestData::ACCOUNT, $twofaccount->account);
        $this->assertEquals(OtpTestData::SECRET, $twofaccount->secret);
        $this->assertEquals(OtpTestData::DIGITS_DEFAULT, $twofaccount->digits);
        $this->assertEquals(null, $twofaccount->period);
        $this->assertEquals(OtpTestData::COUNTER_DEFAULT, $twofaccount->counter);
        $this->assertEquals(OtpTestData::ALGORITHM_DEFAULT, $twofaccount->algorithm);
        $this->assertEquals(null, $twofaccount->icon);
    }


    /**
     * @test
     */
    public function test_create_from_parameters_persists_correct_values_to_db()
    {
        $twofaccount = new TwoFAccount;
        $twofaccount->fillWithOtpParameters(OtpTestData::ARRAY_OF_MINIMUM_VALID_PARAMETERS_FOR_TOTP);
        $twofaccount->save();

        $this->assertDatabaseHas('twofaccounts', [
            'otp_type'      => 'totp',
            'legacy_uri'    => OtpTestData::TOTP_SHORT_URI,
            'service'       => null,
            'account'       => OtpTestData::ACCOUNT,
            'secret'        => OtpTestData::SECRET,
            'digits'        => OtpTestData::DIGITS_DEFAULT,
            'period'        => OtpTestData::PERIOD_DEFAULT,
            'counter'       => null,
            'algorithm'     => OtpTestData::ALGORITHM_DEFAULT,
            'icon'          => null,
        ]);
    }

    
    /**
     * @test
     */
    public function test_create_from_unsupported_parameters_returns_unsupportedOtpTypeException()
    {
        $this->expectException(\App\Exceptions\UnsupportedOtpTypeException::class);
        $twofaccount = new TwoFAccount;
        $twofaccount->fillWithOtpParameters(OtpTestData::ARRAY_OF_PARAMETERS_FOR_UNSUPPORTED_OTP_TYPE);
    }

    
    /**
     * @test
     */
    public function test_create_from_invalid_parameters_type_returns_InvalidOtpParameterException()
    {
        $this->expectException(\App\Exceptions\InvalidOtpParameterException::class);
        $twofaccount = new TwoFAccount;
        $twofaccount->fillWithOtpParameters([
            'account'   => OtpTestData::ACCOUNT,
            'otp_type'  => 'totp',
            'digits' => 'notsupported',
        ]);
    }

    
    /**
     * @test
     */
    public function test_create_from_invalid_parameters_returns_InvalidOtpParameterException()
    {
        $this->expectException(\App\Exceptions\InvalidOtpParameterException::class);
        $twofaccount = new TwoFAccount;
        $twofaccount->fillWithOtpParameters([
            'account'   => OtpTestData::ACCOUNT,
            'otp_type'  => 'totp',
            'algorithm' => 'notsupported',
        ]);
    }


    /**
     * @test
     */
    public function test_update_totp_returns_updated_model()
    {
        $twofaccount = $this->customTotpTwofaccount;
        $twofaccount->fillWithOtpParameters(OtpTestData::ARRAY_OF_MINIMUM_VALID_PARAMETERS_FOR_TOTP);

        $this->assertEquals('totp', $twofaccount->otp_type);
        $this->assertEquals(null, $twofaccount->service);
        $this->assertEquals(OtpTestData::ACCOUNT, $twofaccount->account);
        $this->assertEquals(OtpTestData::SECRET, $twofaccount->secret);
        $this->assertEquals(OtpTestData::DIGITS_DEFAULT, $twofaccount->digits);
        $this->assertEquals(OtpTestData::PERIOD_DEFAULT, $twofaccount->period);
        $this->assertEquals(null, $twofaccount->counter);
        $this->assertEquals(OtpTestData::ALGORITHM_DEFAULT, $twofaccount->algorithm);
        $this->assertEquals(null, $twofaccount->counter);
        $this->assertEquals(null, $twofaccount->icon);
    }


    /**
     * @test
     */
    public function test_update_hotp_returns_updated_model()
    {
        $twofaccount = $this->customTotpTwofaccount;
        $twofaccount->fillWithOtpParameters(OtpTestData::ARRAY_OF_MINIMUM_VALID_PARAMETERS_FOR_HOTP);

        $this->assertEquals('hotp', $twofaccount->otp_type);
        $this->assertEquals(null, $twofaccount->service);
        $this->assertEquals(OtpTestData::ACCOUNT, $twofaccount->account);
        $this->assertEquals(OtpTestData::SECRET, $twofaccount->secret);
        $this->assertEquals(OtpTestData::DIGITS_DEFAULT, $twofaccount->digits);
        $this->assertEquals(null, $twofaccount->period);
        $this->assertEquals(OtpTestData::COUNTER_DEFAULT, $twofaccount->counter);
        $this->assertEquals(OtpTestData::ALGORITHM_DEFAULT, $twofaccount->algorithm);
        $this->assertEquals(null, $twofaccount->counter);
        $this->assertEquals(null, $twofaccount->icon);
    }


    /**
     * @test
     */
    public function test_update_totp_persists_updated_model()
    {
        $twofaccount = $this->customTotpTwofaccount;
        $twofaccount->fillWithOtpParameters(OtpTestData::ARRAY_OF_MINIMUM_VALID_PARAMETERS_FOR_TOTP);
        $twofaccount->save();

        $this->assertDatabaseHas('twofaccounts', [
            'otp_type'      => 'totp',
            'service'       => null,
            'account'       => OtpTestData::ACCOUNT,
            'secret'        => OtpTestData::SECRET,
            'digits'        => OtpTestData::DIGITS_DEFAULT,
            'period'        => OtpTestData::PERIOD_DEFAULT,
            'counter'       => null,
            'algorithm'     => OtpTestData::ALGORITHM_DEFAULT,
            'icon'          => null,
        ]);
    }


    /**
     * @test
     */
    public function test_getOTP_for_totp_returns_the_same_password()
    {
        $twofaccount = new TwoFAccount;

        $otp_from_model = $this->customTotpTwofaccount->getOTP();
        $otp_from_uri = $twofaccount->fillWithURI(OtpTestData::TOTP_FULL_CUSTOM_URI)->getOTP();

        if ($otp_from_model->generated_at === $otp_from_uri->generated_at) {
            $this->assertEquals($otp_from_model, $otp_from_uri);
        }

        $otp_from_model = $this->customTotpTwofaccount->getOTP();
        $otp_from_parameters = $twofaccount->fillWithOtpParameters(OtpTestData::ARRAY_OF_FULL_VALID_PARAMETERS_FOR_CUSTOM_TOTP)->getOTP();

        if ($otp_from_model->generated_at === $otp_from_parameters->generated_at) {
            $this->assertEquals($otp_from_model, $otp_from_parameters);
        }
    }


    /**
     * @test
     */
    public function test_getOTP_for_hotp_returns_the_same_password()
    {
        $twofaccount = new TwoFAccount;

        $otp_from_model = $this->customHotpTwofaccount->getOTP();
        $otp_from_uri = $twofaccount->fillWithURI(OtpTestData::HOTP_FULL_CUSTOM_URI)->getOTP();

        $this->assertEquals($otp_from_model, $otp_from_uri);

        $otp_from_parameters = $twofaccount->fillWithOtpParameters(OtpTestData::ARRAY_OF_FULL_VALID_PARAMETERS_FOR_CUSTOM_HOTP)->getOTP();

        $this->assertEquals($otp_from_model, $otp_from_parameters);
    }


    /**
     * @test
     */
    public function test_getOTP_for_steamtotp_returns_the_same_password()
    {
        $twofaccount = new TwoFAccount;

        $otp_from_model = $this->customSteamTotpTwofaccount->getOTP();
        $otp_from_uri = $twofaccount->fillWithURI(OtpTestData::STEAM_TOTP_URI)->getOTP();

        if ($otp_from_model->generated_at === $otp_from_uri->generated_at) {
            $this->assertEquals($otp_from_model, $otp_from_uri);
        }

        $otp_from_model = $this->customSteamTotpTwofaccount->getOTP();
        $otp_from_parameters = $twofaccount->fillWithOtpParameters(OtpTestData::ARRAY_OF_FULL_VALID_PARAMETERS_FOR_STEAM_TOTP)->getOTP();

        if ($otp_from_model->generated_at === $otp_from_parameters->generated_at) {
            $this->assertEquals($otp_from_model, $otp_from_parameters);
        }
    }


    /**
     * @test
     */
    public function test_getOTP_for_totp_with_invalid_secret_returns_InvalidSecretException()
    {
        $twofaccount = new TwoFAccount;

        $this->expectException(\App\Exceptions\InvalidSecretException::class);
        $otp_from_uri = $twofaccount->fillWithURI('otpauth://totp/'.OtpTestData::ACCOUNT.'?secret=0')->getOTP();
    }


    /**
     * @test
     */
    public function test_getOTP_for_totp_with_undecipherable_secret_returns_UndecipherableException()
    {
        $twofaccount = new TwoFAccount;

        $this->expectException(\App\Exceptions\UndecipherableException::class);
        $otp_from_uri = $twofaccount->fillWithOtpParameters([
            'account'   => OtpTestData::ACCOUNT,
            'otp_type'  => 'totp',
            'secret'    => __('errors.indecipherable'),
        ])->getOTP();
    }


    /**
     * @test
     */
    public function test_getURI_for_custom_totp_model_returns_uri()
    {
        $uri = $this->customTotpTwofaccount->getURI();
        
        $this->assertStringContainsString('otpauth://totp/', $uri);
        $this->assertStringContainsString(OtpTestData::SERVICE, $uri);
        $this->assertStringContainsString(OtpTestData::ACCOUNT, $uri);
        $this->assertStringContainsString('secret='.OtpTestData::SECRET, $uri);
        $this->assertStringContainsString('digits='.OtpTestData::DIGITS_CUSTOM, $uri);
        $this->assertStringContainsString('period='.OtpTestData::PERIOD_CUSTOM, $uri);
        $this->assertStringContainsString('algorithm='.OtpTestData::ALGORITHM_CUSTOM, $uri);
    }


    /**
     * @test
     */
    public function test_getURI_for_custom_hotp_model_returns_uri()
    {
        $uri = $this->customHotpTwofaccount->getURI();
        
        $this->assertStringContainsString('otpauth://hotp/', $uri);
        $this->assertStringContainsString(OtpTestData::SERVICE, $uri);
        $this->assertStringContainsString(OtpTestData::ACCOUNT, $uri);
        $this->assertStringContainsString('secret='.OtpTestData::SECRET, $uri);
        $this->assertStringContainsString('digits='.OtpTestData::DIGITS_CUSTOM, $uri);
        $this->assertStringContainsString('counter='.OtpTestData::COUNTER_CUSTOM, $uri);
        $this->assertStringContainsString('algorithm='.OtpTestData::ALGORITHM_CUSTOM, $uri);
    }

}