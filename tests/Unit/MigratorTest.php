<?php

namespace Tests\Unit;

use App\Exceptions\EncryptedMigrationException;
use App\Exceptions\InvalidMigrationDataException;
use App\Exceptions\UnsupportedMigrationException;
use App\Facades\Icons;
use App\Factories\MigratorFactory;
use App\Models\TwoFAccount;
use App\Providers\MigrationServiceProvider;
use App\Providers\TwoFAuthServiceProvider;
use App\Services\Migrators\AegisMigrator;
use App\Services\Migrators\BitwardenMigrator;
use App\Services\Migrators\GoogleAuthMigrator;
use App\Services\Migrators\Migrator;
use App\Services\Migrators\PlainTextMigrator;
use App\Services\Migrators\TwoFASMigrator;
use App\Services\Migrators\TwoFAuthMigrator;
use App\Services\SettingService;
use Illuminate\Support\Facades\Storage;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use Tests\Data\MigrationTestData;
use Tests\Data\OtpTestData;
use Tests\TestCase;

/**
 * MigratorTest test class
 */
#[CoversClass(MigrationServiceProvider::class)]
#[CoversClass(TwoFAuthServiceProvider::class)]
#[CoversClass(MigratorFactory::class)]
#[CoversClass(Migrator::class)]
#[CoversClass(AegisMigrator::class)]
#[CoversClass(TwoFASMigrator::class)]
#[CoversClass(PlainTextMigrator::class)]
#[CoversClass(GoogleAuthMigrator::class)]
#[CoversClass(TwoFAuthMigrator::class)]
#[UsesClass(TwoFAccount::class)]
class MigratorTest extends TestCase
{
    /**
     * App\Models\TwoFAccount $totpTwofaccount
     */
    protected $totpTwofaccount;

    /**
     * App\Models\TwoFAccount $totpTwofaccount
     */
    protected $hotpTwofaccount;

    /**
     * App\Models\TwoFAccount $steamTwofaccount
     */
    protected $steamTwofaccount;

    /**
     * App\Models\TwoFAccount $GAuthTotpTwofaccount
     */
    protected $GAuthTotpTwofaccount;

    /**
     * App\Models\TwoFAccount $GAuthTotpBisTwofaccount
     */
    protected $GAuthTotpBisTwofaccount;

    protected $fakeTwofaccount;

