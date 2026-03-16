<?php

namespace Tests\Api\v1\Controllers;

use App\Api\v1\Controllers\TwoFAccountShareController;
use App\Api\v1\Requests\TwoFAccountShareStoreRequest;
use App\Models\TwoFAccount;
use App\Models\TwoFAccountShare;
use App\Models\User;
use App\Services\TwoFAccountShareService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;

#[CoversClass(TwoFAccountShareController::class)]
#[CoversClass(TwoFAccountShareStoreRequest::class)]
#[CoversClass(TwoFAccountShareService::class)]
class TwoFAccountShareControllerTest extends FeatureTestCase
{
    protected User $owner;

    protected User $targetUser;

    protected User $thirdUser;

    protected TwoFAccount $twofaccount;

    protected function setUp() : void
    {
        parent::setUp();

        $this->owner = User::factory()->create();
        $this->targetUser = User::factory()->create();
        $this->thirdUser = User::factory()->create();
        $this->twofaccount = TwoFAccount::factory()->for($this->owner)->create();
    }

    #[Test]
    public function test_index_returns_empty_users_when_global_share_status_is_enabled()
    {
        TwoFAccountShare::create([
            'twofaccount_id' => $this->twofaccount->id,
            'shared_with_user_id' => $this->targetUser->id,
            'scope' => TwoFAccountShare::SCOPE_USER,
            'created_by_user_id' => $this->owner->id,
        ]);

        TwoFAccountShare::create([
            'twofaccount_id' => $this->twofaccount->id,
            'shared_with_user_id' => null,
            'scope' => TwoFAccountShare::SCOPE_ALL_USERS,
            'created_by_user_id' => $this->owner->id,
        ]);

        $this->actingAs($this->owner, 'api-guard')
            ->json('GET', '/api/v1/twofaccounts/' . $this->twofaccount->id . '/shares')
            ->assertOk()
            ->assertJsonPath('is_shared_with_all', true)
            ->assertJsonCount(0, 'users');
    }

    #[Test]
    public function test_index_returns_explicit_user_ids_only_when_not_shared_with_all()
    {
        TwoFAccountShare::create([
            'twofaccount_id' => $this->twofaccount->id,
            'shared_with_user_id' => $this->targetUser->id,
            'scope' => TwoFAccountShare::SCOPE_USER,
            'created_by_user_id' => $this->owner->id,
        ]);

        $this->actingAs($this->owner, 'api-guard')
            ->json('GET', '/api/v1/twofaccounts/' . $this->twofaccount->id . '/shares')
            ->assertOk()
            ->assertJsonPath('is_shared_with_all', false)
            ->assertJsonPath('users.0.id', $this->targetUser->id)
            ->assertJsonMissingPath('users.0.name')
            ->assertJsonMissingPath('users.0.email');
    }

    #[Test]
    public function test_index_for_non_owner_is_forbidden()
    {
        $this->actingAs($this->targetUser, 'api-guard')
            ->json('GET', '/api/v1/twofaccounts/' . $this->twofaccount->id . '/shares')
            ->assertForbidden();
    }

    #[Test]
    public function test_store_creates_a_user_share_and_is_idempotent()
    {
        $this->actingAs($this->owner, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts/' . $this->twofaccount->id . '/shares', [
                'ids' => [$this->targetUser->id],
            ])
            ->assertCreated()
            ->assertJsonPath('users.0.id', $this->targetUser->id)
            ->assertJsonPath('twofaccount_id', $this->twofaccount->id)
            ->assertJsonMissingPath('users.0.name')
            ->assertJsonMissingPath('users.0.email')
            ->assertJsonMissingPath('users.0.created')
            ->assertJsonMissingPath('created');

