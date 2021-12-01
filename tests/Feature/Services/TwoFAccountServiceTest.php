<?php

namespace Tests\Feature\Services;

use App\Group;
use App\TwoFAccount;
use Tests\FeatureTestCase;
use Illuminate\Support\Facades\DB;


/**
 * @covers \App\Services\TwoFAccountService
 */
class TwoFAccountServiceTest extends FeatureTestCase
{
    /**
     * App\Services\SettingService $settingService
     */
    protected $twofaccountService;


    /**
     * App\TwoFAccount $customTotpTwofaccount
     */
    protected $customTotpTwofaccount;


    /**
     * App\Group $group
     */
    protected $group;


    /**
     * App\TwoFAccount $customTotpTwofaccount
     */
    protected $customHotpTwofaccount;

    private const ACCOUNT = 'account';
    private const SERVICE = 'service';
    private const SECRET = 'A4GRFHVVRBGY7UIW';
    private const ALGORITHM_DEFAULT = 'sha1';
    private const ALGORITHM_CUSTOM = 'sha256';
    private const DIGITS_DEFAULT = 6;
    private const DIGITS_CUSTOM = 7;
    private const PERIOD_DEFAULT = 30;
    private const PERIOD_CUSTOM = 40;
    private const COUNTER_DEFAULT = 0;
    private const COUNTER_CUSTOM = 5;
    private const IMAGE = 'https%3A%2F%2Fen.opensuse.org%2Fimages%2F4%2F44%2FButton-filled-colour.png';
    private const ICON = 'test.png';
    private const TOTP_FULL_CUSTOM_URI = 'otpauth://totp/'.self::SERVICE.':'.self::ACCOUNT.'?secret='.self::SECRET.'&issuer='.self::SERVICE.'&digits='.self::DIGITS_CUSTOM.'&period='.self::PERIOD_CUSTOM.'&algorithm='.self::ALGORITHM_CUSTOM.'&image='.self::IMAGE;
    private const HOTP_FULL_CUSTOM_URI = 'otpauth://hotp/'.self::SERVICE.':'.self::ACCOUNT.'?secret='.self::SECRET.'&issuer='.self::SERVICE.'&digits='.self::DIGITS_CUSTOM.'&counter='.self::COUNTER_CUSTOM.'&algorithm='.self::ALGORITHM_CUSTOM.'&image='.self::IMAGE;
    private const TOTP_SHORT_URI = 'otpauth://totp/'.self::ACCOUNT.'?secret='.self::SECRET;
    private const HOTP_SHORT_URI = 'otpauth://hotp/'.self::ACCOUNT.'?secret='.self::SECRET;
    private const TOTP_URI_WITH_UNREACHABLE_IMAGE = 'otpauth://totp/service:account?secret=A4GRFHVVRBGY7UIW&image=https%3A%2F%2Fen.opensuse.org%2Fimage.png';
    private const INVALID_OTPAUTH_URI = 'otpauth://Xotp/'.self::ACCOUNT.'?secret='.self::SECRET;
    private const ARRAY_OF_FULL_VALID_PARAMETERS_FOR_CUSTOM_TOTP = [
        'service'   => self::SERVICE,
        'account'   => self::ACCOUNT,
        'icon'      => self::ICON,
        'otp_type'  => 'totp',
        'secret'    => self::SECRET,
        'digits'    => self::DIGITS_CUSTOM,
        'algorithm' => self::ALGORITHM_CUSTOM,
        'period'    => self::PERIOD_CUSTOM,
        'counter'   => null,
    ];
    private const ARRAY_OF_MINIMUM_VALID_PARAMETERS_FOR_TOTP = [
        'account'   => self::ACCOUNT,
        'otp_type'  => 'totp',
        'secret'    => self::SECRET,
    ];
    private const ARRAY_OF_PARAMETERS_FOR_UNSUPPORTED_OTP_TYPE = [
        'account'   => self::ACCOUNT,
        'otp_type'  => 'Xotp',
        'secret'    => self::SECRET,
    ];
    private const ARRAY_OF_INVALID_PARAMETERS_FOR_TOTP = [
        'account'   => self::ACCOUNT,
        'otp_type'  => 'totp',
        'secret'    => 0,
    ];
    private const ARRAY_OF_FULL_VALID_PARAMETERS_FOR_CUSTOM_HOTP = [
        'service'   => self::SERVICE,
        'account'   => self::ACCOUNT,
        'icon'      => self::ICON,
        'otp_type'  => 'hotp',
        'secret'    => self::SECRET,
        'digits'    => self::DIGITS_CUSTOM,
        'algorithm' => self::ALGORITHM_CUSTOM,
        'period'    => null,
        'counter'   => self::COUNTER_CUSTOM,
    ];
    private const ARRAY_OF_MINIMUM_VALID_PARAMETERS_FOR_HOTP = [
        'account'   => self::ACCOUNT,
        'otp_type'  => 'hotp',
        'secret'    => self::SECRET,
    ];


