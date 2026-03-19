<?php

namespace Tests\Feature\Services;

use App\Facades\TwoFAccounts;
use App\Models\Group;
use App\Models\TwoFAccount;
use App\Models\TwoFAccountShare;
use App\Models\User;
use App\Services\TwoFAccountService;
use Illuminate\Support\Facades\Exceptions;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\Data\MigrationTestData;
use Tests\Data\OtpTestData;
use Tests\FeatureTestCase;

/**
 * TwoFAccountServiceTest test class
 */
#[CoversClass(TwoFAccountService::class)]
#[CoversClass(TwoFAccounts::class)]
class TwoFAccountServiceTest extends FeatureTestCase
{
    /**
     * @var \App\Models\User|\Illuminate\Contracts\Auth\Authenticatable
     */
    protected $user;

    /**
     * @var \App\Models\TwoFAccount
     */
    protected $customTotpTwofaccount;

    protected $customHotpTwofaccount;

    /**
     * @var \App\Models\Group
     */
    protected $userGroupA;

    protected $userGroupB;

    protected function setUp() : void
    {
        parent::setUp();

        $this->user       = User::factory()->create();
        $this->userGroupA = Group::factory()->for($this->user)->create();
        $this->userGroupB = Group::factory()->for($this->user)->create();

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
    }

    #[Test]
    public function test_withdraw_comma_separated_ids_deletes_relation()
    {
        $twofaccounts = collect([$this->customHotpTwofaccount, $this->customTotpTwofaccount]);
        $this->userGroupA->twofaccounts()->saveMany($twofaccounts);

        $this->assertDatabaseHas('twofaccounts', [
            'id'       => $this->customHotpTwofaccount->id,
            'group_id' => $this->userGroupA->id,
        ]);

        $this->assertDatabaseHas('twofaccounts', [
            'id'       => $this->customTotpTwofaccount->id,
            'group_id' => $this->userGroupA->id,
        ]);

        TwoFAccounts::withdraw($this->customHotpTwofaccount->id . ',' . $this->customTotpTwofaccount->id);

        $this->assertDatabaseHas('twofaccounts', [
            'id'       => $this->customTotpTwofaccount->id,
            'group_id' => null,
        ]);

        $this->assertDatabaseHas('twofaccounts', [
            'id'       => $this->customHotpTwofaccount->id,
            'group_id' => null,
        ]);
    }

    #[Test]
    public function test_withdraw_array_of_ids_deletes_relation()
    {
        $twofaccounts = collect([$this->customHotpTwofaccount, $this->customTotpTwofaccount]);
        $this->userGroupA->twofaccounts()->saveMany($twofaccounts);

        $this->assertDatabaseHas('twofaccounts', [
            'id'       => $this->customHotpTwofaccount->id,
            'group_id' => $this->userGroupA->id,
        ]);

        $this->assertDatabaseHas('twofaccounts', [
            'id'       => $this->customTotpTwofaccount->id,
            'group_id' => $this->userGroupA->id,
        ]);
        TwoFAccounts::withdraw([$this->customHotpTwofaccount->id, $this->customTotpTwofaccount->id]);

        $this->assertDatabaseHas('twofaccounts', [
            'id'       => $this->customTotpTwofaccount->id,
            'group_id' => null,
        ]);

        $this->assertDatabaseHas('twofaccounts', [
            'id'       => $this->customHotpTwofaccount->id,
            'group_id' => null,
        ]);
    }

    #[Test]
    public function test_withdraw_single_id_deletes_relation()
    {
        $twofaccounts = collect([$this->customHotpTwofaccount, $this->customTotpTwofaccount]);
        $this->userGroupA->twofaccounts()->saveMany($twofaccounts);

        $this->assertDatabaseHas('twofaccounts', [
            'id'       => $this->customTotpTwofaccount->id,
            'group_id' => $this->userGroupA->id,
        ]);

        TwoFAccounts::withdraw($this->customTotpTwofaccount->id);

        $this->assertDatabaseHas('twofaccounts', [
            'id'       => $this->customTotpTwofaccount->id,
            'group_id' => null,
        ]);
    }

