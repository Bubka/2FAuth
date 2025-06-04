<?php

namespace Tests\Feature\Models;

use App\Facades\Icons;
use App\Models\TwoFAccount;
use App\Models\User;
use App\Services\LogoLib\TfaLogoLib;
use Illuminate\Http\Testing\FileFactory;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\Data\HttpRequestTestData;
use Tests\Data\OtpTestData;
use Tests\FeatureTestCase;

/**
 * TwoFAccountModelTest test class
 */
#[CoversClass(TwoFAccount::class)]
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

    public function setUp() : void
    {
        parent::setUp();

        Storage::fake('imagesLink');
        Storage::fake('icons');

        Http::preventStrayRequests();

        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $this->user = User::factory()->create();
        $this->actingAs($this->user, 'api-guard');

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

    #[Test]
    public function test_fill_with_custom_totp_uri_returns_correct_value()
    {
        $file = (new FileFactory)->image('file.png', 10, 10);

        Http::fake([
            OtpTestData::EXTERNAL_IMAGE_URL_DECODED => Http::response($file->tempFile, 200),
        ]);

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

    #[Test]
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

    #[Test]
    public function test_fill_with_ms_corporate_totp_uri_returns_correct_value()
    {
        $twofaccount = new TwoFAccount;
        $twofaccount->fillWithURI(OtpTestData::TOTP_MICROSOFT_CORPORATE_URI_MISMATCHING_ISSUER);

        $this->assertEquals('totp', $twofaccount->otp_type);
        $this->assertEquals(OtpTestData::TOTP_MICROSOFT_CORPORATE_URI_MISMATCHING_ISSUER, $twofaccount->legacy_uri);
        $this->assertEquals(OtpTestData::MICROSOFT, $twofaccount->service);
        $this->assertEquals(OtpTestData::ORGANIZATION . '_' . OtpTestData::ACCOUNT, $twofaccount->account);
        $this->assertEquals(OtpTestData::SECRET, $twofaccount->secret);
        $this->assertEquals(OtpTestData::DIGITS_DEFAULT, $twofaccount->digits);
        $this->assertEquals(OtpTestData::PERIOD_DEFAULT, $twofaccount->period);
        $this->assertEquals(null, $twofaccount->counter);
        $this->assertEquals(OtpTestData::ALGORITHM_DEFAULT, $twofaccount->algorithm);
    }

    #[Test]
    public function test_fill_with_custom_hotp_uri_returns_correct_value()
    {
        $file = (new FileFactory)->image('file.png', 10, 10);

        Http::fake([
            OtpTestData::EXTERNAL_IMAGE_URL_DECODED => Http::response($file->tempFile, 200),
        ]);

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

    #[Test]
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

    #[Test]
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

    #[Test]
    public function test_fill_with_invalid_uri_returns_ValidationException()
    {
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $twofaccount = new TwoFAccount;
        $twofaccount->fillWithURI(OtpTestData::INVALID_OTPAUTH_URI);
    }

    #[Test]
    public function test_fill_with_invalid_uri_with_mismatching_issuer_returns_ValidationException()
    {
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $twofaccount = new TwoFAccount;
        $twofaccount->fillWithURI(OtpTestData::INVALID_OTPAUTH_URI_MISMATCHING_ISSUER);
    }

    #[Test]
    public function test_fill_with_uri_without_label_returns_ValidationException()
    {
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $twofaccount = new TwoFAccount;
        $twofaccount->fillWithURI('otpauth://totp/?secret=' . OtpTestData::SECRET);
    }

    #[Test]
    public function test_fill_with_getOfficialIcons_On_fetches_icon_using_Icons_facade()
    {
        $this->user['preferences->getOfficialIcons'] = true;
        $this->user->save();

        Icons::shouldReceive('buildFromOfficialLogo')
            ->twice()
            ->andReturn('file.png');

        $twofaccount = new TwoFAccount;
        $twofaccount->fillWithURI(OtpTestData::TOTP_FULL_CUSTOM_URI_NO_IMG);
        $twofaccount->fillWithOtpParameters(OtpTestData::ARRAY_OF_FULL_VALID_PARAMETERS_FOR_CUSTOM_TOTP_NO_ICON);
    }

    #[Test]
    public function test_fill_with_getOfficialIcons_Off_skips_icon_fetching()
    {
        // Set the getOfficialIcons preference Off
        $this->user['preferences->getOfficialIcons'] = false;
        $this->user->save();

        $this->mock(TfaLogoLib::class, function (MockInterface $logoLib) {
            $logoLib->shouldNotReceive('getIcon');
        });

        $twofaccount = new TwoFAccount;
        $twofaccount->fillWithURI(OtpTestData::TOTP_FULL_CUSTOM_URI_NO_IMG);

        $twofaccount = new TwoFAccount;
        $twofaccount->fillWithOtpParameters(OtpTestData::ARRAY_OF_FULL_VALID_PARAMETERS_FOR_CUSTOM_TOTP);
    }

    #[Test]
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

    #[Test]
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

    #[Test]
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

    #[Test]
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

    #[Test]
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

    #[Test]
    public function test_create_from_unsupported_parameters_returns_unsupportedOtpTypeException()
    {
        $this->expectException(\App\Exceptions\UnsupportedOtpTypeException::class);
        $twofaccount = new TwoFAccount;
        $twofaccount->fillWithOtpParameters(OtpTestData::ARRAY_OF_PARAMETERS_FOR_UNSUPPORTED_OTP_TYPE);
    }

    #[Test]
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

    #[Test]
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

    #[Test]
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

    #[Test]
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

    #[Test]
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

    #[Test]
    public function test_getOTP_for_totp_returns_the_same_password()
    {
        Http::fake([
            OtpTestData::EXTERNAL_IMAGE_URL_DECODED => Http::response(HttpRequestTestData::ICON_PNG, 200),
        ]);

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

    #[Test]
    public function test_getOTP_for_hotp_returns_the_same_password()
    {
        Http::fake([
            OtpTestData::EXTERNAL_IMAGE_URL_DECODED => Http::response(HttpRequestTestData::ICON_PNG, 200),
        ]);

        $twofaccount = new TwoFAccount;

        $otp_from_model = $this->customHotpTwofaccount->getOTP();
        $otp_from_uri   = $twofaccount->fillWithURI(OtpTestData::HOTP_FULL_CUSTOM_URI)->getOTP();

        $this->assertEquals($otp_from_model, $otp_from_uri);

        $otp_from_parameters = $twofaccount->fillWithOtpParameters(OtpTestData::ARRAY_OF_FULL_VALID_PARAMETERS_FOR_CUSTOM_HOTP)->getOTP();

        $this->assertEquals($otp_from_model, $otp_from_parameters);
    }

    #[Test]
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

    #[Test]
    public function test_getOTP_for_totp_with_invalid_secret_returns_InvalidSecretException()
    {
        $twofaccount = new TwoFAccount;

        $this->expectException(\App\Exceptions\InvalidSecretException::class);
        $otp_from_uri = $twofaccount->fillWithURI('otpauth://totp/' . OtpTestData::ACCOUNT . '?secret=1.0')->getOTP();
    }

    #[Test]
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

    #[Test]
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

    #[Test]
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

    #[Test]
    public function test_fill_succeed_when_image_fetching_fails()
    {
        Http::fake([
            OtpTestData::EXTERNAL_IMAGE_URL_DECODED => new \Exception,
        ]);

        $twofaccount = new TwoFAccount;
        $twofaccount->fillWithURI(OtpTestData::TOTP_FULL_CUSTOM_URI);

        Storage::disk('icons')->assertDirectoryEmpty('/');
        Storage::disk('imagesLink')->assertDirectoryEmpty('/');
    }

    #[Test]
    public function test_fillWithURI_uses_Icons_facade_to_get_the_icon()
    {
        Icons::shouldReceive('buildFromRemoteImage')
            ->once()
            ->andReturn('file.png');

        $twofaccount = new TwoFAccount;
        $twofaccount->fillWithURI(OtpTestData::TOTP_FULL_CUSTOM_URI);
    }

    #[Test]
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

    #[Test]
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

    #[Test]
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

    #[Test]
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

    #[Test]
    public function test_scopeOrphans_retreives_accounts_without_owner()
    {
        Http::fake([
            OtpTestData::EXTERNAL_IMAGE_URL_DECODED => Http::response(HttpRequestTestData::ICON_PNG, 200),
        ]);

        $orphan = new TwoFAccount;
        $orphan->fillWithURI(OtpTestData::HOTP_FULL_CUSTOM_URI);
        $orphan->save();

        $orphans = TwoFAccount::orphans()->get();

        $this->assertCount(1, $orphans);
        $this->assertEquals($orphan->id, $orphans[0]->id);
    }
}