    protected function setUp() : void
    {
        parent::setUp();

        $this->mock(SettingService::class, function (MockInterface $iconStore) {
            foreach (config('2fauth.settings') as $setting => $value) {
                $iconStore->shouldReceive('get')
                    ->with($setting)
                    ->andReturn($value);
            }
        });

        $this->totpTwofaccount             = new TwoFAccount;
        $this->totpTwofaccount->legacy_uri = OtpTestData::TOTP_FULL_CUSTOM_URI_NO_IMG;
        $this->totpTwofaccount->service    = OtpTestData::SERVICE;
        $this->totpTwofaccount->account    = OtpTestData::ACCOUNT;
        $this->totpTwofaccount->icon       = null;
        $this->totpTwofaccount->otp_type   = 'totp';
        $this->totpTwofaccount->secret     = OtpTestData::SECRET;
        $this->totpTwofaccount->digits     = OtpTestData::DIGITS_CUSTOM;
        $this->totpTwofaccount->algorithm  = OtpTestData::ALGORITHM_CUSTOM;
        $this->totpTwofaccount->period     = OtpTestData::PERIOD_CUSTOM;
        $this->totpTwofaccount->counter    = null;

        $this->hotpTwofaccount             = new TwoFAccount;
        $this->hotpTwofaccount->legacy_uri = OtpTestData::HOTP_FULL_CUSTOM_URI_NO_IMG;
        $this->hotpTwofaccount->service    = OtpTestData::SERVICE;
        $this->hotpTwofaccount->account    = OtpTestData::ACCOUNT;
        $this->hotpTwofaccount->icon       = null;
        $this->hotpTwofaccount->otp_type   = 'hotp';
        $this->hotpTwofaccount->secret     = OtpTestData::SECRET;
        $this->hotpTwofaccount->digits     = OtpTestData::DIGITS_CUSTOM;
        $this->hotpTwofaccount->algorithm  = OtpTestData::ALGORITHM_CUSTOM;
        $this->hotpTwofaccount->period     = null;
        $this->hotpTwofaccount->counter    = OtpTestData::COUNTER_CUSTOM;

        $this->steamTwofaccount             = new TwoFAccount;
        $this->steamTwofaccount->legacy_uri = OtpTestData::STEAM_TOTP_URI;
        $this->steamTwofaccount->service    = OtpTestData::STEAM;
        $this->steamTwofaccount->account    = OtpTestData::ACCOUNT;
        $this->steamTwofaccount->icon       = null;
        $this->steamTwofaccount->otp_type   = 'steamtotp';
        $this->steamTwofaccount->secret     = OtpTestData::STEAM_SECRET;
        $this->steamTwofaccount->digits     = OtpTestData::DIGITS_STEAM;
        $this->steamTwofaccount->algorithm  = OtpTestData::ALGORITHM_DEFAULT;
        $this->steamTwofaccount->period     = OtpTestData::PERIOD_DEFAULT;
        $this->steamTwofaccount->counter    = null;

        $this->GAuthTotpTwofaccount            = new TwoFAccount;
        $this->GAuthTotpTwofaccount->service   = OtpTestData::SERVICE;
        $this->GAuthTotpTwofaccount->account   = OtpTestData::ACCOUNT;
        $this->GAuthTotpTwofaccount->icon      = null;
        $this->GAuthTotpTwofaccount->otp_type  = 'totp';
        $this->GAuthTotpTwofaccount->secret    = OtpTestData::SECRET;
        $this->GAuthTotpTwofaccount->digits    = OtpTestData::DIGITS_DEFAULT;
        $this->GAuthTotpTwofaccount->algorithm = OtpTestData::ALGORITHM_DEFAULT;
        $this->GAuthTotpTwofaccount->period    = OtpTestData::PERIOD_DEFAULT;
        $this->GAuthTotpTwofaccount->counter   = null;

        $this->GAuthTotpBisTwofaccount            = new TwoFAccount;
        $this->GAuthTotpBisTwofaccount->service   = OtpTestData::SERVICE . '_bis';
        $this->GAuthTotpBisTwofaccount->account   = OtpTestData::ACCOUNT . '_bis';
        $this->GAuthTotpBisTwofaccount->icon      = null;
        $this->GAuthTotpBisTwofaccount->otp_type  = 'totp';
        $this->GAuthTotpBisTwofaccount->secret    = OtpTestData::SECRET;
        $this->GAuthTotpBisTwofaccount->digits    = OtpTestData::DIGITS_DEFAULT;
        $this->GAuthTotpBisTwofaccount->algorithm = OtpTestData::ALGORITHM_DEFAULT;
        $this->GAuthTotpBisTwofaccount->period    = OtpTestData::PERIOD_DEFAULT;
        $this->GAuthTotpBisTwofaccount->counter   = null;

        $this->fakeTwofaccount     = new TwoFAccount;
        $this->fakeTwofaccount->id = TwoFAccount::FAKE_ID;
    }

    /**
     * Clean up the testing environment before the next test.
     *
     *
     * @throws \Mockery\Exception\InvalidCountException
     */
    protected function tearDown() : void
    {
        $this->forgetMock(SettingService::class);

        parent::tearDown();
    }

    #[Test]
    #[DataProvider('validMigrationsProvider')]
    public function test_migrate_returns_consistent_accounts(Migrator $migrator, mixed $payload, string $expected, bool $hasSteam)
    {
        $accounts = $migrator->migrate($payload);

        if ($expected === 'gauth') {
            $totp = $this->GAuthTotpTwofaccount;
            $hotp = $this->GAuthTotpBisTwofaccount;
        } else {
            $totp = $this->totpTwofaccount;
            $hotp = $this->hotpTwofaccount;
            if ($hasSteam) {
                $steam = $this->steamTwofaccount;
            }
        }

        $this->assertContainsOnlyInstancesOf(TwoFAccount::class, $accounts);
        $this->assertCount($hasSteam ? 3 : 2, $accounts);

        // The returned collection could have non-linear index (because of possible blank lines
        // in the migration payload) so we do not use get() to retrieve items
        $this->assertObjectEquals($totp, $accounts->first());
        $this->assertObjectEquals($hotp, $accounts->slice(1, 1)->first());
        if ($hasSteam) {
            $this->assertObjectEquals($steam, $accounts->last());
        }
    }