    #[Test]
    public function test_withdraw_missing_ids_does_not_throw_exception()
    {
        Exceptions::fake();
        Exceptions::assertNothingReported();

        TwoFAccounts::withdraw(9999999);
    }

    #[Test]
    public function test_withdraw_null_id_does_not_throw_exception()
    {
        Exceptions::fake();
        Exceptions::assertNothingReported();

        TwoFAccounts::withdraw(null);
    }

    #[Test]
    public function test_migrate_from_gauth_returns_correct_accounts()
    {
        $this->actingAs($this->user);

        $twofaccounts = TwoFAccounts::migrate(MigrationTestData::GOOGLE_AUTH_MIGRATION_URI);

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
        $this->assertEquals(OtpTestData::SERVICE . '_bis', $twofaccounts->last()->service);
        $this->assertEquals(OtpTestData::ACCOUNT . '_bis', $twofaccounts->last()->account);
        $this->assertEquals(OtpTestData::SECRET, $twofaccounts->last()->secret);
        $this->assertEquals(OtpTestData::DIGITS_DEFAULT, $twofaccounts->last()->digits);
        $this->assertEquals(OtpTestData::PERIOD_DEFAULT, $twofaccounts->last()->period);
        $this->assertEquals(null, $twofaccounts->last()->counter);
        $this->assertEquals(OtpTestData::ALGORITHM_DEFAULT, $twofaccounts->last()->algorithm);
    }

    #[Test]
    public function test_migrate_from_gauth_returns_flagged_duplicates()
    {
        $this->actingAs($this->user, 'api-guard');

        $parameters = [
            'service'   => OtpTestData::SERVICE,
            'account'   => OtpTestData::ACCOUNT,
            'icon'      => OtpTestData::ICON_PNG,
            'otp_type'  => 'totp',
            'secret'    => OtpTestData::SECRET,
            'digits'    => OtpTestData::DIGITS_DEFAULT,
            'algorithm' => OtpTestData::ALGORITHM_DEFAULT,
            'period'    => OtpTestData::PERIOD_DEFAULT,
        ];

        TwoFAccount::factory()->for($this->user)->create($parameters);

        $parameters['service'] = OtpTestData::SERVICE . '_bis';
        $parameters['account'] = OtpTestData::ACCOUNT . '_bis';

        TwoFAccount::factory()->for($this->user)->create($parameters);

        $twofaccounts = TwoFAccounts::migrate(MigrationTestData::GOOGLE_AUTH_MIGRATION_URI);

        $this->assertEquals(-1, $twofaccounts->first()->id);
        $this->assertEquals(-1, $twofaccounts->last()->id);
    }

    #[Test]
    public function test_migrate_invalid_migration_from_gauth_returns_InvalidMigrationData_exception()
    {
        $this->expectException(\App\Exceptions\InvalidMigrationDataException::class);
        $twofaccounts = TwoFAccounts::migrate(MigrationTestData::GOOGLE_AUTH_MIGRATION_URI_WITH_INVALID_DATA);
    }

    #[Test]
    public function test_export_single_id_returns_collection()
    {
        $twofaccounts = TwoFAccounts::export($this->customTotpTwofaccount->id);

        $this->assertContainsOnlyInstancesOf(TwoFAccount::class, $twofaccounts);
        $this->assertObjectEquals($this->customTotpTwofaccount, $twofaccounts->first());
    }

    #[Test]
    public function test_export_comma_separated_ids_returns_collection()
    {
        $twofaccounts = TwoFAccounts::export($this->customTotpTwofaccount->id . ',' . $this->customHotpTwofaccount->id);

        $this->assertContainsOnlyInstancesOf(TwoFAccount::class, $twofaccounts);
        $this->assertObjectEquals($this->customTotpTwofaccount, $twofaccounts->first());
        $this->assertObjectEquals($this->customHotpTwofaccount, $twofaccounts->last());
    }

