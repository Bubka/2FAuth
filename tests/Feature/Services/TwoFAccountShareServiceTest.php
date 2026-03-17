<?php

namespace Tests\Feature\Services;

use App\Models\TwoFAccount;
use App\Models\TwoFAccountShare;
use App\Models\User;
use App\Services\TwoFAccountShareService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;

#[CoversClass(TwoFAccountShareService::class)]
class TwoFAccountShareServiceTest extends FeatureTestCase
{
    private User $owner;

    private TwoFAccount $twofaccount;

    private TwoFAccountShareService $service;

    protected function setUp() : void
    {
        parent::setUp();

        $this->owner = User::factory()->create();
        $this->twofaccount = TwoFAccount::factory()->for($this->owner)->create();
        $this->service = new TwoFAccountShareService;
    }

    #[Test]
    public function test_share_with_user_creates_share_with_owner_as_creator() : void
    {
        $targetUser = User::factory()->create();

        $result = $this->service->shareWithUser($this->twofaccount, $this->owner, $targetUser);

        $this->assertTrue($result['created']);
        $this->assertNotNull($result['share']);
        $this->assertDatabaseHas('twofaccount_shares', [
            'twofaccount_id' => $this->twofaccount->id,
            'scope' => TwoFAccountShare::SCOPE_USER,
            'shared_with_user_id' => $targetUser->id,
            'created_by_user_id' => $this->owner->id,
        ]);
    }

    #[Test]
    public function test_share_with_user_is_idempotent() : void
    {
        $targetUser = User::factory()->create();

        $firstResult = $this->service->shareWithUser($this->twofaccount, $this->owner, $targetUser);
        $secondResult = $this->service->shareWithUser($this->twofaccount, $this->owner, $targetUser);

        $this->assertTrue($firstResult['created']);
        $this->assertFalse($secondResult['created']);
        $this->assertEquals(1, TwoFAccountShare::query()
            ->where('twofaccount_id', $this->twofaccount->id)
            ->where('scope', TwoFAccountShare::SCOPE_USER)
            ->where('shared_with_user_id', $targetUser->id)
            ->count());
    }

    #[Test]
    public function test_share_with_users_returns_not_created_when_shared_with_all() : void
    {
        $targetUserA = User::factory()->create();
        $targetUserB = User::factory()->create();

        $this->service->shareWithAll($this->twofaccount, $this->owner);

        $results = $this->service->shareWithUsers($this->twofaccount, $this->owner, collect([$targetUserA, $targetUserB]));

        $this->assertCount(2, $results);
        $this->assertFalse($results[0]['created']);
        $this->assertFalse($results[1]['created']);
        $this->assertEquals(0, TwoFAccountShare::query()
            ->where('twofaccount_id', $this->twofaccount->id)
            ->where('scope', TwoFAccountShare::SCOPE_USER)
            ->count());
    }

    #[Test]
    public function test_share_with_users_returns_mixed_created_statuses() : void
    {
        $existingTargetUser = User::factory()->create();
        $newTargetUser = User::factory()->create();

        $this->service->shareWithUser($this->twofaccount, $this->owner, $existingTargetUser);

        $results = $this->service->shareWithUsers(
            $this->twofaccount,
            $this->owner,
            collect([$existingTargetUser, $newTargetUser])
        );

        $this->assertCount(2, $results);
        $this->assertFalse($results[0]['created']);
        $this->assertTrue($results[1]['created']);
    }

    #[Test]
    public function test_share_with_user_returns_null_share_when_shared_with_all() : void
    {
        $targetUser = User::factory()->create();

        $this->service->shareWithAll($this->twofaccount, $this->owner);

        $result = $this->service->shareWithUser($this->twofaccount, $this->owner, $targetUser);

        $this->assertFalse($result['created']);
        $this->assertNull($result['share']);
        $this->assertEquals(0, TwoFAccountShare::query()
            ->where('twofaccount_id', $this->twofaccount->id)
            ->where('scope', TwoFAccountShare::SCOPE_USER)
            ->count());
    }

    #[Test]
    public function test_revoke_all_user_shares_deletes_only_user_scope() : void
    {
        $targetUserA = User::factory()->create();
        $targetUserB = User::factory()->create();

        $this->service->shareWithUser($this->twofaccount, $this->owner, $targetUserA);
        $this->service->shareWithUser($this->twofaccount, $this->owner, $targetUserB);
        $this->service->shareWithAll($this->twofaccount, $this->owner);

        $deletedRows = $this->service->revokeAllUserShares($this->twofaccount);

        $this->assertEquals(2, $deletedRows);
        $this->assertDatabaseHas('twofaccount_shares', [
            'twofaccount_id' => $this->twofaccount->id,
            'scope' => TwoFAccountShare::SCOPE_ALL_USERS,
            'shared_with_user_id' => null,
        ]);
        $this->assertEquals(0, TwoFAccountShare::query()
            ->where('twofaccount_id', $this->twofaccount->id)
            ->where('scope', TwoFAccountShare::SCOPE_USER)
            ->count());
    }