    /**
     * Provide data for TwoFAccount store tests
     */
    public static function validMigrationsProvider()
    {
        return [
            'PLAIN_TEXT_PAYLOAD' => [
                new PlainTextMigrator,
                MigrationTestData::VALID_PLAIN_TEXT_PAYLOAD,
                'custom',
                $hasSteam = true,
            ],
            'PLAIN_TEXT_PAYLOAD_WITH_INTRUDER' => [
                new PlainTextMigrator,
                MigrationTestData::VALID_PLAIN_TEXT_PAYLOAD_WITH_INTRUDER,
                'custom',
                $hasSteam = true,
            ],
            'AEGIS_JSON_MIGRATION_PAYLOAD' => [
                new AegisMigrator,
                MigrationTestData::VALID_AEGIS_JSON_MIGRATION_PAYLOAD,
                'custom',
                $hasSteam = true,
            ],
            '2FAS_MIGRATION_PAYLOAD' => [
                new TwoFASMigrator,
                MigrationTestData::VALID_2FAS_MIGRATION_PAYLOAD,
                'custom',
                $hasSteam = false,
            ],
            'GOOGLE_AUTH_MIGRATION_PAYLOAD' => [
                new GoogleAuthMigrator,
                MigrationTestData::GOOGLE_AUTH_MIGRATION_URI,
                'gauth',
                $hasSteam = false,
            ],
            '2FAUTH_MIGRATION_PAYLOAD' => [
                new TwoFAuthMigrator,
                MigrationTestData::VALID_2FAUTH_JSON_MIGRATION_PAYLOAD,
                'custom',
                $hasSteam = true,
            ],
            'BITWARDEN_AUTH_JSON_MIGRATION_PAYLOAD' => [
                new BitwardenMigrator,
                MigrationTestData::VALID_BITWARDEN_AUTH_JSON_MIGRATION_PAYLOAD,
                'custom',
                $hasSteam = true,
            ],
            'BITWARDEN_JSON_MIGRATION_PAYLOAD' => [
                new BitwardenMigrator,
                MigrationTestData::VALID_BITWARDEN_JSON_MIGRATION_PAYLOAD,
                'custom',
                $hasSteam = false,
            ],
        ];
    }

    #[Test]
    #[DataProvider('invalidMigrationsProvider')]
    public function test_migrate_with_invalid_payload_returns_InvalidMigrationDataException(Migrator $migrator, mixed $payload)
    {
        $this->expectException(InvalidMigrationDataException::class);

        $accounts = $migrator->migrate($payload);
    }