    /**
     * @test
     */
    public function setUp() : void
    {
        parent::setUp();

        $this->twofaccountService = $this->app->make('App\Services\TwoFAccountService');

        $this->customTotpTwofaccount = new TwoFAccount;
        $this->customTotpTwofaccount->legacy_uri = self::TOTP_FULL_CUSTOM_URI;
        $this->customTotpTwofaccount->service = self::SERVICE;
        $this->customTotpTwofaccount->account = self::ACCOUNT;
        $this->customTotpTwofaccount->icon = self::ICON;
        $this->customTotpTwofaccount->otp_type = 'totp';
        $this->customTotpTwofaccount->secret = self::SECRET;
        $this->customTotpTwofaccount->digits = self::DIGITS_CUSTOM;
        $this->customTotpTwofaccount->algorithm = self::ALGORITHM_CUSTOM;
        $this->customTotpTwofaccount->period = self::PERIOD_CUSTOM;
        $this->customTotpTwofaccount->counter = null;
        $this->customTotpTwofaccount->save();

        $this->customHotpTwofaccount = new TwoFAccount;
        $this->customHotpTwofaccount->legacy_uri = self::HOTP_FULL_CUSTOM_URI;
        $this->customHotpTwofaccount->service = self::SERVICE;
        $this->customHotpTwofaccount->account = self::ACCOUNT;
        $this->customHotpTwofaccount->icon = self::ICON;
        $this->customHotpTwofaccount->otp_type = 'hotp';
        $this->customHotpTwofaccount->secret = self::SECRET;
        $this->customHotpTwofaccount->digits = self::DIGITS_CUSTOM;
        $this->customHotpTwofaccount->algorithm = self::ALGORITHM_CUSTOM;
        $this->customHotpTwofaccount->period = null;
        $this->customHotpTwofaccount->counter = self::COUNTER_CUSTOM;
        $this->customHotpTwofaccount->save();


        $this->group = new Group;
        $this->group->name = 'MyGroup';
        $this->group->save();
    }


    /**
     * @test
     */
    public function test_create_custom_totp_from_uri_returns_correct_value()
    {
        $twofaccount = $this->twofaccountService->createFromUri(self::TOTP_FULL_CUSTOM_URI);

        $this->assertEquals('totp', $twofaccount->otp_type);
        $this->assertEquals(self::TOTP_FULL_CUSTOM_URI, $twofaccount->legacy_uri);
        $this->assertEquals(self::SERVICE, $twofaccount->service);
        $this->assertEquals(self::ACCOUNT, $twofaccount->account);
        $this->assertEquals(self::SECRET, $twofaccount->secret);
        $this->assertEquals(self::DIGITS_CUSTOM, $twofaccount->digits);
        $this->assertEquals(self::PERIOD_CUSTOM, $twofaccount->period);
        $this->assertEquals(null, $twofaccount->counter);
        $this->assertEquals(self::ALGORITHM_CUSTOM, $twofaccount->algorithm);
        $this->assertStringEndsWith('.png',$twofaccount->icon);
    }


