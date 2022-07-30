<?php

namespace Tests\Feature\Services;

use App\Models\Group;
use App\Models\TwoFAccount;
use Tests\FeatureTestCase;
use Tests\Classes\OtpTestData;
use App\Facades\TwoFAccounts;


/**
 * @covers \App\Services\TwoFAccountService
 */
class TwoFAccountServiceTest extends FeatureTestCase
{
    /**
     * App\Models\TwoFAccount $customTotpTwofaccount
     */
    protected $customTotpTwofaccount;


    /**
     * App\Models\Group $group
     */
    protected $group;


    /**
     * App\Models\TwoFAccount $customTotpTwofaccount
     */
    protected $customHotpTwofaccount;


    /**
     * @test
     */
    public function setUp() : void
    {
        parent::setUp();

        $this->customTotpTwofaccount = new TwoFAccount;
        $this->customTotpTwofaccount->legacy_uri = OtpTestData::TOTP_FULL_CUSTOM_URI;
        $this->customTotpTwofaccount->service = OtpTestData::SERVICE;
        $this->customTotpTwofaccount->account = OtpTestData::ACCOUNT;
        $this->customTotpTwofaccount->icon = OtpTestData::ICON;
        $this->customTotpTwofaccount->otp_type = 'totp';
        $this->customTotpTwofaccount->secret = OtpTestData::SECRET;
        $this->customTotpTwofaccount->digits = OtpTestData::DIGITS_CUSTOM;
        $this->customTotpTwofaccount->algorithm = OtpTestData::ALGORITHM_CUSTOM;
        $this->customTotpTwofaccount->period = OtpTestData::PERIOD_CUSTOM;
        $this->customTotpTwofaccount->counter = null;
        $this->customTotpTwofaccount->save();

        $this->customHotpTwofaccount = new TwoFAccount;
        $this->customHotpTwofaccount->legacy_uri = OtpTestData::HOTP_FULL_CUSTOM_URI;
        $this->customHotpTwofaccount->service = OtpTestData::SERVICE;
        $this->customHotpTwofaccount->account = OtpTestData::ACCOUNT;
        $this->customHotpTwofaccount->icon = OtpTestData::ICON;
        $this->customHotpTwofaccount->otp_type = 'hotp';
        $this->customHotpTwofaccount->secret = OtpTestData::SECRET;
        $this->customHotpTwofaccount->digits = OtpTestData::DIGITS_CUSTOM;
        $this->customHotpTwofaccount->algorithm = OtpTestData::ALGORITHM_CUSTOM;
        $this->customHotpTwofaccount->period = null;
        $this->customHotpTwofaccount->counter = OtpTestData::COUNTER_CUSTOM;
        $this->customHotpTwofaccount->save();


        $this->group = new Group;
        $this->group->name = 'MyGroup';
        $this->group->save();
    }


    /**
     * @test
     */
    public function test_withdraw_comma_separated_ids_deletes_relation()
    {
        $twofaccounts = collect([$this->customHotpTwofaccount, $this->customTotpTwofaccount]);
        $this->group->twofaccounts()->saveMany($twofaccounts);
        
        TwoFAccounts::withdraw($this->customHotpTwofaccount->id.','.$this->customTotpTwofaccount->id);

        $this->assertDatabaseHas('twofaccounts', [
            'id'      => $this->customTotpTwofaccount->id,
            'group_id'      => null,
        ]);

        $this->assertDatabaseHas('twofaccounts', [
            'id'      => $this->customHotpTwofaccount->id,
            'group_id'      => null,
        ]);
    }


    /**
     * @test
     */
    public function test_withdraw_array_of_model_ids_deletes_relation()
    {
        $twofaccounts = collect([$this->customHotpTwofaccount, $this->customTotpTwofaccount]);
        $this->group->twofaccounts()->saveMany($twofaccounts);
        
        TwoFAccounts::withdraw([$this->customHotpTwofaccount->id, $this->customTotpTwofaccount->id]);

        $this->assertDatabaseHas('twofaccounts', [
            'id'      => $this->customTotpTwofaccount->id,
            'group_id'      => null,
        ]);

        $this->assertDatabaseHas('twofaccounts', [
            'id'      => $this->customHotpTwofaccount->id,
            'group_id'      => null,
        ]);
    }


    /**
     * @test
     */
    public function test_withdraw_single_id_deletes_relation()
    {
        $twofaccounts = collect([$this->customHotpTwofaccount, $this->customTotpTwofaccount]);
        $this->group->twofaccounts()->saveMany($twofaccounts);
        
        TwoFAccounts::withdraw($this->customTotpTwofaccount->id);

        $this->assertDatabaseHas('twofaccounts', [
            'id'      => $this->customTotpTwofaccount->id,
            'group_id'      => null,
        ]);
    }


