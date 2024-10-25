<?php

namespace Tests\Feature\Services;

use App\Events\storeIconsInDatabaseSettingChanged;
use App\Exceptions\FailedIconStoreDatabaseTogglingException;
use App\Facades\IconStore;
use App\Facades\Settings;
use App\Models\Icon;
use App\Models\TwoFAccount;
use App\Services\IconStoreService;
use App\Services\SettingService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\Data\OtpTestData;
use Tests\FeatureTestCase;

/**
 * SettingServiceTest test class
 */
#[CoversClass(SettingService::class)]
#[CoversClass(Settings::class)]
class SettingServiceTest extends FeatureTestCase
{
    /**
     * App\Models\Group $groupOne, $groupTwo
     */
    protected $twofaccountOne;

    protected $twofaccountTwo;

    private const KEY = 'key';

    private const VALUE = 'value';

    private const SETTING_NAME = 'MySetting';

    private const SETTING_NAME_ALT = 'MySettingAlt';

    private const SETTING_VALUE_STRING = 'MyValue';

    private const SETTING_VALUE_TRUE_TRANSFORMED = '{{1}}';

    private const SETTING_VALUE_FALSE_TRANSFORMED = '{{}}';

    private const SETTING_VALUE_INT = 10;

    private const SETTING_VALUE_FLOAT = 10.5;

    private const ACCOUNT = 'account';

    private const SERVICE = 'service';

    private const SECRET = 'A4GRFHVVRBGY7UIW';

    private const ALGORITHM_CUSTOM = 'sha256';

    private const DIGITS_CUSTOM = 7;

    private const PERIOD_CUSTOM = 40;

    private const ICON = 'test.png';

    private const TOTP_FULL_CUSTOM_URI = 'otpauth://totp/' . self::SERVICE . ':' . self::ACCOUNT . '?secret=' . self::SECRET . '&issuer=' . self::SERVICE . '&digits=' . self::DIGITS_CUSTOM . '&period=' . self::PERIOD_CUSTOM . '&algorithm=' . self::ALGORITHM_CUSTOM . '&image=' . OtpTestData::EXTERNAL_IMAGE_URL_ENCODED;

    public function setUp() : void
    {
        parent::setUp();

        $this->twofaccountOne             = new TwoFAccount;
        $this->twofaccountOne->legacy_uri = self::TOTP_FULL_CUSTOM_URI;
        $this->twofaccountOne->service    = self::SERVICE;
        $this->twofaccountOne->account    = self::ACCOUNT;
        $this->twofaccountOne->icon       = self::ICON;
        $this->twofaccountOne->otp_type   = 'totp';
        $this->twofaccountOne->secret     = self::SECRET;
        $this->twofaccountOne->digits     = self::DIGITS_CUSTOM;
        $this->twofaccountOne->algorithm  = self::ALGORITHM_CUSTOM;
        $this->twofaccountOne->period     = self::PERIOD_CUSTOM;
        $this->twofaccountOne->counter    = null;
        $this->twofaccountOne->save();

        $this->twofaccountTwo             = new TwoFAccount;
        $this->twofaccountTwo->legacy_uri = self::TOTP_FULL_CUSTOM_URI;
        $this->twofaccountTwo->service    = self::SERVICE;
        $this->twofaccountTwo->account    = self::ACCOUNT;
        $this->twofaccountTwo->icon       = self::ICON;
        $this->twofaccountTwo->otp_type   = 'totp';
        $this->twofaccountTwo->secret     = self::SECRET;
        $this->twofaccountTwo->digits     = self::DIGITS_CUSTOM;
        $this->twofaccountTwo->algorithm  = self::ALGORITHM_CUSTOM;
        $this->twofaccountTwo->period     = self::PERIOD_CUSTOM;
        $this->twofaccountTwo->counter    = null;
        $this->twofaccountTwo->save();
    }

    #[Test]
    public function test_get_string_setting_returns_correct_value()
    {
        Settings::set(self::SETTING_NAME, self::SETTING_VALUE_STRING);

        $this->assertEquals(self::SETTING_VALUE_STRING, Settings::get(self::SETTING_NAME));
    }

    #[Test]
    public function test_get_boolean_setting_returns_true()
    {
        Settings::set(self::SETTING_NAME, self::SETTING_VALUE_TRUE_TRANSFORMED);

        $this->assertEquals(true, Settings::get(self::SETTING_NAME));
    }

    #[Test]
    public function test_get_boolean_setting_returns_false()
    {
        Settings::set(self::SETTING_NAME, self::SETTING_VALUE_FALSE_TRANSFORMED);

        $this->assertEquals(false, Settings::get(self::SETTING_NAME));
    }

