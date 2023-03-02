<?php

namespace Tests\Unit\Api\v1\Controllers;

use App\Api\v1\Controllers\GroupController;
use App\Api\v1\Requests\GroupAssignRequest;
use App\Api\v1\Requests\GroupStoreRequest;
use App\Api\v1\Resources\GroupResource;
use App\Api\v1\Resources\TwoFAccountReadResource;
use App\Facades\Groups;
use App\Models\Group;
use App\Models\TwoFAccount;
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
     * @var \App\Models\User mocked user
     */
    protected $user;

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

        Groups::shouldReceive('for')
            ->with($this->request->user())
            ->once()
            ->andReturnSelf()
            ->shouldReceive('withTheAllGroup')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('all')
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
        $validated = ['name' => $group->name];

        $this->groupStoreRequest->shouldReceive([
            'validated' => $validated,
            'user'      => new User(),
        ]);

        Groups::shouldReceive('for')
            ->with($this->groupStoreRequest->user())
            ->once()
            ->andReturnSelf()
            ->shouldReceive('create')
            ->with($validated)
            ->once()
            ->andReturn($group);

        $response = $this->controller->store($this->groupStoreRequest);

        $this->assertInstanceOf(Group::class, $response->original);
        // $this->assertInstanceOf(GroupResource::class, $response);
    }

    /**
     * @test
     */
    public function test_show_returns_api_resource_using_groupService()
    {
        $group = Group::factory()->make();

        Groups::shouldReceive('for')
            ->with($this->request->user())
            ->once()
            ->andReturnSelf()
            ->shouldReceive('get')
            ->with($group->id)
            ->once()
            ->andReturn($group);

        $response = $this->controller->show($group, $this->request);

        $this->assertInstanceOf(GroupResource::class, $response);
    }

    /**
     * @test
     */
    public function test_update_returns_api_resource_updated_using_groupService()
    {
        $group = Group::factory()->make();
        $validated = ['name' => $group->name];

        $this->groupStoreRequest->shouldReceive([
                'validated' => $validated,
                'user'      => new User(),
        ]);

        Groups::shouldReceive('for')
            ->with($this->groupStoreRequest->user())
            ->once()
            ->andReturnSelf()
            ->shouldReceive('update')
            ->with($group, $validated)
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
        $validated          = ['ids' => $group->id];

        $groupAssignRequest->shouldReceive([
            'validated' => $validated,
            'user'      => new User(),
        ]);

        Groups::shouldReceive('for')
            ->with($groupAssignRequest->user())
            ->once()
            ->andReturnSelf()
            ->shouldReceive('assign')
            ->with($group->id, $group)
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

        $groupAccounts = new TwoFAccount();
        $groupAccounts = $groupAccounts->newCollection(
            array(
                new TwoFAccount(),
                new TwoFAccount()
            )
        );

        Groups::shouldReceive('for')
            ->with($this->request->user())
            ->once()
            ->andReturnSelf()
            ->shouldReceive('accounts')
            ->with($group)
            ->once()
            ->andReturn($groupAccounts);

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

        Groups::shouldReceive('for')
            ->with($this->request->user())
            ->once()
            ->andReturnSelf()
            ->shouldReceive('delete')
            ->with($group->id)
            ->once()
            ->andReturn(1);

        $response = $this->controller->destroy($group, $this->request);

        $this->assertInstanceOf('Illuminate\Http\JsonResponse', $response);
    }
}
