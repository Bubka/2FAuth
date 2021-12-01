<?php

namespace Tests\Api\v1\Controllers;

use App\User;
use App\Group;
use Tests\FeatureTestCase;
use App\TwoFAccount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


/**
 * @covers \App\Api\v1\Controllers\TwoFAccountController
 * @covers \App\Api\v1\Resources\TwoFAccountReadResource
 * @covers \App\Api\v1\Resources\TwoFAccountStoreResource
 */
class TwoFAccountControllerTest extends FeatureTestCase
{
    /**
     * @var \App\User
    */
    protected $user;

    /**
     * @var \App\Group
    */
    protected $group;

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
    private const JSON_FRAGMENTS_FOR_CUSTOM_TOTP = [
        'service'   => self::SERVICE,
        'account'   => self::ACCOUNT,
        'otp_type'  => 'totp',
        'secret'    => self::SECRET,
        'digits'    => self::DIGITS_CUSTOM,
        'algorithm' => self::ALGORITHM_CUSTOM,
        'period'    => self::PERIOD_CUSTOM,
        'counter'   => null,
    ];
    private const JSON_FRAGMENTS_FOR_DEFAULT_TOTP = [
        'service'   => null,
        'account'   => self::ACCOUNT,
        'otp_type'  => 'totp',
        'secret'    => self::SECRET,
        'digits'    => self::DIGITS_DEFAULT,
        'algorithm' => self::ALGORITHM_DEFAULT,
        'period'    => self::PERIOD_DEFAULT,
        'counter'   => null,
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
    private const JSON_FRAGMENTS_FOR_CUSTOM_HOTP = [
        'service'   => self::SERVICE,
        'account'   => self::ACCOUNT,
        'otp_type'  => 'hotp',
        'secret'    => self::SECRET,
        'digits'    => self::DIGITS_CUSTOM,
        'algorithm' => self::ALGORITHM_CUSTOM,
        'period'    => null,
        'counter'   => self::COUNTER_CUSTOM,
    ];
    private const JSON_FRAGMENTS_FOR_DEFAULT_HOTP = [
        'service' => null,
        'account'   => self::ACCOUNT,
        'otp_type'  => 'hotp',
        'secret'    => self::SECRET,
        'digits'    => self::DIGITS_DEFAULT,
        'algorithm' => self::ALGORITHM_DEFAULT,
        'period'    => null,
        'counter'   => self::COUNTER_DEFAULT,
    ];
    private const ARRAY_OF_INVALID_PARAMETERS = [
        'account'   => null,
        'otp_type'  => 'totp',
        'secret'    => self::SECRET,
    ];

    /**
     * @test
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->group = factory(Group::class)->create();
    }


    /**
     * @test
     */
    public function test_index_returns_twofaccount_collection()
    {
        factory(TwoFAccount::class, 3)->create();

        $response = $this->actingAs($this->user, 'api')
            ->json('GET', '/api/v1/twofaccounts')
            ->assertOk()
            ->assertJsonCount(3, $key = null)
            ->assertJsonStructure([
                '*' => self::VALID_RESOURCE_STRUCTURE_WITHOUT_SECRET
            ]);
    }


    /**
     * @test
     */
    public function test_index_returns_twofaccount_collection_with_secret()
    {
        factory(TwoFAccount::class, 3)->create();

        $response = $this->actingAs($this->user, 'api')
            ->json('GET', '/api/v1/twofaccounts?withSecret=1')
            ->assertOk()
            ->assertJsonCount(3, $key = null)
            ->assertJsonStructure([
                '*' => self::VALID_RESOURCE_STRUCTURE_WITH_SECRET
            ]);
    }


    /**
     * @test
     */
    public function test_show_twofaccount_returns_twofaccount_resource_with_secret()
    {
        $twofaccount = factory(TwoFAccount::class)->create();

        $response = $this->actingAs($this->user, 'api')
            ->json('GET', '/api/v1/twofaccounts/' . $twofaccount->id)
            ->assertOk()
            ->assertJsonStructure(self::VALID_RESOURCE_STRUCTURE_WITH_SECRET);
    }


    /**
     * @test
     */
    public function test_show_twofaccount_returns_twofaccount_resource_without_secret()
    {
        $twofaccount = factory(TwoFAccount::class)->create();

        $response = $this->actingAs($this->user, 'api')
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

    //     $twofaccount = factory(TwoFAccount::class)->create();

    //     DB::table('twofaccounts')
    //         ->where('id', $twofaccount->id)
    //         ->update([
    //             'secret' => '**encrypted**',
    //             'account' => '**encrypted**',
    //         ]);

    //     $response = $this->actingAs($this->user, 'api')
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
        $response = $this->actingAs($this->user, 'api')
            ->json('GET', '/api/v1/twofaccounts/1000')
            ->assertNotFound()
            ->assertJsonStructure([
                'message'
            ]);
    }


    /**
     * @dataProvider provideDataForTestStoreStructure
     * @test
     */
    public function test_store_returns_success_with_consistent_resource_structure(array $data)
    {
        Storage::put('test.png', 'emptied to prevent missing resource replaced by null by the model getter');

        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/v1/twofaccounts', $data)
            ->assertCreated()
            ->assertJsonStructure(self::VALID_RESOURCE_STRUCTURE_WITH_SECRET);
    }


    /**
     * Provide data for TwoFAccount store test
     */
    public function provideDataForTestStoreStructure() : array
    {
        return [
            [[
                'uri' => self::TOTP_FULL_CUSTOM_URI,
            ]],
            [[
                'uri' => self::TOTP_SHORT_URI,
            ]],
            [
                self::ARRAY_OF_FULL_VALID_PARAMETERS_FOR_CUSTOM_TOTP
            ],
            [
                self::ARRAY_OF_MINIMUM_VALID_PARAMETERS_FOR_TOTP
            ],
            [[
                'uri' => self::HOTP_FULL_CUSTOM_URI,
            ]],
            [[
                'uri' => self::HOTP_SHORT_URI,
            ]],
            [
                self::ARRAY_OF_FULL_VALID_PARAMETERS_FOR_CUSTOM_HOTP
            ],
            [
                self::ARRAY_OF_MINIMUM_VALID_PARAMETERS_FOR_HOTP
            ],
        ];
    }


    /**
     * @test
     */
    public function test_store_totp_using_fully_custom_uri_returns_consistent_resource()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/v1/twofaccounts', [
                'uri' => self::TOTP_FULL_CUSTOM_URI,
            ])
            ->assertJsonFragment(self::JSON_FRAGMENTS_FOR_CUSTOM_TOTP);
    }


    /**
     * @test
     */
    public function test_store_totp_using_short_uri_returns_resource_with_default_otp_parameter()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/v1/twofaccounts', [
                'uri' => self::TOTP_SHORT_URI,
            ])
            ->assertJsonFragment(self::JSON_FRAGMENTS_FOR_DEFAULT_TOTP);
    }


    /**
     * @test
     */
    public function test_store_totp_using_fully_custom_parameters_returns_consistent_resource()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/v1/twofaccounts', self::ARRAY_OF_FULL_VALID_PARAMETERS_FOR_CUSTOM_TOTP)
            ->assertJsonFragment(self::JSON_FRAGMENTS_FOR_CUSTOM_TOTP);
    }


    /**
     * @test
     */
    public function test_store_totp_using_minimum_parameters_returns_consistent_resource()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/v1/twofaccounts', self::ARRAY_OF_MINIMUM_VALID_PARAMETERS_FOR_TOTP)
            ->assertJsonFragment(self::JSON_FRAGMENTS_FOR_DEFAULT_TOTP);
    }


    /**
     * @test
     */
    public function test_store_hotp_using_fully_custom_uri_returns_consistent_resource()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/v1/twofaccounts', [
                'uri' => self::HOTP_FULL_CUSTOM_URI,
            ])
            ->assertJsonFragment(self::JSON_FRAGMENTS_FOR_CUSTOM_HOTP);
    }


    /**
     * @test
     */
    public function test_store_hotp_using_short_uri_returns_resource_with_default_otp_parameter()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/v1/twofaccounts', [
                'uri' => self::HOTP_SHORT_URI,
            ])
            ->assertJsonFragment(self::JSON_FRAGMENTS_FOR_DEFAULT_HOTP);
    }


    /**
     * @test
     */
    public function test_store_hotp_using_fully_custom_parameters_returns_consistent_resource()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/v1/twofaccounts', self::ARRAY_OF_FULL_VALID_PARAMETERS_FOR_CUSTOM_HOTP)
            ->assertJsonFragment(self::JSON_FRAGMENTS_FOR_CUSTOM_HOTP);
    }


    /**
     * @test
     */
    public function test_store_hotp_using_minimum_parameters_returns_consistent_resource()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/v1/twofaccounts', self::ARRAY_OF_MINIMUM_VALID_PARAMETERS_FOR_HOTP)
            ->assertJsonFragment(self::JSON_FRAGMENTS_FOR_DEFAULT_HOTP);
    }


    /**
     * @test
     */
    public function test_store_with_invalid_uri_returns_validation_error()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/v1/twofaccounts', [
                'uri' => self::INVALID_OTPAUTH_URI,
            ])
            ->assertStatus(422);
    }


    /**
     * @test
     */
    public function test_store_assigns_created_account_when_default_group_is_a_specific_one()
    {
        // Set the default group to a specific one
        $settingService = resolve('App\Services\SettingService');
        $settingService->set('defaultGroup', $this->group->id);

        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/v1/twofaccounts', [
                'uri' => self::TOTP_SHORT_URI,
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
        $settingService = resolve('App\Services\SettingService');

        // Set the default group to be the active one
        $settingService->set('defaultGroup', -1);
        // Set the active group
        $settingService->set('activeGroup', 1);

        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/v1/twofaccounts', [
                'uri' => self::TOTP_SHORT_URI,
            ])
            ->assertJsonFragment([
                'group_id' => 1
            ]);
    }


    /**
     * @test
     */
    public function test_store_assigns_created_account_when_default_group_is_no_group()
    {
        $settingService = resolve('App\Services\SettingService');

        // Set the default group to No group
        $settingService->set('defaultGroup', 0);

        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/v1/twofaccounts', [
                'uri' => self::TOTP_SHORT_URI,
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
        $settingService = resolve('App\Services\SettingService');

        // Set the default group to a non-existing one
        $settingService->set('defaultGroup', 1000);

        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/v1/twofaccounts', [
                'uri' => self::TOTP_SHORT_URI,
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
        $twofaccount = factory(TwoFAccount::class)->create();

        $response = $this->actingAs($this->user, 'api')
            ->json('PUT', '/api/v1/twofaccounts/' . $twofaccount->id, self::ARRAY_OF_FULL_VALID_PARAMETERS_FOR_CUSTOM_TOTP)
            ->assertOk()
            ->assertJsonFragment(self::JSON_FRAGMENTS_FOR_CUSTOM_TOTP);
    }


    /**
     * @test
     */
    public function test_update_hotp_returns_success_with_updated_resource()
    {
        $twofaccount = factory(TwoFAccount::class)->create();

        $response = $this->actingAs($this->user, 'api')
            ->json('PUT', '/api/v1/twofaccounts/' . $twofaccount->id, self::ARRAY_OF_FULL_VALID_PARAMETERS_FOR_CUSTOM_HOTP)
            ->assertOk()
            ->assertJsonFragment(self::JSON_FRAGMENTS_FOR_CUSTOM_HOTP);
    }


    /**
     * @test
     */
    public function test_update_missing_twofaccount_returns_not_found()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('PUT', '/api/v1/twofaccounts/1000', self::ARRAY_OF_FULL_VALID_PARAMETERS_FOR_CUSTOM_TOTP)
            ->assertNotFound();
    }


    /**
     * @test
     */
    public function test_update_twofaccount_with_invalid_data_returns_validation_error()
    {
        $twofaccount = factory(TwoFAccount::class)->create();

        $response = $this->actingAs($this->user, 'api')
            ->json('PUT', '/api/v1/twofaccounts/' . $twofaccount->id, self::ARRAY_OF_INVALID_PARAMETERS)
            ->assertStatus(422);
    }


    /**
     * @test
     */
    public function test_reorder_returns_success()
    {
        factory(TwoFAccount::class, 3)->create();

        $response = $this->actingAs($this->user, 'api')
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
        factory(TwoFAccount::class, 3)->create();

        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/v1/twofaccounts/reorder', [
                'orderedIds' => '3,2,1'])
            ->assertStatus(422);
    }


    /**
     * @test
     */
    public function test_preview_returns_success_with_resource()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/v1/twofaccounts/preview', [
                'uri' => self::TOTP_FULL_CUSTOM_URI,
            ])
            ->assertOk()
            ->assertJsonFragment(self::JSON_FRAGMENTS_FOR_CUSTOM_TOTP);
    }


    /**
     * @test
     */
    public function test_preview_with_invalid_data_returns_validation_error()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/v1/twofaccounts/preview', [
                'uri' => self::INVALID_OTPAUTH_URI,
            ])
            ->assertStatus(422);
    }


    /**
     * @test
     */
    public function test_preview_with_unreachable_image_returns_success()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/v1/twofaccounts/preview', [
                'uri' => self::TOTP_URI_WITH_UNREACHABLE_IMAGE,
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
        $twofaccount = factory(TwoFAccount::class)->create([
            'otp_type' => 'totp',
            'account' => self::ACCOUNT,
            'service' => self::SERVICE,
            'secret' => self::SECRET,
            'algorithm' => self::ALGORITHM_DEFAULT,
            'digits' => self::DIGITS_DEFAULT,
            'period' => self::PERIOD_DEFAULT,
            'legacy_uri' => self::TOTP_SHORT_URI,
            'icon' => '',
        ]);

        $response = $this->actingAs($this->user, 'api')
            ->json('GET', '/api/v1/twofaccounts/' . $twofaccount->id . '/otp')
            ->assertOk()
            ->assertJsonStructure(self::VALID_OTP_RESOURCE_STRUCTURE_FOR_TOTP)
            ->assertJsonFragment([
                'otp_type' => 'totp',
                'period' => self::PERIOD_DEFAULT,
            ]);
    }


    /**
     * @test
     */
    public function test_get_otp_by_posting_totp_uri_returns_consistent_resource()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/v1/twofaccounts/otp', [
                'uri' => self::TOTP_FULL_CUSTOM_URI,
            ])
            ->assertOk()
            ->assertJsonStructure(self::VALID_OTP_RESOURCE_STRUCTURE_FOR_TOTP)
            ->assertJsonFragment([
                'otp_type' => 'totp',
                'period' => self::PERIOD_CUSTOM,
            ]);
    }


    /**
     * @test
     */
    public function test_get_otp_by_posting_totp_parameters_returns_consistent_resource()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/v1/twofaccounts/otp', self::ARRAY_OF_FULL_VALID_PARAMETERS_FOR_CUSTOM_TOTP)
            ->assertOk()
            ->assertJsonStructure(self::VALID_OTP_RESOURCE_STRUCTURE_FOR_TOTP)
            ->assertJsonFragment([
                'otp_type' => 'totp',
                'period' => self::PERIOD_CUSTOM,
            ]);
    }


    /**
     * @test
     */
    public function test_get_otp_using_hotp_twofaccount_id_returns_consistent_resource()
    {
        $twofaccount = factory(TwoFAccount::class)->create([
            'otp_type' => 'hotp',
            'account' => self::ACCOUNT,
            'service' => self::SERVICE,
            'secret' => self::SECRET,
            'algorithm' => self::ALGORITHM_DEFAULT,
            'digits' => self::DIGITS_DEFAULT,
            'period' => null,
            'legacy_uri' => self::HOTP_SHORT_URI,
            'icon' => '',
        ]);

        $response = $this->actingAs($this->user, 'api')
            ->json('GET', '/api/v1/twofaccounts/' . $twofaccount->id . '/otp')
            ->assertOk()
            ->assertJsonStructure(self::VALID_OTP_RESOURCE_STRUCTURE_FOR_HOTP)
            ->assertJsonFragment([
                'otp_type' => 'hotp',
                'counter' => self::COUNTER_DEFAULT + 1,
            ]);
    }


    /**
     * @test
     */
    public function test_get_otp_by_posting_hotp_uri_returns_consistent_resource()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/v1/twofaccounts/otp', [
                'uri' => self::HOTP_FULL_CUSTOM_URI,
            ])
            ->assertOk()
            ->assertJsonStructure(self::VALID_OTP_RESOURCE_STRUCTURE_FOR_HOTP)
            ->assertJsonFragment([
                'otp_type' => 'hotp',
                'counter' => self::COUNTER_CUSTOM + 1,
            ]);
    }


    /**
     * @test
     */
    public function test_get_otp_by_posting_hotp_parameters_returns_consistent_resource()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/v1/twofaccounts/otp', self::ARRAY_OF_FULL_VALID_PARAMETERS_FOR_CUSTOM_HOTP)
            ->assertOk()
            ->assertJsonStructure(self::VALID_OTP_RESOURCE_STRUCTURE_FOR_HOTP)
            ->assertJsonFragment([
                'otp_type' => 'hotp',
                'counter' => self::COUNTER_CUSTOM + 1,
            ]);
    }


    /**
     * @test
     */
    public function test_get_otp_by_posting_multiple_inputs_returns_bad_request()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/v1/twofaccounts/otp', [
                'uri' => self::HOTP_FULL_CUSTOM_URI,
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
        $settingService = resolve('App\Services\SettingService');
        $settingService->set('useEncryption', true);

        $twofaccount = factory(TwoFAccount::class)->create();

        DB::table('twofaccounts')
            ->where('id', $twofaccount->id)
            ->update([
                'secret' => '**encrypted**',
            ]);

        $response = $this->actingAs($this->user, 'api')
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
        $response = $this->actingAs($this->user, 'api')
            ->json('GET', '/api/v1/twofaccounts/1000/otp')
            ->assertNotFound();
    }


    /**
     * @test
     */
    public function test_get_otp_by_posting_invalid_uri_returns_validation_error()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/v1/twofaccounts/otp', [
                'uri' => self::INVALID_OTPAUTH_URI,
            ])
            ->assertStatus(422);
    }


    /**
     * @test
     */
    public function test_get_otp_by_posting_invalid_parameters_returns_validation_error()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/v1/twofaccounts/otp', self::ARRAY_OF_INVALID_PARAMETERS)
            ->assertStatus(422);
    }


    /**
     * @test
     */
    public function test_count_returns_right_number_of_twofaccount()
    {
        factory(TwoFAccount::class, 3)->create();

        $response = $this->actingAs($this->user, 'api')
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
        factory(TwoFAccount::class, 3)->create();
        $ids = DB::table('twofaccounts')->pluck('id')->implode(',');

        $response = $this->actingAs($this->user, 'api')
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
        factory(TwoFAccount::class, 102)->create();
        $ids = DB::table('twofaccounts')->pluck('id')->implode(',');

        $response = $this->actingAs($this->user, 'api')
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
        $twofaccount = factory(TwoFAccount::class)->create();

        $response = $this->actingAs($this->user, 'api')
            ->json('DELETE', '/api/v1/twofaccounts/' . $twofaccount->id)
            ->assertNoContent();
    }


    /**
     * @test
     */
    public function test_destroy_missing_twofaccount_returns_not_found()
    {
        $twofaccount = factory(TwoFAccount::class)->create();

        $response = $this->actingAs($this->user, 'api')
            ->json('DELETE', '/api/v1/twofaccounts/1000')
            ->assertNotFound();
    }


    /**
     * @test
     */
    public function test_batch_destroy_twofaccount_returns_success()
    {
        factory(TwoFAccount::class, 3)->create();
        $ids = DB::table('twofaccounts')->pluck('id')->implode(',');

        $response = $this->actingAs($this->user, 'api')
            ->json('DELETE', '/api/v1/twofaccounts?ids=' . $ids)
            ->assertNoContent();
    }


    /**
     * @test
     */
    public function test_batch_destroy_too_many_twofaccounts_returns_bad_request()
    {
        factory(TwoFAccount::class, 102)->create();
        $ids = DB::table('twofaccounts')->pluck('id')->implode(',');

        $response = $this->actingAs($this->user, 'api')
            ->json('DELETE', '/api/v1/twofaccounts?ids=' . $ids)
            ->assertStatus(400)
            ->assertJsonStructure([
                'message',
                'reason',
            ]);
    }

}