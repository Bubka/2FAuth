<?php

namespace Tests\Api\v1\Unit;

use App\User;
use Tests\FeatureTestCase;
use App\TwoFAccount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TwoFAccountControllerTest extends FeatureTestCase
{
    /**
     * @var \App\User
    */
    protected $user;

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
    private const ICON = 'test.png';
    private const TOTP_FULL_CUSTOM_URI = 'otpauth://totp/'.self::SERVICE.':'.self::ACCOUNT.'?secret='.self::SECRET.'&issuer='.self::SERVICE.'&digits='.self::DIGITS_CUSTOM.'&period='.self::PERIOD_CUSTOM.'&algorithm='.self::ALGORITHM_CUSTOM;
    private const HOTP_FULL_CUSTOM_URI = 'otpauth://hotp/'.self::SERVICE.':'.self::ACCOUNT.'?secret='.self::SECRET.'&issuer='.self::SERVICE.'&digits='.self::DIGITS_CUSTOM.'&counter='.self::COUNTER_CUSTOM.'&algorithm='.self::ALGORITHM_CUSTOM;
    private const TOTP_SHORT_URI = 'otpauth://totp/'.self::ACCOUNT.'?secret='.self::SECRET;
    private const HOTP_SHORT_URI = 'otpauth://hotp/'.self::ACCOUNT.'?secret='.self::SECRET;
    private const INVALID_OTPAUTH_URI = 'otpauth://Xotp/'.self::ACCOUNT.'?secret='.self::SECRET;


    /**
     * @test
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }


    /**
     * @test
     */
    public function test_index_returns_twofaccount_collection()
    {
        $twofaccount = factory(TwoFAccount::class, 3)->create();

        $response = $this->actingAs($this->user, 'api')
            ->json('GET', '/api/v1/twofaccounts')
            ->assertOk()
            ->assertJsonCount(3, $key = null)
            ->assertJsonStructure([
                '*' => [
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
                ]
            ]
        );
    }


    /**
     * @test
     */
    public function test_index_returns_twofaccount_collection_with_secret()
    {
        $twofaccount = factory(TwoFAccount::class, 3)->create();

        $response = $this->actingAs($this->user, 'api')
            ->json('GET', '/api/v1/twofaccounts?withSecret=1')
            ->assertOk()
            ->assertJsonCount(3, $key = null)
            ->assertJsonStructure([
                '*' => [
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
                ]
            ]
        );
    }


    /**
     * @test
     */
    public function test_show_twofaccount_returns_twofaccount_resource()
    {
        $twofaccount = factory(TwoFAccount::class)->create();

        $response = $this->actingAs($this->user, 'api')
            ->json('GET', '/api/v1/twofaccounts/' . $twofaccount->id)
            ->assertOk()
            ->assertJsonStructure([
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
            ]);
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
            ->assertJsonStructure([
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
            ]);
    }


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
            ->assertJsonStructure([
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
            ]);
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
            [[
                'service'   => self::SERVICE,
                'account'   => self::ACCOUNT,
                'icon'      => self::ICON,
                'otp_type'  => 'totp',
                'secret'    => self::SECRET,
                'digits'    => self::DIGITS_CUSTOM,
                'algorithm' => self::ALGORITHM_CUSTOM,
                'period'    => self::PERIOD_CUSTOM,
                'counter'   => null,
            ]],
            [[
                'account'   => self::ACCOUNT,
                'otp_type'  => 'totp',
                'secret'    => self::SECRET,
            ]],
            [[
                'uri' => self::HOTP_FULL_CUSTOM_URI,
            ]],
            [[
                'uri' => self::HOTP_SHORT_URI,
            ]],
            [[
                'service'   => self::SERVICE,
                'account'   => self::ACCOUNT,
                'icon'      => self::ICON,
                'otp_type'  => 'hotp',
                'secret'    => self::SECRET,
                'digits'    => self::DIGITS_CUSTOM,
                'algorithm' => self::ALGORITHM_CUSTOM,
                'period'    => null,
                'counter'   => self::COUNTER_CUSTOM,
            ]],
            [[
                'account'   => self::ACCOUNT,
                'otp_type'  => 'hotp',
                'secret'    => self::SECRET,
            ]],
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
            ->assertJsonFragment([
                'service'   => self::SERVICE,
                'account'   => self::ACCOUNT,
                'otp_type'  => 'totp',
                'secret'    => self::SECRET,
                'digits'    => self::DIGITS_CUSTOM,
                'algorithm' => self::ALGORITHM_CUSTOM,
                'period'    => self::PERIOD_CUSTOM,
                'counter'   => null,
            ]);
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
            ->assertJsonFragment([
                'service'   => null,
                'account'   => self::ACCOUNT,
                'otp_type'  => 'totp',
                'secret'    => self::SECRET,
                'digits'    => self::DIGITS_DEFAULT,
                'algorithm' => self::ALGORITHM_DEFAULT,
                'period'    => self::PERIOD_DEFAULT,
                'counter'   => null,
            ]);
    }


    /**
     * @test
     */
    public function test_store_totp_using_fully_custom_parameters_returns_consistent_resource()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/v1/twofaccounts', [
                'service'   => self::SERVICE,
                'account'   => self::ACCOUNT,
                'otp_type'  => 'totp',
                'secret'    => self::SECRET,
                'digits'    => self::DIGITS_CUSTOM,
                'algorithm' => self::ALGORITHM_CUSTOM,
                'period'    => self::PERIOD_CUSTOM,
                'counter'   => null,
            ])
            ->assertJsonFragment([
                'service'   => self::SERVICE,
                'account'   => self::ACCOUNT,
                'otp_type'  => 'totp',
                'secret'    => self::SECRET,
                'digits'    => self::DIGITS_CUSTOM,
                'algorithm' => self::ALGORITHM_CUSTOM,
                'period'    => self::PERIOD_CUSTOM,
                'counter'   => null,
            ]);
    }


    /**
     * @test
     */
    public function test_store_totp_using_minimum_parameters_returns_consistent_resource()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/v1/twofaccounts', [
                'account'   => self::ACCOUNT,
                'otp_type'  => 'totp',
                'secret'    => self::SECRET,
            ])
            ->assertJsonFragment([
                'service'   => null,
                'account'   => self::ACCOUNT,
                'otp_type'  => 'totp',
                'secret'    => self::SECRET,
                'digits'    => self::DIGITS_DEFAULT,
                'algorithm' => self::ALGORITHM_DEFAULT,
                'period'    => self::PERIOD_DEFAULT,
                'counter'   => null,
            ]);
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
            ->assertJsonFragment([
                'service'   => self::SERVICE,
                'account'   => self::ACCOUNT,
                'otp_type'  => 'hotp',
                'secret'    => self::SECRET,
                'digits'    => self::DIGITS_CUSTOM,
                'algorithm' => self::ALGORITHM_CUSTOM,
                'period'    => null,
                'counter'   => self::COUNTER_CUSTOM,
            ]);
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
            ->assertJsonFragment([
                'service' => null,
                'account'   => self::ACCOUNT,
                'otp_type'  => 'hotp',
                'secret'    => self::SECRET,
                'digits'    => self::DIGITS_DEFAULT,
                'algorithm' => self::ALGORITHM_DEFAULT,
                'period'    => null,
                'counter'   => self::COUNTER_DEFAULT,
            ]);
    }


    /**
     * @test
     */
    public function test_store_hotp_using_fully_custom_parameters_returns_consistent_resource()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/v1/twofaccounts', [
                'service'   => self::SERVICE,
                'account'   => self::ACCOUNT,
                'otp_type'  => 'hotp',
                'secret'    => self::SECRET,
                'digits'    => self::DIGITS_CUSTOM,
                'algorithm' => self::ALGORITHM_CUSTOM,
                'period'    => null,
                'counter'   => self::COUNTER_CUSTOM,
            ])
            ->assertJsonFragment([
                'service'   => self::SERVICE,
                'account'   => self::ACCOUNT,
                'otp_type'  => 'hotp',
                'secret'    => self::SECRET,
                'digits'    => self::DIGITS_CUSTOM,
                'algorithm' => self::ALGORITHM_CUSTOM,
                'period'    => null,
                'counter'   => self::COUNTER_CUSTOM,
            ]);
    }


    /**
     * @test
     */
    public function test_store_hotp_using_minimum_parameters_returns_consistent_resource()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/v1/twofaccounts', [
                'account'   => self::ACCOUNT,
                'otp_type'  => 'hotp',
                'secret'    => self::SECRET,
            ])
            ->assertJsonFragment([
                'service'   => null,
                'account'   => self::ACCOUNT,
                'otp_type'  => 'hotp',
                'secret'    => self::SECRET,
                'digits'    => self::DIGITS_DEFAULT,
                'algorithm' => self::ALGORITHM_DEFAULT,
                'period'    => null,
                'counter'   => self::COUNTER_DEFAULT,
            ]);
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
    public function test_update_totp_returns_success_with_updated_resource()
    {
        $twofaccount = factory(TwoFAccount::class)->create();

        $response = $this->actingAs($this->user, 'api')
            ->json('PUT', '/api/v1/twofaccounts/' . $twofaccount->id, [
                    'service'   => self::SERVICE,
                    'account'   => self::ACCOUNT,
                    'icon'      => self::ICON,
                    'otp_type'  => 'totp',
                    'secret'    => self::SECRET,
                    'digits'    => self::DIGITS_CUSTOM,
                    'algorithm' => self::ALGORITHM_CUSTOM,
                    'period'    => self::PERIOD_CUSTOM,
                    'counter'   => null,
                ])
            ->assertOk()
            ->assertJsonFragment([
                'service'   => self::SERVICE,
                'account'   => self::ACCOUNT,
                'icon'      => self::ICON,
                'otp_type'  => 'totp',
                'secret'    => self::SECRET,
                'digits'    => self::DIGITS_CUSTOM,
                'algorithm' => self::ALGORITHM_CUSTOM,
                'period'    => self::PERIOD_CUSTOM,
                'counter'   => null,
            ]);
    }


    /**
     * @test
     */
    public function test_update_hotp_returns_success_with_updated_resource()
    {
        $twofaccount = factory(TwoFAccount::class)->create();

        $response = $this->actingAs($this->user, 'api')
            ->json('PUT', '/api/v1/twofaccounts/' . $twofaccount->id, [
                    'service'   => self::SERVICE,
                    'account'   => self::ACCOUNT,
                    'icon'      => self::ICON,
                    'otp_type'  => 'hotp',
                    'secret'    => self::SECRET,
                    'digits'    => self::DIGITS_CUSTOM,
                    'algorithm' => self::ALGORITHM_CUSTOM,
                    'period'    => null,
                    'counter'   => self::COUNTER_CUSTOM,
                ])
            ->assertOk()
            ->assertJsonFragment([
                'service'   => self::SERVICE,
                'account'   => self::ACCOUNT,
                'icon'      => self::ICON,
                'otp_type'  => 'hotp',
                'secret'    => self::SECRET,
                'digits'    => self::DIGITS_CUSTOM,
                'algorithm' => self::ALGORITHM_CUSTOM,
                'period'    => null,
                'counter'   => self::COUNTER_CUSTOM,
            ]);
    }


    /**
     * @test
     */
    public function test_update_missing_twofaccount_returns_not_found()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('PUT', '/api/v1/twofaccounts/1000', [
                'service'   => self::SERVICE,
                'account'   => self::ACCOUNT,
                'icon'      => self::ICON,
                'otp_type'  => 'totp',
                'secret'    => self::SECRET,
                'digits'    => self::DIGITS_CUSTOM,
                'algorithm' => self::ALGORITHM_CUSTOM,
                'period'    => self::PERIOD_CUSTOM,
                'counter'   => null,
            ])
            ->assertNotFound();
    }


    /**
     * @test
     */
    public function test_update_twofaccount_with_invalid_data_returns_validation_error()
    {
        $twofaccount = factory(TwoFAccount::class)->create();

        $response = $this->actingAs($this->user, 'api')
            ->json('PUT', '/api/v1/twofaccounts/' . $twofaccount->id, [
                'service'   => self::SERVICE,
                'account'   => null,
                'icon'      => self::ICON,
                'otp_type'  => 'totp',
                'secret'    => self::SECRET,
                'digits'    => self::DIGITS_CUSTOM,
                'algorithm' => self::ALGORITHM_CUSTOM,
                'period'    => self::PERIOD_CUSTOM,
                'counter'   => null,
            ])
            ->assertStatus(422);
    }




















    


    /**
     * test Hotp TwoFAccount display via API
     *
     * @test
     */
    public function testHotpTwofaccountDisplayWithCounterIncrement()
    {
        $twofaccount = factory(TwoFAccount::class)->create([
            'service' => 'testTOTP',
            'account' => 'test@test.com',
            'uri' => 'otpauth://hotp/test@test.com?secret=A4GRFHVVRBGY7UIW&issuer=test&counter=1',
        ]);

        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/v1/twofaccounts/otp', ['id' => $twofaccount->id])
            ->assertStatus(200);

        $response = $this->actingAs($this->user, 'api')
            ->json('GET', '/api/v1/twofaccounts/' . $twofaccount->id)
            ->assertStatus(200)
            ->assertJsonFragment([
                'service' => 'testTOTP',
                'account' => 'test@test.com',
                'group_id' => null,
                'isConsistent' => true,
                'otpType' => 'hotp',
                'digits' => 6,
                'hotpCounter' => 2,
                'imageLink' => null,
            ])
            ->assertJsonMissing([
                'uri' => 'otpauth://hotp/test@test.com?secret=A4GRFHVVRBGY7UIW&issuer=test',
                'secret' => 'A4GRFHVVRBGY7UIW',
                'algorithm' => 'sha1',
            ]);
    }


    /**
     * test TwoFAccount preview via API
     *
     * @test
     */
    public function testTwofaccountPreview()
    {
        Storage::put('test.png', 'emptied to prevent missing resource replaced by null by the model getter');

        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/v1/twofaccounts/preview', [
                'uri' => 'otpauth://totp/service:account?secret=A4GRFHVVRBGY7UIW&issuer=service&image=https%3A%2F%2Fen.opensuse.org%2Fimages%2F4%2F44%2FButton-filled-colour.png',
            ])
            ->assertStatus(200)
            ->assertJsonFragment([
                'service' => 'service',
                'account' => 'account',
                'uri' => 'otpauth://totp/service:account?secret=A4GRFHVVRBGY7UIW&issuer=service&image=https%3A%2F%2Fen.opensuse.org%2Fimages%2F4%2F44%2FButton-filled-colour.png',
                'secret' => 'A4GRFHVVRBGY7UIW',
                'algorithm' => 'sha1',
                'otpType' => 'totp',
                'digits' => 6,
                'totpPeriod' => 30,
                'hotpCounter' => null,
                'imageLink' => 'https://en.opensuse.org/images/4/44/Button-filled-colour.png',
            ])
            ->assertJsonStructure([
                'icon'
            ]);
    }


    /**
     * test TwoFAccount preview with unreachable image parameter via API
     *
     * @test
     */
    public function testTwofaccountPreviewWithUnreachableImage()
    {

        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/v1/twofaccounts/preview', [
                'uri' => 'otpauth://totp/service:account?secret=A4GRFHVVRBGY7UIW&issuer=service&image=https%3A%2F%2Fen.opensuse.org%2Fimage.png',
            ])
            ->assertStatus(200)
            ->assertJsonMissing([
                'icon'
            ]);
    }


    /**
     * test show account when uri field remains encrypted via API
     *
     * @test
     */
    public function testShowAccountWithUndecipheredUri()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/v1/twofaccounts', [
                    'service' => 'testCreation',
                    'account' => 'test@example.org',
                    'uri' => 'otpauth://totp/test@test.com?secret=A4GRFHZVRBGY7UIW&issuer=test',
                    'icon' => 'test.png',
                ])
            ->assertStatus(201);

        DB::table('twofaccounts')
            ->where('id', 1)
            ->update([
                'uri' => '**encrypted**',
            ]);

        $response = $this->actingAs($this->user, 'api')
            ->json('GET', '/api/v1/twofaccounts/1')
            ->assertStatus(200)
            ->assertJsonFragment([
                'isConsistent' => false,
            ]);
    }


    /**
     * test totp  token generation for a given existing account via API
     *
     * @test
     */
    public function testTotpTokenGenerationWithAccountId()
    {
        $twofaccount = factory(TwoFAccount::class)->create([
            'service' => 'testService',
            'account' => 'testAccount',
            'uri' => 'otpauth://totp/testService:testAccount?secret=A4GRFHVVRBGY7UIW&issuer=testService'
        ]);

        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/v1/twofaccounts/otp', ['id' => $twofaccount->id])
            ->assertStatus(200)
            ->assertJsonStructure([
                'token',
                'totpTimestamp',
                'totpPeriod',
            ]);
    }


    /**
     * test hotp token generation for a given existing account via API
     *
     * @test
     */
    public function testHotpTokenGenerationWithAccountId()
    {
        $twofaccount = factory(TwoFAccount::class)->create([
            'service' => 'testService',
            'account' => 'testAccount',
            'uri' => 'otpauth://hotp/testService:testAccount?secret=A4GRFHVVRBGY7UIW&issuer=testService&counter=1'
        ]);

        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/v1/twofaccounts/otp', ['id' => $twofaccount->id])
            ->assertStatus(200)
            ->assertJsonStructure([
                'token',
            ]);
    }


    /**
     * test token generation by providing an URI via API
     *
     * @test
     */
    public function testTokenGenerationWithUri()
    {
        $uri = 'otpauth://totp/service:account?secret=A4GRFHVVRBGY7UIW&issuer=service';

        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/v1/twofaccounts/otp', ['otp' => ['uri' => $uri]])
            ->assertStatus(200)
            ->assertJsonStructure([
                'token',
                'totpTimestamp',
                'totpPeriod',
            ]);
    }


    /**
     * test totp token generation by providing an array of otp attributes without URI via API
     *
     * @test
     */
    public function testTotpTokenGenerationWithAttributesArray()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/v1/twofaccounts/otp', ['otp' => [
                'service' => 'service',
                'account' => 'account',
                'otpType' => 'totp',
                'secret' => 'A4GRFHVVRBGY7UIW',
                'secretIsBase32Encoded' => 1,
                'digits' => 6,
                'totpPeriod' => 30,
                'algorithm' => 'sha1',
                'uri' => ''
            ]])
            ->assertStatus(200)
            ->assertJsonStructure([
                'token',
                'totpTimestamp',
                'totpPeriod',
            ]);
    }


    /**
     * test hotp token generation by providing an array of otp attributes without URI via API
     *
     * @test
     */
    public function testHotpTokenGenerationWithAttributesArray()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/v1/twofaccounts/otp', ['otp' => [
                'service' => 'service',
                'account' => 'account',
                'otpType' => 'hotp',
                'secret' => 'A4GRFHVVRBGY7UIW',
                'secretIsBase32Encoded' => 1,
                'digits' => 6,
                'hotpCounter' => 1,
                'algorithm' => 'sha1',
                'uri' => ''
            ]])
            ->assertStatus(200)
            ->assertJsonStructure([
                'token',
                'hotpCounter',
            ]);
    }


    /**
     * test token generation by providing an array of otp attributes with a bad otp type via API
     *
     * @test
     */
    public function testTokenGenerationWithBadOtptypeAttribute()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/v1/twofaccounts/otp', ['otp' => [
                'service' => 'service',
                'account' => 'account',
                'otpType' => 'otp',
                'secret' => 'A4GRFHVVRBGY7UIW',
                'secretIsBase32Encoded' => 1,
                'digits' => 6,
                'totpPeriod' => 30,
                'algorithm' => 'sha1',
                'uri' => ''
            ]])
            ->assertStatus(422)
            ->assertJsonStructure([
                'errors' => [
                    'otpType'
                ]
            ]);
    }


    /**
     * test token generation by providing an array of otp attributes without secret via API
     *
     * @test
     */
    public function testTokenGenerationWithMissingSecretAttribute()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/v1/twofaccounts/otp', ['otp' => [
                'service' => 'service',
                'account' => 'account',
                'otpType' => 'totp',
                'secret' => 'A4GRFHVVRBGY7UIW',
                'secretIsBase32Encoded' => 1,
                'digits' => 'x',
                'totpPeriod' => 'y',
                'algorithm' => 'sha1',
                'uri' => ''
            ]])
            ->assertStatus(422)
            ->assertJsonStructure([
                'errors' => [
                    'qrcode'
                ]
            ]);
    }


    /**
     * test token generation by providing an array of bad attributes  via API
     *
     * @test
     */
    public function testTokenGenerationWithBadAttribute()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/v1/twofaccounts/otp', ['otp' => [
                'service' => 'service',
                'account' => 'account',
                'otpType' => 'totp',
                'secret' => '',
                'secretIsBase32Encoded' => 1,
                'digits' => 6,
                'totpPeriod' => 30,
                'algorithm' => 'sha1',
                'uri' => ''
            ]])
            ->assertStatus(422)
            ->assertJsonStructure([
                'errors' => [
                    'secret'
                ]
            ]);
    }


    /**
     * test TwoFAccount index fetching via API
     *
     * @test
     */
    public function testTwofaccountCount()
    {
        $twofaccount = factory(TwoFAccount::class, 3)->create();

        $response = $this->actingAs($this->user, 'api')
            ->json('GET', '/api/v1/twofaccounts/count')
            ->assertStatus(200)
            ->assertJson([
                'count' => 3
            ]
        );
    }


    /**
     * test TwoFAccount deletion via API
     *
     * @test
     */
    public function testTwofaccountDeletion()
    {
        $twofaccount = factory(TwoFAccount::class)->create();

        $response = $this->actingAs($this->user, 'api')
            ->json('DELETE', '/api/v1/twofaccounts/' . $twofaccount->id)
            ->assertStatus(204);
    }


    /**
     * test TwoFAccounts batch deletion via API
     *
     * @test
     */
    public function testTwofaccountBatchDestroy()
    {
        factory(TwoFAccount::class, 3)->create();

        $ids = \Illuminate\Support\Facades\DB::table('twofaccounts')->value('id');

        $response = $this->actingAs($this->user, 'api')
            ->json('DELETE', '/api/v1/twofaccounts/batch', [
                'data' => $ids])
            ->assertStatus(204);
    }


    /**
     * test TwoFAccounts reorder
     *
     * @test
     */
    public function testTwofaccountReorder()
    {
        factory(TwoFAccount::class, 3)->create();

        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/v1/twofaccounts/reorder', [
                'orderedIds' => [3,2,1]])
            ->assertStatus(200);
    }

}