    /**
     * Provide data for TwoFAccount store tests
     */
    public static function invalidMigrationsProvider()
    {
        return [
            'INVALID_PLAIN_TEXT_NO_URI' => [
                new PlainTextMigrator,
                MigrationTestData::INVALID_PLAIN_TEXT_NO_URI,
            ],
            'INVALID_PLAIN_TEXT_ONLY_EMPTY_LINES' => [
                new PlainTextMigrator,
                MigrationTestData::INVALID_PLAIN_TEXT_ONLY_EMPTY_LINES,
            ],
            'INVALID_PLAIN_TEXT_NULL' => [
                new PlainTextMigrator,
                null,
            ],
            'INVALID_PLAIN_TEXT_EMPTY_STRING' => [
                new PlainTextMigrator,
                '',
            ],
            'INVALID_PLAIN_TEXT_INT' => [
                new PlainTextMigrator,
                10,
            ],
            'INVALID_PLAIN_TEXT_BOOL' => [
                new PlainTextMigrator,
                true,
            ],
            'INVALID_AEGIS_JSON_MIGRATION_PAYLOAD' => [
                new AegisMigrator,
                MigrationTestData::INVALID_AEGIS_JSON_MIGRATION_PAYLOAD,
            ],
            'ENCRYPTED_AEGIS_JSON_MIGRATION_PAYLOAD' => [
                new AegisMigrator,
                MigrationTestData::ENCRYPTED_AEGIS_JSON_MIGRATION_PAYLOAD,
            ],
            'INVALID_2FAS_MIGRATION_PAYLOAD' => [
                new TwoFASMigrator,
                MigrationTestData::INVALID_2FAS_MIGRATION_PAYLOAD,
            ],
            'INVALID_GOOGLE_AUTH_MIGRATION_URI' => [
                new GoogleAuthMigrator,
                MigrationTestData::INVALID_GOOGLE_AUTH_MIGRATION_URI,
            ],
            'GOOGLE_AUTH_MIGRATION_URI_WITH_INVALID_DATA' => [
                new GoogleAuthMigrator,
                MigrationTestData::GOOGLE_AUTH_MIGRATION_URI_WITH_INVALID_DATA,
            ],
            'INVALID_2FAUTH_JSON_MIGRATION_PAYLOAD' => [
                new TwoFAuthMigrator,
                MigrationTestData::INVALID_2FAUTH_JSON_MIGRATION_PAYLOAD,
            ],
            'INVALID_BITWARDEN_AUTH_JSON_MIGRATION_PAYLOAD' => [
                new BitwardenMigrator,
                MigrationTestData::INVALID_BITWARDEN_AUTH_JSON_MIGRATION_PAYLOAD,
            ],

        ];
    }

    #[Test]
    #[DataProvider('migrationWithInvalidAccountsProvider')]
    public function test_migrate_returns_fake_accounts(Migrator $migrator, mixed $payload)
    {
        $accounts = $migrator->migrate($payload);

        $this->assertContainsOnlyInstancesOf(TwoFAccount::class, $accounts);
        $this->assertCount(2, $accounts);

        // The returned collection could have non-linear index (because of possible blank lines
        // in the migration payload) so we do not use get() to retrieve items
        $this->assertObjectEquals($this->totpTwofaccount, $accounts->first());
        $this->assertEquals($this->fakeTwofaccount->id, $accounts->last()->id);
    }

    /**
     * Provide data for TwoFAccount store tests
     */
    public static function migrationWithInvalidAccountsProvider()
    {
        return [
            'PLAIN_TEXT_PAYLOAD_WITH_INVALID_URI' => [
                new PlainTextMigrator,
                MigrationTestData::PLAIN_TEXT_PAYLOAD_WITH_INVALID_URI,
            ],
            'VALID_AEGIS_JSON_MIGRATION_PAYLOAD_WITH_UNSUPPORTED_OTP_TYPE' => [
                new AegisMigrator,
                MigrationTestData::VALID_AEGIS_JSON_MIGRATION_PAYLOAD_WITH_UNSUPPORTED_OTP_TYPE,
            ],
            'VALID_2FAS_MIGRATION_PAYLOAD_WITH_UNSUPPORTED_OTP_TYPE' => [
                new TwoFASMigrator,
                MigrationTestData::VALID_2FAS_MIGRATION_PAYLOAD_WITH_UNSUPPORTED_OTP_TYPE,
            ],
            'VALID_2FAUTH_MIGRATION_PAYLOAD_WITH_UNSUPPORTED_OTP_TYPE' => [
                new TwoFAuthMigrator,
                MigrationTestData::VALID_2FAUTH_MIGRATION_PAYLOAD_WITH_UNSUPPORTED_OTP_TYPE,
            ],
        ];
    }

    #[Test]
    public function test_migrate_gauth_returns_fake_accounts()
    {
        $migrator = $this->partialMock(GoogleAuthMigrator::class, function (MockInterface $migrator) {
            $migrator->shouldAllowMockingProtectedMethods()->shouldReceive('toBase32')
                ->andThrow(new \Exception);
        });

        /** @disregard Undefined function */
        $accounts = $migrator->migrate(MigrationTestData::GOOGLE_AUTH_MIGRATION_URI);

        $this->assertContainsOnlyInstancesOf(TwoFAccount::class, $accounts);
        $this->assertCount(2, $accounts);

        // The returned collection could have non-linear index (because of possible blank lines
        // in the migration payload) so we do not use get() to retrieve items
        $this->assertEquals($this->fakeTwofaccount->id, $accounts->first()->id);
        $this->assertEquals($this->fakeTwofaccount->id, $accounts->last()->id);

        $this->forgetMock(GoogleAuthMigrator::class);
    }