    /**
     * @test
     */
    public function test_create_basic_totp_from_uri_returns_default_value()
    {
        $twofaccount = $this->twofaccountService->createFromUri(self::TOTP_SHORT_URI);

        $this->assertEquals('totp', $twofaccount->otp_type);
        $this->assertEquals(self::TOTP_SHORT_URI, $twofaccount->legacy_uri);
        $this->assertEquals(self::ACCOUNT, $twofaccount->account);
        $this->assertEquals(null, $twofaccount->service);
        $this->assertEquals(self::SECRET, $twofaccount->secret);
        $this->assertEquals(self::DIGITS_DEFAULT, $twofaccount->digits);
        $this->assertEquals(self::PERIOD_DEFAULT, $twofaccount->period);
        $this->assertEquals(null, $twofaccount->counter);
        $this->assertEquals(self::ALGORITHM_DEFAULT, $twofaccount->algorithm);
        $this->assertEquals(null, $twofaccount->icon);
    }


    /**
     * @test
     */
    public function test_create_custom_hotp_from_uri_returns_correct_value()
    {
        $twofaccount = $this->twofaccountService->createFromUri(self::HOTP_FULL_CUSTOM_URI);

        $this->assertEquals('hotp', $twofaccount->otp_type);
        $this->assertEquals(self::HOTP_FULL_CUSTOM_URI, $twofaccount->legacy_uri);
        $this->assertEquals(self::SERVICE, $twofaccount->service);
        $this->assertEquals(self::ACCOUNT, $twofaccount->account);
        $this->assertEquals(self::SECRET, $twofaccount->secret);
        $this->assertEquals(self::DIGITS_CUSTOM, $twofaccount->digits);
        $this->assertEquals(null, $twofaccount->period);
        $this->assertEquals(self::COUNTER_CUSTOM, $twofaccount->counter);
        $this->assertEquals(self::ALGORITHM_CUSTOM, $twofaccount->algorithm);
        $this->assertStringEndsWith('.png',$twofaccount->icon);
    }


    /**
     * @test
     */
    public function test_create_basic_hotp_from_uri_returns_default_value()
    {
        $twofaccount = $this->twofaccountService->createFromUri(self::HOTP_SHORT_URI);

        $this->assertEquals('hotp', $twofaccount->otp_type);
        $this->assertEquals(self::HOTP_SHORT_URI, $twofaccount->legacy_uri);
        $this->assertEquals(null, $twofaccount->service);
        $this->assertEquals(self::ACCOUNT, $twofaccount->account);
        $this->assertEquals(self::SECRET, $twofaccount->secret);
        $this->assertEquals(self::DIGITS_DEFAULT, $twofaccount->digits);
        $this->assertEquals(null, $twofaccount->period);
        $this->assertEquals(self::COUNTER_DEFAULT, $twofaccount->counter);
        $this->assertEquals(self::ALGORITHM_DEFAULT, $twofaccount->algorithm);
        $this->assertEquals(null, $twofaccount->icon);
    }


    /**
     * @test
     */
    public function test_create_from_uri_persists_to_db()
    {
        $twofaccount = $this->twofaccountService->createFromUri(self::TOTP_SHORT_URI);

        $this->assertDatabaseHas('twofaccounts', [
            'otp_type'      => 'totp',
            'legacy_uri'    => self::TOTP_SHORT_URI,
            'service'       => null,
            'account'       => self::ACCOUNT,
            'secret'        => self::SECRET,
            'digits'        => self::DIGITS_DEFAULT,
            'period'        => self::PERIOD_DEFAULT,
            'counter'       => null,
            'algorithm'     => self::ALGORITHM_DEFAULT,
            'icon'          => null,
        ]);
    }


