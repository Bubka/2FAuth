<?php

namespace App\Services;

use App\Group;
use App\TwoFAccount;
use App\Services\SettingServiceInterface;
use Illuminate\Database\Eloquent\Collection;

class GroupService
{

    /**
     * The Settings Service instance.
     */
    protected SettingServiceInterface $settingService;


    /**
     * Create a new controller instance.
     * 
     */
    public function __construct(SettingServiceInterface $SettingServiceInterface)
    {
        $this->settingService = $SettingServiceInterface;
    }


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
        $ids = is_array($ids) ? $ids : func_get_args();

        // A group is possibly set as the default group in Settings.
        // In this case we reset the setting to "No group" (groupId = 0)
        $defaultGroupId = $this->settingService->get('defaultGroup');

        if (in_array($defaultGroupId, $ids)) {
            $this->settingService->set('defaultGroup', 0);
        }

        // A group is also possibly set as the active group if the user
        // configured 2FAuth to memorize the active group.
        // In this case we reset the setting to the pseudo "All" group (groupId = 0)
        $activeGroupId = $this->settingService->get('activeGroup');

        if (in_array($activeGroupId, $ids)) {
            $this->settingService->set('activeGroup', 0);
        }

        $deleted = Group::destroy($ids);

        return $deleted;
    }


    /**
     * Assign one or more accounts to a group
     * 
     * @param array|int $ids accounts ids to assign
     * @param Group $group The target group
     * @return void
     */
    public function assign($ids, Group $group = null) : void
    {
        if (!$group) {
            $group = $this->defaultGroup();
        }

        if ($group) {
            // saveMany() expect an iterable so we pass an array to
            // find() to always obtain a list of TwoFAccount
            if (!is_array($ids)) {
                $ids = array($ids);
            }
            $twofaccounts = TwoFAccount::find($ids);

            $group->twofaccounts()->saveMany($twofaccounts);
        }
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
     * @return Group|null The group or null if it does not exist
     */
    private function defaultGroup()
    {
        $id = $this->settingService->get('defaultGroup') === '-1' ? (int) $this->settingService->get('activeGroup') : (int) $this->settingService->get('defaultGroup');

        return Group::find($id);
    }
}