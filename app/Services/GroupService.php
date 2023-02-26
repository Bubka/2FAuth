<?php

namespace App\Services;

use App\Models\Group;
use App\Models\TwoFAccount;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class GroupService
{
    /**
     * Returns all existing groups for the given user
     *
     * @param  \App\Models\User  $user
     * @return Collection<int, Group>
     */
    public static function getAll(User $user) : Collection
    {
        return self::prependTheAllGroup($user->groups()->withCount('twofaccounts')->get(), $user->id);
    }

    /**
     * Creates a group for the given user
     *
     * @param  array  $data
     * @param  \App\Models\User  $user
     * @return \App\Models\Group The created group
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public static function create(array $data, User $user) : Group
    {
        if ($user->cannot('create', Group::class)) {
            Log::notice(sprintf('User ID #%s cannot create groups', $user->id));
            throw new AuthorizationException();
        }

        $group = $user->groups()->create([
            'name' => $data['name'],
        ]);

        Log::info(sprintf('Group "%s" created for user ID #%s', var_export($group->name, true), $user->id));

        return $group;
    }

    /**
     * Updates a group using a list of parameters
     *
     * @param  \App\Models\Group  $group The group
     * @param  array  $data The parameters
     * @param  \App\Models\User  $user
     * @return \App\Models\Group The updated group
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public static function update(Group $group, array $data, User $user) : Group
    {
        if ($user->cannot('update', $group)) {
            Log::notice(sprintf('User ID #%s cannot update group "%s"', $user->id, var_export($group->name, true)));
            throw new AuthorizationException();
        }

        $group->update([
            'name' => $data['name'],
        ]);

        Log::info(sprintf('Group "%s" updated by user ID #%s', var_export($group->name, true), $user->id));

        return $group;
    }

    /**
     * Deletes one or more groups
     *
     * @param  int|array  $ids group ids to delete
     * @param  \App\Models\User  $user
     * @return int The number of deleted
     */
    public static function delete($ids, User $user) : int
    {
        $ids = is_array($ids) ? $ids : [$ids];

        $groups = Group::findMany($ids);

        if ($groups->count() > 0) {
            if ($user->cannot('deleteEach', [$groups[0], $groups])) {
                Log::notice(sprintf('User ID #%s cannot delete all groups in IDs #%s', $user->id, implode(',', $ids)));
                throw new AuthorizationException();
            }

            // One of the groups is possibly set as the default group of the given user.
            // In this case we reset the preference to "No group" (groupId = 0)
            if (in_array($user->preferences['defaultGroup'], $ids)) {
                $user['preferences->defaultGroup'] = 0;
                $user->save();
            }

            // One of the groups is also possibly set as the active group if the user
            // configured 2FAuth to memorize the active group.
            // In this case we reset the preference to the pseudo "All" group (groupId = 0)
            if (in_array($user->preferences['activeGroup'], $ids)) {
                $user['preferences->activeGroup'] = 0;
                $user->save();
            }

            $deleted = Group::destroy($ids);
            Log::info(sprintf('Groups IDs #%s deleted', implode(',#', $ids)));

            return $deleted;
        }

        return 0;
    }

    /**
     * Assign one or more accounts to a user group
     *
     * @param  array|int  $ids accounts ids to assign
     * @param  \App\Models\User  $user
     * @param  \App\Models\Group  $group The target group
     * @return void
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException<\App\Models\TwoFAccount>
     */
    public static function assign($ids, User $user, Group $group = null) : void
    {
        if (! $group) {
            $group = self::defaultGroup($user);
        } else {
            if ($user->cannot('update', $group)) {
                Log::notice(sprintf('User ID #%s cannot update group "%s"', $user->id, var_export($group->name, true)));
                throw new AuthorizationException();
            }
        }

        if ($group) {
            $ids = is_array($ids) ? $ids : [$ids];

            $twofaccounts = TwoFAccount::findOrFail($ids);

            if ($user->cannot('updateEach', [$twofaccounts[0], $twofaccounts])) {
                Log::notice(sprintf('User ID #%s cannot assign twofaccounts %s to group "%s"', $user->id, implode(',', $ids), var_export($group->name, true)));
                throw new AuthorizationException();
            }

            $group->twofaccounts()->saveMany($twofaccounts);
            $group->loadCount('twofaccounts');

            Log::info(sprintf('Twofaccounts IDS #%s assigned to groups "%s"', implode(',', $ids), var_export($group->name, true)));
        } else {
            Log::info('Cannot find a group to assign the TwoFAccounts to');
        }
    }

    /**
     * Prepends the pseudo group named 'All' to a group collection
     *
     * @param  Collection<int, Group>  $groups
     * @return Collection<int, Group>
     */
    private static function prependTheAllGroup(Collection $groups, int $userId) : Collection
    {
        $theAllGroup = new Group([
            'name' => __('commons.all'),
        ]);

        $theAllGroup->id                 = 0;
        $theAllGroup->twofaccounts_count = TwoFAccount::where('user_id', $userId)->count();

        return $groups->prepend($theAllGroup);
    }

    /**
     * Determines the default group of the given user
     *
     * @param  \App\Models\User  $user
     * @return \App\Models\Group|null The group or null if it does not exist
     */
    private static function defaultGroup(User $user)
    {
        $id = $user->preferences['defaultGroup'] === -1 ? (int) $user->preferences['activeGroup'] : (int) $user->preferences['defaultGroup'];

        return Group::find($id);
    }
}