    /**
     * @test
     */
    public function test_create_from_uri_does_not_persist_to_db()
    {
        $twofaccount = $this->twofaccountService->createFromUri(self::TOTP_SHORT_URI, false);

        $this->assertDatabaseMissing('twofaccounts', [
            'otp_type'      => 'totp',
            'legacy_uri'    => self::TOTP_SHORT_URI,
            'service'       => null,
            'account'       => self::ACCOUNT,
            'secret'        => self::SECRET,
            'digits'        => self::DIGITS_DEFAULT,
            'period'        => self::PERIOD_DEFAULT,
            'counter'       => null,
            'algorithm'     => self::ALGORITHM_DEFAULT,
            'icon'          => null,
        ]);
    }


    /**
     * @test
     */
    public function test_create_from_invalid_uri_returns_ValidationException()
    {
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $twofaccount = $this->twofaccountService->createFromUri(self::INVALID_OTPAUTH_URI);
    }


    /**
     * @test
     */
    public function test_create_from_uri_without_label_returns_ValidationException()
    {
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $twofaccount = $this->twofaccountService->createFromUri('otpauth://totp/?secret='.self::SECRET);
    }


    /**
     * @test
     */
    public function test_create_custom_totp_from_parameters_returns_correct_value()
    {
        $twofaccount = $this->twofaccountService->createFromParameters(self::ARRAY_OF_FULL_VALID_PARAMETERS_FOR_CUSTOM_TOTP);

        $this->assertEquals('totp', $twofaccount->otp_type);
        $this->assertEquals(self::SERVICE, $twofaccount->service);
        $this->assertEquals(self::ACCOUNT, $twofaccount->account);
        $this->assertEquals(self::SECRET, $twofaccount->secret);
        $this->assertEquals(self::DIGITS_CUSTOM, $twofaccount->digits);
        $this->assertEquals(self::PERIOD_CUSTOM, $twofaccount->period);
        $this->assertEquals(null, $twofaccount->counter);
        $this->assertEquals(self::ALGORITHM_CUSTOM, $twofaccount->algorithm);
        $this->assertStringEndsWith('.png',$twofaccount->icon);
    }


    /**
     * @test
     */
    public function test_create_basic_totp_from_parameters_returns_correct_value()
    {
        $twofaccount = $this->twofaccountService->createFromParameters(self::ARRAY_OF_MINIMUM_VALID_PARAMETERS_FOR_TOTP);

        $this->assertEquals('totp', $twofaccount->otp_type);
        $this->assertEquals(null, $twofaccount->service);
        $this->assertEquals(self::ACCOUNT, $twofaccount->account);
        $this->assertEquals(self::SECRET, $twofaccount->secret);
        $this->assertEquals(self::DIGITS_DEFAULT, $twofaccount->digits);
        $this->assertEquals(self::PERIOD_DEFAULT, $twofaccount->period);
        $this->assertEquals(null, $twofaccount->counter);
        $this->assertEquals(self::ALGORITHM_DEFAULT, $twofaccount->algorithm);
        $this->assertEquals(null, $twofaccount->icon);
    }


    /**
     * @test
     */
    public function test_create_custom_hotp_from_parameters_returns_correct_value()
    {
        $twofaccount = $this->twofaccountService->createFromParameters(self::ARRAY_OF_FULL_VALID_PARAMETERS_FOR_CUSTOM_HOTP);

        $this->assertEquals('hotp', $twofaccount->otp_type);
        $this->assertEquals(self::SERVICE, $twofaccount->service);
        $this->assertEquals(self::ACCOUNT, $twofaccount->account);
        $this->assertEquals(self::SECRET, $twofaccount->secret);
        $this->assertEquals(self::DIGITS_CUSTOM, $twofaccount->digits);
        $this->assertEquals(null, $twofaccount->period);
        $this->assertEquals(self::COUNTER_CUSTOM, $twofaccount->counter);
        $this->assertEquals(self::ALGORITHM_CUSTOM, $twofaccount->algorithm);
        $this->assertStringEndsWith('.png',$twofaccount->icon);
    }


