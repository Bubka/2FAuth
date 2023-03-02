<?php

namespace Tests\Feature\Services;

use App\Facades\Groups;
use App\Models\Group;
use App\Models\TwoFAccount;
use App\Models\User;
use App\Policies\GroupPolicy;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Collection;
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
    protected $otherUser;

    /**
     * App\Models\Group $groupOne, $groupTwo, $groupThree
     */
    protected $groupOne;

    protected $groupTwo;

    protected $groupThree;

    /**
     * App\Models\Group $twofaccountOne, $twofaccountTwo, $twofaccountThree
     */
    protected $twofaccountOne;

    protected $twofaccountTwo;

    protected $twofaccountThree;

    private const NEW_GROUP_NAME = 'MyNewGroup';

    /**
     * @test
     */
    public function setUp() : void
    {
        parent::setUp();

        $this->user  = User::factory()->create();
        $this->otherUser = User::factory()->create();

        $this->groupOne = Group::factory()->for($this->user)->create();
        $this->groupTwo = Group::factory()->for($this->user)->create();
        $this->groupThree = Group::factory()->for($this->otherUser)->create();

        Group::factory()->count(2)->for($this->otherUser)->create();

        $this->twofaccountOne = TwoFAccount::factory()->for($this->user)->create([
            'group_id' => $this->groupOne->id,
        ]);
        $this->twofaccountTwo = TwoFAccount::factory()->for($this->user)->create([
            'group_id' => $this->groupTwo->id,
        ]);

        $this->twofaccountThree = TwoFAccount::factory()->for($this->otherUser)->create([
            'group_id' => $this->groupThree->id,
        ]);
        TwoFAccount::factory()->for($this->otherUser)->create([
            'group_id' => $this->groupThree->id,
        ]);
    }

    /**
     * @test
     */
    public function test_get_a_user_group_returns_a_group()
    {
        $group = Groups::for($this->user)->get($this->twofaccountOne->id);

        $this->assertInstanceOf(Group::class, $group);
        $this->assertEquals($this->twofaccountOne->id, $group->id);
    }

    /**
     * @test
     */
    public function test_get_multiple_user_group_returns_a_group_collection()
    {
        $groups = Groups::for($this->user)->get([$this->twofaccountOne->id, $this->twofaccountTwo->id]);

        $this->assertInstanceOf(Collection::class, $groups);
        $this->assertCount(2, $groups);
        $this->assertEquals($this->twofaccountOne->id, $groups[0]->id);
        $this->assertEquals($this->twofaccountTwo->id, $groups[1]->id);
    }

    /**
     * @test
     */
    public function test_get_a_missing_group_returns_not_found()
    {
        $this->expectException(\Exception::class);

        $group = Groups::get(1000);
    }

    /**
     * @test
     */
    public function test_get_a_list_of_groups_with_a_missing_group_returns_not_found()
    {
        $this->expectException(\Exception::class);

        $group = Groups::get([$this->twofaccountOne->id, 1000]);
    }

    /**
     * @test
     */
    public function test_user_authorization_to_get()
    {
        $this->expectException(AuthorizationException::class);

        Groups::for($this->otherUser)->get($this->twofaccountOne->id);
    }

    /**
     * @test
     */
    public function test_user_authorization_to_multiple_get()
    {
        $this->expectException(AuthorizationException::class);

        Groups::for($this->otherUser)->get([$this->twofaccountOne->id, $this->twofaccountThree->id]);
    }

    /**
     * @test
     */
    public function test_all_returns_user_groups_only()
    {
        $groups = Groups::for($this->user)->all();

        $this->assertCount(2, $groups);
    }

    /**
     * @test
     */
    public function test_all_returns_all_groups()
    {
        $groups = Groups::all();

        $this->assertCount(5, $groups);
    }

    /**
     * @test
     */
    public function test_withTheAllGroup_returns_pseudo_group_on_top_of_groups()
    {
        $groups = Groups::for($this->user)->withTheAllGroup()->all();

        $this->assertCount(3, $groups);
        $this->assertEquals(0, $groups->first()->id);
        $this->assertEquals(__('commons.all'), $groups->first()->name);
        $this->assertEquals($this->groupOne->user_id, $groups[1]->user_id);
        $this->assertEquals($this->groupTwo->user_id, $groups[2]->user_id);
    }

    /**
     * @test
     */
    public function test_withTheAllGroup_returns_user_groups_with_count()
    {
        $groups = Groups::for($this->user)->withTheAllGroup()->all();

        $this->assertEquals(2, $groups->first()->twofaccounts_count);
        $this->assertEquals(1, $groups[1]->twofaccounts_count);
        $this->assertEquals(1, $groups[2]->twofaccounts_count);
    }

    /**
     * @test
     */
    public function test_withTheAllGroup_returns_all_groups_with_count()
    {
        $groups = Groups::withTheAllGroup()->all();

        $this->assertEquals(4, $groups->first()->twofaccounts_count);
        $this->assertEquals(1, $groups[1]->twofaccounts_count);
        $this->assertEquals(1, $groups[2]->twofaccounts_count);
        $this->assertEquals(2, $groups[3]->twofaccounts_count);
    }

    /**
     * @test
     */
    public function test_create_persists_and_returns_created_group()
    {
        $newGroup = Groups::for($this->user)->create(['name' => self::NEW_GROUP_NAME]);

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
    public function test_user_authorization_to_create()
    {
        $this->mock(GroupPolicy::class, function (MockInterface $groupPolicy) {
            $groupPolicy->shouldReceive('create')
                ->andReturn(false);
        });

        $this->expectException(AuthorizationException::class);

        Groups::for($this->user)->create(['name' => 'lorem'], $this->user);
    }

    /**
     * @test
     */
    public function test_create_without_user_fails()
    {
        $this->expectException(\Exception::class);

        Groups::create(['name' => 'lorem'], $this->user);
    }

    /**
     * @test
     */
    public function test_update_persists_and_returns_updated_group()
    {
        $this->groupOne = Groups::for($this->user)->update($this->groupOne, ['name' => self::NEW_GROUP_NAME]);

        $this->assertDatabaseHas('groups', ['name' => self::NEW_GROUP_NAME]);
        $this->assertInstanceOf(Group::class, $this->groupOne);
        $this->assertEquals(self::NEW_GROUP_NAME, $this->groupOne->name);
    }

    /**
     * @test
     */
    public function test_user_authorization_to_update()
    {
        $this->expectException(AuthorizationException::class);

        Groups::for($this->otherUser)->update($this->groupOne, ['name' => self::NEW_GROUP_NAME]);
    }

    /**
     * @test
     */
    public function test_update_without_user_fails()
    {
        $this->expectException(\Exception::class);

        Groups::update($this->groupOne, ['name' => self::NEW_GROUP_NAME]);
    }

    /**
     * @test
     */
    public function test_delete_a_user_group_clears_db_and_returns_deleted_count()
    {
        $deleted = Groups::for($this->user)->delete($this->groupOne->id);

        $this->assertDatabaseMissing('groups', ['id' => $this->groupOne->id]);
        $this->assertEquals(1, $deleted);
    }

    /**
     * @test
     */
    public function test_delete_multiple_user_groups_clears_db_and_returns_deleted_count()
    {
        $deleted = Groups::for($this->user)->delete([$this->groupOne->id, $this->groupTwo->id]);

        $this->assertDatabaseMissing('groups', ['id' => $this->groupOne->id]);
        $this->assertDatabaseMissing('groups', ['id' => $this->groupTwo->id]);
        $this->assertEquals(2, $deleted);
    }

    /**
     * @test
     */
    public function test_delete_missing_group_does_not_fail_and_returns_deleted_count()
    {
        $this->assertDatabaseMissing('groups', ['id' => 1000]);

        $deleted = Groups::delete([$this->groupOne->id, 1000]);

        $this->assertDatabaseMissing('groups', ['id' => $this->groupOne->id]);
        $this->assertEquals(1, $deleted);
    }

    /**
     * @test
     */
    public function test_delete_default_group_resets_defaultGroup_preference()
    {
        $this->user['preferences->defaultGroup'] = $this->groupOne->id;
        $this->user->save();

        Groups::delete($this->groupOne->id);

        $this->user->refresh();
        $this->assertEquals(0, $this->user->preferences['defaultGroup']);
    }

    /**
     * @test
     */
    public function test_delete_active_group_resets_activeGroup_preference()
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
    public function test_user_authorization_to_delete()
    {
        $this->expectException(AuthorizationException::class);

        Groups::for($this->otherUser)->delete($this->groupOne->id);
    }

    /**
     * @test
     */
    public function test_assign_a_twofaccount_to_a_group_persists_the_relation()
    {
        Groups::assign($this->twofaccountOne->id, $this->groupTwo);

        $this->assertDatabaseHas('twofaccounts', [
            'id'       => $this->twofaccountOne->id,
            'group_id' => $this->groupTwo->id,
        ]);
    }

    /**
     * @test
     */
    public function test_assign_a_twofaccount_to_a_user_group_persists_the_relation()
    {
        Groups::for($this->user)->assign($this->twofaccountOne->id, $this->groupTwo);

        $this->assertDatabaseHas('twofaccounts', [
            'id'       => $this->twofaccountOne->id,
            'group_id' => $this->groupTwo->id,
        ]);
    }

    /**
     * @test
     */
    public function test_assign_multiple_twofaccounts_to_a_user_group_persists_the_relation()
    {
        Groups::for($this->user)->assign([$this->twofaccountOne->id, $this->twofaccountTwo->id], $this->groupTwo);

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
    public function test_assign_a_twofaccount_to_no_group_assigns_to_default_group()
    {
        $this->user['preferences->defaultGroup'] = $this->groupTwo->id;
        $this->user->save();

        Groups::for($this->user)->assign($this->twofaccountOne->id);

        $this->assertDatabaseHas('twofaccounts', [
            'id'       => $this->twofaccountOne->id,
            'group_id' => $this->groupTwo->id,
        ]);
    }

    /**
     * @test
     */
    public function test_assign_a_twofaccount_to_no_group_assigns_to_active_group()
    {
        $this->user['preferences->defaultGroup'] = -1;
        $this->user['preferences->activeGroup']  = $this->groupTwo->id;
        $this->user->save();

        Groups::for($this->user)->assign($this->twofaccountOne->id);

        $this->assertDatabaseHas('twofaccounts', [
            'id'       => $this->twofaccountOne->id,
            'group_id' => $this->groupTwo->id,
        ]);
    }

    /**
     * @test
     */
    public function test_assign_a_twofaccount_to_missing_active_group_returns_not_found()
    {
        $orginalGroup = $this->twofaccountOne->group_id;

        $this->user['preferences->defaultGroup'] = -1;
        $this->user['preferences->activeGroup']  = 1000;
        $this->user->save();

        Groups::for($this->user)->assign($this->twofaccountOne->id);

        $this->assertDatabaseHas('twofaccounts', [
            'id'       => $this->twofaccountOne->id,
            'group_id' => $orginalGroup,
        ]);
    }

    /**
     * @test
     */
    public function test_user_authorization_to_assign_to_group()
    {
        $this->expectException(AuthorizationException::class);

        Groups::for($this->otherUser)->assign($this->twofaccountOne->id, $this->otherUser->groups()->first());
    }

    /**
     * @test
     */
    public function test_user_authorization_to_assign_multiple_to_group()
    {
        $this->expectException(AuthorizationException::class);

        Groups::for($this->otherUser)->assign([$this->twofaccountOne->id, $this->otherUser->twofaccounts()->first()->id], $this->groupTwo);
    }

    /**
     * @test
     */
    public function test_user_authorization_to_assign_an_account()
    {
        $this->expectException(AuthorizationException::class);

        Groups::for($this->user)->assign($this->twofaccountThree->id, $this->user->groups()->first());
    }

    /**
     * @test
     */
    public function test_user_authorization_to_assign_multiple_accounts()
    {
        $this->expectException(AuthorizationException::class);

        Groups::for($this->user)->assign([$this->twofaccountOne->id, $this->twofaccountThree->id], $this->user->groups()->first());
    }
}
