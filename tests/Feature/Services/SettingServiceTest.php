<?php

namespace Tests\Feature\Services;

use Tests\FeatureTestCase;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use App\TwoFAccount;


/**
 * @covers \App\Services\AppstractOptionsService
 */
class SettingServiceTest extends FeatureTestCase
{
    /**
     * App\Services\SettingServiceInterface $settingService
     */
    protected $settingService;


    /**
     * App\Group $groupOne, $groupTwo
     */
    protected $twofaccountOne, $twofaccountTwo;

    private const KEY = 'key';
    private const VALUE = 'value';
    private const SETTING_NAME = 'MySetting';
    private const SETTING_NAME_ALT = 'MySettingAlt';
    private const SETTING_VALUE_STRING = 'MyValue';
    private const SETTING_VALUE_TRUE_TRANSFORMED = '{{1}}';
    private const SETTING_VALUE_FALSE_TRANSFORMED = '{{}}';
    private const SETTING_VALUE_INT = 10;

    private const ACCOUNT = 'account';
    private const SERVICE = 'service';
    private const SECRET = 'A4GRFHVVRBGY7UIW';
    private const ALGORITHM_CUSTOM = 'sha256';
    private const DIGITS_CUSTOM = 7;
    private const PERIOD_CUSTOM = 40;
    private const IMAGE = 'https%3A%2F%2Fen.opensuse.org%2Fimages%2F4%2F44%2FButton-filled-colour.png';
    private const ICON = 'test.png';
    private const TOTP_FULL_CUSTOM_URI = 'otpauth://totp/'.self::SERVICE.':'.self::ACCOUNT.'?secret='.self::SECRET.'&issuer='.self::SERVICE.'&digits='.self::DIGITS_CUSTOM.'&period='.self::PERIOD_CUSTOM.'&algorithm='.self::ALGORITHM_CUSTOM.'&image='.self::IMAGE;

    /**
     * @test
     */
    public function setUp() : void
    {
        parent::setUp();

        $this->settingService = $this->app->make('App\Services\SettingServiceInterface');

        $this->twofaccountOne = new TwoFAccount;
        $this->twofaccountOne->legacy_uri = self::TOTP_FULL_CUSTOM_URI;
        $this->twofaccountOne->service = self::SERVICE;
        $this->twofaccountOne->account = self::ACCOUNT;
        $this->twofaccountOne->icon = self::ICON;
        $this->twofaccountOne->otp_type = 'totp';
        $this->twofaccountOne->secret = self::SECRET;
        $this->twofaccountOne->digits = self::DIGITS_CUSTOM;
        $this->twofaccountOne->algorithm = self::ALGORITHM_CUSTOM;
        $this->twofaccountOne->period = self::PERIOD_CUSTOM;
        $this->twofaccountOne->counter = null;
        $this->twofaccountOne->save();

        $this->twofaccountTwo = new TwoFAccount;
        $this->twofaccountTwo->legacy_uri = self::TOTP_FULL_CUSTOM_URI;
        $this->twofaccountTwo->service = self::SERVICE;
        $this->twofaccountTwo->account = self::ACCOUNT;
        $this->twofaccountTwo->icon = self::ICON;
        $this->twofaccountTwo->otp_type = 'totp';
        $this->twofaccountTwo->secret = self::SECRET;
        $this->twofaccountTwo->digits = self::DIGITS_CUSTOM;
        $this->twofaccountTwo->algorithm = self::ALGORITHM_CUSTOM;
        $this->twofaccountTwo->period = self::PERIOD_CUSTOM;
        $this->twofaccountTwo->counter = null;
        $this->twofaccountTwo->save();
    }


    /**
     * @test
     */
    public function test_get_string_setting_returns_correct_value()
    {
        DB::table('options')->insert(
            [self::KEY => self::SETTING_NAME, self::VALUE => strval(self::SETTING_VALUE_STRING)]
        );

        $this->assertEquals(self::SETTING_VALUE_STRING, $this->settingService->get(self::SETTING_NAME));
    }


    /**
     * @test
     */
    public function test_get_boolean_setting_returns_true()
    {
        DB::table('options')->insert(
            [self::KEY => self::SETTING_NAME, self::VALUE => strval(self::SETTING_VALUE_TRUE_TRANSFORMED)]
        );

        $this->assertEquals(true, $this->settingService->get(self::SETTING_NAME));
    }


    /**
     * @test
     */
    public function test_get_boolean_setting_returns_false()
    {
        DB::table('options')->insert(
            [self::KEY => self::SETTING_NAME, self::VALUE => strval(self::SETTING_VALUE_FALSE_TRANSFORMED)]
        );

        $this->assertEquals(false, $this->settingService->get(self::SETTING_NAME));
    }


    /**
     * @test
     */
    public function test_get_int_setting_returns_int()
    {
        DB::table('options')->insert(
            [self::KEY => self::SETTING_NAME, self::VALUE => strval(self::SETTING_VALUE_INT)]
        );

        $value = $this->settingService->get(self::SETTING_NAME);

        $this->assertEquals(self::SETTING_VALUE_INT, $value);
        $this->assertIsInt($value);
    }


