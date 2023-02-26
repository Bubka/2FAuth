<?php

namespace Tests\Feature\Services;

use App\Facades\Groups;
use App\Models\Group;
use App\Models\TwoFAccount;
use App\Models\User;
use App\Policies\GroupPolicy;
use Illuminate\Auth\Access\AuthorizationException;
use Mockery\MockInterface;
use Tests\FeatureTestCase;

/**
 * @covers \App\Services\GroupService
 * @covers \App\Facades\Groups
 */
class GroupServiceTest extends FeatureTestCase
{
    /**
     * @var \App\Models\User|\Illuminate\Contracts\Auth\Authenticatable
     */
    protected $user;

    /**
     * @var \App\Models\User|\Illuminate\Contracts\Auth\Authenticatable
     */
    protected $admin;

    /**
     * App\Models\Group $groupOne, $groupTwo
     */
    protected $groupOne;

    protected $groupTwo;

    /**
     * App\Models\Group $twofaccountOne, $twofaccountTwo
     */
    protected $twofaccountOne;

    protected $twofaccountTwo;

    private const NEW_GROUP_NAME = 'MyNewGroup';

    /**
     * @test
     */
    public function setUp() : void
    {
        parent::setUp();

        $this->user  = User::factory()->create();
        $this->admin = User::factory()->administrator()->create();

        $this->groupOne = Group::factory()->for($this->user)->create();
        $this->groupTwo = Group::factory()->for($this->user)->create();

        Group::factory()->count(3)->for($this->admin)->create();

        $this->twofaccountOne = TwoFAccount::factory()->for($this->user)->create([
            'group_id' => $this->groupOne->id,
        ]);
        $this->twofaccountTwo = TwoFAccount::factory()->for($this->user)->create([
            'group_id' => $this->groupTwo->id,
        ]);

        TwoFAccount::factory()->for($this->admin)->create();
    }

    /**
     * @test
     */
    public function test_getAll_returns_pseudo_group_on_top_of_user_groups_only()
    {
        $groups = Groups::getAll($this->user);

        $this->assertCount(3, $groups);
        $this->assertEquals(0, $groups->first()->id);
        $this->assertEquals(__('commons.all'), $groups->first()->name);
        $this->assertEquals($this->groupOne->user_id, $groups[1]->user_id);
        $this->assertEquals($this->groupTwo->user_id, $groups[2]->user_id);
    }

    /**
     * @test
     */
    public function test_getAll_returns_groups_with_count()
    {
        $groups = Groups::getAll($this->user);

        $this->assertEquals(2, $groups->first()->twofaccounts_count);
        $this->assertEquals(1, $groups[1]->twofaccounts_count);
        $this->assertEquals(1, $groups[2]->twofaccounts_count);
    }

    /**
     * @test
     */
    public function test_create_persists_and_returns_created_group()
    {
        $newGroup = Groups::create(['name' => self::NEW_GROUP_NAME], $this->user);

        $this->assertDatabaseHas('groups', [
            'name'    => self::NEW_GROUP_NAME,
            'user_id' => $this->user->id,
        ]);
        $this->assertInstanceOf(Group::class, $newGroup);
        $this->assertEquals(self::NEW_GROUP_NAME, $newGroup->name);
    }

    /**
     * @test
     */
    public function test_create_authorization()
    {
        $this->mock(GroupPolicy::class, function (MockInterface $groupPolicy) {
            $groupPolicy->shouldReceive('create')
                ->andReturn(false);
        });

        $this->expectException(AuthorizationException::class);

        Groups::create(['name' => 'lorem'], $this->user);
    }

    /**
     * @test
     */
    public function test_update_persists_and_returns_updated_group()
    {
        $this->groupOne = Groups::update($this->groupOne, ['name' => self::NEW_GROUP_NAME], $this->user);

        $this->assertDatabaseHas('groups', ['name' => self::NEW_GROUP_NAME]);
        $this->assertInstanceOf(Group::class, $this->groupOne);
        $this->assertEquals(self::NEW_GROUP_NAME, $this->groupOne->name);
    }

    /**
     * @test
     */
    public function test_update_fails_when_user_does_not_own_the_group()
    {
        $this->expectException(AuthorizationException::class);

        Groups::update($this->groupOne, ['name' => self::NEW_GROUP_NAME], $this->admin);
    }

    /**
     * @test
     */
    public function test_delete_a_groupId_clear_db_and_returns_deleted_count()
    {
        $deleted = Groups::delete($this->groupOne->id, $this->user);

        $this->assertDatabaseMissing('groups', ['id' => $this->groupOne->id]);
        $this->assertEquals(1, $deleted);
    }

