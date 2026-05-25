<?php

namespace Tests\Api\v1\Controllers;

use App\Api\v1\Controllers\TwoFAccountShareController;
use App\Api\v1\Requests\TwoFAccountShareStoreRequest;
use App\Api\v1\Resources\UserShareRecipientResource;
use App\Events\TwoFAccountShareRevoked;
use App\Events\TwoFAccountShared;
use App\Facades\Settings;
use App\Models\TwoFAccount;
use App\Models\TwoFAccountShare;
use App\Models\User;
use App\Services\TwoFAccountShareService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;

#[CoversClass(TwoFAccountShare::class)]
#[CoversClass(TwoFAccountShareController::class)]
#[CoversClass(TwoFAccountShareStoreRequest::class)]
#[CoversClass(TwoFAccountShareService::class)]
#[CoversClass(UserShareRecipientResource::class)]
#[CoversClass(TwoFAccountShareRevoked::class)]
class TwoFAccountShareControllerTest extends FeatureTestCase
{
    protected User $owner;

    protected User $targetUser;

    protected User $thirdUser;

    protected TwoFAccount $twofaccount;

    protected function setUp() : void
    {
        parent::setUp();

        Event::fake();

        $this->owner = User::factory()->create();
        $this->targetUser = User::factory()->create();
        $this->thirdUser = User::factory()->create();
        $this->twofaccount = TwoFAccount::factory()->for($this->owner)->create();
    }

    #[Test]
    public function test_index_returns_is_shared_with_all_over_specific_user_list()
    {
        Settings::set('enableAllUsersSharingScope', true);

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
            ->assertJsonPath('twofaccount_id', $this->twofaccount->id)
            ->assertJsonPath('is_shared_with_all', true)
            ->assertJsonMissingPath('specific_users');

        Settings::set('enableAllUsersSharingScope', false);
    }

    #[Test]
    public function test_index_returns_specific_user_when_all_users_scope_is_disabled()
    {
        Settings::set('enableAllUsersSharingScope', false);

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
            ->assertJsonPath('twofaccount_id', $this->twofaccount->id)
            ->assertJsonPath('is_shared_with_all', false)
            ->assertJsonPath('specific_users.0.id', $this->targetUser->id)
            ->assertJsonPath('specific_users.0.name', $this->targetUser->name)
            ->assertJsonMissingPath('specific_users.0.email');
    }

    #[Test]
    public function test_index_returns_no_user_when_all_users_scope_is_disabled()
    {
        Settings::set('enableAllUsersSharingScope', false);

        TwoFAccountShare::create([
            'twofaccount_id' => $this->twofaccount->id,
            'shared_with_user_id' => null,
            'scope' => TwoFAccountShare::SCOPE_ALL_USERS,
            'created_by_user_id' => $this->owner->id,
        ]);

        $this->actingAs($this->owner, 'api-guard')
            ->json('GET', '/api/v1/twofaccounts/' . $this->twofaccount->id . '/shares')
            ->assertOk()
            ->assertJsonPath('twofaccount_id', $this->twofaccount->id)
            ->assertJsonPath('is_shared_with_all', false)
            ->assertJsonCount(0, 'specific_users');
    }