        $this->actingAs($this->owner, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts/' . $this->twofaccount->id . '/shares', [
                'ids' => [$this->targetUser->id],
            ])
            ->assertOk()
            ->assertJsonPath('users.0.id', $this->targetUser->id)
            ->assertJsonPath('twofaccount_id', $this->twofaccount->id)
            ->assertJsonMissingPath('users.0.name')
            ->assertJsonMissingPath('users.0.email')
            ->assertJsonMissingPath('users.0.created')
            ->assertJsonMissingPath('created');
    }

    #[Test]
    public function test_store_creates_shares_for_multiple_users_in_one_request()
    {
        $this->actingAs($this->owner, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts/' . $this->twofaccount->id . '/shares', [
                'ids' => [$this->targetUser->id, $this->thirdUser->id],
            ])
            ->assertCreated()
            ->assertJsonPath('users.0.id', $this->targetUser->id)
            ->assertJsonPath('users.1.id', $this->thirdUser->id)
            ->assertJsonPath('twofaccount_id', $this->twofaccount->id)
            ->assertJsonMissingPath('users.0.name')
            ->assertJsonMissingPath('users.0.email')
            ->assertJsonMissingPath('users.0.created')
            ->assertJsonMissingPath('users.1.name')
            ->assertJsonMissingPath('users.1.email')
            ->assertJsonMissingPath('users.1.created')
            ->assertJsonMissingPath('created');

        $this->assertDatabaseHas('twofaccount_shares', [
            'twofaccount_id' => $this->twofaccount->id,
            'shared_with_user_id' => $this->targetUser->id,
            'scope' => TwoFAccountShare::SCOPE_USER,
        ]);

        $this->assertDatabaseHas('twofaccount_shares', [
            'twofaccount_id' => $this->twofaccount->id,
            'shared_with_user_id' => $this->thirdUser->id,
            'scope' => TwoFAccountShare::SCOPE_USER,
        ]);
    }

    #[Test]
    public function test_store_returns_conflict_when_shared_with_all_users()
    {
        TwoFAccountShare::create([
            'twofaccount_id' => $this->twofaccount->id,
            'shared_with_user_id' => null,
            'scope' => TwoFAccountShare::SCOPE_ALL_USERS,
            'created_by_user_id' => $this->owner->id,
        ]);

        $this->actingAs($this->owner, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts/' . $this->twofaccount->id . '/shares', [
                'ids' => [$this->targetUser->id, $this->thirdUser->id],
            ])
            ->assertStatus(409)
            ->assertJsonStructure([
                'message',
                'reason',
            ]);

        $this->assertDatabaseMissing('twofaccount_shares', [
            'twofaccount_id' => $this->twofaccount->id,
            'shared_with_user_id' => $this->targetUser->id,
            'scope' => TwoFAccountShare::SCOPE_USER,
        ]);

        $this->assertDatabaseMissing('twofaccount_shares', [
            'twofaccount_id' => $this->twofaccount->id,
            'shared_with_user_id' => $this->thirdUser->id,
            'scope' => TwoFAccountShare::SCOPE_USER,
        ]);
    }

    #[Test]
    public function test_store_rejects_sharing_with_owner()
    {
        $this->actingAs($this->owner, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts/' . $this->twofaccount->id . '/shares', [
                'ids' => [$this->owner->id],
            ])
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'reason',
            ]);
    }

    #[Test]
    public function test_store_accepts_single_user_payload()
    {
        $this->actingAs($this->owner, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts/' . $this->twofaccount->id . '/shares', [
                'user_id' => $this->targetUser->id,
            ])
            ->assertCreated()
            ->assertJsonPath('users.0.id', $this->targetUser->id)
            ->assertJsonPath('twofaccount_id', $this->twofaccount->id)
            ->assertJsonMissingPath('users.0.name')
            ->assertJsonMissingPath('users.0.email')
            ->assertJsonMissingPath('users.0.created')
            ->assertJsonMissingPath('created');
    }

    #[Test]
    public function test_destroy_revokes_a_user_share()
    {
        TwoFAccountShare::create([
            'twofaccount_id' => $this->twofaccount->id,
            'shared_with_user_id' => $this->targetUser->id,
            'scope' => TwoFAccountShare::SCOPE_USER,
            'created_by_user_id' => $this->owner->id,
        ]);

        $this->actingAs($this->owner, 'api-guard')
            ->json('DELETE', '/api/v1/twofaccounts/' . $this->twofaccount->id . '/shares/' . $this->targetUser->id)
            ->assertNoContent();

        $this->assertDatabaseMissing('twofaccount_shares', [
            'twofaccount_id' => $this->twofaccount->id,
            'shared_with_user_id' => $this->targetUser->id,
            'scope' => TwoFAccountShare::SCOPE_USER,
        ]);
    }

    #[Test]
    public function test_destroy_returns_conflict_when_shared_with_all_users()
    {
        TwoFAccountShare::create([
            'twofaccount_id' => $this->twofaccount->id,
            'shared_with_user_id' => $this->targetUser->id,
            'scope' => TwoFAccountShare::SCOPE_USER,
            'created_by_user_id' => $this->owner->id,
        ]);

        TwoFAccountShare::create([
            'twofaccount_id' => $this->twofaccount->id,
            'shared_with_user_id' => null,
            'scope' => TwoFAccountShare::SCOPE_ALL_USERS,
            'created_by_user_id' => $this->owner->id,
        ]);

        $this->actingAs($this->owner, 'api-guard')
            ->json('DELETE', '/api/v1/twofaccounts/' . $this->twofaccount->id . '/shares/' . $this->targetUser->id)
            ->assertStatus(409)
            ->assertJsonStructure([
                'message',
                'reason',
            ]);

        $this->assertDatabaseHas('twofaccount_shares', [
            'twofaccount_id' => $this->twofaccount->id,
            'shared_with_user_id' => $this->targetUser->id,
            'scope' => TwoFAccountShare::SCOPE_USER,
        ]);
    }

    #[Test]
    public function test_destroy_all_users_revokes_all_scope_user_shares_only()
    {
        TwoFAccountShare::create([
            'twofaccount_id' => $this->twofaccount->id,
            'shared_with_user_id' => $this->targetUser->id,
            'scope' => TwoFAccountShare::SCOPE_USER,
            'created_by_user_id' => $this->owner->id,
        ]);

        TwoFAccountShare::create([
            'twofaccount_id' => $this->twofaccount->id,
            'shared_with_user_id' => $this->thirdUser->id,
            'scope' => TwoFAccountShare::SCOPE_USER,
            'created_by_user_id' => $this->owner->id,
        ]);

        $this->actingAs($this->owner, 'api-guard')
            ->json('DELETE', '/api/v1/twofaccounts/' . $this->twofaccount->id . '/shares')
            ->assertNoContent();

        $this->assertDatabaseMissing('twofaccount_shares', [
            'twofaccount_id' => $this->twofaccount->id,
            'scope' => TwoFAccountShare::SCOPE_USER,
            'shared_with_user_id' => $this->targetUser->id,
        ]);

        $this->assertDatabaseMissing('twofaccount_shares', [
            'twofaccount_id' => $this->twofaccount->id,
            'scope' => TwoFAccountShare::SCOPE_USER,
            'shared_with_user_id' => $this->thirdUser->id,
        ]);
    }

    #[Test]
    public function test_destroy_all_users_returns_conflict_when_shared_with_all_users()
    {
        TwoFAccountShare::create([
            'twofaccount_id' => $this->twofaccount->id,
            'shared_with_user_id' => $this->targetUser->id,
            'scope' => TwoFAccountShare::SCOPE_USER,
            'created_by_user_id' => $this->owner->id,
        ]);

        TwoFAccountShare::create([
            'twofaccount_id' => $this->twofaccount->id,
            'shared_with_user_id' => null,
            'scope' => TwoFAccountShare::SCOPE_ALL_USERS,
            'created_by_user_id' => $this->owner->id,
        ]);

        $this->actingAs($this->owner, 'api-guard')
            ->json('DELETE', '/api/v1/twofaccounts/' . $this->twofaccount->id . '/shares')
            ->assertStatus(409)
            ->assertJsonStructure([
                'message',
                'reason',
            ]);

        $this->assertDatabaseHas('twofaccount_shares', [
            'twofaccount_id' => $this->twofaccount->id,
            'shared_with_user_id' => $this->targetUser->id,
            'scope' => TwoFAccountShare::SCOPE_USER,
        ]);
    }

    #[Test]
    public function test_destroy_all_users_for_non_owner_is_forbidden()
    {
        $this->actingAs($this->targetUser, 'api-guard')
            ->json('DELETE', '/api/v1/twofaccounts/' . $this->twofaccount->id . '/shares')
            ->assertForbidden();
    }

    #[Test]
    public function test_share_all_and_unshare_all_toggle_global_share()
    {
        $this->actingAs($this->owner, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts/' . $this->twofaccount->id . '/shares/all')
            ->assertCreated()
            ->assertJsonPath('is_shared_with_all', true)
            ->assertJsonPath('twofaccount_id', $this->twofaccount->id);

        $this->assertDatabaseHas('twofaccount_shares', [
            'twofaccount_id' => $this->twofaccount->id,
            'scope' => TwoFAccountShare::SCOPE_ALL_USERS,
            'shared_with_user_id' => null,
        ]);

        $this->actingAs($this->owner, 'api-guard')
            ->json('DELETE', '/api/v1/twofaccounts/' . $this->twofaccount->id . '/shares/all')
            ->assertNoContent();

        $this->assertDatabaseMissing('twofaccount_shares', [
            'twofaccount_id' => $this->twofaccount->id,
            'scope' => TwoFAccountShare::SCOPE_ALL_USERS,
        ]);
    }

    #[Test]
    public function test_share_all_revokes_existing_user_shares()
    {
        TwoFAccountShare::create([
            'twofaccount_id' => $this->twofaccount->id,
            'shared_with_user_id' => $this->targetUser->id,
            'scope' => TwoFAccountShare::SCOPE_USER,
            'created_by_user_id' => $this->owner->id,
        ]);

        TwoFAccountShare::create([
            'twofaccount_id' => $this->twofaccount->id,
            'shared_with_user_id' => $this->thirdUser->id,
            'scope' => TwoFAccountShare::SCOPE_USER,
            'created_by_user_id' => $this->owner->id,
        ]);

        $this->actingAs($this->owner, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts/' . $this->twofaccount->id . '/shares/all')
            ->assertCreated()
            ->assertJsonPath('is_shared_with_all', true)
            ->assertJsonPath('twofaccount_id', $this->twofaccount->id);

        $this->assertDatabaseMissing('twofaccount_shares', [
            'twofaccount_id' => $this->twofaccount->id,
            'scope' => TwoFAccountShare::SCOPE_USER,
            'shared_with_user_id' => $this->targetUser->id,
        ]);

        $this->assertDatabaseMissing('twofaccount_shares', [
            'twofaccount_id' => $this->twofaccount->id,
            'scope' => TwoFAccountShare::SCOPE_USER,
            'shared_with_user_id' => $this->thirdUser->id,
        ]);

        $this->assertDatabaseHas('twofaccount_shares', [
            'twofaccount_id' => $this->twofaccount->id,
            'scope' => TwoFAccountShare::SCOPE_ALL_USERS,
            'shared_with_user_id' => null,
        ]);
    }

    #[Test]
    public function test_unshare_all_returns_conflict_when_share_all_is_not_applied()
    {
        $this->actingAs($this->owner, 'api-guard')
            ->json('DELETE', '/api/v1/twofaccounts/' . $this->twofaccount->id . '/shares/all')
            ->assertStatus(409)
            ->assertJsonStructure([
                'message',
                'reason',
            ]);
    }
}
