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
     * Assign one or more accounts to a group
     *
     * @param  array|int  $ids accounts ids to assign
     * @param  \App\Models\Group|null  $group The group the accounts will be assigned to
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public static function assign($ids, User $user, Group $group = null) : void
    {
        if (! $group) {
            $group = self::defaultGroup($user);
        }

        if ($group) {
            $ids          = is_array($ids) ? $ids : [$ids];
            $twofaccounts = TwoFAccount::find($ids);

            if ($user->cannot('updateEach', [(new TwoFAccount), $twofaccounts])) {
                throw new AuthorizationException();
            }

            $group->twofaccounts()->saveMany($twofaccounts);
            $group->loadCount('twofaccounts');

            Log::info(sprintf('Twofaccounts #%s assigned to group %s (ID #%s)', implode(',', $ids), var_export($group->name, true), $group->id));
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
    public static function prependTheAllGroup(Collection $groups, User $user) : Collection
    {
        $theAllGroup = new Group([
            'name' => __('commons.all'),
        ]);

        $theAllGroup->id                 = 0;
        $theAllGroup->twofaccounts_count = $user->twofaccounts->count();

        return $groups->prepend($theAllGroup);
    }

    /**
     * Set owner of given groups
     * 
     * @param  Collection<int, Group>  $groups
     * @param  \App\Models\User  $user
     */
    public static function setUser(Collection $groups, User $user) : void
    {
        $groups->each(function ($group, $key) use ($user) {
            $group->user_id = $user->id;
            $group->save();
        });
    }

    /**
     * Determines the default group of the given user
     *
     * @return \App\Models\Group|null The group or null if it does not exist
     */
    private static function defaultGroup(User $user)
    {
        $id = $user->preferences['defaultGroup'] === -1 ? (int) $user->preferences['activeGroup'] : (int) $user->preferences['defaultGroup'];

        return Group::find($id);
    }
}