    #[Test]
    public function test_export_array_of_ids_returns_collection()
    {
        $twofaccounts = TwoFAccounts::export([$this->customTotpTwofaccount->id, $this->customHotpTwofaccount->id]);

        $this->assertContainsOnlyInstancesOf(TwoFAccount::class, $twofaccounts);
        $this->assertObjectEquals($this->customTotpTwofaccount, $twofaccounts->first());
        $this->assertObjectEquals($this->customHotpTwofaccount, $twofaccounts->last());
    }

    #[Test]
    public function test_delete_comma_separated_ids()
    {
        $twofaccounts = TwoFAccount::factory()->count(2)->for($this->user)->create();

        $this->assertDatabaseHas('twofaccounts', [
            'id' => $twofaccounts[0]->id,
        ]);
        $this->assertDatabaseHas('twofaccounts', [
            'id' => $twofaccounts[1]->id,
        ]);

        TwoFAccounts::delete($twofaccounts[0]->id . ',' . $twofaccounts[1]->id);

        $this->assertDatabaseMissing('twofaccounts', [
            'id' => $twofaccounts[0]->id,
        ]);
        $this->assertDatabaseMissing('twofaccounts', [
            'id' => $twofaccounts[1]->id,
        ]);
    }

    #[Test]
    public function test_delete_array_of_ids()
    {
        $twofaccounts = TwoFAccount::factory()->count(2)->for($this->user)->create();

        $this->assertDatabaseHas('twofaccounts', [
            'id' => $twofaccounts[0]->id,
        ]);
        $this->assertDatabaseHas('twofaccounts', [
            'id' => $twofaccounts[1]->id,
        ]);

        TwoFAccounts::delete([$twofaccounts[0]->id, $twofaccounts[1]->id]);

        $this->assertDatabaseMissing('twofaccounts', [
            'id' => $twofaccounts[0]->id,
        ]);
        $this->assertDatabaseMissing('twofaccounts', [
            'id' => $twofaccounts[1]->id,
        ]);
    }

    #[Test]
    public function test_delete_single_id()
    {
        $twofaccount = TwoFAccount::factory()->for($this->user)->create();

        $this->assertDatabaseHas('twofaccounts', [
            'id' => $twofaccount->id,
        ]);

        TwoFAccounts::delete($twofaccount->id);

        $this->assertDatabaseMissing('twofaccounts', [
            'id' => $twofaccount->id,
        ]);
    }

    #[Test]
    public function test_delete_single_id_cascades_to_related_shares()
    {
        $twofaccount = TwoFAccount::factory()->for($this->user)->create();
        $targetUser = User::factory()->create();

        $userShare = TwoFAccountShare::create([
            'twofaccount_id' => $twofaccount->id,
            'shared_with_user_id' => $targetUser->id,
            'scope' => TwoFAccountShare::SCOPE_USER,
            'created_by_user_id' => $this->user->id,
        ]);

        $allUsersShare = TwoFAccountShare::create([
            'twofaccount_id' => $twofaccount->id,
            'shared_with_user_id' => null,
            'scope' => TwoFAccountShare::SCOPE_ALL_USERS,
            'created_by_user_id' => $this->user->id,
        ]);

        TwoFAccounts::delete($twofaccount->id);

        $this->assertDatabaseMissing('twofaccount_shares', [
            'id' => $userShare->id,
        ]);
        $this->assertDatabaseMissing('twofaccount_shares', [
            'id' => $allUsersShare->id,
        ]);
    }

    #[Test]
    public function test_transfer_ownership_sets_new_owner_and_returns_refreshed_model()
    {
        $owner = User::factory()->create();
        $newOwner = User::factory()->create();
        $twofaccount = TwoFAccount::factory()->for($owner)->create();

        $transferredTwofaccount = TwoFAccounts::transferOwnership($twofaccount, $newOwner);

        $this->assertEquals($newOwner->id, $transferredTwofaccount->user_id);
        $this->assertDatabaseHas('twofaccounts', [
            'id'      => $twofaccount->id,
            'user_id' => $newOwner->id,
        ]);
    }

