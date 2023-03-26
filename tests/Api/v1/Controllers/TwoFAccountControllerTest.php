<?php

namespace Tests\Api\v1\Controllers;

use App\Facades\Settings;
use App\Models\Group;
use App\Models\TwoFAccount;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\Classes\LocalFile;
use Tests\Data\MigrationTestData;
use Tests\Data\OtpTestData;
use Tests\FeatureTestCase;

/**
 * @covers \App\Api\v1\Controllers\TwoFAccountController
 * @covers \App\Api\v1\Resources\TwoFAccountCollection
 * @covers \App\Api\v1\Resources\TwoFAccountReadResource
 * @covers \App\Api\v1\Resources\TwoFAccountStoreResource
 * @covers \App\Api\v1\Resources\TwoFAccountExportResource
 * @covers \App\Api\v1\Resources\TwoFAccountExportCollection
 * @covers \App\Providers\MigrationServiceProvider
 * @covers \App\Providers\TwoFAuthServiceProvider
 * @covers \App\Policies\TwoFAccountPolicy
 */
class TwoFAccountControllerTest extends FeatureTestCase
{
    /**
     * @var \App\Models\User|\Illuminate\Contracts\Auth\Authenticatable
     */
    protected $user;

    protected $anotherUser;

    /**
     * @var App\Models\Group
     */
    protected $userGroupA;

    protected $userGroupB;

    protected $anotherUserGroupA;

    protected $anotherUserGroupB;

    /**
     * @var App\Models\TwoFAccount
     */
    protected $twofaccountA;

    protected $twofaccountB;

    protected $twofaccountC;

    protected $twofaccountD;

    private const VALID_RESOURCE_STRUCTURE_WITHOUT_SECRET = [
        'id',
        'group_id',
        'service',
        'account',
        'icon',
        'otp_type',
        'digits',
        'algorithm',
        'period',
        'counter',
    ];

    private const VALID_RESOURCE_STRUCTURE_WITH_SECRET = [
        'id',
        'group_id',
        'service',
        'account',
        'icon',
        'otp_type',
        'secret',
        'digits',
        'algorithm',
        'period',
        'counter',
    ];

    private const VALID_OTP_RESOURCE_STRUCTURE_FOR_TOTP = [
        'generated_at',
        'otp_type',
        'password',
        'period',
    ];

    private const VALID_OTP_RESOURCE_STRUCTURE_FOR_HOTP = [
        'otp_type',
        'password',
        'counter',
    ];

    private const VALID_EXPORT_STRUTURE = [
        'app',
        'schema',
        'datetime',
        'data' => [
            '*' => [
                'otp_type',
                'account',
                'service',
                'icon',
                'icon_mime',
                'icon_file',
                'secret',
                'digits',
                'algorithm',
                'period',
                'counter',
                'legacy_uri',
            ], ],
    ];

    private const JSON_FRAGMENTS_FOR_CUSTOM_TOTP = [
        'service'   => OtpTestData::SERVICE,
        'account'   => OtpTestData::ACCOUNT,
        'otp_type'  => 'totp',
        'secret'    => OtpTestData::SECRET,
        'digits'    => OtpTestData::DIGITS_CUSTOM,
        'algorithm' => OtpTestData::ALGORITHM_CUSTOM,
        'period'    => OtpTestData::PERIOD_CUSTOM,
        'counter'   => null,
    ];

    private const JSON_FRAGMENTS_FOR_DEFAULT_TOTP = [
        'service'   => null,
        'account'   => OtpTestData::ACCOUNT,
        'otp_type'  => 'totp',
        'secret'    => OtpTestData::SECRET,
        'digits'    => OtpTestData::DIGITS_DEFAULT,
        'algorithm' => OtpTestData::ALGORITHM_DEFAULT,
        'period'    => OtpTestData::PERIOD_DEFAULT,
        'counter'   => null,
    ];

    private const JSON_FRAGMENTS_FOR_CUSTOM_HOTP = [
        'service'   => OtpTestData::SERVICE,
        'account'   => OtpTestData::ACCOUNT,
        'otp_type'  => 'hotp',
        'secret'    => OtpTestData::SECRET,
        'digits'    => OtpTestData::DIGITS_CUSTOM,
        'algorithm' => OtpTestData::ALGORITHM_CUSTOM,
        'period'    => null,
        'counter'   => OtpTestData::COUNTER_CUSTOM,
    ];

    private const JSON_FRAGMENTS_FOR_DEFAULT_HOTP = [
        'service'   => null,
        'account'   => OtpTestData::ACCOUNT,
        'otp_type'  => 'hotp',
        'secret'    => OtpTestData::SECRET,
        'digits'    => OtpTestData::DIGITS_DEFAULT,
        'algorithm' => OtpTestData::ALGORITHM_DEFAULT,
        'period'    => null,
        'counter'   => OtpTestData::COUNTER_DEFAULT,
    ];

    private const ARRAY_OF_INVALID_PARAMETERS = [
        'account'  => null,
        'otp_type' => 'totp',
        'secret'   => OtpTestData::SECRET,
    ];

