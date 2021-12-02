<?php

namespace Tests\Api\v1\Controllers;

use App\Models\User;
use App\Models\Group;
use Tests\FeatureTestCase;
use App\Models\TwoFAccount;


/**
 * @covers \App\Api\v1\Controllers\GroupController
 * @covers \App\Api\v1\Resources\GroupResource
 */
class GroupControllerTest extends FeatureTestCase
{
    /**
     * @var \App\Models\User
    */
    protected $user;


    /**
     * @test
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }


    /**
     * @test
     */
    public function test_index_returns_group_collection_with_pseudo_group()
    {
        Group::factory()->count(3)->create();

        $response = $this->actingAs($this->user, 'api')
            ->json('GET', '/api/v1/groups')
            ->assertOk()
            ->assertJsonCount(4, $key = null)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'name',
                    'twofaccounts_count',
                ]
            ])
            ->assertJsonFragment([
                'id' => 0,
                'name' => 'All',
                'twofaccounts_count' => 0,
            ]);
    }


    /**
     * @test
     */
    public function test_store_returns_created_group_resource()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/v1/groups', [
                'name' => 'My second group',
            ])
            ->assertCreated()
            ->assertExactJson([
                'id' => 1,
                'name' => 'My second group',
                'twofaccounts_count' => 0,
            ]);
    }


    /**
     * @test
     */
    public function test_store_invalid_data_returns_validation_error()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/v1/groups', [
                'name' => null,
            ])
            ->assertStatus(422);
    }


    /**
     * @test
     */
    public function test_show_returns_group_resource()
    {
        $group = Group::factory()->create([
            'name' => 'My group',
        ]);

        $response = $this->actingAs($this->user, 'api')
            ->json('GET', '/api/v1/groups/' . $group->id)
            ->assertOk()
            ->assertExactJson([
                'id' => 1,
                'name' => 'My group',
                'twofaccounts_count' => 0,
            ]);
    }


    /**
     * @test
     */
    public function test_show_missing_group_returns_not_found()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('GET', '/api/v1/groups/1000')
            ->assertNotFound()
            ->assertJsonStructure([
                'message'
            ]);
    }


    /**
     * @test
     */
    public function test_update_returns_updated_group_resource()
    {
        $group = Group::factory()->create();

        $response = $this->actingAs($this->user, 'api')
            ->json('PUT', '/api/v1/groups/' . $group->id, [
                'name' => 'name updated',
            ])
            ->assertOk()
            ->assertExactJson([
                'id' => 1,
                'name' => 'name updated',
                'twofaccounts_count' => 0,
            ]);
    }


    /**
     * @test
     */
    public function test_update_missing_group_returns_not_found()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('PUT', '/api/v1/groups/1000', [
                'name' => 'testUpdate',
            ])
            ->assertNotFound()
            ->assertJsonStructure([
                'message'
            ]);
    }


    /**
     * @test
     */
    public function test_update_with_invalid_data_returns_validation_error()
    {
        $group = Group::factory()->create();

        $response = $this->actingAs($this->user, 'api')
            ->json('PUT', '/api/v1/groups/' . $group->id, [
                'name' => null,
            ])
            ->assertStatus(422);
    }


    /**
     * @test
     */
    public function test_assign_accounts_returns_updated_group_resource()
    {
        $group = Group::factory()->create();
        $accounts = TwoFAccount::factory()->count(2)->create();

        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/v1/groups/' . $group->id . '/assign', [
                'ids' => [1,2],
            ])
            ->assertOk()
            ->assertExactJson([
                'id' => $group->id,
                'name' => $group->name,
                'twofaccounts_count' => 2,
            ]);
    }


    /**
     * @test
     */
    public function test_assign_accounts_to_missing_group_returns_not_found()
    {
        $accounts = TwoFAccount::factory()->count(2)->create();

        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/v1/groups/1000/assign', [
                'ids' => [1,2],
            ])
            ->assertNotFound()
            ->assertJsonStructure([
                'message'
            ]);
    }


    /**
     * @test
     */
    public function test_assign_invalid_accounts_returns_validation_error()
    {
        $group = Group::factory()->create();
        $accounts = TwoFAccount::factory()->count(2)->create();

        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/v1/groups/' . $group->id . '/assign', [
                'ids' => 1,
            ])
            ->assertStatus(422);
    }


    /**
     * @test
     */
    public function test_get_assigned_accounts_returns_twofaccounts_collection()
    {
        $group = Group::factory()->create();
        $accounts = TwoFAccount::factory()->count(2)->create();

        $assign = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/v1/groups/' . $group->id . '/assign', [
                'ids' => [1,2],
            ]);

        $response = $this->actingAs($this->user, 'api')
            ->json('GET', '/api/v1/groups/' . $group->id . '/twofaccounts')
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
                    'counter'
                ]
            ]);
    }


    /**
     * @test
     */
    public function test_get_assigned_accounts_returns_twofaccounts_collection_with_secret()
    {
        $group = Group::factory()->create();
        $accounts = TwoFAccount::factory()->count(2)->create();

        $assign = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/v1/groups/' . $group->id . '/assign', [
                'ids' => [1,2],
            ]);

        $response = $this->actingAs($this->user, 'api')
            ->json('GET', '/api/v1/groups/' . $group->id . '/twofaccounts?withSecret=1')
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
                    'counter'
                ]
            ]);
    }


    /**
     * @test
     */
    public function test_get_assigned_accounts_of_missing_group_returns_not_found()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('GET', '/api/v1/groups/1000/twofaccounts')
            ->assertNotFound()
            ->assertJsonStructure([
                'message'
            ]);
    }


    /**
     * test Group deletion via API
     *
     * @test
     */
    public function test_destroy_group_returns_success()
    {
        $group = Group::factory()->create();

        $response = $this->actingAs($this->user, 'api')
            ->json('DELETE', '/api/v1/groups/' . $group->id)
            ->assertNoContent();
    }


    /**
     * test Group deletion via API
     *
     * @test
     */
    public function test_destroy_missing_group_returns_not_found()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('DELETE', '/api/v1/groups/1000')
            ->assertNotFound()
            ->assertJsonStructure([
                'message'
            ]);
    }
}