    #[Test]
    public function test_transfer_ownership_removes_obsolete_user_share_to_new_owner_only()
    {
        $owner = User::factory()->create();
        $newOwner = User::factory()->create();
        $otherSharedUser = User::factory()->create();
        $twofaccount = TwoFAccount::factory()->for($owner)->create();

        TwoFAccountShare::create([
            'twofaccount_id' => $twofaccount->id,
            'shared_with_user_id' => $newOwner->id,
            'scope' => TwoFAccountShare::SCOPE_USER,
            'created_by_user_id' => $owner->id,
        ]);
        TwoFAccountShare::create([
            'twofaccount_id' => $twofaccount->id,
            'shared_with_user_id' => $otherSharedUser->id,
            'scope' => TwoFAccountShare::SCOPE_USER,
            'created_by_user_id' => $owner->id,
        ]);

        TwoFAccounts::transferOwnership($twofaccount, $newOwner);

        $this->assertDatabaseMissing('twofaccount_shares', [
            'twofaccount_id' => $twofaccount->id,
            'scope' => TwoFAccountShare::SCOPE_USER,
            'shared_with_user_id' => $newOwner->id,
        ]);
        $this->assertDatabaseHas('twofaccount_shares', [
            'twofaccount_id' => $twofaccount->id,
            'scope' => TwoFAccountShare::SCOPE_USER,
            'shared_with_user_id' => $otherSharedUser->id,
        ]);
    }

    #[Test]
    public function test_transfer_ownership_preserves_all_users_share_scope()
    {
        $owner = User::factory()->create();
        $newOwner = User::factory()->create();
        $twofaccount = TwoFAccount::factory()->for($owner)->create();

        TwoFAccountShare::create([
            'twofaccount_id' => $twofaccount->id,
            'shared_with_user_id' => null,
            'scope' => TwoFAccountShare::SCOPE_ALL_USERS,
            'created_by_user_id' => $owner->id,
        ]);

        TwoFAccounts::transferOwnership($twofaccount, $newOwner);

        $this->assertDatabaseHas('twofaccount_shares', [
            'twofaccount_id' => $twofaccount->id,
            'scope' => TwoFAccountShare::SCOPE_ALL_USERS,
            'shared_with_user_id' => null,
            'created_by_user_id' => $owner->id,
        ]);
    }

    #[Test]
    public function test_transfer_ownership_preserves_created_by_user_id_for_remaining_user_share()
    {
        $owner = User::factory()->create();
        $newOwner = User::factory()->create();
        $otherSharedUser = User::factory()->create();
        $twofaccount = TwoFAccount::factory()->for($owner)->create();

        TwoFAccountShare::create([
            'twofaccount_id' => $twofaccount->id,
            'shared_with_user_id' => $otherSharedUser->id,
            'scope' => TwoFAccountShare::SCOPE_USER,
            'created_by_user_id' => $owner->id,
        ]);

        TwoFAccounts::transferOwnership($twofaccount, $newOwner);

        $this->assertDatabaseHas('twofaccount_shares', [
            'twofaccount_id' => $twofaccount->id,
            'scope' => TwoFAccountShare::SCOPE_USER,
            'shared_with_user_id' => $otherSharedUser->id,
            'created_by_user_id' => $owner->id,
        ]);
    }

    #[Test]
    public function test_setUser_sets_twofaccounts_user()
    {
        $twofaccountA = TwoFAccount::factory()->create();
        $twofaccountB = TwoFAccount::factory()->create();

        $this->assertEquals(null, $twofaccountA->user_id);
        $this->assertEquals(null, $twofaccountB->user_id);

        TwoFAccounts::setUser(TwoFAccount::all(), $this->user);

        $this->assertEquals($this->user->id, $twofaccountA->refresh()->user_id);
        $this->assertEquals($this->user->id, $twofaccountB->refresh()->user_id);
    }
}
