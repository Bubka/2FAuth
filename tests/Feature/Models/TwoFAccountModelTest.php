<?php

namespace Tests\Feature\Models;

use App\Models\TwoFAccount;
use App\Models\User;
use Illuminate\Http\Testing\FileFactory;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\Data\HttpRequestTestData;
use Tests\Data\OtpTestData;
use Tests\FeatureTestCase;

/**
 * @covers \App\Models\TwoFAccount
 */
class TwoFAccountModelTest extends FeatureTestCase
{
    /**
     * @var \App\Models\User|\Illuminate\Contracts\Auth\Authenticatable
     */
    protected $user;

    protected $anotherUser;

    /**
     * @var \App\Models\TwoFAccount
     */
    protected $customTotpTwofaccount;

    protected $customHotpTwofaccount;

    protected $customSteamTotpTwofaccount;

    /**
     * Helpers $helpers;
     */
    protected $helpers;

    /**
     * @test
     */
    public function setUp() : void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->customTotpTwofaccount = TwoFAccount::factory()->for($this->user)->create([
            'legacy_uri' => OtpTestData::TOTP_FULL_CUSTOM_URI,
            'service'    => OtpTestData::SERVICE,
            'account'    => OtpTestData::ACCOUNT,
            'icon'       => OtpTestData::ICON_PNG,
            'otp_type'   => 'totp',
            'secret'     => OtpTestData::SECRET,
            'digits'     => OtpTestData::DIGITS_CUSTOM,
            'algorithm'  => OtpTestData::ALGORITHM_CUSTOM,
            'period'     => OtpTestData::PERIOD_CUSTOM,
            'counter'    => null,
        ]);

        $this->customHotpTwofaccount = TwoFAccount::factory()->for($this->user)->create([
            'legacy_uri' => OtpTestData::HOTP_FULL_CUSTOM_URI,
            'service'    => OtpTestData::SERVICE,
            'account'    => OtpTestData::ACCOUNT,
            'icon'       => OtpTestData::ICON_PNG,
            'otp_type'   => 'hotp',
            'secret'     => OtpTestData::SECRET,
            'digits'     => OtpTestData::DIGITS_CUSTOM,
            'algorithm'  => OtpTestData::ALGORITHM_CUSTOM,
            'period'     => null,
            'counter'    => OtpTestData::COUNTER_CUSTOM,
        ]);

