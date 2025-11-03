<?php

namespace Tests\Api\v1\Controllers;

use App\Api\v1\Controllers\GroupController;
use App\Api\v1\Resources\GroupResource;
use App\Listeners\DissociateTwofaccountFromGroup;
use App\Listeners\ResetUsersPreference;
use App\Models\Group;
use App\Models\TwoFAccount;
use App\Models\User;
use App\Policies\GroupPolicy;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;

#[CoversClass(GroupController::class)]
#[CoversClass(GroupResource::class)]
#[CoversClass(ResetUsersPreference::class)]
#[CoversClass(GroupPolicy::class)]
#[CoversClass(Group::class)]
#[CoversClass(DissociateTwofaccountFromGroup::class)]
class GroupControllerTest extends FeatureTestCase
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

    private const NEW_GROUP_NAME = 'MyNewGroup';

    protected function setUp() : void
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

    #[Test]
    public function test_index_returns_user_groups_only_with_pseudo_group()
    {
        $this->actingAs($this->user, 'api-guard')
            ->json('GET', '/api/v1/groups')
            ->assertOk()
            ->assertExactJson([
                '0' => [
                    'id'                 => 0,
                    'name'               => 'All',
                    'twofaccounts_count' => 2,
                ],
                '1' => [
                    'id'                 => $this->userGroupA->id,
                    'name'               => $this->userGroupA->name,
                    'twofaccounts_count' => 2,
                ],
                '2' => [
                    'id'                 => $this->userGroupB->id,
                    'name'               => $this->userGroupB->name,
                    'twofaccounts_count' => 0,
                ],
            ]);
    }

    #[Test]
    public function test_orphan_groups_are_reassign_to_the_only_user()
    {
        config(['auth.defaults.guard' => 'reverse-proxy-guard']);

        $this->anotherUser->delete();
        $this->userGroupA->user_id = null;
        $this->userGroupA->save();

        $this->assertCount(1, User::all());
        $this->assertNull($this->userGroupA->user_id);

        $this->actingAs($this->user, 'reverse-proxy-guard')
            ->json('GET', '/api/v1/groups')
            ->assertOk();

        $this->userGroupA->refresh();

        $this->assertNotNull($this->userGroupA->user_id);
    }

    #[Test]
    public function test_store_returns_created_group_resource()
    {
        $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/groups', [
                'name' => self::NEW_GROUP_NAME,
            ])
            ->assertCreated()
            ->assertJsonFragment([
                'name'               => self::NEW_GROUP_NAME,
                'twofaccounts_count' => 0,
            ]);

        $this->assertDatabaseHas('groups', [
            'name'    => self::NEW_GROUP_NAME,
            'user_id' => $this->user->id,
        ]);
    }

    #[Test]
    public function test_store_with_existing_group_name_returns_validation_error()
    {
        $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/groups', [
                'name' => $this->userGroupA->name,
            ])
            ->assertStatus(422);
    }

    #[Test]
    public function test_store_with_all_group_name_returns_validation_error()
    {
        $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/groups', [
                'name' => __('label.all'),
            ])
            ->assertStatus(422);
    }

    #[Test]
    public function test_store_invalid_data_returns_validation_error()
    {
        $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/groups', [
                'name' => null,
            ])
            ->assertStatus(422);
    }

    #[Test]
    public function test_show_returns_group_resource()
    {
        $group = Group::factory()->for($this->user)->create([
            'name' => 'My group',
        ]);

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('GET', '/api/v1/groups/' . $group->id)
            ->assertOk()
            ->assertJsonFragment([
                'name'               => 'My group',
                'twofaccounts_count' => 0,
            ]);
    }

    #[Test]
    public function test_show_missing_group_returns_not_found()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('GET', '/api/v1/groups/1000')
            ->assertNotFound()
            ->assertJsonStructure([
                'message',
            ]);
    }

    #[Test]
    public function test_show_group_of_another_user_is_forbidden()
    {
        $response = $this->actingAs($this->anotherUser, 'api-guard')
            ->json('GET', '/api/v1/groups/' . $this->userGroupA->id)
            ->assertForbidden()
            ->assertJsonStructure([
                'message',
            ]);
    }

    #[Test]
    public function test_show_missing_group_with_id_0_returns_the_virtual_all_group_resource()
    {
        $userTwofaccounts = $this->user->twofaccounts;

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('GET', '/api/v1/groups/0')
            ->assertOk()
            ->assertJsonFragment([
                'name'               => __('label.all'),
                'twofaccounts_count' => $userTwofaccounts->count(),
            ]);
    }

    #[Test]
    public function test_update_returns_updated_group_resource()
    {
        $group = Group::factory()->for($this->user)->create();

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('PUT', '/api/v1/groups/' . $group->id, [
                'name' => 'name updated',
            ])
            ->assertOk()
            ->assertJsonFragment([
                'name'               => 'name updated',
                'twofaccounts_count' => 0,
            ]);
    }

    #[Test]
    public function test_update_missing_group_returns_not_found()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('PUT', '/api/v1/groups/1000', [
                'name' => 'testUpdate',
            ])
            ->assertNotFound()
            ->assertJsonStructure([
                'message',
            ]);
    }

    #[Test]
    public function test_update_with_invalid_data_returns_validation_error()
    {
        $group = Group::factory()->for($this->user)->create();

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('PUT', '/api/v1/groups/' . $group->id, [
                'name' => null,
            ])
            ->assertStatus(422);
    }

    #[Test]
    public function test_update_group_of_another_user_is_forbidden()
    {
        $response = $this->actingAs($this->anotherUser, 'api-guard')
            ->json('PUT', '/api/v1/groups/' . $this->userGroupA->id, [
                'name' => 'name updated',
            ])
            ->assertForbidden()
            ->assertJsonStructure([
                'message',
            ]);
    }

    #[Test]
    public function test_assign_accounts_returns_updated_group_resource()
    {
        $group    = Group::factory()->for($this->user)->create();
        $accounts = TwoFAccount::factory()->count(2)->for($this->user)->create();

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/groups/' . $group->id . '/assign', [
                'ids' => [$accounts[0]->id, $accounts[1]->id],
            ])
            ->assertOk()
            ->assertExactJson([
                'id'                 => $group->id,
                'name'               => $group->name,
                'twofaccounts_count' => 2,
            ]);
    }

    #[Test]
    public function test_assign_accounts_to_missing_group_returns_not_found()
    {
        $accounts = TwoFAccount::factory()->count(2)->for($this->user)->create();

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/groups/1000/assign', [
                'ids' => [$accounts[0]->id, $accounts[1]->id],
            ])
            ->assertNotFound()
            ->assertJsonStructure([
                'message',
            ]);
    }

    #[Test]
    public function test_assign_invalid_accounts_returns_validation_error()
    {
        $group    = Group::factory()->for($this->user)->create();
        $accounts = TwoFAccount::factory()->count(2)->for($this->user)->create();

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/groups/' . $group->id . '/assign', [
                'ids' => 1,
            ])
            ->assertStatus(422);
    }

    #[Test]
    public function test_assign_to_group_of_another_user_is_forbidden()
    {
        $response = $this->actingAs($this->anotherUser, 'api-guard')
            ->json('POST', '/api/v1/groups/' . $this->userGroupA->id . '/assign', [
                'ids' => [$this->twofaccountC->id, $this->twofaccountD->id],
            ])
            ->assertForbidden()
            ->assertJsonStructure([
                'message',
            ]);
    }

    #[Test]
    public function test_assign_accounts_of_another_user_is_forbidden()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/groups/' . $this->userGroupA->id . '/assign', [
                'ids' => [$this->twofaccountC->id, $this->twofaccountD->id],
            ])
            ->assertForbidden()
            ->assertJsonStructure([
                'message',
            ]);
    }
    

    #[Test]
    public function test_reorder_returns_success()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/groups/reorder', [
                'orderedIds' => [$this->userGroupB->id, $this->userGroupA->id],
            ])
            ->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'orderedIds'
            ])
            ->assertJsonFragment([
                'orderedIds' => [
                    $this->userGroupB->id,
                    $this->userGroupA->id
                ]
            ]);
    }

    #[Test]
    public function test_reorder_with_invalid_data_returns_validation_error()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/groups/reorder', [
                'orderedIds' => '3,2,1',
            ])
            ->assertStatus(422);
    }

    #[Test]
    public function test_reorder_groups_of_another_user_is_forbidden()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/groups/reorder', [
                'orderedIds' => [$this->anotherUserGroupB->id, $this->anotherUserGroupA->id],
            ])
            ->assertForbidden()
            ->assertJsonStructure([
                'message',
            ]);
    }

    #[Test]
    public function test_accounts_returns_twofaccounts_collection()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('GET', '/api/v1/groups/' . $this->userGroupA->id . '/twofaccounts')
            ->assertOk()
            ->assertJsonCount(2)
            ->assertJsonStructure([
                '*' => [
                    'group_id',
                    'service',
                    'account',
                    'icon',
                    'otp_type',
                    'digits',
                    'algorithm',
                    'period',
                    'counter',
                ],
            ])
            ->assertJsonFragment([
                'account' => $this->twofaccountA->account,
            ])
            ->assertJsonFragment([
                'account' => $this->twofaccountB->account,
            ]);
    }

    #[Test]
    public function test_accounts_returns_twofaccounts_collection_with_secret()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('GET', '/api/v1/groups/' . $this->userGroupA->id . '/twofaccounts?withSecret=1')
            ->assertOk()
            ->assertJsonCount(2)
            ->assertJsonStructure([
                '*' => [
                    'group_id',
                    'service',
                    'account',
                    'icon',
                    'secret',
                    'otp_type',
                    'digits',
                    'algorithm',
                    'period',
                    'counter',
                ],
            ]);
    }

    #[Test]
    public function test_accounts_of_missing_group_returns_not_found()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('GET', '/api/v1/groups/1000/twofaccounts')
            ->assertNotFound()
            ->assertJsonStructure([
                'message',
            ]);
    }

    #[Test]
    public function test_accounts_of_another_user_group_is_forbidden()
    {
        $response = $this->actingAs($this->anotherUser, 'api-guard')
            ->json('GET', '/api/v1/groups/' . $this->userGroupA->id . '/twofaccounts')
            ->assertForbidden()
            ->assertJsonStructure([
                'message',
            ]);
    }

    #[Test]
    public function test_accounts_of_the_all_group_returns_user_twofaccounts_collection()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('GET', '/api/v1/groups/0/twofaccounts')
            ->assertOk()
            ->assertJsonCount(2);
    }

    /**
     * test Group deletion via API
     */
    #[Test]
    public function test_destroy_group_returns_success()
    {
        $group = Group::factory()->for($this->user)->create();

        $this->actingAs($this->user, 'api-guard')
            ->json('DELETE', '/api/v1/groups/' . $group->id)
            ->assertNoContent();
    }

    /**
     * test Group deletion via API
     */
    #[Test]
    public function test_destroy_missing_group_returns_not_found()
    {
        $this->actingAs($this->user, 'api-guard')
            ->json('DELETE', '/api/v1/groups/1000')
            ->assertNotFound()
            ->assertJsonStructure([
                'message',
            ]);
    }

    #[Test]
    public function test_destroy_group_of_another_user_is_forbidden()
    {
        $response = $this->actingAs($this->anotherUser, 'api-guard')
            ->json('DELETE', '/api/v1/groups/' . $this->userGroupA->id)
            ->assertForbidden()
            ->assertJsonStructure([
                'message',
            ]);
    }

    #[Test]
    public function test_destroy_the_all_group_is_forbidden()
    {
        $response = $this->actingAs($this->anotherUser, 'api-guard')
            ->json('DELETE', '/api/v1/groups/0')
            ->assertForbidden()
            ->assertJsonStructure([
                'message',
            ]);
    }

    #[Test]
    public function test_destroy_group_resets_user_preferences()
    {
        // Set the default group to a specific one
        $this->user['preferences->defaultGroup'] = $this->userGroupA->id;
        // Set the active group
        $this->user['preferences->activeGroup'] = $this->userGroupA->id;
        $this->user->save();

        $this->assertEquals($this->userGroupA->id, $this->user->preferences['defaultGroup']);
        $this->assertEquals($this->userGroupA->id, $this->user->preferences['activeGroup']);

        $this->actingAs($this->user, 'api-guard')
            ->json('DELETE', '/api/v1/groups/' . $this->userGroupA->id);

        $this->user->refresh();

        $this->assertEquals(0, $this->user->preferences['defaultGroup']);
        $this->assertEquals(0, $this->user->preferences['activeGroup']);
    }

    #[Test]
    public function test_twofaccount_is_released_on_group_destroy()
    {
        $this->actingAs($this->user, 'api-guard')
            ->json('DELETE', '/api/v1/groups/' . $this->userGroupA->id)
            ->assertNoContent();

        $this->twofaccountA->refresh();
        $this->twofaccountB->refresh();

        $this->assertNull($this->twofaccountA->group_id);
        $this->assertNull($this->twofaccountB->group_id);
    }
}