    /**
     * @test
     */
    public function test_delete_an_array_of_ids_clear_db_and_returns_deleted_count()
    {
        $deleted = Groups::delete([$this->groupOne->id, $this->groupTwo->id], $this->user);

        $this->assertDatabaseMissing('groups', ['id' => $this->groupOne->id]);
        $this->assertDatabaseMissing('groups', ['id' => $this->groupTwo->id]);
        $this->assertEquals(2, $deleted);
    }

    /**
     * @test
     */
    public function test_delete_missing_id_does_not_fail_and_returns_deleted_count()
    {
        $this->assertDatabaseMissing('groups', ['id' => 1000]);

        $deleted = Groups::delete([$this->groupOne->id, 1000], $this->user);

        $this->assertDatabaseMissing('groups', ['id' => $this->groupOne->id]);
        $this->assertEquals(1, $deleted);
    }

    /**
     * @test
     */
    public function test_delete_default_group_reset_defaultGroup_preference()
    {
        $this->user['preferences->defaultGroup'] = $this->groupOne->id;
        $this->user->save();

        Groups::delete($this->groupOne->id, $this->user);

        $this->user->refresh();
        $this->assertEquals(0, $this->user->preferences['defaultGroup']);
    }

    /**
     * @test
     */
    public function test_delete_active_group_reset_activeGroup_preference()
    {
        $this->user['preferences->rememberActiveGroup'] = true;
        $this->user['preferences->activeGroup']         = $this->groupOne->id;
        $this->user->save();

        Groups::delete($this->groupOne->id, $this->user);

        $this->user->refresh();
        $this->assertEquals(0, $this->user->preferences['activeGroup']);
    }

    /**
     * @test
     */
    public function test_delete_fails_when_user_does_not_own_one_of_the_groups()
    {
        $this->expectException(AuthorizationException::class);

        Groups::delete($this->groupOne->id, $this->admin);
    }

    /**
     * @test
     */
    public function test_assign_a_twofaccountid_to_a_specified_group_persists_the_relation()
    {
        Groups::assign($this->twofaccountOne->id, $this->user, $this->groupTwo);

        $this->assertDatabaseHas('twofaccounts', [
            'id'       => $this->twofaccountOne->id,
            'group_id' => $this->groupTwo->id,
        ]);
    }

    /**
     * @test
     */
    public function test_assign_multiple_twofaccountid_to_a_specified_group_persists_the_relation()
    {
        Groups::assign([$this->twofaccountOne->id, $this->twofaccountTwo->id], $this->user, $this->groupTwo);

        $this->assertDatabaseHas('twofaccounts', [
            'id'       => $this->twofaccountOne->id,
            'group_id' => $this->groupTwo->id,
        ]);
        $this->assertDatabaseHas('twofaccounts', [
            'id'       => $this->twofaccountTwo->id,
            'group_id' => $this->groupTwo->id,
        ]);
    }

    /**
     * @test
     */
    public function test_assign_a_twofaccountid_to_no_group_assigns_to_default_group()
    {
        $this->user['preferences->defaultGroup'] = $this->groupTwo->id;
        $this->user->save();

        Groups::assign($this->twofaccountOne->id, $this->user);

        $this->assertDatabaseHas('twofaccounts', [
            'id'       => $this->twofaccountOne->id,
            'group_id' => $this->groupTwo->id,
        ]);
    }

    /**
     * @test
     */
    public function test_assign_a_twofaccountid_to_no_group_assigns_to_active_group()
    {
        $this->user['preferences->defaultGroup'] = -1;
        $this->user['preferences->activeGroup']  = $this->groupTwo->id;
        $this->user->save();

        Groups::assign($this->twofaccountOne->id, $this->user);

        $this->assertDatabaseHas('twofaccounts', [
            'id'       => $this->twofaccountOne->id,
            'group_id' => $this->groupTwo->id,
        ]);
    }

    /**
     * @test
     */
    public function test_assign_a_twofaccountid_to_missing_active_group_returns_not_found()
    {
        $orginalGroup = $this->twofaccountOne->group_id;

        $this->user['preferences->defaultGroup'] = -1;
        $this->user['preferences->activeGroup']  = 1000;
        $this->user->save();

        Groups::assign($this->twofaccountOne->id, $this->user);

        $this->assertDatabaseHas('twofaccounts', [
            'id'       => $this->twofaccountOne->id,
            'group_id' => $orginalGroup,
        ]);
    }

    /**
     * @test
     */
    public function test_assign_fails_when_user_does_not_own_the_group()
    {
        $this->expectException(AuthorizationException::class);

        Groups::assign($this->twofaccountOne->id, $this->user, $this->admin->groups()->first());
    }

    /**
     * @test
     */
    public function test_assign_fails_when_user_does_not_own_one_of_the_accounts()
    {
        $this->expectException(AuthorizationException::class);

        Groups::assign([$this->twofaccountOne->id, $this->admin->twofaccounts()->first()->id], $this->user, $this->groupTwo);
    }
}