    #[Test]
    #[DataProvider('AegisWithIconMigrationProvider')]
    public function test_migrate_aegis_payload_with_icon_sets_and_stores_the_icon($migration)
    {
        Icons::spy();
        Storage::fake('icons');

        $migrator = new AegisMigrator;
        $accounts = $migrator->migrate($migration);

        $this->assertContainsOnlyInstancesOf(TwoFAccount::class, $accounts);
        $this->assertCount(1, $accounts);

        Icons::shouldHaveReceived('buildFromResource')->once();
        Storage::disk('icons')->assertExists($accounts->first()->icon);
    }

    /**
     * Provide data for TwoFAccount store tests
     */
    public static function AegisWithIconMigrationProvider()
    {
        return [
            'SVG' => [
                MigrationTestData::VALID_AEGIS_JSON_MIGRATION_PAYLOAD_WITH_SVG_ICON,
            ],
            'PNG' => [
                MigrationTestData::VALID_AEGIS_JSON_MIGRATION_PAYLOAD_WITH_PNG_ICON,
            ],
            'JPG' => [
                MigrationTestData::VALID_AEGIS_JSON_MIGRATION_PAYLOAD_WITH_JPG_ICON,
            ],
        ];
    }

    #[Test]
    public function test_migrate_aegis_payload_with_unsupported_icon_does_not_fail()
    {
        Storage::fake('icons');

        $migrator = new AegisMigrator;
        $accounts = $migrator->migrate(MigrationTestData::VALID_AEGIS_JSON_MIGRATION_PAYLOAD_WITH_UNSUPPORTED_ICON);

        $this->assertContainsOnlyInstancesOf(TwoFAccount::class, $accounts);
        $this->assertCount(1, $accounts);

        $this->assertNull($this->fakeTwofaccount->icon);
        Storage::disk('icons')->assertDirectoryEmpty('/');
    }

    #[Test]
    #[DataProvider('TwoFAuthWithIconMigrationProvider')]
    public function test_migrate_2fauth_payload_with_icon_sets_and_stores_the_icon($migration)
    {
        Icons::spy();
        Storage::fake('icons');

        $migrator = new TwoFAuthMigrator;
        $accounts = $migrator->migrate($migration);

        $this->assertContainsOnlyInstancesOf(TwoFAccount::class, $accounts);
        $this->assertCount(1, $accounts);

        Icons::shouldHaveReceived('buildFromResource')->once();
        Storage::disk('icons')->assertExists($accounts->first()->icon);
    }

    /**
     * Provide data for TwoFAccount store tests
     */
    public static function TwoFAuthWithIconMigrationProvider()
    {
        return [
            'SVG' => [
                MigrationTestData::VALID_2FAUTH_JSON_MIGRATION_PAYLOAD_WITH_SVG_ICON,
            ],
            'PNG' => [
                MigrationTestData::VALID_2FAUTH_JSON_MIGRATION_PAYLOAD_WITH_PNG_ICON,
            ],
            'JPG' => [
                MigrationTestData::VALID_2FAUTH_JSON_MIGRATION_PAYLOAD_WITH_JPG_ICON,
            ],
            'BMP' => [
                MigrationTestData::VALID_2FAUTH_JSON_MIGRATION_PAYLOAD_WITH_BMP_ICON,
            ],
            'XBMP' => [
                MigrationTestData::VALID_2FAUTH_JSON_MIGRATION_PAYLOAD_WITH_XBMP_ICON,
            ],
            'WEBP' => [
                MigrationTestData::VALID_2FAUTH_JSON_MIGRATION_PAYLOAD_WITH_WEBP_ICON,
            ],
        ];
    }

