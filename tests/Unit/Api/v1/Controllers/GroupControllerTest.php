<?php

namespace Tests\Unit\Api\v1\Controllers;

use App\Api\v1\Controllers\GroupController;
use App\Api\v1\Requests\GroupAssignRequest;
use App\Api\v1\Requests\GroupStoreRequest;
use App\Api\v1\Resources\GroupResource;
use App\Api\v1\Resources\TwoFAccountReadResource;
use App\Facades\Groups;
use App\Models\Group;
use App\Models\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\Request;
use Mockery;
use Tests\TestCase;

/**
 * @covers \App\Api\v1\Controllers\GroupController
 */
class GroupControllerTest extends TestCase
{
    use WithoutMiddleware;

    /**
     * @var \App\Api\v1\Controllers\GroupController tested controller
     */
    protected $controller;

    /**
     * @var \App\Api\v1\Requests\GroupStoreRequest mocked request
     */
    protected $groupStoreRequest;

    /**
     * @var \Illuminate\Http\Request mocked request
     */
    protected $request;

    public function setUp() : void
    {
        parent::setUp();

        $this->groupStoreRequest = Mockery::mock(GroupStoreRequest::class);
        $this->request           = Mockery::mock(Request::class);

        $this->request->shouldReceive('user')
            ->andReturn(new User());

        $this->controller = new GroupController();
    }

    /**
     * @test
     */
    public function test_index_returns_api_resources_using_groupService()
    {
        $groups = Group::factory()->count(3)->make();

        Groups::shouldReceive('getAll')
            ->once()
            ->andReturn($groups);

        $response = $this->controller->index($this->request);

        $this->assertContainsOnlyInstancesOf(GroupResource::class, $response->collection);
    }

    /**
     * @test
     */
    public function test_store_returns_api_resource_stored_using_groupService()
    {
        $group = Group::factory()->make();

        $this->groupStoreRequest->shouldReceive([
            'validated' => ['name' => $group->name],
            'user'      => new User(),
        ])
            ->once();

        Groups::shouldReceive('create')
            ->once()
            ->andReturn($group);

        $response = $this->controller->store($this->groupStoreRequest);

        $this->assertInstanceOf(Group::class, $response->original);
        // $this->assertInstanceOf(GroupResource::class, $response);
    }

    /**
     * @test
     */
    public function test_show_returns_api_resource()
    {
        $group = Group::factory()->make();

        $response = $this->controller->show($group);

        $this->assertInstanceOf(GroupResource::class, $response);
    }

    /**
     * @test
     */
    public function test_update_returns_api_resource_updated_using_groupService()
    {
        $group = Group::factory()->make();

        $this->groupStoreRequest->shouldReceive([
            'validated' => ['name' => $group->name],
            'user'      => new User(),
        ])
            ->once();

        Groups::shouldReceive('update')
            ->once()
            ->andReturn($group);

        $response = $this->controller->update($this->groupStoreRequest, $group);

        $this->assertInstanceOf(GroupResource::class, $response);
    }

    /**
     * @test
     */
    public function test_assignAccounts_returns_api_resource_assigned_using_groupService()
    {
        $group              = Group::factory()->make();
        $groupAssignRequest = Mockery::mock(GroupAssignRequest::class);
        $user               = new User();

        $groupAssignRequest->shouldReceive([
            'validated' => ['ids' => $group->id],
            'user'      => $user,
        ])
            ->once();

        Groups::shouldReceive('assign')
            ->with($group->id, $user, $group)
            ->once();

        $response = $this->controller->assignAccounts($groupAssignRequest, $group);

        $this->assertInstanceOf(GroupResource::class, $response);
    }

    /**
     * @test
     */
    public function test_accounts_returns_api_resources_fetched_using_groupService()
    {
        $group = Group::factory()->make();

        $response = $this->controller->accounts($group, $this->request);

        $this->assertContainsOnlyInstancesOf(TwoFAccountReadResource::class, $response->collection);
    }

    /**
     * @test
     */
    public function test_destroy_uses_group_service()
    {
        $group     = Group::factory()->make();
        $group->id = 0;

        Groups::shouldReceive('delete')
            ->once()
            ->with($group->id, $this->request->user())
            ->andReturn(0);

        $response = $this->controller->destroy($group, $this->request);

        $this->assertInstanceOf('Illuminate\Http\JsonResponse', $response);
    }
}
