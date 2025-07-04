<?php

namespace Tests\Unit\Api\v1\Controllers;

use App\Api\v1\Controllers\GroupController;
use App\Api\v1\Requests\GroupStoreRequest;
use App\Api\v1\Resources\GroupResource;
use App\Api\v1\Resources\TwoFAccountReadResource;
use App\Facades\Groups;
use App\Models\Group;
use App\Models\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\Request;
use Mockery;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * GroupControllerTest test class
 */
#[CoversClass(GroupController::class)]
class GroupControllerTest extends TestCase
{
    use WithoutMiddleware;

    /**
     * @var \App\Models\User|\Illuminate\Contracts\Auth\Authenticatable
     */
    protected $user;

    protected function setUp() : void
    {
        parent::setUp();

        $this->user = new User;

        // We do not use $this->actingAs($this->user) to prevent intelephense
        // static analysis error. Dumb, but I don't like errors...
        $this->app['auth']->guard(null)->setUser($this->user);
        $this->app['auth']->shouldUse(null);
    }

    #[Test]
    public function test_index_returns_api_resources()
    {
        $user       = Mockery::mock(User::class);
        $request    = Mockery::mock(Request::class);
        $groups     = Group::factory()->count(3)->make();
        $controller = new GroupController;

        $user->shouldReceive('groups->withCount->get')
            ->once()
            ->andReturn($groups);

        $request->shouldReceive('user')
            ->andReturn($user);

        Groups::shouldReceive('prependTheAllGroup')
            ->once()
            ->andReturn($groups);

        $response = $controller->index($request);

        $this->assertContainsOnlyInstancesOf(GroupResource::class, $response->collection);
    }

    #[Test]
    public function test_store_uses_validated_data_and_returns_api_resource()
    {
        $request    = Mockery::mock(GroupStoreRequest::class);
        $controller = new GroupController;
        $group      = Group::factory()->for($this->user)->make();
        $validated  = ['name' => $group->name];

        $request->shouldReceive([
            'validated'            => $validated,
            'user->groups->create' => $group,
        ]);

        $response = $controller->store($request);

        $this->assertInstanceOf(Group::class, $response->original);
        // $this->assertInstanceOf(GroupResource::class, $response);
    }

    #[Test]
    public function test_show_returns_api_resource()
    {
        $request    = Mockery::mock(GroupStoreRequest::class);
        $controller = Mockery::mock(GroupController::class)->makePartial();
        $group      = Group::factory()->make();

        $response = $controller->show($request, $group);

        $this->assertInstanceOf(GroupResource::class, $response);
    }

    #[Test]
    public function test_update_validates_data_and_returns_api_resource()
    {
        $request    = Mockery::mock(GroupStoreRequest::class);
        $controller = Mockery::mock(GroupController::class)->makePartial();
        $group      = Group::factory()->make();
        $validated  = ['name' => $group->name];

        $request->shouldReceive([
            'validated' => $validated,
        ]);

        $response = $controller->update($request, $group);

        $this->assertInstanceOf(GroupResource::class, $response);
    }

    // 26/03/25: Cannot be tested as a Unit test anymore because of the call to $group->loadCount()
    // in the assignAccounts() controller method. The loadCount() has been introduced
    // in the controller by commit 19f3a71c "Move group->loadCount from the Assign void method to the caller"
    // on Feb 24-2025 as part of the CWE-362 fix.
    // #[Test]
    // public function test_assignAccounts_returns_api_resource_assigned_using_groupService()
    // {
    //     $request    = Mockery::mock(GroupAssignRequest::class);
    //     $controller = Mockery::mock(GroupController::class)->makePartial();
    //     $group      = Group::factory()->make();
    //     $validated  = ['ids' => $group->id];

    //     $request->shouldReceive([
    //         'validated' => $validated,
    //         'user'      => $this->user,
    //     ]);

    //     Groups::shouldReceive('assign')
    //         ->with($group->id, $this->user, $group)
    //         ->once();

    //     $response = $controller->assignAccounts($request, $group);

    //     $this->assertInstanceOf(GroupResource::class, $response);
    // }

    #[Test]
    public function test_accounts_returns_api_resources()
    {
        $request    = Mockery::mock(GroupStoreRequest::class);
        $controller = Mockery::mock(GroupController::class)->makePartial();
        $group      = Group::factory()->make();

        $response = $controller->accounts($request, $group);

        $this->assertContainsOnlyInstancesOf(TwoFAccountReadResource::class, $response->collection);
    }

    #[Test]
    public function test_destroy_uses_group_service()
    {
        $controller = Mockery::mock(GroupController::class)->makePartial();
        $group      = Group::factory()->make();
        $group->id  = 0;

        $response = $controller->destroy($group);

        $this->assertInstanceOf('Illuminate\Http\JsonResponse', $response);
        $this->assertEquals(204, $response->status());
    }
}