    /**
     * @test
     */
    public function test_withdraw_missing_ids_returns_void()
    {
        $this->assertNull(TwoFAccounts::withdraw(null));
    }

    
    /**
     * @test
     */
    public function test_delete_comma_separated_ids()
    {        
        TwoFAccounts::delete($this->customHotpTwofaccount->id.','.$this->customTotpTwofaccount->id);

        $this->assertDatabaseMissing('twofaccounts', [
            'id'      => $this->customTotpTwofaccount->id,
        ]);
        $this->assertDatabaseMissing('twofaccounts', [
            'id'      => $this->customHotpTwofaccount->id,
        ]);
    }


    /**
     * @test
     */
    public function test_delete_array_of_ids()
    {        
        TwoFAccounts::delete([$this->customTotpTwofaccount->id, $this->customHotpTwofaccount->id]);

        $this->assertDatabaseMissing('twofaccounts', [
            'id'      => $this->customTotpTwofaccount->id,
        ]);
        $this->assertDatabaseMissing('twofaccounts', [
            'id'      => $this->customHotpTwofaccount->id,
        ]);
    }


    /**
     * @test
     */
    public function test_delete_single_id()
    {        
        TwoFAccounts::delete($this->customTotpTwofaccount->id);

        $this->assertDatabaseMissing('twofaccounts', [
            'id'      => $this->customTotpTwofaccount->id,
        ]);
    }


    /**
     * @test
     */
    public function test_convert_migration_from_gauth_returns_correct_accounts()
    {        
        $twofaccounts = TwoFAccounts::convertMigrationFromGA(OtpTestData::GOOGLE_AUTH_MIGRATION_URI);

        $this->assertCount(2, $twofaccounts);

        $this->assertEquals('totp', $twofaccounts->first()->otp_type);
        $this->assertEquals(OtpTestData::SERVICE, $twofaccounts->first()->service);
        $this->assertEquals(OtpTestData::ACCOUNT, $twofaccounts->first()->account);
        $this->assertEquals(OtpTestData::SECRET, $twofaccounts->first()->secret);
        $this->assertEquals(OtpTestData::DIGITS_DEFAULT, $twofaccounts->first()->digits);
        $this->assertEquals(OtpTestData::PERIOD_DEFAULT, $twofaccounts->first()->period);
        $this->assertEquals(null, $twofaccounts->first()->counter);
        $this->assertEquals(OtpTestData::ALGORITHM_DEFAULT, $twofaccounts->first()->algorithm);

        $this->assertEquals('totp', $twofaccounts->last()->otp_type);
        $this->assertEquals(OtpTestData::SERVICE.'_bis', $twofaccounts->last()->service);
        $this->assertEquals(OtpTestData::ACCOUNT.'_bis', $twofaccounts->last()->account);
        $this->assertEquals(OtpTestData::SECRET, $twofaccounts->last()->secret);
        $this->assertEquals(OtpTestData::DIGITS_DEFAULT, $twofaccounts->last()->digits);
        $this->assertEquals(OtpTestData::PERIOD_DEFAULT, $twofaccounts->last()->period);
        $this->assertEquals(null, $twofaccounts->last()->counter);
        $this->assertEquals(OtpTestData::ALGORITHM_DEFAULT, $twofaccounts->last()->algorithm);
    }


    /**
     * @test
     */
    public function test_convert_migration_from_gauth_returns_flagged_duplicates()
    {
        $parameters = [
            'service'   => OtpTestData::SERVICE,
            'account'   => OtpTestData::ACCOUNT,
            'icon'      => OtpTestData::ICON,
            'otp_type'  => 'totp',
            'secret'    => OtpTestData::SECRET,
            'digits'    => OtpTestData::DIGITS_DEFAULT,
            'algorithm' => OtpTestData::ALGORITHM_DEFAULT,
            'period'    => OtpTestData::PERIOD_DEFAULT,
        ];
        $twofaccount = new TwoFAccount;
        $twofaccount->fillWithOtpParameters($parameters)->save();

        $parameters['service'] = OtpTestData::SERVICE.'_bis';
        $parameters['account'] = OtpTestData::ACCOUNT.'_bis';

        $twofaccount = new TwoFAccount;
        $twofaccount->fillWithOtpParameters($parameters)->save();

        $twofaccounts = TwoFAccounts::convertMigrationFromGA(OtpTestData::GOOGLE_AUTH_MIGRATION_URI);

        $this->assertEquals(-1, $twofaccounts->first()->id);
        $this->assertEquals(-1, $twofaccounts->last()->id);
    }


    /**
     * @test
     */
    public function test_convert_invalid_migration_from_gauth_returns_InvalidGoogleAuthMigration_excpetion()
    {
        $this->expectException(\App\Exceptions\InvalidGoogleAuthMigration::class);
        $twofaccounts = TwoFAccounts::convertMigrationFromGA(OtpTestData::GOOGLE_AUTH_MIGRATION_URI_WITH_INVALID_DATA);
    }

}