    #[Test]
    public function test_get_int_setting_returns_int()
    {
        Settings::set(self::SETTING_NAME, self::SETTING_VALUE_INT);

        $value = Settings::get(self::SETTING_NAME);

        $this->assertEquals(self::SETTING_VALUE_INT, $value);
        $this->assertIsInt($value);
    }

    #[Test]
    public function test_get_float_setting_returns_float()
    {
        Settings::set(self::SETTING_NAME, self::SETTING_VALUE_FLOAT);

        $value = Settings::get(self::SETTING_NAME);

        $this->assertEquals(self::SETTING_VALUE_FLOAT, $value);
        $this->assertIsFloat($value);
    }

    #[Test]
    public function test_all_returns_default_and_overloaded_settings()
    {
        $default_options = config('2fauth.settings');
        unset($default_options['lastRadarScan']);

        Settings::set(self::SETTING_NAME, self::SETTING_VALUE_STRING);

        $all = Settings::all()->toArray();

        $this->assertArrayHasKey(self::SETTING_NAME, $all);
        $this->assertEquals($all[self::SETTING_NAME], self::SETTING_VALUE_STRING);

        foreach ($default_options as $key => $val) {
            $this->assertArrayHasKey($key, $all);
            $this->assertEquals($all[$key], $val);
        }
    }

    #[Test]
    public function test_set_setting_persist_correct_value_in_db_and_cache()
    {
        $value  = Settings::set(self::SETTING_NAME, self::SETTING_VALUE_STRING);
        $cached = Cache::get(SettingService::CACHE_ITEM_NAME); // returns a Collection

        $this->assertDatabaseHas('options', [
            self::KEY   => self::SETTING_NAME,
            self::VALUE => self::SETTING_VALUE_STRING,
        ]);

        $this->assertEquals($cached->get(self::SETTING_NAME), self::SETTING_VALUE_STRING);
    }

    #[Test]
    public function test_set_useEncryption_On_encrypts_all_data()
    {
        Settings::set('useEncryption', true);

        $twofaccounts = DB::table('twofaccounts')->get();
        Icon::factory()->create();
        $icons = DB::table('icons')->get();

        $twofaccounts->each(function ($item, $key) {
            $this->assertEquals(self::ACCOUNT, Crypt::decryptString($item->account));
            $this->assertEquals(self::SECRET, Crypt::decryptString($item->secret));
            $this->assertEquals(self::TOTP_FULL_CUSTOM_URI, Crypt::decryptString($item->legacy_uri));
        });

        $icons->each(function ($item, $key) {
            $this->assertEquals(OtpTestData::ICON_PNG_DATA, Crypt::decryptString($item->content));
        });
    }

    #[Test]
    public function test_set_useEncryption_On_twice_prevents_successive_encryption()
    {
        Settings::set('useEncryption', true);
        Settings::set('useEncryption', true);

        $twofaccounts = DB::table('twofaccounts')->get();
        Icon::factory()->create();
        $icons = DB::table('icons')->get();

        $twofaccounts->each(function ($item, $key) {
            $this->assertEquals(self::ACCOUNT, Crypt::decryptString($item->account));
            $this->assertEquals(self::SECRET, Crypt::decryptString($item->secret));
            $this->assertEquals(self::TOTP_FULL_CUSTOM_URI, Crypt::decryptString($item->legacy_uri));
        });

        $icons->each(function ($item, $key) {
            $this->assertEquals(OtpTestData::ICON_PNG_DATA, Crypt::decryptString($item->content));
        });
    }

    #[Test]
    public function test_set_useEncryption_Off_decrypts_all_accounts()
    {
        Settings::set('useEncryption', true);
        Settings::set('useEncryption', false);

        $twofaccounts = DB::table('twofaccounts')->get();
        Icon::factory()->create();
        $icons = DB::table('icons')->get();

        $twofaccounts->each(function ($item, $key) {
            $this->assertEquals(self::ACCOUNT, $item->account);
            $this->assertEquals(self::SECRET, $item->secret);
            $this->assertEquals(self::TOTP_FULL_CUSTOM_URI, $item->legacy_uri);
        });

        $icons->each(function ($item, $key) {
            $this->assertEquals(OtpTestData::ICON_PNG_DATA, $item->content);
        });
    }

    #[Test]
    #[DataProvider('provideUndecipherableData')]
    public function test_set_useEncryption_Off_returns_exception_when_data_are_undecipherable(array $data)
    {
        $this->expectException(\App\Exceptions\DbEncryptionException::class);

        Settings::set('useEncryption', true);

        $affected = DB::table('twofaccounts')
            ->where('id', $this->twofaccountOne->id)
            ->update($data);

        Settings::set('useEncryption', false);

        $twofaccount = TwoFAccount::find($this->twofaccountOne->id);
    }

