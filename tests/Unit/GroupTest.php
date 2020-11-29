<?php

namespace Tests\Unit;

use App\User;
use App\Group;
use Tests\TestCase;
use App\TwoFAccount;

class GroupTest extends TestCase
{
    /** @var \App\User */
    protected $user;


    /**
     * @test
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }


    /**
     * test Group display via API
     *
     * @test
     */
    public function testGroupDisplay()
    {

        $group = factory(Group::class)->create([
            'name' => 'My group',
        ]);


        $response = $this->actingAs($this->user, 'api')
            ->json('GET', '/api/groups/' . $group->id)
            ->assertStatus(200)
            ->assertJsonFragment([
                'name' => 'My group',
            ]);
    }


    /**
     * test missing Group display via API
     *
     * @test
     */
    public function testMissingGroupDisplay()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('GET', '/api/groups/1000')
            ->assertStatus(404);
    }


    /**
     * test Group creation via API
     *
     * @test
     */
    public function testGroupCreation()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/groups', [
                    'name' => 'My second group',
                ])
            ->assertStatus(201)
            ->assertJsonFragment([
                    'name' => 'My second group',
            ]);
    }


    /**
     * test Group creation when fields are empty via API
     *
     * @test
     */
    public function testGroupCreationWithEmptyRequest()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/groups', [
                    'name' => '',
                ])
            ->assertStatus(422);
    }


    /**
     * test Group update via API
     *
     * @test
     */
    public function testGroupUpdate()
    {
        $group = factory(Group::class)->create();

        $response = $this->actingAs($this->user, 'api')
            ->json('PUT', '/api/groups/' . $group->id, [
                    'name' => 'name updated',
                ])
            ->assertStatus(200)
            ->assertJsonFragment([
                'name' => 'name updated',
            ]);
    }


    /**
     * test Group update via API
     *
     * @test
     */
    public function testGroupUpdateOfMissingGroup()
    {
        $group = factory(Group::class)->create();
        $id = $group->id;
        $group->delete();

        $response = $this->actingAs($this->user, 'api')
            ->json('PUT', '/api/groups/' . $id, [
                    'name' => 'testUpdate',
                ])
            ->assertStatus(404);
    }


    /**
     * test Group index fetching via API
     *
     * @test
     */
    public function testGroupIndexListing()
    {
        factory(Group::class, 3)->create();

        $response = $this->actingAs($this->user, 'api')
            ->json('GET', '/api/groups')
            ->assertStatus(200)
            ->assertJsonCount(4, $key = null)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'name',
                    'twofaccounts_count',
                    'isActive',
                ]
            ])
            ->assertJsonFragment([
                'id' => 0,
                'name' => 'All',
            ]);
    }


    /**
     * test Group deletion via API
     *
     * @test
     */
    public function testGroupDeletion()
    {
        $group = factory(Group::class)->create();

        $response = $this->actingAs($this->user, 'api')
            ->json('DELETE', '/api/groups/' . $group->id)
            ->assertStatus(204);
    }
}
