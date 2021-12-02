<?php

namespace Tests\Unit\Api\v1\Controllers;

use App\Models\User;
use App\Models\Group;
use Tests\TestCase;
use App\Models\TwoFAccount;
use App\Services\GroupService;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Api\v1\Controllers\GroupController;
use Mockery;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Api\v1\Requests\GroupStoreRequest;

/**
 * @covers \App\Api\v1\Controllers\GroupController
 */
class GroupControllerTest extends TestCase
{
    use WithoutMiddleware;

    /**
     * @var \Mockery\Mock|\App\Services\GroupService
     */
    protected $groupServiceMock;


    /**
     * @var \App\Api\v1\Controllers\GroupController mocked controller
     */
    protected $controller;


    /**
     * @var \App\Api\v1\Requests\GroupStoreRequest mocked request
     */
    protected $groupStoreRequest;


    public function setUp() : void
    {
        parent::setUp();

        $this->groupServiceMock = Mockery::mock($this->app->make(GroupService::class));
        $this->groupStoreRequest = Mockery::mock('App\Api\v1\Requests\GroupStoreRequest');

        $this->controller = new GroupController($this->groupServiceMock);
    }


    /**
     * @test
     */
    public function test_index_returns_api_resources_using_groupService()
    {
        $groups = Group::factory()->count(3)->make();

        $this->groupServiceMock->shouldReceive('getAll')
            ->once()
            ->andReturn($groups);

        $response = $this->controller->index();

        $this->assertContainsOnlyInstancesOf('App\Api\v1\Resources\GroupResource', $response->collection);
    }


    /**
     * @test
     */
    public function test_store_returns_api_resource_stored_using_groupService()
    {
        $group = Group::factory()->make();

        $this->groupStoreRequest->shouldReceive('validated')
            ->once()
            ->andReturn(['name' => $group->name]);

        $this->groupServiceMock->shouldReceive('create')
            ->once()
            ->andReturn($group);

        $response = $this->controller->store($this->groupStoreRequest);

        $this->assertInstanceOf('App\Models\Group', $response->original);
    }


    /**
     * @test
     */
    public function test_show_returns_api_resource()
    {
        $group = Group::factory()->make();

        $response = $this->controller->show($group);

        $this->assertInstanceOf('App\Api\v1\Resources\GroupResource', $response);
    }


    /**
     * @test
     */
    public function test_update_returns_api_resource_updated_using_groupService()
    {
        $group = Group::factory()->make();

        $this->groupStoreRequest->shouldReceive('validated')
            ->once()
            ->andReturn(['name' => $group->name]);

        $this->groupServiceMock->shouldReceive('update')
            ->once()
            ->andReturn($group);

        $response = $this->controller->update($this->groupStoreRequest, $group);

        $this->assertInstanceOf('App\Api\v1\Resources\GroupResource', $response);
    }


    /**
     * @test
     */
    public function test_assignAccounts_returns_api_resource_assigned_using_groupService()
    {
        $group = Group::factory()->make();
        $groupAssignRequest = Mockery::mock('App\Api\v1\Requests\GroupAssignRequest');

        $groupAssignRequest->shouldReceive('validated')
            ->once()
            ->andReturn(['ids' => $group->id]);

        $this->groupServiceMock->shouldReceive('assign')
            ->with($group->id, $group)
            ->once();

        $response = $this->controller->assignAccounts($groupAssignRequest, $group);

        $this->assertInstanceOf('App\Api\v1\Resources\GroupResource', $response);
    }


    /**
     * @test
     */
    public function test_accounts_returns_api_resources_fetched_using_groupService()
    {
        $group = Group::factory()->make();

        \Facades\App\Services\SettingService::shouldReceive('get')
            ->with('useEncryption')
            ->andReturn(false);

        $twofaccounts = TwoFAccount::factory()->count(3)->make();

        $this->groupServiceMock->shouldReceive('getAccounts')
            ->with($group)
            ->once()
            ->andReturn($twofaccounts);

        $response = $this->controller->accounts($group);
        // TwoFAccountCollection
        $this->assertContainsOnlyInstancesOf('App\Api\v1\Resources\TwoFAccountReadResource', $response->collection);
    }


    /**
     * @test
     */
    public function test_destroy_uses_group_service()
    {
        $group = Group::factory()->make();

        $this->groupServiceMock->shouldReceive('delete')
            ->once()
            ->with($group->id);

        $response = $this->controller->destroy($group);

        $this->assertInstanceOf('Illuminate\Http\JsonResponse', $response);
    }
}