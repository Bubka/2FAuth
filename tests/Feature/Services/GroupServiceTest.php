<?php

namespace Tests\Feature\Services;

use App\Facades\Groups;
use App\Models\Group;
use App\Models\TwoFAccount;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
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

        $this->user      = User::factory()->create();
        $this->otherUser = User::factory()->create();

        $this->groupOne   = Group::factory()->for($this->user)->create();
        $this->groupTwo   = Group::factory()->for($this->user)->create();
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
    }

    /**
     * @test
     */
    public function test_assign_a_twofaccount_to_a_group_persists_the_relation()
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
    public function test_assign_multiple_twofaccounts_to_group_persists_the_relation()
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
    public function test_assign_a_twofaccount_to_no_group_assigns_to_user_default_group()
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
    public function test_assign_a_twofaccount_to_no_group_assigns_to_user_active_group()
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
    public function test_assign_a_twofaccount_to_missing_active_group_returns_not_found()
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
    public function test_user_can_assign_an_account()
    {
        $this->expectException(AuthorizationException::class);

        Groups::assign($this->twofaccountThree->id, $this->user, $this->user->groups()->first());
    }

    /**
     * @test
     */
    public function test_user_can_assign_multiple_accounts()
    {
        $this->expectException(AuthorizationException::class);

        Groups::assign([$this->twofaccountOne->id, $this->twofaccountThree->id], $this->user, $this->user->groups()->first());
    }

    /**
     * @test
     */
    public function test_prependTheAllGroup_add_the_group_on_top_of_groups()
    {
        $groups = Groups::prependTheAllGroup($this->user->groups, $this->user);

        $this->assertCount(3, $groups);
        $this->assertEquals(0, $groups->first()->id);
        $this->assertEquals(2, $groups->first()->twofaccounts_count);
    }
}
