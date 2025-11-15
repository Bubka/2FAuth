<?php

namespace Tests\Api\v1\Controllers;

use App\Api\v1\Controllers\TwoFAccountController;
use App\Api\v1\Resources\TwoFAccountCollection;
use App\Api\v1\Resources\TwoFAccountExportCollection;
use App\Api\v1\Resources\TwoFAccountExportResource;
use App\Api\v1\Resources\TwoFAccountReadResource;
use App\Api\v1\Resources\TwoFAccountStoreResource;
use App\Facades\IconStore;
use App\Facades\Settings;
use App\Models\Group;
use App\Models\TwoFAccount;
use App\Models\User;
use App\Policies\TwoFAccountPolicy;
use App\Providers\MigrationServiceProvider;
use App\Providers\TwoFAuthServiceProvider;
use App\Services\LogoLib\TfaLogoLib;
use Illuminate\Http\Testing\FileFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\Classes\LocalFile;
use Tests\Data\CommonDataProvider;
use Tests\Data\HttpRequestTestData;
use Tests\Data\MigrationTestData;
use Tests\Data\OtpTestData;
use Tests\FeatureTestCase;

/**
 * TwoFAccountControllerTest test class
 */
#[CoversClass(TwoFAccountController::class)]
#[CoversClass(TwoFAccountCollection::class)]
#[CoversClass(TwoFAccountReadResource::class)]
#[CoversClass(TwoFAccountStoreResource::class)]
#[CoversClass(TwoFAccountExportResource::class)]
#[CoversClass(TwoFAccountExportCollection::class)]
#[CoversClass(MigrationServiceProvider::class)]
#[CoversClass(TwoFAuthServiceProvider::class)]
#[CoversClass(TwoFAccountPolicy::class)]
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

    protected $twofaccountE;

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

    private const VALID_EMBEDDED_OTP_RESOURCE_STRUCTURE_FOR_TOTP = [
        'generated_at',
        'password',
        'next_password',
    ];

    private const VALID_OTP_RESOURCE_STRUCTURE_FOR_HOTP = [
        'otp_type',
        'password',
        'counter',
    ];

    private const VALID_RESOURCE_STRUCTURE_WITH_OTP = [
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
        'otp' => self::VALID_EMBEDDED_OTP_RESOURCE_STRUCTURE_FOR_TOTP,
    ];

    private const VALID_COLLECTION_RESOURCE_STRUCTURE_WITH_OTP = [
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
        'otp' => self::VALID_EMBEDDED_OTP_RESOURCE_STRUCTURE_FOR_TOTP,
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
            ],
        ],
    ];

    private const VALID_EXPORT_AS_URIS_STRUTURE = [
        'app',
        'datetime',
        'data' => [
            '*' => [
                'uri',
            ],
        ],
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

    protected function setUp() : void
    {
        parent::setUp();

        Storage::fake('icons');
        Storage::fake('iconPacks');
        Storage::fake('logos');
        Storage::fake('imagesLink');

        Http::preventStrayRequests();
        Http::fake([
            CommonDataProvider::TFA_URL                      => Http::response(HttpRequestTestData::SVG_LOGO_BODY, 200),
            CommonDataProvider::SELFH_URL                    => Http::response(HttpRequestTestData::SVG_LOGO_BODY, 200),
            CommonDataProvider::DASHBOARDICONS_URL           => Http::response(HttpRequestTestData::SVG_LOGO_BODY, 200),
            TfaLogoLib::TFA_JSON_URL                         => Http::response(HttpRequestTestData::TFA_JSON_BODY, 200),
            OtpTestData::EXTERNAL_IMAGE_URL_DECODED          => Http::response((new FileFactory)->image('file.png', 10, 10)->tempFile, 200),
            OtpTestData::EXTERNAL_INFECTED_IMAGE_URL_DECODED => Http::response((new FileFactory)->createWithContent('infected.svg', OtpTestData::ICON_SVG_DATA_INFECTED)->tempFile, 200),
            'example.com/*'                                  => Http::response(null, 400),
        ]);

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
        $this->twofaccountE = TwoFAccount::factory()->for($this->anotherUser)->create([
            'group_id' => $this->anotherUserGroupB->id,
        ]);
    }

    #[Test]
    #[DataProvider('validResourceStructureProvider')]
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
    public static function validResourceStructureProvider()
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
            'VALID_COLLECTION_RESOURCE_STRUCTURE_WITH_OTP' => [
                '?withOtp=1',
                self::VALID_COLLECTION_RESOURCE_STRUCTURE_WITH_OTP,
            ],
        ];
    }

    #[Test]
    public function test_index_returns_user_accounts_with_given_ids()
    {
        $response = $this->actingAs($this->anotherUser, 'api-guard')
            ->json('GET', '/api/v1/twofaccounts?ids=' . $this->twofaccountC->id . ',' . $this->twofaccountE->id)
            ->assertOk()
            ->assertJsonCount(2, $key = null)
            ->assertJsonStructure([
                '*' => self::VALID_RESOURCE_STRUCTURE_WITHOUT_SECRET,
            ])
            ->assertJsonFragment([
                'id' => $this->twofaccountC->id,
            ])
            ->assertJsonFragment([
                'id' => $this->twofaccountE->id,
            ]);
    }

    #[Test]
    public function test_index_returns_only_user_accounts_in_given_ids()
    {
        $response = $this->actingAs($this->anotherUser, 'api-guard')
            ->json('GET', '/api/v1/twofaccounts?ids=' . $this->twofaccountA->id . ',' . $this->twofaccountE->id)
            ->assertOk()
            ->assertJsonCount(1, $key = null)
            ->assertJsonStructure([
                '*' => self::VALID_RESOURCE_STRUCTURE_WITHOUT_SECRET,
            ])
            ->assertJsonMissing([
                'id' => $this->twofaccountA->id,
            ])
            ->assertJsonFragment([
                'id' => $this->twofaccountE->id,
            ]);
    }

    #[Test]
    public function test_orphan_accounts_are_reassign_to_the_only_user()
    {
        config(['auth.defaults.guard' => 'reverse-proxy-guard']);

        $this->anotherUser->delete();
        $this->twofaccountA->user_id = null;
        $this->twofaccountA->save();

        $this->assertCount(1, User::all());
        $this->assertNull($this->twofaccountA->user_id);
        $this->assertCount(1, TwoFAccount::orphans()->get());

        $this->actingAs($this->user, 'reverse-proxy-guard')
            ->json('GET', '/api/v1/twofaccounts')
            ->assertOk();

        $this->twofaccountA->refresh();

        $this->assertNotNull($this->twofaccountA->user_id);
    }

    #[Test]
    public function test_show_returns_twofaccount_resource_with_secret()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('GET', '/api/v1/twofaccounts/' . $this->twofaccountA->id)
            ->assertOk()
            ->assertJsonStructure(self::VALID_RESOURCE_STRUCTURE_WITH_SECRET);
    }

    #[Test]
    public function test_show_returns_twofaccount_resource_without_secret()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('GET', '/api/v1/twofaccounts/' . $this->twofaccountA->id . '?withSecret=0')
            ->assertOk()
            ->assertJsonStructure(self::VALID_RESOURCE_STRUCTURE_WITHOUT_SECRET);
    }

    // #[Test]
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

    #[Test]
    public function test_show_returns_twofaccount_resource_with_otp()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('GET', '/api/v1/twofaccounts/' . $this->twofaccountA->id . '?withOtp=1')
            ->assertOk()
            ->assertJsonStructure(self::VALID_RESOURCE_STRUCTURE_WITH_OTP);
    }

    #[Test]
    public function test_show_returns_twofaccount_resource_without_otp()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('GET', '/api/v1/twofaccounts/' . $this->twofaccountA->id . '?withOtp=0')
            ->assertOk()
            ->assertJsonStructure(self::VALID_RESOURCE_STRUCTURE_WITHOUT_SECRET);
    }

    #[Test]
    public function test_show_missing_twofaccount_returns_not_found()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('GET', '/api/v1/twofaccounts/1000')
            ->assertNotFound()
            ->assertJsonStructure([
                'message',
            ]);
    }

    #[Test]
    public function test_show_twofaccount_of_another_user_is_forbidden()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('GET', '/api/v1/twofaccounts/' . $this->twofaccountC->id)
            ->assertForbidden()
            ->assertJsonStructure([
                'message',
            ]);
    }

    #[Test]
    #[DataProvider('accountCreationProvider')]
    public function test_store_without_encryption_returns_success_with_consistent_resource_structure($payload, $expected)
    {
        Settings::set('useEncryption', false);

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts', $payload)
            ->assertCreated()
            ->assertJsonStructure(self::VALID_RESOURCE_STRUCTURE_WITH_SECRET)
            ->assertJsonFragment($expected);
    }

    #[Test]
    #[DataProvider('accountCreationProvider')]
    public function test_store_with_encryption_returns_success_with_consistent_resource_structure($payload, $expected)
    {
        Settings::set('useEncryption', true);

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts', $payload)
            ->assertCreated()
            ->assertJsonStructure(self::VALID_RESOURCE_STRUCTURE_WITH_SECRET)
            ->assertJsonFragment($expected);
    }

    /**
     * Provide data for TwoFAccount store tests
     */
    public static function accountCreationProvider()
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

    #[Test]
    public function test_store_with_invalid_uri_returns_validation_error()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts', [
                'uri' => OtpTestData::INVALID_OTPAUTH_URI,
            ])
            ->assertStatus(422);
    }

    #[Test]
    public function test_store_assigns_created_account_to_provided_groupid()
    {
        // Set the default group to No group
        $this->user['preferences->defaultGroup'] = 0;
        $this->user->save();

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts', array_merge(
                OtpTestData::ARRAY_OF_FULL_VALID_PARAMETERS_FOR_CUSTOM_TOTP,
                ['group_id' => $this->userGroupA->id]
            ))
            ->assertJsonFragment([
                'group_id' => $this->userGroupA->id,
            ]);
    }

    #[Test]
    public function test_store_with_assignement_to_missing_groupid_returns_validation_error()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts', array_merge(
                OtpTestData::ARRAY_OF_FULL_VALID_PARAMETERS_FOR_CUSTOM_TOTP,
                ['group_id' => 9999999]
            ))
            ->assertJsonValidationErrorFor('group_id');
    }

    #[Test]
    public function test_store_with_assignement_to_null_groupid_does_not_assign_account_to_group()
    {
        // Set the default group to No group
        $this->user['preferences->defaultGroup'] = 0;
        $this->user->save();

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts', array_merge(
                OtpTestData::ARRAY_OF_FULL_VALID_PARAMETERS_FOR_CUSTOM_TOTP,
                ['group_id' => null]
            ))
            ->assertJsonFragment([
                'group_id' => null,
            ]);
    }

    #[Test]
    public function test_store_with_assignement_to_null_groupid_is_overriden_by_specific_default_group()
    {
        // Set the default group to a specific group
        $this->user['preferences->defaultGroup'] = $this->userGroupA->id;
        $this->user->save();

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts', array_merge(
                OtpTestData::ARRAY_OF_FULL_VALID_PARAMETERS_FOR_CUSTOM_TOTP,
                ['group_id' => null]
            ))
            ->assertJsonFragment([
                'group_id' => $this->user->preferences['defaultGroup'],
            ]);
    }

    #[Test]
    public function test_store_with_assignement_to_zero_groupid_overrides_specific_default_group()
    {
        // Set the default group to a specific group
        $this->user['preferences->defaultGroup'] = $this->userGroupA->id;
        $this->user->save();

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts', array_merge(
                OtpTestData::ARRAY_OF_FULL_VALID_PARAMETERS_FOR_CUSTOM_TOTP,
                ['group_id' => 0]
            ))
            ->assertJsonFragment([
                'group_id' => null,
            ]);
    }

    #[Test]
    public function test_store_with_assignement_to_provided_groupid_overrides_specific_default_group()
    {
        // Set the default group to a specific group
        $this->user['preferences->defaultGroup'] = $this->userGroupA->id;
        $this->user->save();

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts', array_merge(
                OtpTestData::ARRAY_OF_FULL_VALID_PARAMETERS_FOR_CUSTOM_TOTP,
                ['group_id' => $this->userGroupB->id]
            ))
            ->assertJsonFragment([
                'group_id' => $this->userGroupB->id,
            ]);
    }

    #[Test]
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
                'group_id' => $this->user->preferences['defaultGroup'],
            ]);
    }

    #[Test]
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
                'group_id' => $this->user->preferences['activeGroup'],
            ]);
    }

    #[Test]
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

    #[Test]
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

    #[Test]
    public function test_update_totp_returns_success_with_updated_resource()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('PUT', '/api/v1/twofaccounts/' . $this->twofaccountA->id, OtpTestData::ARRAY_OF_FULL_VALID_PARAMETERS_FOR_CUSTOM_TOTP)
            ->assertOk()
            ->assertJsonFragment(self::JSON_FRAGMENTS_FOR_CUSTOM_TOTP);
    }

    #[Test]
    public function test_update_hotp_returns_success_with_updated_resource()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('PUT', '/api/v1/twofaccounts/' . $this->twofaccountA->id, OtpTestData::ARRAY_OF_FULL_VALID_PARAMETERS_FOR_CUSTOM_HOTP)
            ->assertOk()
            ->assertJsonFragment(self::JSON_FRAGMENTS_FOR_CUSTOM_HOTP);
    }

    #[Test]
    public function test_update_missing_twofaccount_returns_not_found()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('PUT', '/api/v1/twofaccounts/1000', OtpTestData::ARRAY_OF_FULL_VALID_PARAMETERS_FOR_CUSTOM_TOTP)
            ->assertNotFound();
    }

    #[Test]
    public function test_update_with_assignement_to_null_group_returns_success_with_updated_resource()
    {
        $this->assertNotEquals(null, $this->twofaccountA->group_id);

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('PUT', '/api/v1/twofaccounts/' . $this->twofaccountA->id, array_merge(
                OtpTestData::ARRAY_OF_FULL_VALID_PARAMETERS_FOR_CUSTOM_TOTP,
                ['group_id' => null]
            ))
            ->assertOk()
            ->assertJsonFragment([
                'group_id' => null,
            ])
            ->assertJsonFragment(self::JSON_FRAGMENTS_FOR_CUSTOM_TOTP);
    }

    #[Test]
    public function test_update_with_assignement_to_zero_group_returns_success_with_updated_resource()
    {
        $this->assertNotEquals(null, $this->twofaccountA->group_id);

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('PUT', '/api/v1/twofaccounts/' . $this->twofaccountA->id, array_merge(
                OtpTestData::ARRAY_OF_FULL_VALID_PARAMETERS_FOR_CUSTOM_TOTP,
                ['group_id' => 0]
            ))
            ->assertOk()
            ->assertJsonFragment([
                'group_id' => null,
            ])
            ->assertJsonFragment(self::JSON_FRAGMENTS_FOR_CUSTOM_TOTP);
    }

    #[Test]
    public function test_update_with_assignement_to_new_groupid_returns_success_with_updated_resource()
    {
        $this->assertEquals($this->userGroupA->id, $this->twofaccountA->group_id);

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('PUT', '/api/v1/twofaccounts/' . $this->twofaccountA->id, array_merge(
                OtpTestData::ARRAY_OF_FULL_VALID_PARAMETERS_FOR_CUSTOM_TOTP,
                ['group_id' => $this->userGroupB->id]
            ))
            ->assertOk()
            ->assertJsonFragment([
                'group_id' => $this->userGroupB->id,
            ])
            ->assertJsonFragment(self::JSON_FRAGMENTS_FOR_CUSTOM_TOTP);
    }

    #[Test]
    public function test_update_with_assignement_to_missing_groupid_returns_validation_error()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('PUT', '/api/v1/twofaccounts/' . $this->twofaccountA->id, array_merge(
                OtpTestData::ARRAY_OF_FULL_VALID_PARAMETERS_FOR_CUSTOM_TOTP,
                ['group_id' => 9999999]
            ))
            ->assertJsonValidationErrorFor('group_id');
    }

    #[Test]
    public function test_update_twofaccount_with_invalid_data_returns_validation_error()
    {
        $twofaccount = TwoFAccount::factory()->create();

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('PUT', '/api/v1/twofaccounts/' . $this->twofaccountA->id, self::ARRAY_OF_INVALID_PARAMETERS)
            ->assertStatus(422);
    }

    #[Test]
    public function test_update_twofaccount_of_another_user_is_forbidden()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('PUT', '/api/v1/twofaccounts/' . $this->twofaccountC->id, OtpTestData::ARRAY_OF_FULL_VALID_PARAMETERS_FOR_CUSTOM_HOTP)
            ->assertForbidden()
            ->assertJsonStructure([
                'message',
            ]);
    }

    #[Test]
    public function test_update_with_removed_icon_prevents_official_logo_fetching()
    {
        $attributes = ([
            'otp_type'   => 'totp',
            'account'    => OtpTestData::ACCOUNT,
            'service'    => OtpTestData::SERVICE,
            'secret'     => OtpTestData::SECRET,
            'algorithm'  => OtpTestData::ALGORITHM_DEFAULT,
            'digits'     => OtpTestData::DIGITS_DEFAULT,
            'period'     => OtpTestData::PERIOD_DEFAULT,
            'legacy_uri' => OtpTestData::TOTP_SHORT_URI,
            'icon'       => 'icon.png',
        ]);
        $twofaccount        = TwoFAccount::factory()->for($this->user)->create($attributes);
        $attributes['icon'] = '';

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('PUT', '/api/v1/twofaccounts/' . $twofaccount->id, $attributes);

        $this->assertNull($response->json('icon'));
    }

    #[Test]
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

    #[Test]
    public function test_migrate_with_invalid_gauth_payload_returns_validation_error()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts/migration', [
                'uri' => MigrationTestData::INVALID_GOOGLE_AUTH_MIGRATION_URI,
            ])
            ->assertStatus(422);
    }

    #[Test]
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

    #[Test]
    public function test_migrate_identifies_duplicates_in_authenticated_user_twofaccounts_only()
    {
        $this->user['preferences->getOfficialIcons'] = false;

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

    #[Test]
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

    #[Test]
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

    #[Test]
    #[DataProvider('invalidAegisJsonFileProvider')]
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
    public static function invalidAegisJsonFileProvider()
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

    #[Test]
    #[DataProvider('validPlainTextFileProvider')]
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
    public static function validPlainTextFileProvider()
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

    #[Test]
    #[DataProvider('invalidPlainTextFileProvider')]
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
    public static function invalidPlainTextFileProvider()
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

    #[Test]
    public function test_reorder_returns_success()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts/reorder', [
                'orderedIds' => [$this->twofaccountB->id, $this->twofaccountA->id],
            ])
            ->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'orderedIds',
            ])
            ->assertJsonFragment([
                'orderedIds' => [
                    $this->twofaccountB->id,
                    $this->twofaccountA->id,
                ],
            ]);
    }

    #[Test]
    public function test_reorder_with_invalid_data_returns_validation_error()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts/reorder', [
                'orderedIds' => '3,2,1',
            ])
            ->assertStatus(422);
    }

    #[Test]
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

    #[Test]
    public function test_preview_returns_success_with_resource()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts/preview', [
                'uri' => OtpTestData::TOTP_FULL_CUSTOM_URI,
            ])
            ->assertOk()
            ->assertJsonFragment(self::JSON_FRAGMENTS_FOR_CUSTOM_TOTP);
    }

    #[Test]
    public function test_preview_with_invalid_data_returns_validation_error()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts/preview', [
                'uri' => OtpTestData::INVALID_OTPAUTH_URI,
            ])
            ->assertStatus(422);
    }

    #[Test]
    public function test_preview_with_unreachable_image_but_official_logo_returns_success()
    {
        $this->user['preferences->getOfficialIcons'] = true;

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts/preview', [
                'uri' => OtpTestData::TOTP_URI_WITH_UNREACHABLE_IMAGE,
            ])
            ->assertOk();

        $this->assertNotNull($response->json('icon'));
    }

    #[Test]
    public function test_preview_with_unreachable_image_returns_success_with_no_icon()
    {
        $this->user['preferences->getOfficialIcons'] = false;

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts/preview', [
                'uri' => OtpTestData::TOTP_URI_WITH_UNREACHABLE_IMAGE,
            ])
            ->assertOk()
            ->assertJsonFragment([
                'icon' => null,
            ]);
    }

    #[Test]
    public function test_preview_with_infected_svg_image_stores_sanitized_image()
    {
        $this->user['preferences->getOfficialIcons'] = true;

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts/preview', [
                'uri' => OtpTestData::TOTP_URI_WITH_INFECTED_SVG_IMAGE,
            ])
            ->assertOk();

        $svgContent = IconStore::get($response->getData()->icon);
        $this->assertStringNotContainsString(OtpTestData::ICON_SVG_MALICIOUS_CODE, $svgContent);
    }

    #[Test]
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

    #[Test]
    public function test_export_returns_plain_text_with_otpauth_uris()
    {
        $this->twofaccountA = TwoFAccount::factory()->for($this->user)->create(self::JSON_FRAGMENTS_FOR_DEFAULT_TOTP);
        $this->twofaccountB = TwoFAccount::factory()->for($this->user)->create(self::JSON_FRAGMENTS_FOR_DEFAULT_HOTP);

        $this->actingAs($this->user, 'api-guard')
            ->json('GET', '/api/v1/twofaccounts/export?ids=' . $this->twofaccountA->id . ',' . $this->twofaccountB->id . '&otpauth=1')
            ->assertOk()
            ->assertJsonStructure(self::VALID_EXPORT_AS_URIS_STRUTURE)
            ->assertJsonFragment(['uri' => $this->twofaccountA->getURI()])
            ->assertJsonFragment(['uri' => $this->twofaccountB->getURI()]);
    }

    #[Test]
    public function test_export_returns_json_migration_resource_when_otpauth_param_is_off()
    {
        $this->twofaccountA = TwoFAccount::factory()->for($this->user)->create(self::JSON_FRAGMENTS_FOR_DEFAULT_TOTP);
        $this->twofaccountB = TwoFAccount::factory()->for($this->user)->create(self::JSON_FRAGMENTS_FOR_DEFAULT_HOTP);

        $this->actingAs($this->user, 'api-guard')
            ->json('GET', '/api/v1/twofaccounts/export?ids=' . $this->twofaccountA->id . ',' . $this->twofaccountB->id . '&otpauth=0')
            ->assertOk()
            ->assertJsonStructure(self::VALID_EXPORT_STRUTURE)
            ->assertJsonFragment(self::JSON_FRAGMENTS_FOR_DEFAULT_TOTP)
            ->assertJsonFragment(self::JSON_FRAGMENTS_FOR_DEFAULT_HOTP);
    }

    #[Test]
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

    #[Test]
    public function test_export_missing_twofaccount_returns_existing_ones_only()
    {
        $this->twofaccountA = TwoFAccount::factory()->for($this->user)->create(self::JSON_FRAGMENTS_FOR_DEFAULT_TOTP);

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('GET', '/api/v1/twofaccounts/export?ids=' . $this->twofaccountA->id . ',1000')
            ->assertJsonFragment(self::JSON_FRAGMENTS_FOR_DEFAULT_TOTP);
    }

    #[Test]
    public function test_export_twofaccount_of_another_user_is_forbidden()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('GET', '/api/v1/twofaccounts/export?ids=' . $this->twofaccountC->id)
            ->assertForbidden()
            ->assertJsonStructure([
                'message',
            ]);
    }

    #[Test]
    public function test_export_returns_nulled_icon_resource_when_icon_file_is_missing()
    {
        $this->twofaccountA = TwoFAccount::factory()->for($this->user)->create(array_merge(
            self::JSON_FRAGMENTS_FOR_DEFAULT_HOTP,
            [
                'icon' => 'icon_without_file_on_disk.png',
            ]
        ));

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('GET', '/api/v1/twofaccounts/export?ids=' . $this->twofaccountA->id)
            ->assertJsonFragment([
                'icon'      => 'icon_without_file_on_disk.png',
                'icon_file' => null,
                'icon_mime' => null,
            ]);
    }

    #[Test]
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

    #[Test]
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

    #[Test]
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

    #[Test]
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

    #[Test]
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

    #[Test]
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

    #[Test]
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

    #[Test]
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

    #[Test]
    public function test_get_otp_using_missing_twofaccount_id_returns_not_found()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('GET', '/api/v1/twofaccounts/1000/otp')
            ->assertNotFound();
    }

    #[Test]
    public function test_get_otp_by_posting_invalid_uri_returns_validation_error()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts/otp', [
                'uri' => OtpTestData::INVALID_OTPAUTH_URI,
            ])
            ->assertStatus(422);
    }

    #[Test]
    public function test_get_otp_by_posting_invalid_parameters_returns_validation_error()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts/otp', self::ARRAY_OF_INVALID_PARAMETERS)
            ->assertStatus(422);
    }

    #[Test]
    public function test_get_otp_of_another_user_twofaccount_is_forbidden()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('GET', '/api/v1/twofaccounts/' . $this->twofaccountC->id . '/otp')
            ->assertForbidden()
            ->assertJsonStructure([
                'message',
            ]);
    }

    #[Test]
    public function test_count_returns_right_number_of_twofaccounts()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('GET', '/api/v1/twofaccounts/count')
            ->assertStatus(200)
            ->assertExactJson([
                'count' => 2,
            ]);
    }

    #[Test]
    public function test_withdraw_returns_success()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('PATCH', '/api/v1/twofaccounts/withdraw?ids=1,2')
            ->assertOk()
            ->assertJsonStructure([
                'message',
            ]);
    }

    #[Test]
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

    #[Test]
    public function test_destroy_twofaccount_returns_success()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('DELETE', '/api/v1/twofaccounts/' . $this->twofaccountA->id)
            ->assertNoContent();
    }

    #[Test]
    public function test_destroy_missing_twofaccount_returns_not_found()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('DELETE', '/api/v1/twofaccounts/1000')
            ->assertNotFound();
    }

    #[Test]
    public function test_destroy_twofaccount_of_another_user_is_forbidden()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('DELETE', '/api/v1/twofaccounts/' . $this->twofaccountC->id)
            ->assertForbidden()
            ->assertJsonStructure([
                'message',
            ]);
    }

    #[Test]
    public function test_batch_destroy_twofaccount_returns_success()
    {
        TwoFAccount::factory()->count(3)->for($this->user)->create();

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('DELETE', '/api/v1/twofaccounts?ids=' . $this->twofaccountA->id . ',' . $this->twofaccountB->id)
            ->assertNoContent();
    }

    #[Test]
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

    #[Test]
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