    /**
     * Provide invalid data for validation test
     */
    public static function provideUndecipherableData() : array
    {
        return [
            [[
                'account' => 'undecipherableString',
            ]],
            [[
                'secret' => 'undecipherableString',
            ]],
            [[
                'legacy_uri' => 'undecipherableString',
            ]],
        ];
    }

    #[Test]
    public function test_set_true_setting_persist_transformed_boolean()
    {
        $value = Settings::set(self::SETTING_NAME, true);

        $this->assertDatabaseHas('options', [
            self::KEY   => self::SETTING_NAME,
            self::VALUE => self::SETTING_VALUE_TRUE_TRANSFORMED,
        ]);
    }

    #[Test]
    public function test_set_false_setting_persist_transformed_boolean()
    {
        $value = Settings::set(self::SETTING_NAME, false);

        $this->assertDatabaseHas('options', [
            self::KEY   => self::SETTING_NAME,
            self::VALUE => self::SETTING_VALUE_FALSE_TRANSFORMED,
        ]);
    }

    #[Test]
    public function test_del_remove_setting_from_db_and_cache()
    {
        DB::table('options')->insert(
            [self::KEY => self::SETTING_NAME, self::VALUE => strval(self::SETTING_VALUE_STRING)]
        );

        Settings::delete(self::SETTING_NAME);
        $cached = Cache::get(SettingService::CACHE_ITEM_NAME); // returns a Collection

        $this->assertDatabaseMissing('options', [
            self::KEY   => self::SETTING_NAME,
            self::VALUE => self::SETTING_VALUE_STRING,
        ]);
        $this->assertFalse($cached->has(self::SETTING_NAME));
    }

    #[Test]
    public function test_isEdited_returns_true()
    {
        DB::table('options')->insert(
            [self::KEY => 'showOtpAsDot', self::VALUE => strval(self::SETTING_VALUE_TRUE_TRANSFORMED)]
        );

        $this->assertTrue(Settings::isEdited('showOtpAsDot'));
    }

    #[Test]
    public function test_isEdited_returns_false()
    {
        DB::table('options')->where(self::KEY, 'showOtpAsDot')->delete();

        $this->assertFalse(Settings::isEdited('showOtpAsDot'));
    }

    #[Test]
    public function test_cache_is_requested_at_instanciation()
    {
        Cache::shouldReceive('remember')
            ->andReturn(collect([]));

        $settingService = new SettingService;

        Cache::shouldHaveReceived('remember');
    }

    #[Test]
    public function test_cache_is_updated_when_setting_is_set()
    {
        Cache::shouldReceive('remember', 'put')
            ->andReturn(collect([]), true);

        $settingService = new SettingService;
        $settingService->set(self::SETTING_NAME, self::SETTING_VALUE_STRING);

        Cache::shouldHaveReceived('put');
    }

    #[Test]
    public function test_cache_is_updated_when_setting_is_deleted()
    {
        Cache::shouldReceive('remember', 'put')
            ->andReturn(collect([]), true);

        $settingService = new SettingService;
        $settingService->delete(self::SETTING_NAME);

        Cache::shouldHaveReceived('put');
    }

    #[Test]
    public function test_set_storeIconsInDatabase_setting_dispatches_storeIconsInDatabaseSettingChanged()
    {
        Event::fake([
            storeIconsInDatabaseSettingChanged::class,
        ]);

        Settings::set('storeIconsInDatabase', true);

        Event::assertDispatched(storeIconsInDatabaseSettingChanged::class);
    }

    #[Test]
    public function test_set_storeIconsInDatabase_setting_impacts_the_icon_store()
    {
        Settings::set('storeIconsInDatabase', false);

        $this->assertFalse(IconStore::usesDatabase());

        Settings::set('storeIconsInDatabase', true);

        $this->assertTrue(IconStore::usesDatabase());
    }

    #[Test]
    public function test_set_storeIconsInDatabase_is_cancelled_if_database_toggling_failed()
    {
        $this->expectException(FailedIconStoreDatabaseTogglingException::class);
        
        $newValue = true;

        IconStore::shouldReceive('setDatabaseReplication')
            ->once()
            ->with($newValue)
            ->andThrow(FailedIconStoreDatabaseTogglingException::class);

        Settings::set('storeIconsInDatabase', $newValue);

        $this->assertFalse(Settings::get('storeIconsInDatabase'));
    }
}