    /**
     * @test
     */
    public function setUp() : void
    {
        parent::setUp();

        $this->user       = User::factory()->create();
        $this->userGroupA = Group::factory()->for($this->user)->create();
        $this->userGroupB = Group::factory()->for($this->user)->create();

        $this->twofaccountA = TwoFAccount::factory()->for($this->user)->create([
            'group_id' => $this->userGroupA->id,
        ]);
        $this->twofaccountB = TwoFAccount::factory()->for($this->user)->create([
            'group_id' => $this->userGroupA->id,
        ]);

        $this->anotherUser       = User::factory()->create();
        $this->anotherUserGroupA = Group::factory()->for($this->anotherUser)->create();
        $this->anotherUserGroupB = Group::factory()->for($this->anotherUser)->create();

        $this->twofaccountC = TwoFAccount::factory()->for($this->anotherUser)->create([
            'group_id' => $this->anotherUserGroupA->id,
        ]);
        $this->twofaccountD = TwoFAccount::factory()->for($this->anotherUser)->create([
            'group_id' => $this->anotherUserGroupB->id,
        ]);
    }

    /**
     * @test
     *
     * @dataProvider indexUrlParameterProvider
     */
    public function test_index_returns_user_twofaccounts_only($urlParameter, $expected)
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('GET', '/api/v1/twofaccounts' . $urlParameter)
            ->assertOk()
            ->assertJsonCount(2, $key = null)
            ->assertJsonStructure([
                '*' => $expected,
            ])
            ->assertJsonFragment([
                'id' => $this->twofaccountA->id,
            ])
            ->assertJsonFragment([
                'id' => $this->twofaccountB->id,
            ])
            ->assertJsonMissing([
                'id' => $this->twofaccountC->id,
            ])
            ->assertJsonMissing([
                'id' => $this->twofaccountD->id,
            ]);
    }

    /**
     * Provide data for index tests
     */
    public function indexUrlParameterProvider()
    {
        return [
            'VALID_RESOURCE_STRUCTURE_WITHOUT_SECRET' => [
                '',
                self::VALID_RESOURCE_STRUCTURE_WITHOUT_SECRET,
            ],
            'VALID_RESOURCE_STRUCTURE_WITH_SECRET' => [
                '?withSecret=1',
                self::VALID_RESOURCE_STRUCTURE_WITH_SECRET,
            ],
        ];
    }

    /**
     * @test
     */
    public function test_show_returns_twofaccount_resource_with_secret()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('GET', '/api/v1/twofaccounts/' . $this->twofaccountA->id)
            ->assertOk()
            ->assertJsonStructure(self::VALID_RESOURCE_STRUCTURE_WITH_SECRET);
    }

    /**
     * @test
     */
    public function test_show_returns_twofaccount_resource_without_secret()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('GET', '/api/v1/twofaccounts/' . $this->twofaccountA->id . '?withSecret=0')
            ->assertOk()
            ->assertJsonStructure(self::VALID_RESOURCE_STRUCTURE_WITHOUT_SECRET);
    }

    /**
     * @test
     */
    // public function test_show_twofaccount_with_indeciphered_data_returns_replaced_data()
    // {
    //     $dbEncryptionService = resolve('App\Services\DbEncryptionService');
    //     $dbEncryptionService->setTo(true);

    //     $twofaccount = TwoFAccount::factory()->create();

    //     DB::table('twofaccounts')
    //         ->where('id', $twofaccount->id)
    //         ->update([
    //             'secret' => '**encrypted**',
    //             'account' => '**encrypted**',
    //         ]);

    //     $response = $this->actingAs($this->user, 'api-guard')
    //         ->json('GET', '/api/v1/twofaccounts/' . $twofaccount->id)
    //         ->assertJsonFragment([
    //             'secret' => '*indecipherable*',
    //             'account' => '*indecipherable*',
    //         ]);
    // }

    /**
     * @test
     */
    public function test_show_missing_twofaccount_returns_not_found()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('GET', '/api/v1/twofaccounts/1000')
            ->assertNotFound()
            ->assertJsonStructure([
                'message',
            ]);
    }

    /**
     * @test
     */
    public function test_show_twofaccount_of_another_user_is_forbidden()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('GET', '/api/v1/twofaccounts/' . $this->twofaccountC->id)
            ->assertForbidden()
            ->assertJsonStructure([
                'message',
            ]);
    }

    /**
     * @dataProvider accountCreationProvider
     *
     * @test
     */
    public function test_store_without_encryption_returns_success_with_consistent_resource_structure($payload, $expected)
    {
        Settings::set('useEncryption', false);
        Storage::put('test.png', 'emptied to prevent missing resource replaced by null by the model getter');

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts', $payload)
            ->assertCreated()
            ->assertJsonStructure(self::VALID_RESOURCE_STRUCTURE_WITH_SECRET)
            ->assertJsonFragment($expected);
    }

    /**
     * @dataProvider accountCreationProvider
     *
     * @test
     */
    public function test_store_with_encryption_returns_success_with_consistent_resource_structure($payload, $expected)
    {
        Settings::set('useEncryption', true);
        Storage::put('test.png', 'emptied to prevent missing resource replaced by null by the model getter');

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts', $payload)
            ->assertCreated()
            ->assertJsonStructure(self::VALID_RESOURCE_STRUCTURE_WITH_SECRET)
            ->assertJsonFragment($expected);
    }

    /**
     * Provide data for TwoFAccount store tests
     */
    public function accountCreationProvider()
    {
        return [
            'TOTP_FULL_CUSTOM_URI' => [
                [
                    'uri' => OtpTestData::TOTP_FULL_CUSTOM_URI,
                ],
                self::JSON_FRAGMENTS_FOR_CUSTOM_TOTP,
            ],
            'TOTP_SHORT_URI' => [
                [
                    'uri' => OtpTestData::TOTP_SHORT_URI,
                ],
                self::JSON_FRAGMENTS_FOR_DEFAULT_TOTP,
            ],
            'ARRAY_OF_FULL_VALID_PARAMETERS_FOR_CUSTOM_TOTP' => [
                OtpTestData::ARRAY_OF_FULL_VALID_PARAMETERS_FOR_CUSTOM_TOTP,
                self::JSON_FRAGMENTS_FOR_CUSTOM_TOTP,
            ],
            'ARRAY_OF_MINIMUM_VALID_PARAMETERS_FOR_TOTP' => [
                OtpTestData::ARRAY_OF_MINIMUM_VALID_PARAMETERS_FOR_TOTP,
                self::JSON_FRAGMENTS_FOR_DEFAULT_TOTP,
            ],
            'HOTP_FULL_CUSTOM_URI' => [
                [
                    'uri' => OtpTestData::HOTP_FULL_CUSTOM_URI,
                ],
                self::JSON_FRAGMENTS_FOR_CUSTOM_HOTP,
            ],
            'HOTP_SHORT_URI' => [
                [
                    'uri' => OtpTestData::HOTP_SHORT_URI,
                ],
                self::JSON_FRAGMENTS_FOR_DEFAULT_HOTP,
            ],
            'ARRAY_OF_FULL_VALID_PARAMETERS_FOR_CUSTOM_HOTP' => [
                OtpTestData::ARRAY_OF_FULL_VALID_PARAMETERS_FOR_CUSTOM_HOTP,
                self::JSON_FRAGMENTS_FOR_CUSTOM_HOTP,
            ],
            'ARRAY_OF_MINIMUM_VALID_PARAMETERS_FOR_HOTP' => [
                OtpTestData::ARRAY_OF_MINIMUM_VALID_PARAMETERS_FOR_HOTP,
                self::JSON_FRAGMENTS_FOR_DEFAULT_HOTP,
            ],
        ];
    }

    /**
     * @test
     */
    public function test_store_with_invalid_uri_returns_validation_error()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts', [
                'uri' => OtpTestData::INVALID_OTPAUTH_URI,
            ])
            ->assertStatus(422);
    }

    /**
     * @test
     */
    public function test_store_assigns_created_account_when_default_group_is_a_specific_one()
    {
        // Set the default group to a specific one
        $this->user['preferences->defaultGroup'] = $this->userGroupA->id;
        $this->user->save();

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts', [
                'uri' => OtpTestData::TOTP_SHORT_URI,
            ])
            ->assertJsonFragment([
                'group_id' => $this->userGroupA->id,
            ]);
    }

    /**
     * @test
     */
    public function test_store_assigns_created_account_when_default_group_is_the_active_one()
    {
        // Set the default group to be the active one
        $this->user['preferences->defaultGroup'] = -1;
        // Set the active group
        $this->user['preferences->activeGroup'] = $this->userGroupA->id;
        $this->user->save();

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts', [
                'uri' => OtpTestData::TOTP_SHORT_URI,
            ])
            ->assertJsonFragment([
                'group_id' => $this->userGroupA->id,
            ]);
    }

    /**
     * @test
     */
    public function test_store_assigns_created_account_when_default_group_is_no_group()
    {
        // Set the default group to No group
        $this->user['preferences->defaultGroup'] = 0;
        $this->user->save();

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts', [
                'uri' => OtpTestData::TOTP_SHORT_URI,
            ])
            ->assertJsonFragment([
                'group_id' => null,
            ]);
    }

    /**
     * @test
     */
    public function test_store_assigns_created_account_when_default_group_does_not_exist()
    {
        // Set the default group to a non-existing one
        $this->user['preferences->defaultGroup'] = 1000;
        $this->user->save();

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts', [
                'uri' => OtpTestData::TOTP_SHORT_URI,
            ])
            ->assertJsonFragment([
                'group_id' => null,
            ]);
    }

    /**
     * @test
     */
    public function test_update_totp_returns_success_with_updated_resource()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('PUT', '/api/v1/twofaccounts/' . $this->twofaccountA->id, OtpTestData::ARRAY_OF_FULL_VALID_PARAMETERS_FOR_CUSTOM_TOTP)
            ->assertOk()
            ->assertJsonFragment(self::JSON_FRAGMENTS_FOR_CUSTOM_TOTP);
    }

    /**
     * @test
     */
    public function test_update_hotp_returns_success_with_updated_resource()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('PUT', '/api/v1/twofaccounts/' . $this->twofaccountA->id, OtpTestData::ARRAY_OF_FULL_VALID_PARAMETERS_FOR_CUSTOM_HOTP)
            ->assertOk()
            ->assertJsonFragment(self::JSON_FRAGMENTS_FOR_CUSTOM_HOTP);
    }

    /**
     * @test
     */
    public function test_update_missing_twofaccount_returns_not_found()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('PUT', '/api/v1/twofaccounts/1000', OtpTestData::ARRAY_OF_FULL_VALID_PARAMETERS_FOR_CUSTOM_TOTP)
            ->assertNotFound();
    }

    /**
     * @test
     */
    public function test_update_twofaccount_with_invalid_data_returns_validation_error()
    {
        $twofaccount = TwoFAccount::factory()->create();

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('PUT', '/api/v1/twofaccounts/' . $this->twofaccountA->id, self::ARRAY_OF_INVALID_PARAMETERS)
            ->assertStatus(422);
    }

    /**
     * @test
     */
    public function test_update_twofaccount_of_another_user_is_forbidden()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('PUT', '/api/v1/twofaccounts/' . $this->twofaccountC->id, OtpTestData::ARRAY_OF_FULL_VALID_PARAMETERS_FOR_CUSTOM_HOTP)
            ->assertForbidden()
            ->assertJsonStructure([
                'message',
            ]);
    }

    /**
     * @test
     */
    public function test_migrate_valid_gauth_payload_returns_success_with_consistent_resources()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts/migration', [
                'payload'    => MigrationTestData::GOOGLE_AUTH_MIGRATION_URI,
                'withSecret' => 1,
            ])
            ->assertOk()
            ->assertJsonCount(2, $key = null)
            ->assertJsonFragment([
                'id'        => 0,
                'service'   => OtpTestData::SERVICE,
                'account'   => OtpTestData::ACCOUNT,
                'otp_type'  => 'totp',
                'secret'    => OtpTestData::SECRET,
                'digits'    => OtpTestData::DIGITS_DEFAULT,
                'algorithm' => OtpTestData::ALGORITHM_DEFAULT,
                'period'    => OtpTestData::PERIOD_DEFAULT,
                'counter'   => null,
            ])
            ->assertJsonFragment([
                'id'        => 0,
                'service'   => OtpTestData::SERVICE . '_bis',
                'account'   => OtpTestData::ACCOUNT . '_bis',
                'otp_type'  => 'totp',
                'secret'    => OtpTestData::SECRET,
                'digits'    => OtpTestData::DIGITS_DEFAULT,
                'algorithm' => OtpTestData::ALGORITHM_DEFAULT,
                'period'    => OtpTestData::PERIOD_DEFAULT,
                'counter'   => null,
            ]);
    }

    /**
     * @test
     */
    public function test_migrate_with_invalid_gauth_payload_returns_validation_error()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts/migration', [
                'uri' => MigrationTestData::INVALID_GOOGLE_AUTH_MIGRATION_URI,
            ])
            ->assertStatus(422);
    }

    /**
     * @test
     */
    public function test_migrate_payload_with_duplicates_returns_negative_ids()
    {
        $twofaccount = TwoFAccount::factory()->for($this->user)->create([
            'otp_type'   => 'totp',
            'account'    => OtpTestData::ACCOUNT,
            'service'    => OtpTestData::SERVICE,
            'secret'     => OtpTestData::SECRET,
            'algorithm'  => OtpTestData::ALGORITHM_DEFAULT,
            'digits'     => OtpTestData::DIGITS_DEFAULT,
            'period'     => OtpTestData::PERIOD_DEFAULT,
            'legacy_uri' => OtpTestData::TOTP_SHORT_URI,
            'icon'       => '',
        ]);

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts/migration?withSecret=1', [
                'payload' => MigrationTestData::GOOGLE_AUTH_MIGRATION_URI,
            ])
            ->assertOk()
            ->assertJsonFragment([
                'id'        => -1,
                'service'   => OtpTestData::SERVICE,
                'account'   => OtpTestData::ACCOUNT,
                'otp_type'  => 'totp',
                'secret'    => OtpTestData::SECRET,
                'digits'    => OtpTestData::DIGITS_DEFAULT,
                'algorithm' => OtpTestData::ALGORITHM_DEFAULT,
                'period'    => OtpTestData::PERIOD_DEFAULT,
                'counter'   => null,
            ])
            ->assertJsonFragment([
                'id'        => 0,
                'service'   => OtpTestData::SERVICE . '_bis',
                'account'   => OtpTestData::ACCOUNT . '_bis',
                'otp_type'  => 'totp',
                'secret'    => OtpTestData::SECRET,
                'digits'    => OtpTestData::DIGITS_DEFAULT,
                'algorithm' => OtpTestData::ALGORITHM_DEFAULT,
                'period'    => OtpTestData::PERIOD_DEFAULT,
                'counter'   => null,
            ]);
    }

    /**
     * @test
     */
    public function test_migrate_identify_duplicates_in_authenticated_user_twofaccounts_only()
    {
        $twofaccount = TwoFAccount::factory()->for($this->anotherUser)->create([
            'otp_type'   => 'totp',
            'account'    => OtpTestData::ACCOUNT,
            'service'    => OtpTestData::SERVICE,
            'secret'     => OtpTestData::SECRET,
            'algorithm'  => OtpTestData::ALGORITHM_DEFAULT,
            'digits'     => OtpTestData::DIGITS_DEFAULT,
            'period'     => OtpTestData::PERIOD_DEFAULT,
            'legacy_uri' => OtpTestData::TOTP_SHORT_URI,
            'icon'       => '',
        ]);

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts/migration?withSecret=1', [
                'payload' => MigrationTestData::GOOGLE_AUTH_MIGRATION_URI,
            ])
            ->assertOk()
            ->assertJsonFragment([
                'id'        => 0,
                'account'   => OtpTestData::ACCOUNT,
                'service'   => OtpTestData::SERVICE,
                'otp_type'  => 'totp',
                'secret'    => OtpTestData::SECRET,
                'algorithm' => OtpTestData::ALGORITHM_DEFAULT,
                'digits'    => OtpTestData::DIGITS_DEFAULT,
                'period'    => OtpTestData::PERIOD_DEFAULT,
                'icon'      => null,
            ])
            ->assertJsonFragment([
                'id'        => 0,
                'service'   => OtpTestData::SERVICE . '_bis',
                'account'   => OtpTestData::ACCOUNT . '_bis',
                'otp_type'  => 'totp',
                'secret'    => OtpTestData::SECRET,
                'digits'    => OtpTestData::DIGITS_DEFAULT,
                'algorithm' => OtpTestData::ALGORITHM_DEFAULT,
                'period'    => OtpTestData::PERIOD_DEFAULT,
                'counter'   => null,
            ]);
    }

    /**
     * @test
     */
    public function test_migrate_invalid_gauth_payload_returns_bad_request()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts/migration', [
                'payload' => MigrationTestData::GOOGLE_AUTH_MIGRATION_URI_WITH_INVALID_DATA,
            ])
            ->assertStatus(400)
            ->assertJsonStructure([
                'message',
            ]);
    }

    /**
     * @test
     */
    public function test_migrate_valid_aegis_json_file_returns_success()
    {
        $file = LocalFile::fake()->validAegisJsonFile();

        $response = $this->withHeaders(['Content-Type' => 'multipart/form-data'])
            ->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts/migration', [
                'file'       => $file,
                'withSecret' => 1,
            ])
            ->assertOk()
            ->assertJsonCount(3, $key = null)
            ->assertJsonFragment([
                'id'        => 0,
                'service'   => OtpTestData::SERVICE,
                'account'   => OtpTestData::ACCOUNT,
                'otp_type'  => 'totp',
                'secret'    => OtpTestData::SECRET,
                'digits'    => OtpTestData::DIGITS_CUSTOM,
                'algorithm' => OtpTestData::ALGORITHM_CUSTOM,
                'period'    => OtpTestData::PERIOD_CUSTOM,
                'counter'   => null,
            ])
            ->assertJsonFragment([
                'id'        => 0,
                'service'   => OtpTestData::SERVICE,
                'account'   => OtpTestData::ACCOUNT,
                'otp_type'  => 'hotp',
                'secret'    => OtpTestData::SECRET,
                'digits'    => OtpTestData::DIGITS_CUSTOM,
                'algorithm' => OtpTestData::ALGORITHM_CUSTOM,
                'period'    => null,
                'counter'   => OtpTestData::COUNTER_CUSTOM,
            ])
            ->assertJsonFragment([
                'id'        => 0,
                'service'   => OtpTestData::STEAM,
                'account'   => OtpTestData::ACCOUNT,
                'otp_type'  => 'steamtotp',
                'secret'    => OtpTestData::STEAM_SECRET,
                'digits'    => OtpTestData::DIGITS_STEAM,
                'algorithm' => OtpTestData::ALGORITHM_DEFAULT,
                'period'    => OtpTestData::PERIOD_DEFAULT,
                'counter'   => null,
            ]);
    }

    /**
     * @test
     *
     * @dataProvider invalidAegisJsonFileProvider
     */
    public function test_migrate_invalid_aegis_json_file_returns_bad_request($file)
    {
        $response = $this->withHeaders(['Content-Type' => 'multipart/form-data'])
            ->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts/migration', [
                'file' => $file,
            ])
            ->assertStatus(400);
    }

    /**
     * Provide invalid Aegis JSON files for import tests
     */
    public function invalidAegisJsonFileProvider()
    {
        return [
            'encryptedAegisJsonFile' => [
                LocalFile::fake()->encryptedAegisJsonFile(),
            ],
            'invalidAegisJsonFile' => [
                LocalFile::fake()->invalidAegisJsonFile(),
            ],
        ];
    }

    /**
     * @test
     *
     * @dataProvider validPlainTextFileProvider
     */
    public function test_migrate_valid_plain_text_file_returns_success($file)
    {
        $response = $this->withHeaders(['Content-Type' => 'multipart/form-data'])
            ->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts/migration', [
                'file'       => $file,
                'withSecret' => 1,
            ])
            ->assertOk()
            ->assertJsonCount(3, $key = null)
            ->assertJsonFragment([
                'id'        => 0,
                'service'   => OtpTestData::SERVICE,
                'account'   => OtpTestData::ACCOUNT,
                'otp_type'  => 'totp',
                'secret'    => OtpTestData::SECRET,
                'digits'    => OtpTestData::DIGITS_CUSTOM,
                'algorithm' => OtpTestData::ALGORITHM_CUSTOM,
                'period'    => OtpTestData::PERIOD_CUSTOM,
                'counter'   => null,
            ])
            ->assertJsonFragment([
                'id'        => 0,
                'service'   => OtpTestData::SERVICE,
                'account'   => OtpTestData::ACCOUNT,
                'otp_type'  => 'hotp',
                'secret'    => OtpTestData::SECRET,
                'digits'    => OtpTestData::DIGITS_CUSTOM,
                'algorithm' => OtpTestData::ALGORITHM_CUSTOM,
                'period'    => null,
                'counter'   => OtpTestData::COUNTER_CUSTOM,
            ])
            ->assertJsonFragment([
                'id'        => 0,
                'service'   => OtpTestData::STEAM,
                'account'   => OtpTestData::ACCOUNT,
                'otp_type'  => 'steamtotp',
                'secret'    => OtpTestData::STEAM_SECRET,
                'digits'    => OtpTestData::DIGITS_STEAM,
                'algorithm' => OtpTestData::ALGORITHM_DEFAULT,
                'period'    => OtpTestData::PERIOD_DEFAULT,
                'counter'   => null,
            ]);
    }

    /**
     * Provide valid Plain Text files for import tests
     */
    public function validPlainTextFileProvider()
    {
        return [
            'validPlainTextFile' => [
                LocalFile::fake()->validPlainTextFile(),
            ],
            'validPlainTextFileWithNewLines' => [
                LocalFile::fake()->validPlainTextFileWithNewLines(),
            ],
        ];
    }

    /**
     * @test
     *
     * @dataProvider invalidPlainTextFileProvider
     */
    public function test_migrate_invalid_plain_text_file_returns_bad_request($file)
    {
        $response = $this->withHeaders(['Content-Type' => 'multipart/form-data'])
            ->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts/migration', [
                'file' => $file,
            ])
            ->assertStatus(400);
    }

    /**
     * Provide invalid Plain Text files for import tests
     */
    public function invalidPlainTextFileProvider()
    {
        return [
            'invalidPlainTextFileEmpty' => [
                LocalFile::fake()->invalidPlainTextFileEmpty(),
            ],
            'invalidPlainTextFileNoUri' => [
                LocalFile::fake()->invalidPlainTextFileNoUri(),
            ],
            'invalidPlainTextFileWithInvalidUri' => [
                LocalFile::fake()->invalidPlainTextFileWithInvalidUri(),
            ],
            'invalidPlainTextFileWithInvalidLine' => [
                LocalFile::fake()->invalidPlainTextFileWithInvalidLine(),
            ],
        ];
    }

    /**
     * @test
     */
    public function test_reorder_returns_success()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts/reorder', [
                'orderedIds' => [$this->twofaccountB->id, $this->twofaccountA->id],
            ])
            ->assertStatus(200)
            ->assertJsonStructure([
                'message',
            ]);
    }

    /**
     * @test
     */
    public function test_reorder_with_invalid_data_returns_validation_error()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts/reorder', [
                'orderedIds' => '3,2,1',
            ])
            ->assertStatus(422);
    }

    /**
     * @test
     */
    public function test_reorder_twofaccounts_of_another_user_is_forbidden()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts/reorder', [
                'orderedIds' => [$this->twofaccountB->id, $this->twofaccountD->id],
            ])
            ->assertForbidden()
            ->assertJsonStructure([
                'message',
            ]);
    }

    /**
     * @test
     */
    public function test_preview_returns_success_with_resource()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts/preview', [
                'uri' => OtpTestData::TOTP_FULL_CUSTOM_URI,
            ])
            ->assertOk()
            ->assertJsonFragment(self::JSON_FRAGMENTS_FOR_CUSTOM_TOTP);
    }

    /**
     * @test
     */
    public function test_preview_with_invalid_data_returns_validation_error()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts/preview', [
                'uri' => OtpTestData::INVALID_OTPAUTH_URI,
            ])
            ->assertStatus(422);
    }

    /**
     * @test
     */
    public function test_preview_with_unreachable_image_returns_success()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts/preview', [
                'uri' => OtpTestData::TOTP_URI_WITH_UNREACHABLE_IMAGE,
            ])
            ->assertOk()
            ->assertJsonFragment([
                'icon' => null,
            ]);
    }

    /**
     * @test
     */
    public function test_export_returns_json_migration_resource()
    {
        $this->twofaccountA = TwoFAccount::factory()->for($this->user)->create(self::JSON_FRAGMENTS_FOR_DEFAULT_TOTP);
        $this->twofaccountB = TwoFAccount::factory()->for($this->user)->create(self::JSON_FRAGMENTS_FOR_DEFAULT_HOTP);

        $this->actingAs($this->user, 'api-guard')
            ->json('GET', '/api/v1/twofaccounts/export?ids=' . $this->twofaccountA->id . ',' . $this->twofaccountB->id)
            ->assertOk()
            ->assertJsonStructure(self::VALID_EXPORT_STRUTURE)
            ->assertJsonFragment(self::JSON_FRAGMENTS_FOR_DEFAULT_TOTP)
            ->assertJsonFragment(self::JSON_FRAGMENTS_FOR_DEFAULT_HOTP);
    }

    /**
     * @test
     */
    public function test_export_too_many_ids_returns_bad_request()
    {
        TwoFAccount::factory()->count(102)->for($this->user)->create();

        $ids = DB::table('twofaccounts')->where('user_id', $this->user->id)->pluck('id')->implode(',');

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('GET', '/api/v1/twofaccounts/export?ids=' . $ids)
            ->assertStatus(400)
            ->assertJsonStructure([
                'message',
                'reason',
            ]);
    }

    /**
     * @test
     */
    public function test_export_missing_twofaccount_returns_existing_ones_only()
    {
        $this->twofaccountA = TwoFAccount::factory()->for($this->user)->create(self::JSON_FRAGMENTS_FOR_DEFAULT_TOTP);

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('GET', '/api/v1/twofaccounts/export?ids=' . $this->twofaccountA->id . ',1000')
            ->assertJsonFragment(self::JSON_FRAGMENTS_FOR_DEFAULT_TOTP);
    }

    /**
     * @test
     */
    public function test_export_twofaccount_of_another_user_is_forbidden()
    {
        $response = $this->actingAs($this->user, 'api-guard')
        ->json('GET', '/api/v1/twofaccounts/export?ids=' . $this->twofaccountC->id)
            ->assertForbidden()
            ->assertJsonStructure([
                'message',
            ]);
    }

    /**
     * @test
     */
    public function test_get_otp_using_totp_twofaccount_id_returns_consistent_resource()
    {
        $twofaccount = TwoFAccount::factory()->for($this->user)->create([
            'otp_type'   => 'totp',
            'account'    => OtpTestData::ACCOUNT,
            'service'    => OtpTestData::SERVICE,
            'secret'     => OtpTestData::SECRET,
            'algorithm'  => OtpTestData::ALGORITHM_DEFAULT,
            'digits'     => OtpTestData::DIGITS_DEFAULT,
            'period'     => OtpTestData::PERIOD_DEFAULT,
            'legacy_uri' => OtpTestData::TOTP_SHORT_URI,
            'icon'       => '',
        ]);

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('GET', '/api/v1/twofaccounts/' . $twofaccount->id . '/otp')
            ->assertOk()
            ->assertJsonStructure(self::VALID_OTP_RESOURCE_STRUCTURE_FOR_TOTP)
            ->assertJsonFragment([
                'otp_type' => 'totp',
                'period'   => OtpTestData::PERIOD_DEFAULT,
            ]);
    }

    /**
     * @test
     */
    public function test_get_otp_by_posting_totp_uri_returns_consistent_resource()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts/otp', [
                'uri' => OtpTestData::TOTP_FULL_CUSTOM_URI,
            ])
            ->assertOk()
            ->assertJsonStructure(self::VALID_OTP_RESOURCE_STRUCTURE_FOR_TOTP)
            ->assertJsonFragment([
                'otp_type' => 'totp',
                'period'   => OtpTestData::PERIOD_CUSTOM,
            ]);
    }

    /**
     * @test
     */
    public function test_get_otp_by_posting_totp_parameters_returns_consistent_resource()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts/otp', OtpTestData::ARRAY_OF_FULL_VALID_PARAMETERS_FOR_CUSTOM_TOTP)
            ->assertOk()
            ->assertJsonStructure(self::VALID_OTP_RESOURCE_STRUCTURE_FOR_TOTP)
            ->assertJsonFragment([
                'otp_type' => 'totp',
                'period'   => OtpTestData::PERIOD_CUSTOM,
            ]);
    }

    /**
     * @test
     */
    public function test_get_otp_using_hotp_twofaccount_id_returns_consistent_resource()
    {
        $twofaccount = TwoFAccount::factory()->for($this->user)->create([
            'otp_type'   => 'hotp',
            'account'    => OtpTestData::ACCOUNT,
            'service'    => OtpTestData::SERVICE,
            'secret'     => OtpTestData::SECRET,
            'algorithm'  => OtpTestData::ALGORITHM_DEFAULT,
            'digits'     => OtpTestData::DIGITS_DEFAULT,
            'period'     => null,
            'legacy_uri' => OtpTestData::HOTP_SHORT_URI,
            'icon'       => '',
        ]);

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('GET', '/api/v1/twofaccounts/' . $twofaccount->id . '/otp')
            ->assertOk()
            ->assertJsonStructure(self::VALID_OTP_RESOURCE_STRUCTURE_FOR_HOTP)
            ->assertJsonFragment([
                'otp_type' => 'hotp',
                'counter'  => OtpTestData::COUNTER_DEFAULT + 1,
            ]);
    }

    /**
     * @test
     */
    public function test_get_otp_by_posting_hotp_uri_returns_consistent_resource()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts/otp', [
                'uri' => OtpTestData::HOTP_FULL_CUSTOM_URI,
            ])
            ->assertOk()
            ->assertJsonStructure(self::VALID_OTP_RESOURCE_STRUCTURE_FOR_HOTP)
            ->assertJsonFragment([
                'otp_type' => 'hotp',
                'counter'  => OtpTestData::COUNTER_CUSTOM + 1,
            ]);
    }

    /**
     * @test
     */
    public function test_get_otp_by_posting_hotp_parameters_returns_consistent_resource()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts/otp', OtpTestData::ARRAY_OF_FULL_VALID_PARAMETERS_FOR_CUSTOM_HOTP)
            ->assertOk()
            ->assertJsonStructure(self::VALID_OTP_RESOURCE_STRUCTURE_FOR_HOTP)
            ->assertJsonFragment([
                'otp_type' => 'hotp',
                'counter'  => OtpTestData::COUNTER_CUSTOM + 1,
            ]);
    }

    /**
     * @test
     */
    public function test_get_otp_by_posting_multiple_inputs_returns_bad_request()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts/otp', [
                'uri' => OtpTestData::HOTP_FULL_CUSTOM_URI,
                'key' => 'value',
            ])
            ->assertStatus(400)
            ->assertJsonStructure([
                'message',
                'reason',
            ]);
    }

    /**
     * @test
     */
    public function test_get_otp_using_indecipherable_twofaccount_id_returns_bad_request()
    {
        Settings::set('useEncryption', true);

        $twofaccount = TwoFAccount::factory()->for($this->user)->create();

        DB::table('twofaccounts')
            ->where('id', $twofaccount->id)
            ->update([
                'secret' => '**encrypted**',
            ]);

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('GET', '/api/v1/twofaccounts/' . $twofaccount->id . '/otp')
            ->assertStatus(400)
            ->assertJsonStructure([
                'message',
            ]);
    }

    /**
     * @test
     */
    public function test_get_otp_using_missing_twofaccount_id_returns_not_found()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('GET', '/api/v1/twofaccounts/1000/otp')
            ->assertNotFound();
    }

    /**
     * @test
     */
    public function test_get_otp_by_posting_invalid_uri_returns_validation_error()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts/otp', [
                'uri' => OtpTestData::INVALID_OTPAUTH_URI,
            ])
            ->assertStatus(422);
    }

    /**
     * @test
     */
    public function test_get_otp_by_posting_invalid_parameters_returns_validation_error()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts/otp', self::ARRAY_OF_INVALID_PARAMETERS)
            ->assertStatus(422);
    }

    /**
     * @test
     */
    public function test_get_otp_of_another_user_twofaccount_is_forbidden()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('GET', '/api/v1/twofaccounts/' . $this->twofaccountC->id . '/otp')
            ->assertForbidden()
            ->assertJsonStructure([
                'message',
            ]);
    }

    /**
     * @test
     */
    public function test_count_returns_right_number_of_twofaccounts()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('GET', '/api/v1/twofaccounts/count')
            ->assertStatus(200)
            ->assertExactJson([
                'count' => 2,
            ]);
    }

    /**
     * @test
     */
    public function test_withdraw_returns_success()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('PATCH', '/api/v1/twofaccounts/withdraw?ids=1,2')
            ->assertOk()
            ->assertJsonStructure([
                'message',
            ]);
    }

    /**
     * @test
     */
    public function test_withdraw_too_many_ids_returns_bad_request()
    {
        TwoFAccount::factory()->count(102)->for($this->user)->create();

        $ids = DB::table('twofaccounts')->where('user_id', $this->user->id)->pluck('id')->implode(',');

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('PATCH', '/api/v1/twofaccounts/withdraw?ids=' . $ids)
            ->assertStatus(400)
            ->assertJsonStructure([
                'message',
                'reason',
            ]);
    }

    /**
     * @test
     */
    public function test_destroy_twofaccount_returns_success()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('DELETE', '/api/v1/twofaccounts/' . $this->twofaccountA->id)
            ->assertNoContent();
    }

    /**
     * @test
     */
    public function test_destroy_missing_twofaccount_returns_not_found()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('DELETE', '/api/v1/twofaccounts/1000')
            ->assertNotFound();
    }

    /**
     * @test
     */
    public function test_destroy_twofaccount_of_another_user_is_forbidden()
    {
        $response = $this->actingAs($this->user, 'api-guard')
        ->json('DELETE', '/api/v1/twofaccounts/' . $this->twofaccountC->id)
            ->assertForbidden()
            ->assertJsonStructure([
                'message',
            ]);
    }

    /**
     * @test
     */
    public function test_batch_destroy_twofaccount_returns_success()
    {
        TwoFAccount::factory()->count(3)->for($this->user)->create();

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('DELETE', '/api/v1/twofaccounts?ids=' . $this->twofaccountA->id . ',' . $this->twofaccountB->id)
            ->assertNoContent();
    }

    /**
     * @test
     */
    public function test_batch_destroy_too_many_twofaccounts_returns_bad_request()
    {
        TwoFAccount::factory()->count(102)->for($this->user)->create();

        $ids = DB::table('twofaccounts')->where('user_id', $this->user->id)->pluck('id')->implode(',');

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('DELETE', '/api/v1/twofaccounts?ids=' . $ids)
            ->assertStatus(400)
            ->assertJsonStructure([
                'message',
                'reason',
            ]);
    }

    /**
     * @test
     */
    public function test_batch_destroy_twofaccount_of_another_user_is_forbidden()
    {
        TwoFAccount::factory()->count(2)->for($this->anotherUser)->create();

        $ids = DB::table('twofaccounts')
            ->where('user_id', $this->anotherUser->id)
            ->pluck('id')
            ->implode(',');

        $response = $this->actingAs($this->user, 'api-guard')
        ->json('DELETE', '/api/v1/twofaccounts?ids=' . $ids)
            ->assertForbidden()
            ->assertJsonStructure([
                'message',
            ]);
    }
}