        $this->customSteamTotpTwofaccount = TwoFAccount::factory()->for($this->user)->create([
            'legacy_uri' => OtpTestData::STEAM_TOTP_URI,
            'service'    => OtpTestData::STEAM,
            'account'    => OtpTestData::ACCOUNT,
            'otp_type'   => 'steamtotp',
            'secret'     => OtpTestData::STEAM_SECRET,
            'digits'     => OtpTestData::DIGITS_STEAM,
            'algorithm'  => OtpTestData::ALGORITHM_DEFAULT,
            'period'     => OtpTestData::PERIOD_DEFAULT,
            'counter'    => null,
        ]);
    }

    /**
     * @test
     */
    public function test_fill_with_custom_totp_uri_returns_correct_value()
    {
        $file = (new FileFactory)->image('file.png', 10, 10);

        Http::preventStrayRequests();
        Http::fake([
            'https://en.opensuse.org/images/4/44/Button-filled-colour.png' => Http::response($file->tempFile, 200),
        ]);

        Storage::fake('imagesLink');
        Storage::fake('icons');

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
        $this->assertNotNull($twofaccount->icon);

        Storage::disk('icons')->assertExists($twofaccount->icon);
        Storage::disk('imagesLink')->assertMissing($twofaccount->icon);
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
        $file = (new FileFactory)->image('file.png', 10, 10);

        Http::preventStrayRequests();
        Http::fake([
            'https://en.opensuse.org/images/4/44/Button-filled-colour.png' => Http::response($file->tempFile, 200),
        ]);

        Storage::fake('imagesLink');
        Storage::fake('icons');

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
        $this->assertNotNull($twofaccount->icon);

        Storage::disk('icons')->assertExists($twofaccount->icon);
        Storage::disk('imagesLink')->assertMissing($twofaccount->icon);
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
            'otp_type'   => 'totp',
            'legacy_uri' => OtpTestData::TOTP_SHORT_URI,
            'service'    => null,
            'account'    => OtpTestData::ACCOUNT,
            'secret'     => OtpTestData::SECRET,
            'digits'     => OtpTestData::DIGITS_DEFAULT,
            'period'     => OtpTestData::PERIOD_DEFAULT,
            'counter'    => null,
            'algorithm'  => OtpTestData::ALGORITHM_DEFAULT,
            'icon'       => null,
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
        $twofaccount->fillWithURI('otpauth://totp/?secret=' . OtpTestData::SECRET);
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
        $this->assertStringEndsWith('.png', $twofaccount->icon);
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
        $this->assertStringEndsWith('.png', $twofaccount->icon);
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
            'otp_type'   => 'totp',
            'legacy_uri' => OtpTestData::TOTP_SHORT_URI,
            'service'    => null,
            'account'    => OtpTestData::ACCOUNT,
            'secret'     => OtpTestData::SECRET,
            'digits'     => OtpTestData::DIGITS_DEFAULT,
            'period'     => OtpTestData::PERIOD_DEFAULT,
            'counter'    => null,
            'algorithm'  => OtpTestData::ALGORITHM_DEFAULT,
            'icon'       => null,
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
            'account'  => OtpTestData::ACCOUNT,
            'otp_type' => 'totp',
            'digits'   => 'notsupported',
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
            'otp_type'  => 'totp',
            'service'   => null,
            'account'   => OtpTestData::ACCOUNT,
            'secret'    => OtpTestData::SECRET,
            'digits'    => OtpTestData::DIGITS_DEFAULT,
            'period'    => OtpTestData::PERIOD_DEFAULT,
            'counter'   => null,
            'algorithm' => OtpTestData::ALGORITHM_DEFAULT,
            'icon'      => null,
        ]);
    }

    /**
     * @test
     */
    public function test_getOTP_for_totp_returns_the_same_password()
    {
        Http::preventStrayRequests();
        Http::fake([
            'https://en.opensuse.org/images/4/44/Button-filled-colour.png' => Http::response(HttpRequestTestData::ICON_PNG, 200),
        ]);

        Storage::fake('imagesLink');
        Storage::fake('icons');

        $twofaccount = new TwoFAccount;

        $otp_from_model = $this->customTotpTwofaccount->getOTP();
        $otp_from_uri   = $twofaccount->fillWithURI(OtpTestData::TOTP_FULL_CUSTOM_URI)->getOTP();

        if ($otp_from_model->generated_at === $otp_from_uri->generated_at) {
            $this->assertEquals($otp_from_model, $otp_from_uri);
        }

        $otp_from_model      = $this->customTotpTwofaccount->getOTP();
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
        Http::preventStrayRequests();
        Http::fake([
            'https://en.opensuse.org/images/4/44/Button-filled-colour.png' => Http::response(HttpRequestTestData::ICON_PNG, 200),
        ]);

        Storage::fake('imagesLink');
        Storage::fake('icons');

        $twofaccount = new TwoFAccount;

        $otp_from_model = $this->customHotpTwofaccount->getOTP();
        $otp_from_uri   = $twofaccount->fillWithURI(OtpTestData::HOTP_FULL_CUSTOM_URI)->getOTP();

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
        $otp_from_uri   = $twofaccount->fillWithURI(OtpTestData::STEAM_TOTP_URI)->getOTP();

        if ($otp_from_model->generated_at === $otp_from_uri->generated_at) {
            $this->assertEquals($otp_from_model, $otp_from_uri);
        }

        $otp_from_model      = $this->customSteamTotpTwofaccount->getOTP();
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
        $otp_from_uri = $twofaccount->fillWithURI('otpauth://totp/' . OtpTestData::ACCOUNT . '?secret=1.0')->getOTP();
    }

    /**
     * @test
     */
    public function test_getOTP_for_totp_with_undecipherable_secret_returns_UndecipherableException()
    {
        $twofaccount = new TwoFAccount;

        $this->expectException(\App\Exceptions\UndecipherableException::class);
        $otp_from_uri = $twofaccount->fillWithOtpParameters([
            'account'  => OtpTestData::ACCOUNT,
            'otp_type' => 'totp',
            'secret'   => __('errors.indecipherable'),
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
        $this->assertStringContainsString('secret=' . OtpTestData::SECRET, $uri);
        $this->assertStringContainsString('digits=' . OtpTestData::DIGITS_CUSTOM, $uri);
        $this->assertStringContainsString('period=' . OtpTestData::PERIOD_CUSTOM, $uri);
        $this->assertStringContainsString('algorithm=' . OtpTestData::ALGORITHM_CUSTOM, $uri);
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
        $this->assertStringContainsString('secret=' . OtpTestData::SECRET, $uri);
        $this->assertStringContainsString('digits=' . OtpTestData::DIGITS_CUSTOM, $uri);
        $this->assertStringContainsString('counter=' . OtpTestData::COUNTER_CUSTOM, $uri);
        $this->assertStringContainsString('algorithm=' . OtpTestData::ALGORITHM_CUSTOM, $uri);
    }

    /**
     * @test
     */
    public function test_fill_succeed_when_image_fetching_fails()
    {
        Http::preventStrayRequests();

        Storage::fake('imagesLink');
        Storage::fake('icons');

        $twofaccount = new TwoFAccount;
        $twofaccount->fillWithURI(OtpTestData::TOTP_FULL_CUSTOM_URI);

        Storage::disk('icons')->assertDirectoryEmpty('/');
        Storage::disk('imagesLink')->assertDirectoryEmpty('/');
    }

    /**
     * @test
     */
    public function test_saving_totp_without_period_set_default_one()
    {
        $twofaccount           = new TwoFAccount;
        $twofaccount->service  = OtpTestData::SERVICE;
        $twofaccount->account  = OtpTestData::ACCOUNT;
        $twofaccount->otp_type = TwoFAccount::TOTP;
        $twofaccount->secret   = OtpTestData::SECRET;

        $twofaccount->save();

        $account = TwoFAccount::find($twofaccount->id);

        $this->assertEquals(TwoFAccount::DEFAULT_PERIOD, $account->period);
    }

    /**
     * @test
     */
    public function test_saving_hotp_without_counter_set_default_one()
    {
        $twofaccount           = new TwoFAccount;
        $twofaccount->service  = OtpTestData::SERVICE;
        $twofaccount->account  = OtpTestData::ACCOUNT;
        $twofaccount->otp_type = TwoFAccount::HOTP;
        $twofaccount->secret   = OtpTestData::SECRET;

        $twofaccount->save();

        $account = TwoFAccount::find($twofaccount->id);

        $this->assertEquals(TwoFAccount::DEFAULT_COUNTER, $account->counter);
    }

    /**
     * @test
     */
    public function test_equals_returns_true()
    {
        $twofaccount             = new TwoFAccount;
        $twofaccount->legacy_uri = OtpTestData::TOTP_FULL_CUSTOM_URI;
        $twofaccount->service    = OtpTestData::SERVICE;
        $twofaccount->account    = OtpTestData::ACCOUNT;
        $twofaccount->icon       = OtpTestData::ICON_PNG;
        $twofaccount->otp_type   = 'totp';
        $twofaccount->secret     = OtpTestData::SECRET;
        $twofaccount->digits     = OtpTestData::DIGITS_CUSTOM;
        $twofaccount->algorithm  = OtpTestData::ALGORITHM_CUSTOM;
        $twofaccount->period     = OtpTestData::PERIOD_CUSTOM;
        $twofaccount->counter    = null;
        $twofaccount->save();

        $this->assertTrue($twofaccount->equals($this->customTotpTwofaccount));
    }

    /**
     * @test
     */
    public function test_equals_returns_false()
    {
        $twofaccount             = new TwoFAccount;
        $twofaccount->legacy_uri = OtpTestData::TOTP_FULL_CUSTOM_URI;
        $twofaccount->service    = OtpTestData::SERVICE;
        $twofaccount->account    = OtpTestData::ACCOUNT;
        $twofaccount->icon       = OtpTestData::ICON_PNG;
        $twofaccount->otp_type   = 'totp';
        $twofaccount->secret     = OtpTestData::SECRET;
        $twofaccount->digits     = OtpTestData::DIGITS_CUSTOM;
        $twofaccount->algorithm  = OtpTestData::ALGORITHM_CUSTOM;
        $twofaccount->period     = OtpTestData::PERIOD_CUSTOM;
        $twofaccount->counter    = null;
        $twofaccount->save();

        $this->assertFalse($twofaccount->equals($this->customHotpTwofaccount));
    }

    /**
     * @test
     *
     * @dataProvider iconResourceProvider
     */
    public function test_set_icon_stores_and_set_the_icon($res, $ext)
    {
        Storage::fake('imagesLink');
        Storage::fake('icons');

        $previousIcon = $this->customTotpTwofaccount->icon;
        $this->customTotpTwofaccount->setIcon($res, $ext);

        $this->assertNotEquals($previousIcon, $this->customTotpTwofaccount->icon);

        Storage::disk('icons')->assertExists($this->customTotpTwofaccount->icon);
        Storage::disk('imagesLink')->assertMissing($this->customTotpTwofaccount->icon);
    }

    /**
     * Provide data for Icon store tests
     */
    public function iconResourceProvider()
    {
        return [
            'PNG' => [
                base64_decode(OtpTestData::ICON_PNG_DATA),
                'png',
            ],
            'JPG' => [
                base64_decode(OtpTestData::ICON_JPEG_DATA),
                'jpg',
            ],
            'WEBP' => [
                base64_decode(OtpTestData::ICON_WEBP_DATA),
                'webp',
            ],
            'BMP' => [
                base64_decode(OtpTestData::ICON_BMP_DATA),
                'bmp',
            ],
            'SVG' => [
                OtpTestData::ICON_SVG_DATA,
                'svg',
            ],
        ];
    }

    /**
     * @test
     *
     * @dataProvider invalidIconResourceProvider
     */
    public function test_set_invalid_icon_ends_without_error($res, $ext)
    {
        Storage::fake('imagesLink');
        Storage::fake('icons');

        $previousIcon = $this->customTotpTwofaccount->icon;
        $this->customTotpTwofaccount->setIcon($res, $ext);

        $this->assertEquals($previousIcon, $this->customTotpTwofaccount->icon);

        Storage::disk('icons')->assertMissing($this->customTotpTwofaccount->icon);
        Storage::disk('imagesLink')->assertMissing($this->customTotpTwofaccount->icon);
    }

    /**
     * Provide data for Icon store tests
     */
    public function invalidIconResourceProvider()
    {
        return [
            'INVALID_PNG' => [
                'lkjdslfkjslkdfjlskdjflksjf',
                'png',
            ],
        ];
    }
}
