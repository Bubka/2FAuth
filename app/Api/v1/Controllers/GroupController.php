<?php

namespace App\Api\v1\Controllers;

use App\Models\Group;
use App\Services\GroupService;
use App\Api\v1\Requests\GroupStoreRequest;
use App\Api\v1\Requests\GroupAssignRequest;
use App\Api\v1\Resources\GroupResource;
use App\Api\v1\Resources\TwoFAccountCollection;
use App\Http\Controllers\Controller;

class GroupController extends Controller
{
    /**
     * The TwoFAccount Service instance.
     */
    protected $groupService;


    /**
     * Create a new controller instance.
     *
     * @param  \App\Services\GroupService $groupService
     * @return void
     */
    public function __construct(GroupService $groupService)
    {
        $this->groupService = $groupService;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \App\Api\v1\Resources\GroupResource
     */
    public function index()
    {
        $groups = $this->groupService->getAll();

        return GroupResource::collection($groups);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Api\v1\Requests\GroupStoreRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(GroupStoreRequest $request)
    {
        $validated = $request->validated();

        $group = $this->groupService->create($validated);

        return (new GroupResource($group))
            ->response()
            ->setStatusCode(201);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Group  $group
     * @return \App\Api\v1\Resources\GroupResource
     */
    public function show(Group $group)
    {
        return new GroupResource($group);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Api\v1\Requests\GroupStoreRequest  $request
     * @param  \App\Models\Group $group
     * @return \App\Api\v1\Resources\GroupResource
     */
    public function update(GroupStoreRequest $request, Group $group)
    {
        $validated = $request->validated();

        $this->groupService->update($group, $validated);

        return new GroupResource($group);

    }


    /**
     * Associate the specified accounts with the group
     *
     * @param  \App\Api\v1\Requests\GroupAssignRequest  $request
     * @param  \App\Models\Group  $group
     * @return \App\Api\v1\Resources\GroupResource
     */
    public function assignAccounts(GroupAssignRequest $request, Group $group)
    {
        $validated = $request->validated();

        $this->groupService->assign($validated['ids'], $group);
            
        return new GroupResource($group);

    }


    /**
     * Get accounts assign to the group
     *
     * @param  \App\Models\Group  $group
     * @return \App\Api\v1\Resources\TwoFAccountCollection
     */
    public function accounts(Group $group)
    {
        $twofaccounts = $this->groupService->getAccounts($group);
            
        return new TwoFAccountCollection($twofaccounts);

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Group $group)
    {
        $this->groupService->delete($group->id);

        return response()->json(null, 204);
    }

}
