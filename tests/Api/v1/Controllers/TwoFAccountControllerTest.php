<?php

namespace Tests\Api\v1\Controllers;

use App\Models\User;
use App\Models\Group;
use App\Facades\Settings;
use Tests\FeatureTestCase;
use Tests\Classes\OtpTestData;
use App\Models\TwoFAccount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\Classes\LocalFile;


/**
 * @covers \App\Api\v1\Controllers\TwoFAccountController
 * @covers \App\Api\v1\Resources\TwoFAccountReadResource
 * @covers \App\Api\v1\Resources\TwoFAccountStoreResource
 */
class TwoFAccountControllerTest extends FeatureTestCase
{
    /**
     * @var \App\Models\User
    */
    protected $user;

    /**
     * @var \App\Models\Group
    */
    protected $group;



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
        'counter'
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
        'counter'
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
        'service' => null,
        'account'   => OtpTestData::ACCOUNT,
        'otp_type'  => 'hotp',
        'secret'    => OtpTestData::SECRET,
        'digits'    => OtpTestData::DIGITS_DEFAULT,
        'algorithm' => OtpTestData::ALGORITHM_DEFAULT,
        'period'    => null,
        'counter'   => OtpTestData::COUNTER_DEFAULT,
    ];
    private const ARRAY_OF_INVALID_PARAMETERS = [
        'account'   => null,
        'otp_type'  => 'totp',
        'secret'    => OtpTestData::SECRET,
    ];


    /**
     * @test
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->group = Group::factory()->create();
    }


    /**
     * @test
     * 
     * @dataProvider indexUrlParameterProvider
     */
    public function test_index_returns_twofaccount_collection($urlParameter, $expected)
    {
        TwoFAccount::factory()->count(3)->create();

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('GET', '/api/v1/twofaccounts'.$urlParameter)
            ->assertOk()
            ->assertJsonCount(3, $key = null)
            ->assertJsonStructure([
                '*' => $expected
            ]);
    }


    /**
     * Provide data for index tests
     */
    public function indexUrlParameterProvider()
    {
        return [
            'VALID_RESOURCE_STRUCTURE_WITHOUT_SECRET'  => [
                '',
                self::VALID_RESOURCE_STRUCTURE_WITHOUT_SECRET
            ],
            'VALID_RESOURCE_STRUCTURE_WITH_SECRET'  => [
                '?withSecret=1',
                self::VALID_RESOURCE_STRUCTURE_WITH_SECRET
            ],
        ];
    }


    /**
     * @test
     */
    public function test_show_twofaccount_returns_twofaccount_resource_with_secret()
    {
        $twofaccount = TwoFAccount::factory()->create();

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('GET', '/api/v1/twofaccounts/' . $twofaccount->id)
            ->assertOk()
            ->assertJsonStructure(self::VALID_RESOURCE_STRUCTURE_WITH_SECRET);
    }


    /**
     * @test
     */
    public function test_show_twofaccount_returns_twofaccount_resource_without_secret()
    {
        $twofaccount = TwoFAccount::factory()->create();

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('GET', '/api/v1/twofaccounts/' . $twofaccount->id . '?withSecret=0')
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
                'message'
            ]);
    }


    /**
     * @dataProvider accountCreationProvider
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
            'TOTP_FULL_CUSTOM_URI'  => [
                [
                    'uri' => OtpTestData::TOTP_FULL_CUSTOM_URI,
                ],
                self::JSON_FRAGMENTS_FOR_CUSTOM_TOTP
            ],
            'TOTP_SHORT_URI'  => [
                [
                    'uri' => OtpTestData::TOTP_SHORT_URI,
                ],
                self::JSON_FRAGMENTS_FOR_DEFAULT_TOTP
            ],
            'ARRAY_OF_FULL_VALID_PARAMETERS_FOR_CUSTOM_TOTP'  => [
                OtpTestData::ARRAY_OF_FULL_VALID_PARAMETERS_FOR_CUSTOM_TOTP,
                self::JSON_FRAGMENTS_FOR_CUSTOM_TOTP
            ],
            'ARRAY_OF_MINIMUM_VALID_PARAMETERS_FOR_TOTP'  => [
                OtpTestData::ARRAY_OF_MINIMUM_VALID_PARAMETERS_FOR_TOTP,
                self::JSON_FRAGMENTS_FOR_DEFAULT_TOTP
            ],
            'HOTP_FULL_CUSTOM_URI'  => [
                [
                    'uri' => OtpTestData::HOTP_FULL_CUSTOM_URI,
                ],
                self::JSON_FRAGMENTS_FOR_CUSTOM_HOTP
            ],
            'HOTP_SHORT_URI'  => [
                [
                    'uri' => OtpTestData::HOTP_SHORT_URI,
                ],
                self::JSON_FRAGMENTS_FOR_DEFAULT_HOTP
            ],
            'ARRAY_OF_FULL_VALID_PARAMETERS_FOR_CUSTOM_HOTP'  => [
                OtpTestData::ARRAY_OF_FULL_VALID_PARAMETERS_FOR_CUSTOM_HOTP,
                self::JSON_FRAGMENTS_FOR_CUSTOM_HOTP
            ],
            'ARRAY_OF_MINIMUM_VALID_PARAMETERS_FOR_HOTP'  => [
                OtpTestData::ARRAY_OF_MINIMUM_VALID_PARAMETERS_FOR_HOTP,
                self::JSON_FRAGMENTS_FOR_DEFAULT_HOTP
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
        Settings::set('defaultGroup', $this->group->id);

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts', [
                'uri' => OtpTestData::TOTP_SHORT_URI,
            ])
            ->assertJsonFragment([
                'group_id' => $this->group->id
            ]);
    }


    /**
     * @test
     */
    public function test_store_assigns_created_account_when_default_group_is_the_active_one()
    {
        // Set the default group to be the active one
        Settings::set('defaultGroup', -1);
        // Set the active group
        Settings::set('activeGroup', $this->group->id);

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts', [
                'uri' => OtpTestData::TOTP_SHORT_URI,
            ])
            ->assertJsonFragment([
                'group_id' => $this->group->id
            ]);
    }


    /**
     * @test
     */
    public function test_store_assigns_created_account_when_default_group_is_no_group()
    {
        // Set the default group to No group
        Settings::set('defaultGroup', 0);

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts', [
                'uri' => OtpTestData::TOTP_SHORT_URI,
            ])
            ->assertJsonFragment([
                'group_id' => null
            ]);
    }


    /**
     * @test
     */
    public function test_store_assigns_created_account_when_default_group_does_not_exist()
    {
        // Set the default group to a non-existing one
        Settings::set('defaultGroup', 1000);

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts', [
                'uri' => OtpTestData::TOTP_SHORT_URI,
            ])
            ->assertJsonFragment([
                'group_id' => null
            ]);
    }


    /**
     * @test
     */
    public function test_update_totp_returns_success_with_updated_resource()
    {
        $twofaccount = TwoFAccount::factory()->create();

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('PUT', '/api/v1/twofaccounts/' . $twofaccount->id, OtpTestData::ARRAY_OF_FULL_VALID_PARAMETERS_FOR_CUSTOM_TOTP)
            ->assertOk()
            ->assertJsonFragment(self::JSON_FRAGMENTS_FOR_CUSTOM_TOTP);
    }


    /**
     * @test
     */
    public function test_update_hotp_returns_success_with_updated_resource()
    {
        $twofaccount = TwoFAccount::factory()->create();

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('PUT', '/api/v1/twofaccounts/' . $twofaccount->id, OtpTestData::ARRAY_OF_FULL_VALID_PARAMETERS_FOR_CUSTOM_HOTP)
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
            ->json('PUT', '/api/v1/twofaccounts/' . $twofaccount->id, self::ARRAY_OF_INVALID_PARAMETERS)
            ->assertStatus(422);
    }


    /**
     * @test
     */
    public function test_import_valid_gauth_payload_returns_success_with_consistent_resources()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts/migration', [
                'payload' => OtpTestData::GOOGLE_AUTH_MIGRATION_URI,
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
                'counter'   => null
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
                'counter'   => null
            ]);
    }


    /**
     * @test
     */
    public function test_import_with_invalid_gauth_payload_returns_validation_error()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts/migration', [
                'uri' => OtpTestData::INVALID_GOOGLE_AUTH_MIGRATION_URI,
            ])
            ->assertStatus(422);
    }


    /**
     * @test
     */
    public function test_import_gauth_payload_with_duplicates_returns_negative_ids()
    {
        $twofaccount = TwoFAccount::factory()->create([
            'otp_type' => 'totp',
            'account' => OtpTestData::ACCOUNT,
            'service' => OtpTestData::SERVICE,
            'secret' => OtpTestData::SECRET,
            'algorithm' => OtpTestData::ALGORITHM_DEFAULT,
            'digits' => OtpTestData::DIGITS_DEFAULT,
            'period' => OtpTestData::PERIOD_DEFAULT,
            'legacy_uri' => OtpTestData::TOTP_SHORT_URI,
            'icon' => '',
        ]);

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts/migration', [
                'payload' => OtpTestData::GOOGLE_AUTH_MIGRATION_URI,
            ])
            ->assertOk()
            ->assertJsonFragment([
                'id'        => -1,
                'service'   => OtpTestData::SERVICE,
                'account'   => OtpTestData::ACCOUNT,
            ]);
    }


    /**
     * @test
     */
    public function test_import_invalid_gauth_payload_returns_bad_request()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts/migration', [
                'payload' => OtpTestData::GOOGLE_AUTH_MIGRATION_URI_WITH_INVALID_DATA,
            ])
            ->assertStatus(400)
            ->assertJsonStructure([
                'message'
            ]);
    }


    /**
     * @test
     */
    public function test_import_valid_aegis_json_file_returns_success()
    {
        $file = LocalFile::fake()->validAegisJsonFile();

        $response = $this->withHeaders(['Content-Type' => 'multipart/form-data'])
            ->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts/migration', [
                'file' => $file,
                'withSecret' => 1,
            ])
            ->assertOk()
            ->assertJsonCount(5, $key = null)
            ->assertJsonFragment([
                'id'        => 0,
                'service'   => OtpTestData::SERVICE . '_totp',
                'account'   => OtpTestData::ACCOUNT . '_totp',
                'otp_type'  => 'totp',
                'secret'    => OtpTestData::SECRET,
                'digits'    => OtpTestData::DIGITS_DEFAULT,
                'algorithm' => OtpTestData::ALGORITHM_DEFAULT,
                'period'    => OtpTestData::PERIOD_DEFAULT,
                'counter'   => null
            ])
            ->assertJsonFragment([
                'id'        => 0,
                'service'   => OtpTestData::SERVICE . '_totp_custom',
                'account'   => OtpTestData::ACCOUNT . '_totp_custom',
                'otp_type'  => 'totp',
                'secret'    => OtpTestData::SECRET,
                'digits'    => OtpTestData::DIGITS_CUSTOM,
                'algorithm' => OtpTestData::ALGORITHM_CUSTOM,
                'period'    => OtpTestData::PERIOD_CUSTOM,
                'counter'   => null
            ])
            ->assertJsonFragment([
                'id'        => 0,
                'service'   => OtpTestData::SERVICE . '_hotp',
                'account'   => OtpTestData::ACCOUNT . '_hotp',
                'otp_type'  => 'hotp',
                'secret'    => OtpTestData::SECRET,
                'digits'    => OtpTestData::DIGITS_DEFAULT,
                'algorithm' => OtpTestData::ALGORITHM_DEFAULT,
                'period'    => null,
                'counter'   => OtpTestData::COUNTER_DEFAULT
            ])
            ->assertJsonFragment([
                'id'        => 0,
                'service'   => OtpTestData::SERVICE . '_hotp_custom',
                'account'   => OtpTestData::ACCOUNT . '_hotp_custom',
                'otp_type'  => 'totp',
                'secret'    => OtpTestData::SECRET,
                'digits'    => OtpTestData::DIGITS_CUSTOM,
                'algorithm' => OtpTestData::ALGORITHM_CUSTOM,
                'period'    => null,
                'counter'   => OtpTestData::COUNTER_CUSTOM,
            ])
            ->assertJsonFragment([
                'id'        => 0,
                'service'   => OtpTestData::STEAM,
                'account'   => OtpTestData::ACCOUNT . '_steam',
                'otp_type'  => 'steamtotp',
                'secret'    => OtpTestData::STEAM_SECRET,
                'digits'    => OtpTestData::DIGITS_STEAM,
                'algorithm' => OtpTestData::ALGORITHM_DEFAULT,
                'period'    => OtpTestData::PERIOD_DEFAULT,
                'counter'   => null
            ]);
    }


    /**
     * @test
     * 
     * @dataProvider invalidAegisJsonFileProvider
     */
    public function test_import_invalid_aegis_json_file_returns_bad_request($file)
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
            'validPlainTextFile' => [
                LocalFile::fake()->encryptedAegisJsonFile()
            ],
            'validPlainTextFileWithNewLines' => [
                LocalFile::fake()->invalidAegisJsonFile()
            ],
        ];
    }


    /**
     * @test
     * 
     * @dataProvider validPlainTextFileProvider
     */
    public function test_import_valid_plain_text_file_returns_success($file)
    {
        $response = $this->withHeaders(['Content-Type' => 'multipart/form-data'])
            ->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts/migration', [
                'file' => $file,
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
                'counter'   => null
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
                'counter'   => OtpTestData::COUNTER_CUSTOM
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
                'counter'   => null
            ]);
    }


    /**
     * Provide valid Plain Text files for import tests
     */
    public function validPlainTextFileProvider()
    {
        return [
            'validPlainTextFile' => [
                LocalFile::fake()->validPlainTextFile()
            ],
            'validPlainTextFileWithNewLines' => [
                LocalFile::fake()->validPlainTextFileWithNewLines()
            ],
        ];
    }


    /**
     * @test
     * 
     * @dataProvider invalidPlainTextFileProvider
     */
    public function test_import_invalid_plain_text_file_returns_bad_request($file)
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
            'validPlainTextFile' => [
                LocalFile::fake()->invalidPlainTextFileEmpty()
            ],
            'validPlainTextFileWithNewLines' => [
                LocalFile::fake()->invalidPlainTextFileNoUri()
            ],
            'validPlainTextFileWithNewLines' => [
                LocalFile::fake()->invalidPlainTextFileWithInvalidUri()
            ],
            'validPlainTextFileWithNewLines' => [
                LocalFile::fake()->invalidPlainTextFileWithInvalidLine()
            ],
        ];
    }


    /**
     * @test
     */
    public function test_reorder_returns_success()
    {
        TwoFAccount::factory()->count(3)->create();

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts/reorder', [
                'orderedIds' => [3,2,1]])
            ->assertStatus(200)
            ->assertJsonStructure([
                'message'
            ]);
    }


    /**
     * @test
     */
    public function test_reorder_with_invalid_data_returns_validation_error()
    {
        TwoFAccount::factory()->count(3)->create();

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts/reorder', [
                'orderedIds' => '3,2,1'])
            ->assertStatus(422);
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
                'icon' => null
            ]);
    }


    /**
     * @test
     */
    public function test_get_otp_using_totp_twofaccount_id_returns_consistent_resource()
    {
        $twofaccount = TwoFAccount::factory()->create([
            'otp_type' => 'totp',
            'account' => OtpTestData::ACCOUNT,
            'service' => OtpTestData::SERVICE,
            'secret' => OtpTestData::SECRET,
            'algorithm' => OtpTestData::ALGORITHM_DEFAULT,
            'digits' => OtpTestData::DIGITS_DEFAULT,
            'period' => OtpTestData::PERIOD_DEFAULT,
            'legacy_uri' => OtpTestData::TOTP_SHORT_URI,
            'icon' => '',
        ]);

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('GET', '/api/v1/twofaccounts/' . $twofaccount->id . '/otp')
            ->assertOk()
            ->assertJsonStructure(self::VALID_OTP_RESOURCE_STRUCTURE_FOR_TOTP)
            ->assertJsonFragment([
                'otp_type' => 'totp',
                'period' => OtpTestData::PERIOD_DEFAULT,
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
                'period' => OtpTestData::PERIOD_CUSTOM,
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
                'period' => OtpTestData::PERIOD_CUSTOM,
            ]);
    }


    /**
     * @test
     */
    public function test_get_otp_using_hotp_twofaccount_id_returns_consistent_resource()
    {
        $twofaccount = TwoFAccount::factory()->create([
            'otp_type' => 'hotp',
            'account' => OtpTestData::ACCOUNT,
            'service' => OtpTestData::SERVICE,
            'secret' => OtpTestData::SECRET,
            'algorithm' => OtpTestData::ALGORITHM_DEFAULT,
            'digits' => OtpTestData::DIGITS_DEFAULT,
            'period' => null,
            'legacy_uri' => OtpTestData::HOTP_SHORT_URI,
            'icon' => '',
        ]);

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('GET', '/api/v1/twofaccounts/' . $twofaccount->id . '/otp')
            ->assertOk()
            ->assertJsonStructure(self::VALID_OTP_RESOURCE_STRUCTURE_FOR_HOTP)
            ->assertJsonFragment([
                'otp_type' => 'hotp',
                'counter' => OtpTestData::COUNTER_DEFAULT + 1,
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
                'counter' => OtpTestData::COUNTER_CUSTOM + 1,
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
                'counter' => OtpTestData::COUNTER_CUSTOM + 1,
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

        $twofaccount = TwoFAccount::factory()->create();

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
    public function test_count_returns_right_number_of_twofaccount()
    {
        TwoFAccount::factory()->count(3)->create();

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('GET', '/api/v1/twofaccounts/count')
            ->assertStatus(200)
            ->assertExactJson([
                'count' => 3
            ]);
    }


    /**
     * @test
     */
    public function test_withdraw_returns_success()
    {
        TwoFAccount::factory()->count(3)->create();
        $ids = DB::table('twofaccounts')->pluck('id')->implode(',');

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('PATCH', '/api/v1/twofaccounts/withdraw?ids=1,2,3' . $ids)
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
        TwoFAccount::factory()->count(102)->create();
        $ids = DB::table('twofaccounts')->pluck('id')->implode(',');

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
        $twofaccount = TwoFAccount::factory()->create();

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('DELETE', '/api/v1/twofaccounts/' . $twofaccount->id)
            ->assertNoContent();
    }


    /**
     * @test
     */
    public function test_destroy_missing_twofaccount_returns_not_found()
    {
        $twofaccount = TwoFAccount::factory()->create();

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('DELETE', '/api/v1/twofaccounts/1000')
            ->assertNotFound();
    }


    /**
     * @test
     */
    public function test_batch_destroy_twofaccount_returns_success()
    {
        TwoFAccount::factory()->count(3)->create();
        $ids = DB::table('twofaccounts')->pluck('id')->implode(',');

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('DELETE', '/api/v1/twofaccounts?ids=' . $ids)
            ->assertNoContent();
    }


    /**
     * @test
     */
    public function test_batch_destroy_too_many_twofaccounts_returns_bad_request()
    {
        TwoFAccount::factory()->count(102)->create();
        $ids = DB::table('twofaccounts')->pluck('id')->implode(',');

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('DELETE', '/api/v1/twofaccounts?ids=' . $ids)
            ->assertStatus(400)
            ->assertJsonStructure([
                'message',
                'reason',
            ]);
    }

}