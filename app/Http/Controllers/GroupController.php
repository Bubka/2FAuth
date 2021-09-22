<?php

namespace App\Http\Controllers;

use App\Group;
use App\TwoFAccount;
use App\Services\GroupService;
use App\Http\Requests\GroupStoreRequest;
use App\Http\Requests\GroupAssignRequest;
use App\Http\Resources\GroupResource;
use App\Http\Resources\TwoFAccountCollection;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    /**
     * The TwoFAccount Service instance.
     */
    protected $groupService;


    /**
     * Create a new controller instance.
     *
     * @param  GroupService  $groupService
     * @return void
     */
    public function __construct(GroupService $groupService)
    {
        $this->groupService = $groupService;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $groups = $this->groupService->getAll();

        return GroupResource::collection($groups);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\GroupRequest  $request
     * @return \App\Http\Resources\GroupResource
     */
    public function store(GroupStoreRequest $request)
    {
        $validated = $request->validated();

        $group = $this->groupService->Create($validated);

        return (new GroupResource($group))
            ->response()
            ->setStatusCode(201);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function show(Group $group)
    {
        return new GroupResource($group);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\GroupRequest  $request
     * @param  \App\Group $group
     * @return \App\Http\Resources\GroupResource
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
     * @param  \App\Http\Requests\GroupAssignRequest  $request
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function assignAccounts(GroupAssignRequest $request, Group $group)
    {
        $validated = $request->validated();

        $this->groupService->assign($validated['ids'], $group);
            
        return response()->json($group, 200);

    }


    /**
     * Get accounts assign to the group
     *
     * @param  \App\Group  $group
     * @return \App\Http\Resources\TwoFAccountCollection
     */
    public function accounts(Group $group)
    {
        $groups = $this->groupService->getAccounts($group);
            
        return new TwoFAccountCollection($groups);

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group)
    {
        $this->groupService->delete($group->id);

        return response()->json(null, 204);
    }

}
