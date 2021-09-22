<?php

namespace App\Services;

use App\Group;
use App\TwoFAccount;
use App\Classes\Options;
use Illuminate\Database\Eloquent\Collection;

class GroupService
{

    /**
     * Returns all existing groups
     * 
     * @return Collection
     */
    public function getAll() : Collection
    {
        // We return the complete collection of groups
        // stored in db plus a pseudo group corresponding to 'All'
        //
        // This pseudo group contains all twofaccounts regardless
        // of the user created group they belong to.
        
        // Get the user created groups
        $groups = Group::withCount('twofaccounts')->get();

        // Create the pseudo group
        $allGroup = new Group([
            'name' => __('commons.all')
        ]);

        $allGroup->id = 0;
        $allGroup->twofaccounts_count = TwoFAccount::count();

        return $groups->prepend($allGroup);
    }


    /**
     * Creates a group
     * 
     * @param array $data
     * @return Group The created group
     */
    public function create(array $data) : Group
    {
        $group = Group::create([
            'name' => $data['name'],
        ]);

        $group->save();

        return $group;
    }


    /**
     * Updates a group using a list of parameters
     * 
     * @param Group $group The group
     * @param array $data The parameters
     * @return Group The updated group
     */
    public function update(Group $group, array $data) : Group
    {
        $group->update([
            'name' => $data['name'],
        ]);

        return $group;
    }


    /**
     * Deletes one or more groups
     * 
     * @param int|array $ids group ids to delete
     * @return int The number of deleted
     */
    public function delete($ids) : int
    {
        $deleted = Group::destroy($ids);

        return $deleted;
    }


    /**
     * Assign one or more accounts to a group
     * 
     * @param array|int $ids accounts ids to assign
     * @param Group $group The target group
     * @return Group The updated group
     */
    public function assign(mixed $ids, Group $group = null) : Group
    {
        if (!$group) {
            $group = $this->destinationGroup();
        }

        $twofaccounts = TwoFAccount::find($ids);

        $group->twofaccounts()->saveMany($twofaccounts);
        $group->loadCount('twofaccounts');

        return $group;
    }

    /**
     * Finds twofaccounts assigned to the group
     * 
     * @param Group $group The group
     * @return Collection The assigned accounts
     */
    public function getAccounts(Group $group) : Collection
    {
        $twofaccounts = $group->twofaccounts()->where('group_id', $group->id)->get();

        return $twofaccounts;
    }


    /**
     * Determines the destination group
     * 
     * @return Group The group
     */
    private function destinationGroup() : Group
    {
        $id = Options::get('defaultGroup') === '-1' ? (int) Options::get('activeGroup') : (int) Options::get('defaultGroup');

        return Group::find($id);
    }
}