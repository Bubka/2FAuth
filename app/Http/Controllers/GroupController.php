<?php

namespace App\Http\Controllers;

use App\Group;
use App\TwoFAccount;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // The index method has to return the complete collection of groups
        // stored in db plus a pseudo group corresponding to 'all'
        
        // Get the stored groups
        $groups = Group::withCount('twofaccounts')->get();

        // Create the pseudo group
        $allGroup = new Group([
            'name' => __('commons.all')
        ]);

        $allGroup->id = 0;
        $allGroup->twofaccounts_count = TwoFAccount::count();

        // Merge them all
        $groups->prepend($allGroup);

        return response()->json($groups->toArray());
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'name' => 'required|string|max:32|unique:groups',
        ]);

        $group = Group::create([
            'name' => $request->name,
        ]);

        return response()->json($group, 201);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function show(Group $group)
    {
        return response()->json($group, 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Group  $twofaccount
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $this->validate($request, [
            'name' => 'required|string|max:32|unique:groups',
        ]);

        // Here we catch a possible missing model exception in order to
        // delete orphan submited icon
        try {

            $group = Group::FindOrFail($id);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            
            throw $e;
        }

        $group->update([
            'name' => $request->name,
        ]);

        return response()->json($group, 200);

    }


    /**
     * Associate the specified accounts with the group
     *
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function associateAccounts(Request $request)
    {
        if( $request->input('groupId') > 0 ) {

            $twofaccounts = TwoFAccount::find($request->input('accountsIds'));
            $group = Group::FindOrFail($request->input('groupId'));
            
            $group->twofaccounts()->saveMany($twofaccounts);
            
            return response()->json($group, 200);
        }
        else {

            TwoFAccount::whereIn('id', $request->input('accountsIds'))
                        ->update(['group_id' => NULL]);
            
            return response()->json(['message' => 'moved to null'], 200);
        }


    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group)
    {
        $group->delete();

        return response()->json(null, 204);
    }

}
