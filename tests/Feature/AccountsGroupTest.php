<?php

namespace Tests\Feature;

use App\User;
use App\Group;
use Tests\TestCase;
use App\TwoFAccount;

class AccountsGroupTest extends TestCase
{
    /** @var \App\User, \App\TwoFAccount, \App\Group */
    protected $user, $twofaccounts, $group;


    /**
     * @test
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->twofaccounts = factory(Twofaccount::class, 3)->create();
        $this->group = factory(Group::class)->create();
    }


    /**
     * test 2FAccounts creation associated to a user group via API
     *
     * @test
     */
    public function testCreateAccountWhenDefaultGroupIsASpecificOne()
    {

        // Set the default group to the existing one
        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/settings/options', [
                    'defaultGroup' => $this->group->id,
                ])
            ->assertStatus(200);

        // Create the account
        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/twofaccounts', [
                    'service' => 'testCreation',
                    'account' => 'test@example.org',
                    'uri' => 'otpauth://totp/test@test.com?secret=A4GRFHZVRBGY7UIW&issuer=test',
                    'icon' => 'test.png',
                ])
            ->assertStatus(201)
            ->assertJsonFragment([
                'group_id' => $this->group->id
            ]);
    }


    /**
     * test 2FAccounts creation associated to a user group via API
     *
     * @test
     */
    public function testCreateAccountWhenDefaultGroupIsSetToActiveOne()
    {

        // Set the default group as the active one
        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/settings/options', [
                    'defaultGroup' => -1,
                ])
            ->assertStatus(200);

        // Set the active group
        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/settings/options', [
                    'activeGroup' => 1,
                ])
            ->assertStatus(200);

        // Create the account
        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/twofaccounts', [
                    'service' => 'testCreation',
                    'account' => 'test@example.org',
                    'uri' => 'otpauth://totp/test@test.com?secret=A4GRFHZVRBGY7UIW&issuer=test',
                    'icon' => 'test.png',
                ])
            ->assertStatus(201)
            ->assertJsonFragment([
                'group_id' => 1
            ]);
    }


    /**
     * test 2FAccounts creation associated to a user group via API
     *
     * @test
     */
    public function testCreateAccountWhenDefaultIsNoGroup()
    {

        // Set the default group to No group
        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/settings/options', [
                    'defaultGroup' => 0,
                ])
            ->assertStatus(200);

        // Create the account
        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/twofaccounts', [
                    'service' => 'testCreation',
                    'account' => 'test@example.org',
                    'uri' => 'otpauth://totp/test@test.com?secret=A4GRFHZVRBGY7UIW&issuer=test',
                    'icon' => 'test.png',
                ])
            ->assertStatus(201)
            ->assertJsonMissing([
                'group_id' => null
            ]);
    }


    /**
     * test 2FAccounts creation associated to a user group via API
     *
     * @test
     */
    public function testCreateAccountWhenDefaultGroupDoNotExists()
    {

        // Set the default group to a non existing one
        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/settings/options', [
                    'defaultGroup' => 1000,
                ])
            ->assertStatus(200);

        // Create the account
        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/twofaccounts', [
                    'service' => 'testCreation',
                    'account' => 'test@example.org',
                    'uri' => 'otpauth://totp/test@test.com?secret=A4GRFHZVRBGY7UIW&issuer=test',
                    'icon' => 'test.png',
                ])
            ->assertStatus(201)
            ->assertJsonMissing([
                'group_id' => null
            ]);
    }


    /**
     * test 2FAccounts association with a user group via API
     *
     * @test
     */
    public function testMoveAccountsToGroup()
    {
        // We associate all 3 accounts to the user group
        $response = $this->actingAs($this->user, 'api')
            ->json('PATCH', '/api/group/accounts/', [
                    'groupId' => $this->group->id,
                    'accountsIds' => [1,2,3]
                ])
            ->assertJsonFragment([
                'id' => $this->group->id,
                'name' => $this->group->name
            ])
            ->assertStatus(200);

        // test if the accounts have the correct foreign key
        $response = $this->actingAs($this->user, 'api')
            ->json('GET', '/api/twofaccounts/1')
            ->assertJsonFragment([
                'group_id' => $this->group->id
            ]);

        $response = $this->actingAs($this->user, 'api')
            ->json('GET', '/api/twofaccounts/2')
            ->assertJsonFragment([
                'group_id' => $this->group->id
            ]);

        $response = $this->actingAs($this->user, 'api')
            ->json('GET', '/api/twofaccounts/3')
            ->assertJsonFragment([
                'group_id' => $this->group->id
            ]);

        // test the accounts count of the user group
        $response = $this->actingAs($this->user, 'api')
            ->json('GET', '/api/groups')
            ->assertJsonFragment([
                'twofaccounts_count' => 3
            ]
        );
    }


    /**
     * test 2FAccounts association with a missing group via API
     *
     * @test
     */
    public function testMoveAccountsToMissingGroup()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('PATCH', '/api/group/accounts/', [
                    'groupId' => '1000',
                    'accountsIds' => $this->twofaccounts->keys()
                ])
            ->assertStatus(404);
    }


    /**
     * test 2FAccounts association with the pseudo group via API
     *
     * @test
     */
    public function testMoveAccountsToPseudoGroup()
    {

        $response = $this->actingAs($this->user, 'api')
            ->json('PATCH', '/api/group/accounts/', [
                    'groupId' => $this->group->id,
                    'accountsIds' => [1,2,3]
                ]);

        // We associate the first account to the pseudo group
        $response = $this->actingAs($this->user, 'api')
            ->json('PATCH', '/api/group/accounts/', [
                    'groupId' => 0,
                    'accountsIds' => [1]
                ])
            ->assertStatus(200);


        // test if the forein keys are set to NULL
        $response = $this->actingAs($this->user, 'api')
            ->json('GET', '/api/twofaccounts/1')
            ->assertJsonFragment([
                'group_id' => null
            ]);

        // test the accounts count of the group
        $response = $this->actingAs($this->user, 'api')
            ->json('GET', '/api/groups')
            ->assertJsonFragment([
                'twofaccounts_count' => 3, // the 3 accounts for 'all'
                'twofaccounts_count' => 2  // the 2 accounts that remain in the user group
            ]
        );

    }

}