    /**
     * @test
     */
    public function test_create_basic_hotp_from_parameters_returns_correct_value()
    {
        $twofaccount = $this->twofaccountService->createFromParameters(self::ARRAY_OF_MINIMUM_VALID_PARAMETERS_FOR_HOTP);

        $this->assertEquals('hotp', $twofaccount->otp_type);
        $this->assertEquals(null, $twofaccount->service);
        $this->assertEquals(self::ACCOUNT, $twofaccount->account);
        $this->assertEquals(self::SECRET, $twofaccount->secret);
        $this->assertEquals(self::DIGITS_DEFAULT, $twofaccount->digits);
        $this->assertEquals(null, $twofaccount->period);
        $this->assertEquals(self::COUNTER_DEFAULT, $twofaccount->counter);
        $this->assertEquals(self::ALGORITHM_DEFAULT, $twofaccount->algorithm);
        $this->assertEquals(null, $twofaccount->icon);
    }


    /**
     * @test
     */
    public function test_create_from_parameters_persists_to_db()
    {
        $twofaccount = $this->twofaccountService->createFromParameters(self::ARRAY_OF_MINIMUM_VALID_PARAMETERS_FOR_TOTP);

        $this->assertDatabaseHas('twofaccounts', [
            'otp_type'      => 'totp',
            'legacy_uri'    => self::TOTP_SHORT_URI,
            'service'       => null,
            'account'       => self::ACCOUNT,
            'secret'        => self::SECRET,
            'digits'        => self::DIGITS_DEFAULT,
            'period'        => self::PERIOD_DEFAULT,
            'counter'       => null,
            'algorithm'     => self::ALGORITHM_DEFAULT,
            'icon'          => null,
        ]);
    }