    #[Test]
    public function test_revoke_user_share_deletes_only_target_user_share() : void
    {
        $targetUserA = User::factory()->create();
        $targetUserB = User::factory()->create();

        $this->service->shareWithUser($this->twofaccount, $this->owner, $targetUserA);
        $this->service->shareWithUser($this->twofaccount, $this->owner, $targetUserB);

        $deletedRows = $this->service->revokeUserShare($this->twofaccount, $targetUserA);

        $this->assertEquals(1, $deletedRows);
        $this->assertDatabaseMissing('twofaccount_shares', [
            'twofaccount_id' => $this->twofaccount->id,
            'scope' => TwoFAccountShare::SCOPE_USER,
            'shared_with_user_id' => $targetUserA->id,
        ]);
        $this->assertDatabaseHas('twofaccount_shares', [
            'twofaccount_id' => $this->twofaccount->id,
            'scope' => TwoFAccountShare::SCOPE_USER,
            'shared_with_user_id' => $targetUserB->id,
        ]);
    }

    #[Test]
    public function test_revoke_user_share_returns_zero_when_target_share_does_not_exist() : void
    {
        $targetUser = User::factory()->create();

        $deletedRows = $this->service->revokeUserShare($this->twofaccount, $targetUser);

        $this->assertEquals(0, $deletedRows);
    }

    #[Test]
    public function test_share_with_all_is_idempotent_and_unshare_toggles_state() : void
    {
        $firstResult = $this->service->shareWithAll($this->twofaccount, $this->owner);
        $secondResult = $this->service->shareWithAll($this->twofaccount, $this->owner);

        $this->assertTrue($firstResult['created']);
        $this->assertFalse($secondResult['created']);
        $this->assertTrue($this->service->isSharedWithAll($this->twofaccount));
        $this->assertEquals(1, TwoFAccountShare::query()
            ->where('twofaccount_id', $this->twofaccount->id)
            ->where('scope', TwoFAccountShare::SCOPE_ALL_USERS)
            ->count());

        $deletedRows = $this->service->unshareWithAll($this->twofaccount);

        $this->assertEquals(1, $deletedRows);
        $this->assertFalse($this->service->isSharedWithAll($this->twofaccount));
    }

    #[Test]
    public function test_share_with_all_keeps_initial_creator_on_subsequent_calls() : void
    {
        $otherOwner = User::factory()->create();

        $this->service->shareWithAll($this->twofaccount, $this->owner);
        $this->service->shareWithAll($this->twofaccount, $otherOwner);

        $this->assertDatabaseHas('twofaccount_shares', [
            'twofaccount_id' => $this->twofaccount->id,
            'scope' => TwoFAccountShare::SCOPE_ALL_USERS,
            'shared_with_user_id' => null,
            'created_by_user_id' => $this->owner->id,
        ]);
        $this->assertDatabaseMissing('twofaccount_shares', [
            'twofaccount_id' => $this->twofaccount->id,
            'scope' => TwoFAccountShare::SCOPE_ALL_USERS,
            'shared_with_user_id' => null,
            'created_by_user_id' => $otherOwner->id,
        ]);
    }

    #[Test]
    public function test_unshare_with_all_returns_zero_when_no_global_share_exists() : void
    {
        $deletedRows = $this->service->unshareWithAll($this->twofaccount);

        $this->assertEquals(0, $deletedRows);
        $this->assertFalse($this->service->isSharedWithAll($this->twofaccount));
    }

    #[Test]
    public function test_explicit_shared_users_returns_sorted_users_only() : void
    {
        $targetUserA = User::factory()->create();
        $targetUserB = User::factory()->create();

        $this->service->shareWithUser($this->twofaccount, $this->owner, $targetUserB);
        $this->service->shareWithUser($this->twofaccount, $this->owner, $targetUserA);
        $this->service->shareWithAll($this->twofaccount, $this->owner);

        $users = $this->service->explicitSharedUsers($this->twofaccount);

        $this->assertCount(2, $users);
        $this->assertEquals(
            [$targetUserA->id, $targetUserB->id],
            $users->pluck('id')->all()
        );
    }

    #[Test]
    public function test_explicit_shared_users_returns_empty_when_only_shared_with_all() : void
    {
        $this->service->shareWithAll($this->twofaccount, $this->owner);

        $users = $this->service->explicitSharedUsers($this->twofaccount);

        $this->assertCount(0, $users);
    }
}