    /**
     * @test
     */
    public function test_all_returns_native_and_user_settings()
    {
        $native_options = config('2fauth.options');

        DB::table('options')->insert(
            [self::KEY => self::SETTING_NAME, self::VALUE => strval(self::SETTING_VALUE_STRING)]
        );

        $all = $this->settingService->all();

        $this->assertCount(count($native_options)+1, $all);

        $this->assertArrayHasKey(self::SETTING_NAME, $all);
        $this->assertEquals($all[self::SETTING_NAME], self::SETTING_VALUE_STRING);

        foreach ($native_options as $key => $val) {
            $this->assertArrayHasKey($key, $all);
            $this->assertEquals($all[$key], $val);
        }

    }


    /**
     * @test
     */
    public function test_set_setting_persist_correct_value()
    {
        $value = $this->settingService->set(self::SETTING_NAME, self::SETTING_VALUE_STRING);

        $this->assertDatabaseHas('options', [
            self::KEY => self::SETTING_NAME,
            self::VALUE => self::SETTING_VALUE_STRING
        ]);
    }
    

    /**
     * @test
     */
    public function test_set_useEncryption_on_encrypts_all_accounts()
    {
        $this->settingService->set('useEncryption', true);

        $twofaccounts = DB::table('twofaccounts')->get();

        $twofaccounts->each(function ($item, $key) {
            $this->assertEquals(self::ACCOUNT, Crypt::decryptString($item->account));
            $this->assertEquals(self::SECRET, Crypt::decryptString($item->secret));
            $this->assertEquals(self::TOTP_FULL_CUSTOM_URI, Crypt::decryptString($item->legacy_uri));
        });
    }


    /**
     * @test
     */
    public function test_set_useEncryption_on_twice_prevents_successive_encryption()
    {
        $this->settingService->set('useEncryption', true);
        $this->settingService->set('useEncryption', true);

        $twofaccounts = DB::table('twofaccounts')->get();

        $twofaccounts->each(function ($item, $key) {
            $this->assertEquals(self::ACCOUNT, Crypt::decryptString($item->account));
            $this->assertEquals(self::SECRET, Crypt::decryptString($item->secret));
            $this->assertEquals(self::TOTP_FULL_CUSTOM_URI, Crypt::decryptString($item->legacy_uri));
        });
    }


    /**
     * @test
     */
    public function test_set_useEncryption_off_decrypts_all_accounts()
    {
        $this->settingService->set('useEncryption', true);
        $this->settingService->set('useEncryption', false);

        $twofaccounts = DB::table('twofaccounts')->get();

        $twofaccounts->each(function ($item, $key) {
            $this->assertEquals(self::ACCOUNT, $item->account);
            $this->assertEquals(self::SECRET, $item->secret);
            $this->assertEquals(self::TOTP_FULL_CUSTOM_URI, $item->legacy_uri);
        });
    }


    /**
     * @test
     * @dataProvider provideUndecipherableData
     */
    public function test_set_useEncryption_off_returns_exception_when_data_are_undecipherable(array $data)
    {
        $this->expectException(\App\Exceptions\DbEncryptionException::class);

        $this->settingService->set('useEncryption', true);

        $affected = DB::table('twofaccounts')
            ->where('id', $this->twofaccountOne->id)
            ->update($data);

            $this->settingService->set('useEncryption', false);

        $twofaccount = TwoFAccount::find($this->twofaccountOne->id);
    }


    /**
     * Provide invalid data for validation test
     */
    public function provideUndecipherableData() : array
    {
        return [
            [[
                'account' => 'undecipherableString'
            ]],
            [[
                'secret' => 'undecipherableString'
            ]],
            [[
                'legacy_uri' => 'undecipherableString'
            ]],
        ];
    }


    /**
     * @test
     */
    public function test_set_array_of_settings_persist_correct_values()
    {
        $value = $this->settingService->set([
            self::SETTING_NAME => self::SETTING_VALUE_STRING,
            self::SETTING_NAME_ALT => self::SETTING_VALUE_INT,
        ]);

        $this->assertDatabaseHas('options', [
            self::KEY => self::SETTING_NAME,
            self::VALUE => self::SETTING_VALUE_STRING
        ]);

        $this->assertDatabaseHas('options', [
            self::KEY => self::SETTING_NAME_ALT,
            self::VALUE => self::SETTING_VALUE_INT
        ]);
    }


    /**
     * @test
     */
    public function test_set_true_setting_persist_transformed_boolean()
    {
        $value = $this->settingService->set(self::SETTING_NAME, true);

        $this->assertDatabaseHas('options', [
            self::KEY => self::SETTING_NAME,
            self::VALUE => self::SETTING_VALUE_TRUE_TRANSFORMED
        ]);
    }


    /**
     * @test
     */
    public function test_set_false_setting_persist_transformed_boolean()
    {
        $value = $this->settingService->set(self::SETTING_NAME, false);

        $this->assertDatabaseHas('options', [
            self::KEY => self::SETTING_NAME,
            self::VALUE => self::SETTING_VALUE_FALSE_TRANSFORMED
        ]);
    }


    /**
     * @test
     */
    public function test_del_remove_setting_from_db()
    {
        DB::table('options')->insert(
            [self::KEY => self::SETTING_NAME, self::VALUE => strval(self::SETTING_VALUE_STRING)]
        );

        $value = $this->settingService->delete(self::SETTING_NAME);

        $this->assertDatabaseMissing('options', [
            self::KEY => self::SETTING_NAME,
            self::VALUE => self::SETTING_VALUE_STRING
        ]);
    }
}