    #[Test]
    public function test_index_returns_explicit_user_ids()
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
            ->assertJsonPath('twofaccount_id', $this->twofaccount->id)
            ->assertJsonPath('is_shared_with_all', false)
            ->assertJsonPath('specific_users.0.id', $this->targetUser->id)
            ->assertJsonPath('specific_users.0.name', $this->targetUser->name)
            ->assertJsonMissingPath('specific_users.0.email');
    }

    #[Test]
    public function test_index_returns_no_user_when_no_share_exists()
    {
        $this->actingAs($this->owner, 'api-guard')
            ->json('GET', '/api/v1/twofaccounts/' . $this->twofaccount->id . '/shares')
            ->assertOk()
            ->assertJsonPath('twofaccount_id', $this->twofaccount->id)
            ->assertJsonPath('is_shared_with_all', false)
            ->assertJsonCount(0, 'specific_users');
    }

    #[Test]
    public function test_index_returns_no_user_when_no_share_exists_with_all_users_scope_enabled()
    {
        Settings::set('enableAllUsersSharingScope', true);

        $this->actingAs($this->owner, 'api-guard')
            ->json('GET', '/api/v1/twofaccounts/' . $this->twofaccount->id . '/shares')
            ->assertOk()
            ->assertJsonPath('twofaccount_id', $this->twofaccount->id)
            ->assertJsonPath('is_shared_with_all', false)
            ->assertJsonCount(0, 'specific_users');

        Settings::set('enableAllUsersSharingScope', false);
    }

    #[Test]
    public function test_index_returns_explicit_user_ids_sorted_by_id()
    {
        TwoFAccountShare::create([
            'twofaccount_id' => $this->twofaccount->id,
            'shared_with_user_id' => $this->thirdUser->id,
            'scope' => TwoFAccountShare::SCOPE_USER,
            'created_by_user_id' => $this->owner->id,
        ]);

        TwoFAccountShare::create([
            'twofaccount_id' => $this->twofaccount->id,
            'shared_with_user_id' => $this->targetUser->id,
            'scope' => TwoFAccountShare::SCOPE_USER,
            'created_by_user_id' => $this->owner->id,
        ]);

        $sortedIds = [$this->targetUser->id, $this->thirdUser->id];
        sort($sortedIds);

        $this->actingAs($this->owner, 'api-guard')
            ->json('GET', '/api/v1/twofaccounts/' . $this->twofaccount->id . '/shares')
            ->assertOk()
            ->assertJsonPath('specific_users.0.id', $sortedIds[0])
            ->assertJsonPath('specific_users.1.id', $sortedIds[1]);
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
                'user_ids' => [$this->targetUser->id],
            ])
            ->assertCreated()
            ->assertJsonPath('specific_users.0.id', $this->targetUser->id)
            ->assertJsonPath('specific_users.0.name', $this->targetUser->name)
            ->assertJsonPath('twofaccount_id', $this->twofaccount->id)
            ->assertJsonMissingPath('specific_users.0.email')
            ->assertJsonMissingPath('specific_users.0.created')
            ->assertJsonMissingPath('created');

        $this->actingAs($this->owner, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts/' . $this->twofaccount->id . '/shares', [
                'user_ids' => [$this->targetUser->id],
            ])
            ->assertOk()
            ->assertJsonPath('twofaccount_id', $this->twofaccount->id)
            ->assertJsonPath('is_shared_with_all', false)
            ->assertJsonPath('specific_users.0.id', $this->targetUser->id)
            ->assertJsonPath('specific_users.0.name', $this->targetUser->name)
            ->assertJsonMissingPath('specific_users.0.email')
            ->assertJsonMissingPath('specific_users.0.created')
            ->assertJsonMissingPath('created');
    }

    #[Test]
    public function test_store_creates_shares_for_multiple_users_in_one_request()
    {
        $this->actingAs($this->owner, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts/' . $this->twofaccount->id . '/shares', [
                'user_ids' => [$this->targetUser->id, $this->thirdUser->id],
            ])
            ->assertCreated()
            ->assertJsonPath('twofaccount_id', $this->twofaccount->id)
            ->assertJsonPath('is_shared_with_all', false)
            ->assertJsonPath('specific_users.0.id', $this->targetUser->id)
            ->assertJsonPath('specific_users.0.name', $this->targetUser->name)
            ->assertJsonPath('specific_users.1.id', $this->thirdUser->id)
            ->assertJsonPath('specific_users.1.name', $this->thirdUser->name)
            ->assertJsonMissingPath('specific_users.0.email')
            ->assertJsonMissingPath('specific_users.0.created')
            ->assertJsonMissingPath('specific_users.1.email')
            ->assertJsonMissingPath('specific_users.1.created')
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
    public function test_store_sends_share_notification_to_target_users() : void
    {
        $this->actingAs($this->owner, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts/' . $this->twofaccount->id . '/shares', [
                'user_ids' => [$this->targetUser->id, $this->thirdUser->id],
            ])
            ->assertCreated();

        Event::assertDispatched(TwoFAccountShared::class, function (TwoFAccountShared $event) {
            $recipientIds = $event->recipients->pluck('id')->all();
            sort($recipientIds);

            return $recipientIds === [$this->targetUser->id, $this->thirdUser->id];
        });
    }

    #[Test]
    public function test_store_returns_user_ids_sorted_by_id()
    {
        $sortedIds = [$this->targetUser->id, $this->thirdUser->id];
        sort($sortedIds);

        $this->actingAs($this->owner, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts/' . $this->twofaccount->id . '/shares', [
                'user_ids' => [$this->thirdUser->id, $this->targetUser->id],
            ])
            ->assertCreated()
            ->assertJsonPath('specific_users.0.id', $sortedIds[0])
            ->assertJsonPath('specific_users.1.id', $sortedIds[1]);
    }

    #[Test]
    public function test_store_returns_conflict_when_shared_with_all_users()
    {
        Settings::set('enableAllUsersSharingScope', true);

        TwoFAccountShare::create([
            'twofaccount_id' => $this->twofaccount->id,
            'shared_with_user_id' => null,
            'scope' => TwoFAccountShare::SCOPE_ALL_USERS,
            'created_by_user_id' => $this->owner->id,
        ]);

        $this->actingAs($this->owner, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts/' . $this->twofaccount->id . '/shares', [
                'user_ids' => [$this->targetUser->id, $this->thirdUser->id],
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

        Settings::set('enableAllUsersSharingScope', false);
    }

    #[Test]
    public function test_store_rejects_sharing_with_owner()
    {
        $this->actingAs($this->owner, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts/' . $this->twofaccount->id . '/shares', [
                'user_ids' => [$this->owner->id],
            ])
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'reason',
            ]);
    }

    #[Test]
    public function test_store_rejects_mixed_ids_when_owner_is_included_and_creates_nothing()
    {
        $this->actingAs($this->owner, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts/' . $this->twofaccount->id . '/shares', [
                'user_ids' => [$this->owner->id, $this->targetUser->id],
            ])
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'reason',
            ]);

        $this->assertDatabaseMissing('twofaccount_shares', [
            'twofaccount_id' => $this->twofaccount->id,
            'shared_with_user_id' => $this->targetUser->id,
            'scope' => TwoFAccountShare::SCOPE_USER,
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
            ->assertJsonPath('twofaccount_id', $this->twofaccount->id)
            ->assertJsonPath('is_shared_with_all', false)
            ->assertJsonPath('specific_users.0.id', $this->targetUser->id)
            ->assertJsonPath('specific_users.0.name', $this->targetUser->name)
            ->assertJsonMissingPath('specific_users.0.email')
            ->assertJsonMissingPath('specific_users.0.created')
            ->assertJsonMissingPath('created');
    }

    #[Test]
    public function test_store_prefers_ids_over_legacy_user_id_when_both_are_provided()
    {
        $this->actingAs($this->owner, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts/' . $this->twofaccount->id . '/shares', [
                'user_ids' => [$this->thirdUser->id],
                'user_id' => $this->targetUser->id,
            ])
            ->assertCreated()
            ->assertJsonPath('specific_users.0.id', $this->thirdUser->id);

        $this->assertDatabaseHas('twofaccount_shares', [
            'twofaccount_id' => $this->twofaccount->id,
            'shared_with_user_id' => $this->thirdUser->id,
            'scope' => TwoFAccountShare::SCOPE_USER,
        ]);

        $this->assertDatabaseMissing('twofaccount_shares', [
            'twofaccount_id' => $this->twofaccount->id,
            'shared_with_user_id' => $this->targetUser->id,
            'scope' => TwoFAccountShare::SCOPE_USER,
        ]);
    }

    #[Test]
    public function test_store_with_empty_ids_returns_validation_error()
    {
        $this->actingAs($this->owner, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts/' . $this->twofaccount->id . '/shares', [
                'user_ids' => [],
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrorFor('user_ids');
    }

    #[Test]
    public function test_store_with_duplicate_ids_returns_validation_error()
    {
        $this->actingAs($this->owner, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts/' . $this->twofaccount->id . '/shares', [
                'user_ids' => [$this->targetUser->id, $this->targetUser->id],
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrorFor('user_ids.1');
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
    public function test_destroy_returns_no_content_when_user_share_does_not_exist()
    {
        $this->actingAs($this->owner, 'api-guard')
            ->json('DELETE', '/api/v1/twofaccounts/' . $this->twofaccount->id . '/shares/' . $this->targetUser->id)
            ->assertNoContent();
    }

    #[Test]
    public function test_destroy_returns_conflict_when_shared_with_all_users()
    {
        Settings::set('enableAllUsersSharingScope', true);

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

        Settings::set('enableAllUsersSharingScope', false);
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
    public function test_destroy_all_users_sends_revoke_notification_to_shared_users() : void
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

        Event::assertDispatched(TwoFAccountShareRevoked::class, function (TwoFAccountShareRevoked $event) {
            $recipientIds = $event->recipients->pluck('id')->all();
            sort($recipientIds);

            return $recipientIds === [$this->targetUser->id, $this->thirdUser->id];
        });
    }

    #[Test]
    public function test_destroy_all_users_returns_no_content_when_no_explicit_share_exists()
    {
        $this->actingAs($this->owner, 'api-guard')
            ->json('DELETE', '/api/v1/twofaccounts/' . $this->twofaccount->id . '/shares')
            ->assertNoContent();
    }

    #[Test]
    public function test_destroy_all_users_revokes_explicit_and_global_shares_when_both_exist()
    {
        Settings::set('enableAllUsersSharingScope', true);

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
            ->assertNoContent();

        $this->assertDatabaseMissing('twofaccount_shares', [
            'twofaccount_id' => $this->twofaccount->id,
            'shared_with_user_id' => $this->targetUser->id,
            'scope' => TwoFAccountShare::SCOPE_USER,
        ]);

        $this->assertDatabaseMissing('twofaccount_shares', [
            'twofaccount_id' => $this->twofaccount->id,
            'shared_with_user_id' => null,
            'scope' => TwoFAccountShare::SCOPE_ALL_USERS,
        ]);

        Settings::set('enableAllUsersSharingScope', false);
    }

    #[Test]
    public function test_destroy_all_users_revokes_explicit_shares_only_when_share_with_all_is_disabled()
    {
        Settings::set('enableAllUsersSharingScope', false);

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
            ->assertNoContent();

        $this->assertDatabaseMissing('twofaccount_shares', [
            'twofaccount_id' => $this->twofaccount->id,
            'shared_with_user_id' => $this->targetUser->id,
            'scope' => TwoFAccountShare::SCOPE_USER,
        ]);

        $this->assertDatabaseHas('twofaccount_shares', [
            'twofaccount_id' => $this->twofaccount->id,
            'shared_with_user_id' => null,
            'scope' => TwoFAccountShare::SCOPE_ALL_USERS,
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
    public function test_share_all_and_unshare_all_toggle_the_share()
    {
        Settings::set('enableAllUsersSharingScope', true);

        $this->actingAs($this->owner, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts/' . $this->twofaccount->id . '/shares/all')
            ->assertCreated()
            ->assertJsonPath('is_shared_with_all', true)
            ->assertJsonPath('twofaccount_id', $this->twofaccount->id)
            ->assertJsonMissingPath('created');

        $this->assertDatabaseHas('twofaccount_shares', [
            'twofaccount_id' => $this->twofaccount->id,
            'scope' => TwoFAccountShare::SCOPE_ALL_USERS,
            'shared_with_user_id' => null,
        ]);

        $this->actingAs($this->owner, 'api-guard')
            ->json('DELETE', '/api/v1/twofaccounts/' . $this->twofaccount->id . '/shares')
            ->assertNoContent();

        $this->assertDatabaseMissing('twofaccount_shares', [
            'twofaccount_id' => $this->twofaccount->id,
            'scope' => TwoFAccountShare::SCOPE_ALL_USERS,
        ]);

        Settings::set('enableAllUsersSharingScope', false);
    }

    #[Test]
    public function test_share_all_sends_shared_notification_to_all_users_except_owner() : void
    {
        Settings::set('enableAllUsersSharingScope', true);

        $this->actingAs($this->owner, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts/' . $this->twofaccount->id . '/shares/all')
            ->assertCreated();

        Event::assertDispatched(TwoFAccountShared::class, function (TwoFAccountShared $event) {
            $recipientIds = $event->recipients->pluck('id')->all();
            sort($recipientIds);

            return $event->scope === TwoFAccountShare::SCOPE_ALL_USERS
                && $recipientIds === [$this->targetUser->id, $this->thirdUser->id];
        });

        Settings::set('enableAllUsersSharingScope', false);
    }

    #[Test]
    public function test_share_all_revokes_existing_user_shares()
    {
        Settings::set('enableAllUsersSharingScope', true);

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
            ->assertJsonPath('twofaccount_id', $this->twofaccount->id)
            ->assertJsonMissingPath('created');

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

        Settings::set('enableAllUsersSharingScope', false);
    }

    #[Test]
    public function test_share_all_returns_ok_when_already_shared_with_all_users()
    {
        Settings::set('enableAllUsersSharingScope', true);

        TwoFAccountShare::create([
            'twofaccount_id' => $this->twofaccount->id,
            'shared_with_user_id' => null,
            'scope' => TwoFAccountShare::SCOPE_ALL_USERS,
            'created_by_user_id' => $this->owner->id,
        ]);

        $this->actingAs($this->owner, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts/' . $this->twofaccount->id . '/shares/all')
            ->assertOk()
            ->assertJsonPath('is_shared_with_all', true)
            ->assertJsonPath('twofaccount_id', $this->twofaccount->id)
            ->assertJsonMissingPath('created');

        Settings::set('enableAllUsersSharingScope', false);
    }

    #[Test]
    public function test_share_all_when_already_applied_cleans_up_stale_explicit_user_shares()
    {
        Settings::set('enableAllUsersSharingScope', true);

        TwoFAccountShare::create([
            'twofaccount_id' => $this->twofaccount->id,
            'shared_with_user_id' => null,
            'scope' => TwoFAccountShare::SCOPE_ALL_USERS,
            'created_by_user_id' => $this->owner->id,
        ]);

        TwoFAccountShare::create([
            'twofaccount_id' => $this->twofaccount->id,
            'shared_with_user_id' => $this->targetUser->id,
            'scope' => TwoFAccountShare::SCOPE_USER,
            'created_by_user_id' => $this->owner->id,
        ]);

        $this->actingAs($this->owner, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts/' . $this->twofaccount->id . '/shares/all')
            ->assertOk();

        $this->assertDatabaseMissing('twofaccount_shares', [
            'twofaccount_id' => $this->twofaccount->id,
            'scope' => TwoFAccountShare::SCOPE_USER,
            'shared_with_user_id' => $this->targetUser->id,
        ]);

        $this->assertDatabaseHas('twofaccount_shares', [
            'twofaccount_id' => $this->twofaccount->id,
            'scope' => TwoFAccountShare::SCOPE_ALL_USERS,
            'shared_with_user_id' => null,
        ]);

        Settings::set('enableAllUsersSharingScope', false);
    }

    #[Test]
    public function test_destroy_all_returns_no_content_when_share_all_is_not_applied()
    {
        $this->actingAs($this->owner, 'api-guard')
            ->json('DELETE', '/api/v1/twofaccounts/' . $this->twofaccount->id . '/shares')
            ->assertNoContent();
    }

    #[Test]
    public function test_destroy_all_returns_no_content_when_called_twice()
    {
        Settings::set('enableAllUsersSharingScope', true);

        $this->actingAs($this->owner, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts/' . $this->twofaccount->id . '/shares/all')
            ->assertStatus(201);

        $this->actingAs($this->owner, 'api-guard')
            ->json('DELETE', '/api/v1/twofaccounts/' . $this->twofaccount->id . '/shares')
            ->assertNoContent();

        $this->actingAs($this->owner, 'api-guard')
            ->json('DELETE', '/api/v1/twofaccounts/' . $this->twofaccount->id . '/shares')
            ->assertNoContent();

        Settings::set('enableAllUsersSharingScope', false);
    }

    #[Test]
    public function test_destroy_all_for_non_owner_is_forbidden_when_account_is_shared_with_all()
    {
        Settings::set('enableAllUsersSharingScope', true);

        TwoFAccountShare::create([
            'twofaccount_id' => $this->twofaccount->id,
            'shared_with_user_id' => null,
            'scope' => TwoFAccountShare::SCOPE_ALL_USERS,
            'created_by_user_id' => $this->owner->id,
        ]);

        $this->actingAs($this->targetUser, 'api-guard')
            ->json('DELETE', '/api/v1/twofaccounts/' . $this->twofaccount->id . '/shares')
            ->assertForbidden();

        Settings::set('enableAllUsersSharingScope', false);
    }

    #[Test]
    public function test_recipients_returns_other_users_and_excludes_authenticated_user() : void
    {
        TwoFAccountShare::create([
            'twofaccount_id' => $this->twofaccount->id,
            'shared_with_user_id' => $this->targetUser->id,
            'scope' => TwoFAccountShare::SCOPE_USER,
            'created_by_user_id' => $this->owner->id,
        ]);

        $response = $this->actingAs($this->owner, 'api-guard')
            ->json('GET', '/api/v1/twofaccounts/' . $this->twofaccount->id . '/recipients')
            ->assertOk()
            ->assertJsonCount(2)
            ->assertJsonMissing(['id' => $this->owner->id]);

        $recipients = collect($response->json())->keyBy('id');

        $this->assertTrue($recipients->has($this->targetUser->id));
        $this->assertTrue($recipients->has($this->thirdUser->id));
        $this->assertTrue($recipients->get($this->targetUser->id)['is_shared_with']);
        $this->assertFalse($recipients->get($this->thirdUser->id)['is_shared_with']);
        $this->assertArrayHasKey('is_shared_since', $recipients->get($this->targetUser->id));
        $this->assertArrayNotHasKey('is_shared_since', $recipients->get($this->thirdUser->id));
        $this->assertArrayNotHasKey('email', $recipients->get($this->targetUser->id));
        $this->assertArrayNotHasKey('email', $recipients->get($this->thirdUser->id));
    }

    #[Test]
    #[DataProvider('provideBadRecipientsKeyword')]
    public function test_recipients_returns_empty_if_no_input(string $filter) : void
    {
        $this->actingAs($this->owner, 'api-guard')
            ->json('GET', '/api/v1/twofaccounts/' . $this->twofaccount->id . '/recipients?filter[nameOrEmail]=' . $filter)
            ->assertOk()
            ->assertJsonCount(0);
    }

    /**
     * Data provider for invalid recipient filter keywords
     */
    public static function provideBadRecipientsKeyword() : array
    {
        return [
            [''],
            ['&'],
            ['&é'],
        ];
    }

    #[Test]
    public function test_recipients_returns_empty_if_no_match() : void
    {
        $nameFilter = 'NonExistingName';

        $this->actingAs($this->owner, 'api-guard')
            ->json('GET', '/api/v1/twofaccounts/' . $this->twofaccount->id . '/recipients?filter[nameOrEmail]=' . $nameFilter)
            ->assertOk()
            ->assertJsonCount(0);
    }

    #[Test]
    public function test_recipients_supports_partial_name_filtering() : void
    {
        $nameFilter = substr($this->targetUser->name, 0, 3);

        $this->actingAs($this->owner, 'api-guard')
            ->json('GET', '/api/v1/twofaccounts/' . $this->twofaccount->id . '/recipients?filter[nameOrEmail]=' . $nameFilter)
            ->assertOk()
            ->assertJsonCount(1)
            ->assertJsonPath('0.id', $this->targetUser->id)
            ->assertJsonPath('0.name', $this->targetUser->name);
    }

    #[Test]
    public function test_recipients_supports_exact_email_filtering() : void
    {
        $emailFilter = $this->targetUser->email;

        $this->actingAs($this->owner, 'api-guard')
            ->json('GET', '/api/v1/twofaccounts/' . $this->twofaccount->id . '/recipients?filter[nameOrEmail]=' . $emailFilter)
            ->assertOk()
            ->assertJsonCount(1)
            ->assertJsonPath('0.id', $this->targetUser->id)
            ->assertJsonPath('0.name', $this->targetUser->name);
    }

    #[Test]
    public function test_recipients_does_not_support_partial_email_filtering() : void
    {
        $emailFilter = substr($this->targetUser->email, 0, 3);

        $this->actingAs($this->owner, 'api-guard')
            ->json('GET', '/api/v1/twofaccounts/' . $this->twofaccount->id . '/recipients?filter[nameOrEmail]=' . $emailFilter)
            ->assertOk()
            ->assertJsonCount(0);
    }

    #[Test]
    public function test_recipients_for_non_owner_is_allowed() : void
    {
        $this->actingAs($this->targetUser, 'api-guard')
            ->json('GET', '/api/v1/twofaccounts/' . $this->twofaccount->id . '/recipients')
            ->assertOk();
    }

    #[Test]
    public function test_recipients_returns_is_shared_since_with_timezoned_format() : void
    {
        $timezone                             = 'Europe/Paris';
        $this->owner['preferences->timezone'] = $timezone;
        $this->owner->save();

        $sharedAt = Carbon::create(2026, 1, 15, 10, 30, 45, 'UTC');

        $share = TwoFAccountShare::create([
            'twofaccount_id' => $this->twofaccount->id,
            'shared_with_user_id' => $this->targetUser->id,
            'scope' => TwoFAccountShare::SCOPE_USER,
            'created_by_user_id' => $this->owner->id,
        ]);

        DB::table('twofaccount_shares')
            ->where('id', $share->id)
            ->update([
                'created_at' => $sharedAt,
                'updated_at' => $sharedAt,
            ]);

        $expectedSharedSince = Carbon::parse($sharedAt)->tz($timezone)->toDayDateTimeString();

        $response = $this->actingAs($this->owner, 'api-guard')
            ->json('GET', '/api/v1/twofaccounts/' . $this->twofaccount->id . '/recipients')
            ->assertOk();

        $recipients = collect($response->json())->keyBy('id');

        $this->assertSame($expectedSharedSince, $recipients->get($this->targetUser->id)['is_shared_since']);
    }

    #[Test]
    #[DataProvider('provideSharingRoutes')]
    public function test_sharing_routes_return_403_when_sharing_feature_is_disabled(string $method, string $uri, array $payload = []) : void
    {
        Settings::set('enableSharing', false);

        $uri = str_replace(
            ['{accountId}', '{userId}'],
            [(string) $this->twofaccount->id, (string) $this->targetUser->id],
            $uri
        );

        if (isset($payload['ids'])) {
            $payload['ids'] = [$this->targetUser->id];
        }

        $response = $this->actingAs($this->owner, 'api-guard')
            ->json($method, $uri, $payload);

        $response
            ->assertForbidden()
            ->assertJsonPath('message', __('error.sharing_is_disabled'));
    }

    public static function provideSharingRoutes() : array
    {
        return [
            ['GET', '/api/v1/twofaccounts/{accountId}/shares'],
            ['POST', '/api/v1/twofaccounts/{accountId}/shares', ['ids' => [0]]],
            ['DELETE', '/api/v1/twofaccounts/{accountId}/shares/{userId}'],
            ['DELETE', '/api/v1/twofaccounts/{accountId}/shares'],
            ['POST', '/api/v1/twofaccounts/{accountId}/shares/all'],
            ['GET', '/api/v1/twofaccounts/{accountId}/recipients'],
        ];
    }

    #[Test]
    public function test_share_all_route_returns_403_when_all_users_scope_is_disabled() : void
    {
        Settings::set('enableAllUsersSharingScope', false);

        $this->actingAs($this->owner, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts/' . $this->twofaccount->id . '/shares/all')
            ->assertForbidden()
            ->assertJsonPath('message', __('error.all_users_sharing_scope_is_disabled'));

        $this->assertDatabaseMissing('twofaccount_shares', [
            'twofaccount_id' => $this->twofaccount->id,
            'scope' => TwoFAccountShare::SCOPE_ALL_USERS,
            'shared_with_user_id' => null,
        ]);
    }
}