    /**
     * @test
     */
    public function test_create_from_parameters_does_not_persist_to_db()
    {
        $twofaccount = $this->twofaccountService->createFromParameters(self::ARRAY_OF_MINIMUM_VALID_PARAMETERS_FOR_TOTP, false);

        $this->assertDatabaseMissing('twofaccounts', [
            'otp_type'      => 'totp',
            'legacy_uri'    => self::TOTP_SHORT_URI,
            'service'       => null,
            'account'       => self::ACCOUNT,
            'secret'        => self::SECRET,
            'digits'        => self::DIGITS_DEFAULT,
            'period'        => self::PERIOD_DEFAULT,
            'counter'       => null,
            'algorithm'     => self::ALGORITHM_DEFAULT,
            'icon'          => null,
        ]);
    }

    
    /**
     * @test
     */
    public function test_create_from_unsupported_parameters_returns_ValidationException()
    {
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $twofaccount = $this->twofaccountService->createFromParameters(self::ARRAY_OF_PARAMETERS_FOR_UNSUPPORTED_OTP_TYPE);
    }

    
    /**
     * @test
     */
    public function test_create_from_invalid_parameters_type_returns_InvalidOtpParameterException()
    {
        $this->expectException(\App\Exceptions\InvalidOtpParameterException::class);
        $twofaccount = $this->twofaccountService->createFromParameters([
            'account'   => self::ACCOUNT,
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
        $twofaccount = $this->twofaccountService->createFromParameters([
            'account'   => self::ACCOUNT,
            'otp_type'  => 'totp',
            'algorithm' => 'notsupported',
        ]);
    }


    /**
     * @test
     */
    public function test_update_totp_returns_updated_model()
    {
        $twofaccount = $this->twofaccountService->update($this->customTotpTwofaccount, self::ARRAY_OF_MINIMUM_VALID_PARAMETERS_FOR_TOTP);

        $this->assertEquals('totp', $twofaccount->otp_type);
        $this->assertEquals(null, $twofaccount->service);
        $this->assertEquals(self::ACCOUNT, $twofaccount->account);
        $this->assertEquals(self::SECRET, $twofaccount->secret);
        $this->assertEquals(self::DIGITS_DEFAULT, $twofaccount->digits);
        $this->assertEquals(self::PERIOD_DEFAULT, $twofaccount->period);
        $this->assertEquals(null, $twofaccount->counter);
        $this->assertEquals(self::ALGORITHM_DEFAULT, $twofaccount->algorithm);
        $this->assertEquals(null, $twofaccount->counter);
        $this->assertEquals(null, $twofaccount->icon);
    }


    /**
     * @test
     */
    public function test_update_hotp_returns_updated_model()
    {
        $twofaccount = $this->twofaccountService->update($this->customTotpTwofaccount, self::ARRAY_OF_MINIMUM_VALID_PARAMETERS_FOR_HOTP);

        $this->assertEquals('hotp', $twofaccount->otp_type);
        $this->assertEquals(null, $twofaccount->service);
        $this->assertEquals(self::ACCOUNT, $twofaccount->account);
        $this->assertEquals(self::SECRET, $twofaccount->secret);
        $this->assertEquals(self::DIGITS_DEFAULT, $twofaccount->digits);
        $this->assertEquals(null, $twofaccount->period);
        $this->assertEquals(self::COUNTER_DEFAULT, $twofaccount->counter);
        $this->assertEquals(self::ALGORITHM_DEFAULT, $twofaccount->algorithm);
        $this->assertEquals(null, $twofaccount->counter);
        $this->assertEquals(null, $twofaccount->icon);
    }


    /**
     * @test
     */
    public function test_update_totp_persists_updated_model()
    {
        $twofaccount = $this->twofaccountService->update($this->customTotpTwofaccount, self::ARRAY_OF_MINIMUM_VALID_PARAMETERS_FOR_TOTP);

        $this->assertDatabaseHas('twofaccounts', [
            'otp_type'      => 'totp',
            'service'       => null,
            'account'       => self::ACCOUNT,
            'secret'        => self::SECRET,
            'digits'        => self::DIGITS_DEFAULT,
            'period'        => self::PERIOD_DEFAULT,
            'counter'       => null,
            'algorithm'     => self::ALGORITHM_DEFAULT,
            'icon'          => null,
        ]);
    }


    /**
     * @test
     */
    public function test_getOTP_for_totp_returns_the_same_password()
    {
        $otp_from_model = $this->twofaccountService->getOTP($this->customTotpTwofaccount);
        $otp_from_id = $this->twofaccountService->getOTP($this->customTotpTwofaccount->id);
        $otp_from_uri = $this->twofaccountService->getOTP(self::TOTP_FULL_CUSTOM_URI);

        // Those assertions may fail if the 3 previous assignments are not done at the same exact timestamp
        $this->assertEquals($otp_from_model, $otp_from_id);
        $this->assertEquals($otp_from_model, $otp_from_uri);
    }


    /**
     * @test
     */
    public function test_getOTP_for_hotp_returns_the_same_password()
    {
        $otp_from_model = $this->twofaccountService->getOTP($this->customHotpTwofaccount);
        $otp_from_id = $this->twofaccountService->getOTP($this->customHotpTwofaccount->id);
        $otp_from_uri = $this->twofaccountService->getOTP(self::HOTP_FULL_CUSTOM_URI);

        // Those assertions may fail if the 3 previous assignments are not done at the same exact timestamp
        $this->assertEquals($otp_from_model, $otp_from_id);
        $this->assertEquals($otp_from_model, $otp_from_uri);
    }


    /**
     * @test
     */
    public function test_getOTP_for_totp_with_invalid_secret_returns_InvalidSecretException()
    {
        $this->expectException(\App\Exceptions\InvalidSecretException::class);
        $otp_from_uri = $this->twofaccountService->getOTP('otpauth://totp/'.self::ACCOUNT.'?secret=0');
    }


    /**
     * @test
     */
    public function test_getOTP_for_totp_with_undecipherable_secret_returns_UndecipherableException()
    {
        $this->expectException(\App\Exceptions\UndecipherableException::class);
        $otp_from_uri = $this->twofaccountService->getOTP([
            'account'   => self::ACCOUNT,
            'otp_type'  => 'totp',
            'secret'    => __('errors.indecipherable'),
        ]);
    }


    /**
     * @test
     */
    public function test_getURI_for_custom_totp_model_returns_uri()
    {
        $uri = $this->twofaccountService->getURI($this->customTotpTwofaccount);
        
        $this->assertStringContainsString('otpauth://totp/', $uri);
        $this->assertStringContainsString(self::SERVICE, $uri);
        $this->assertStringContainsString(self::ACCOUNT, $uri);
        $this->assertStringContainsString('secret='.self::SECRET, $uri);
        $this->assertStringContainsString('digits='.self::DIGITS_CUSTOM, $uri);
        $this->assertStringContainsString('period='.self::PERIOD_CUSTOM, $uri);
        $this->assertStringContainsString('algorithm='.self::ALGORITHM_CUSTOM, $uri);
    }


    /**
     * @test
     */
    public function test_getURI_for_custom_totp_model_id_returns_uri()
    {
        $uri = $this->twofaccountService->getURI($this->customTotpTwofaccount->id);
        
        $this->assertStringContainsString('otpauth://totp/', $uri);
        $this->assertStringContainsString(self::SERVICE, $uri);
        $this->assertStringContainsString(self::ACCOUNT, $uri);
        $this->assertStringContainsString('secret='.self::SECRET, $uri);
        $this->assertStringContainsString('digits='.self::DIGITS_CUSTOM, $uri);
        $this->assertStringContainsString('period='.self::PERIOD_CUSTOM, $uri);
        $this->assertStringContainsString('algorithm='.self::ALGORITHM_CUSTOM, $uri);
    }


    /**
     * @test
     */
    public function test_getURI_for_custom_hotp_model_returns_uri()
    {
        $uri = $this->twofaccountService->getURI($this->customHotpTwofaccount);
        
        $this->assertStringContainsString('otpauth://hotp/', $uri);
        $this->assertStringContainsString(self::SERVICE, $uri);
        $this->assertStringContainsString(self::ACCOUNT, $uri);
        $this->assertStringContainsString('secret='.self::SECRET, $uri);
        $this->assertStringContainsString('digits='.self::DIGITS_CUSTOM, $uri);
        $this->assertStringContainsString('counter='.self::COUNTER_CUSTOM, $uri);
        $this->assertStringContainsString('algorithm='.self::ALGORITHM_CUSTOM, $uri);
    }


    /**
     * @test
     */
    public function test_getURI_for_custom_hotp_model_id_returns_uri()
    {
        $uri = $this->twofaccountService->getURI($this->customHotpTwofaccount->id);
        
        $this->assertStringContainsString('otpauth://hotp/', $uri);
        $this->assertStringContainsString(self::SERVICE, $uri);
        $this->assertStringContainsString(self::ACCOUNT, $uri);
        $this->assertStringContainsString('secret='.self::SECRET, $uri);
        $this->assertStringContainsString('digits='.self::DIGITS_CUSTOM, $uri);
        $this->assertStringContainsString('counter='.self::COUNTER_CUSTOM, $uri);
        $this->assertStringContainsString('algorithm='.self::ALGORITHM_CUSTOM, $uri);
    }


    /**
     * @test
     */
    public function test_getURI_for_totp_dto_returns_uri()
    {
        $dto = new \App\Services\Dto\TwoFAccountDto;

        $dto->otp_type = 'totp';
        $dto->account = self::ACCOUNT;
        $dto->secret = self::SECRET;

        $uri = $this->twofaccountService->getURI($dto);
        
        $this->assertStringContainsString('otpauth://totp/', $uri);
        $this->assertStringContainsString(self::ACCOUNT, $uri);
        $this->assertStringContainsString('secret='.self::SECRET, $uri);
    }


    /**
     * @test
     */
    public function test_withdraw_comma_separated_ids_deletes_relation()
    {
        $twofaccounts = collect([$this->customHotpTwofaccount, $this->customTotpTwofaccount]);
        $this->group->twofaccounts()->saveMany($twofaccounts);
        
        $this->twofaccountService->withdraw($this->customHotpTwofaccount->id.','.$this->customTotpTwofaccount->id);

        $this->assertDatabaseHas('twofaccounts', [
            'id'      => $this->customTotpTwofaccount->id,
            'group_id'      => null,
        ]);

        $this->assertDatabaseHas('twofaccounts', [
            'id'      => $this->customHotpTwofaccount->id,
            'group_id'      => null,
        ]);
    }


    /**
     * @test
     */
    public function test_withdraw_array_of_model_ids_deletes_relation()
    {
        $twofaccounts = collect([$this->customHotpTwofaccount, $this->customTotpTwofaccount]);
        $this->group->twofaccounts()->saveMany($twofaccounts);
        
        $this->twofaccountService->withdraw([$this->customHotpTwofaccount->id, $this->customTotpTwofaccount->id]);

        $this->assertDatabaseHas('twofaccounts', [
            'id'      => $this->customTotpTwofaccount->id,
            'group_id'      => null,
        ]);

        $this->assertDatabaseHas('twofaccounts', [
            'id'      => $this->customHotpTwofaccount->id,
            'group_id'      => null,
        ]);
    }


    /**
     * @test
     */
    public function test_withdraw_single_id_deletes_relation()
    {
        $twofaccounts = collect([$this->customHotpTwofaccount, $this->customTotpTwofaccount]);
        $this->group->twofaccounts()->saveMany($twofaccounts);
        
        $this->twofaccountService->withdraw($this->customTotpTwofaccount->id);

        $this->assertDatabaseHas('twofaccounts', [
            'id'      => $this->customTotpTwofaccount->id,
            'group_id'      => null,
        ]);
    }


    /**
     * @test
     */
    public function test_withdraw_missing_ids_returns_void()
    {
        $this->assertNull($this->twofaccountService->withdraw(null));
    }

    
    /**
     * @test
     */
    public function test_delete_comma_separated_ids()
    {        
        $this->twofaccountService->delete($this->customHotpTwofaccount->id.','.$this->customTotpTwofaccount->id);

        $this->assertDatabaseMissing('twofaccounts', [
            'id'      => $this->customTotpTwofaccount->id,
        ]);
        $this->assertDatabaseMissing('twofaccounts', [
            'id'      => $this->customHotpTwofaccount->id,
        ]);
    }


    /**
     * @test
     */
    public function test_delete_array_of_ids()
    {        
        $this->twofaccountService->delete([$this->customTotpTwofaccount->id, $this->customHotpTwofaccount->id]);

        $this->assertDatabaseMissing('twofaccounts', [
            'id'      => $this->customTotpTwofaccount->id,
        ]);
        $this->assertDatabaseMissing('twofaccounts', [
            'id'      => $this->customHotpTwofaccount->id,
        ]);
    }


    /**
     * @test
     */
    public function test_delete_single_id()
    {        
        $this->twofaccountService->delete($this->customTotpTwofaccount->id);

        $this->assertDatabaseMissing('twofaccounts', [
            'id'      => $this->customTotpTwofaccount->id,
        ]);
    }

}