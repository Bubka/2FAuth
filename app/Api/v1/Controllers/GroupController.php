<?php

namespace App\Api\v1\Controllers;

use App\Api\v1\Requests\GroupAssignRequest;
use App\Api\v1\Requests\GroupStoreRequest;
use App\Api\v1\Resources\GroupResource;
use App\Api\v1\Resources\TwoFAccountCollection;
use App\Facades\Groups;
use App\Http\Controllers\Controller;
use App\Models\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $groups = Groups::for($request->user())->withTheAllGroup()->all();

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

        $group = Groups::for($request->user())->create($validated);

        return (new GroupResource($group))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Group  $group
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Api\v1\Resources\GroupResource
     */
    public function show(Group $group, Request $request)
    {
        $group = Groups::for($request->user())->get($group->id);

        return new GroupResource($group);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Api\v1\Requests\GroupStoreRequest  $request
     * @param  \App\Models\Group  $group
     * @return \App\Api\v1\Resources\GroupResource
     */
    public function update(GroupStoreRequest $request, Group $group)
    {
        $validated = $request->validated();

        Groups::for($request->user())->update($group, $validated);

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

        Groups::for($request->user())->assign($validated['ids'], $group);

        return new GroupResource($group);
    }

    /**
     * Get accounts assigned to the group
     *
     * @param  \App\Models\Group  $group
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Api\v1\Resources\TwoFAccountCollection
     */
    public function accounts(Group $group, Request $request)
    {
        $groups = Groups::for($request->user())->accounts($group);

        return new TwoFAccountCollection($groups);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Group  $group
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Group $group, Request $request)
    {
        Groups::for($request->user())->delete($group->id);

        return response()->json(null, 204);
    }
}
