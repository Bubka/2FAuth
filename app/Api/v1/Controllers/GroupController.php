<?php

namespace App\Api\v1\Controllers;

use App\Api\v1\Requests\GroupAssignRequest;
use App\Api\v1\Requests\GroupStoreRequest;
use App\Api\v1\Requests\ReorderRequest;
use App\Api\v1\Resources\GroupResource;
use App\Api\v1\Resources\TwoFAccountCollection;
use App\Facades\Groups;
use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    /**
     * Display all user groups.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        // Quick fix for #176
        if (config('auth.defaults.guard') === 'reverse-proxy-guard' && User::count() === 1) {
            if (Group::orphans()->exists()) {
                $groups = Group::orphans()->get();
                Groups::setUser($groups, $request->user());
            }
        }

        // We do not use fluent call all over the call chain to ease tests
        $user   = $request->user();
        $groups = $user->groups()->withCount('twofaccounts')->get()->sortBy('order_column');

        return GroupResource::collection(Groups::prependTheAllGroup($groups, $request->user()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(GroupStoreRequest $request)
    {
        $this->authorize('create', Group::class);

        $validated = $request->validated();

        $group = $request->user()->groups()->create($validated);

        return (new GroupResource($group))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @return \App\Api\v1\Resources\GroupResource
     */
    public function show(Request $request, Group $group)
    {
        $this->authorize('view', $group);

        // group with id==0 is the 'All' virtual group.
        // Eloquent specifically returns a non-persisted Group instance
        // with just the name property. The twofaccounts_count has to be
        // set here.
        if ($group->id === 0) {
            $group->twofaccounts_count = $request->user()->twofaccounts->count();
        }

        return new GroupResource($group);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \App\Api\v1\Resources\GroupResource
     */
    public function update(GroupStoreRequest $request, Group $group)
    {
        $this->authorize('update', $group);

        $validated = $request->validated();

        $group->update($validated);

        return new GroupResource($group);
    }

    /**
     * Save Groups order
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function reorder(ReorderRequest $request)
    {
        $validated = $request->validated();

        $groups = Group::whereIn('id', $validated['orderedIds'])->get();
        $this->authorize('updateEach', [new Group, $groups]);

        Group::setNewOrder($validated['orderedIds']);
        $orderedIds = $request->user()->groups->sortBy('order_column')->pluck('id');

        return response()->json([
            'message'    => 'order saved',
            'orderedIds' => $orderedIds,
        ], 200);
    }

    /**
     * Associate the specified accounts with the group
     *
     * @return \App\Api\v1\Resources\GroupResource
     */
    public function assignAccounts(GroupAssignRequest $request, Group $group)
    {
        $this->authorize('update', $group);

        $validated = $request->validated();

        try {
            Groups::assign($validated['ids'], $request->user(), $group);
            $group->loadCount('twofaccounts');
        } catch (ModelNotFoundException $exc) {
            abort(404);
        } catch (AuthorizationException $exc) {
            abort(403);
        } catch (\Throwable $th) {
            abort(409, 'Conflict');
        }

        return new GroupResource($group);
    }

    /**
     * Get accounts assigned to the group
     *
     * @return \App\Api\v1\Resources\TwoFAccountCollection
     */
    public function accounts(Request $request, Group $group)
    {
        $this->authorize('view', $group);

        // group with id==0 is the 'All' virtual group that lists
        // all the user's twofaccounts. From the db pov the accounts
        // are not assigned to any group record.
        if ($group->id === 0) {
            $twofaccounts = $request->user()->twofaccounts;
        } else {
            $twofaccounts = $group->twofaccounts;
        }

        return new TwoFAccountCollection($twofaccounts);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Group $group)
    {
        $this->authorize('delete', $group);

        $group->delete();

        return response()->json(null, 204);
    }
}
