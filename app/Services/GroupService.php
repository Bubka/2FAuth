<?php

namespace App\Services;

use App\Models\Group;
use App\Models\TwoFAccount;
use App\Facades\Settings;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\App;

class GroupService
{
    /**
     * Returns all existing groups
     * 
     * @return Collection
     */
    public static function getAll() : Collection
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
     * @return \App\Models\Group The created group
     */
    public static function create(array $data) : Group
    {
        $group = Group::create([
            'name' => $data['name'],
        ]);

        $group->save();

        Log::info(sprintf('Group %s created', var_export($group->name, true)));

        return $group;
    }


    /**
     * Updates a group using a list of parameters
     * 
     * @param \App\Models\Group $group The group
     * @param array $data The parameters
     * @return \App\Models\Group The updated group
     */
    public static function update(Group $group, array $data) : Group
    {
        $group->update([
            'name' => $data['name'],
        ]);

        Log::info(sprintf('Group %s updated', var_export($group->name, true)));

        return $group;
    }


    /**
     * Deletes one or more groups
     * 
     * @param int|array $ids group ids to delete
     * @return int The number of deleted
     */
    public static function delete($ids) : int
    {
        $ids = is_array($ids) ? $ids : func_get_args();

        // A group is possibly set as the default group in Settings.
        // In this case we reset the setting to "No group" (groupId = 0)
        $defaultGroupId = Settings::get('defaultGroup');

        if (in_array($defaultGroupId, $ids)) {
            Settings::set('defaultGroup', 0);
        }

        // A group is also possibly set as the active group if the user
        // configured 2FAuth to memorize the active group.
        // In this case we reset the setting to the pseudo "All" group (groupId = 0)
        $activeGroupId = Settings::get('activeGroup');

        if (in_array($activeGroupId, $ids)) {
            Settings::set('activeGroup', 0);
        }

        $deleted = Group::destroy($ids);

        Log::info(sprintf('Groups #%s deleted', implode(',#', $ids)));

        return $deleted;
    }


    /**
     * Assign one or more accounts to a group
     * 
     * @param array|int $ids accounts ids to assign
     * @param \App\Models\Group $group The target group
     * @return void
     */
    public static function assign($ids, Group $group = null) : void
    {
        if (!$group) {
            $group = self::defaultGroup();
        }

        if ($group) {
            // saveMany() expect an iterable so we pass an array to
            // find() to always obtain a list of TwoFAccount
            if (!is_array($ids)) {
                $ids = array($ids);
            }
            $twofaccounts = TwoFAccount::find($ids);

            $group->twofaccounts()->saveMany($twofaccounts);
            $group->loadCount('twofaccounts');

            Log::info(sprintf('Twofaccounts #%s assigned to groups %s', implode(',#', $ids), var_export($group->name, true)));
        }
        else Log::info('Cannot find a group to assign the TwoFAccounts to');
    }


    /**
     * Finds twofaccounts assigned to the group
     * 
     * @param \App\Models\Group $group The group
     * @return Collection The assigned accounts
     */
    public static function getAccounts(Group $group) : Collection
    {
        $twofaccounts = $group->twofaccounts()->where('group_id', $group->id)->get();

        return $twofaccounts;
    }


    /**
     * Determines the destination group
     * 
     * @return \App\Models\Group|null The group or null if it does not exist
     */
    private static function defaultGroup()
    {
        $id = Settings::get('defaultGroup') === -1 ? (int) Settings::get('activeGroup') : (int) Settings::get('defaultGroup');

        return Group::find($id);
    }
}