    #[Test]
    public function test_migrate_2fauth_payload_with_unsupported_icon_does_not_fail()
    {
        Storage::fake('icons');

        $migrator = new TwoFAuthMigrator;
        $accounts = $migrator->migrate(MigrationTestData::VALID_2FAUTH_JSON_MIGRATION_PAYLOAD_WITH_UNSUPPORTED_ICON);

        $this->assertContainsOnlyInstancesOf(TwoFAccount::class, $accounts);
        $this->assertCount(1, $accounts);

        $this->assertNull($this->fakeTwofaccount->icon);
        Storage::disk('icons')->assertDirectoryEmpty('/');
    }

    #[Test]
    #[DataProvider('factoryProvider')]
    public function test_factory_returns_relevant_migrator($payload, $migratorClass)
    {
        $factory = new MigratorFactory;

        $migrator = $factory->create($payload);

        $this->assertInstanceOf($migratorClass, $migrator);
    }

    /**
     * Provide data for TwoFAccount store tests
     */
    public static function factoryProvider()
    {
        return [
            'VALID_PLAIN_TEXT_PAYLOAD' => [
                MigrationTestData::VALID_PLAIN_TEXT_PAYLOAD,
                PlainTextMigrator::class,
            ],
            'VALID_AEGIS_JSON_MIGRATION_PAYLOAD' => [
                MigrationTestData::VALID_AEGIS_JSON_MIGRATION_PAYLOAD,
                AegisMigrator::class,
            ],
            'VALID_AEGIS_JSON_MIGRATION_PAYLOAD_WITH_UNSUPPORTED_ICON' => [
                MigrationTestData::VALID_AEGIS_JSON_MIGRATION_PAYLOAD_WITH_UNSUPPORTED_ICON,
                AegisMigrator::class,
            ],
            'VALID_2FAS_MIGRATION_PAYLOAD' => [
                MigrationTestData::VALID_2FAS_MIGRATION_PAYLOAD,
                TwoFASMigrator::class,
            ],
            'GOOGLE_AUTH_MIGRATION_URI' => [
                MigrationTestData::GOOGLE_AUTH_MIGRATION_URI,
                GoogleAuthMigrator::class,
            ],
            '2FAUTH_MIGRATION_URI' => [
                MigrationTestData::VALID_2FAUTH_JSON_MIGRATION_PAYLOAD,
                TwoFAuthMigrator::class,
            ],
            'BITWARDEN_AUTH_MIGRATION_URI' => [
                MigrationTestData::VALID_BITWARDEN_AUTH_JSON_MIGRATION_PAYLOAD,
                BitwardenMigrator::class,
            ],
            'BITWARDEN_MIGRATION_URI' => [
                MigrationTestData::VALID_BITWARDEN_JSON_MIGRATION_PAYLOAD,
                BitwardenMigrator::class,
            ],
        ];
    }

    #[Test]
    public function test_factory_throw_UnsupportedMigrationException()
    {
        $this->expectException(UnsupportedMigrationException::class);
        $factory = new MigratorFactory;

        $migrator = $factory->create('not_a_valid_payload');
    }

    #[Test]
    #[DataProvider('encryptedMigrationDataProvider')]
    public function test_factory_throw_EncryptedMigrationException($payload)
    {
        $this->expectException(EncryptedMigrationException::class);

        $factory = new MigratorFactory;

        $migrator = $factory->create($payload);
    }

    /**
     * Provide data for TwoFAccount store tests
     */
    public static function encryptedMigrationDataProvider()
    {
        return [
            'ENCRYPTED_AEGIS_JSON_MIGRATION_PAYLOAD' => [
                MigrationTestData::ENCRYPTED_AEGIS_JSON_MIGRATION_PAYLOAD,
            ],
            'ENCRYPTED_2FAS_MIGRATION_PAYLOAD' => [
                MigrationTestData::ENCRYPTED_2FAS_MIGRATION_PAYLOAD,
            ],
            'ENCRYPTED_BITWARDEN_AUTH_JSON_MIGRATION_PAYLOAD' => [
                MigrationTestData::ENCRYPTED_BITWARDEN_AUTH_JSON_MIGRATION_PAYLOAD,
            ],
            'ENCRYPTED_BITWARDEN_JSON_MIGRATION_PAYLOAD' => [
                MigrationTestData::ENCRYPTED_BITWARDEN_JSON_MIGRATION_PAYLOAD,
            ],
        ];
    }
}
