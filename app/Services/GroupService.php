<?php

namespace App\Services;

use App\Models\Group;
use App\Models\TwoFAccount;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GroupService
{
    /**
     * Assign one or more accounts to a group
     *
     * @param  array|int  $ids  accounts ids to assign
     * @param  User  $user  The user who owns the accounts & the target group
     * @param  mixed  $targetGroup  The group the accounts should be assigned to
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public static function assign($ids, User $user, mixed $targetGroup = null) : void
    {
        // targetGroup == 0 == The pseudo group named 'All' == No group
        // It means we do not want the accounts to be associated to a group, either a
        // specific group or the default group from user preferences.
        // If you need to release the accounts from an existing association, use the
        // TwoFAccountService::withdraw() method.
        if ($targetGroup === 0 || $targetGroup === '0') {
            Log::info('Group assignment skipped, no group explicitly requested');

            return;
        }

        // Two main cases :
        // - A group (or group id) is passed as parameter => It has priority for use, if the group is valid
        // - No group is passed => We try to identify a destination group through user preferences
        $group = null;

        if (! is_null($targetGroup)) {
            if ($targetGroup instanceof Group && $targetGroup->exists && $targetGroup->user_id == $user->id) {
                $group = $targetGroup;
            } else {
                $group = Group::where('id', (int) $targetGroup)
                    ->where('user_id', $user->id)
                    ->first();
            }
        }

        if (! $group) {
            $group = self::defaultGroup($user);
        }

        if ($group) {
            $ids = is_array($ids) ? $ids : [$ids];

            DB::transaction(function () use ($group, $ids, $user) {
                $group        = Group::sharedLock()->find($group->id);
                $twofaccounts = TwoFAccount::sharedLock()->find($ids);

                if (! $group) {
                    throw new ModelNotFoundException('group no longer exists');
                }

                if ($user->cannot('updateEach', [(new TwoFAccount), $twofaccounts])) {
                    throw new AuthorizationException;
                }

                $group->twofaccounts()->saveMany($twofaccounts);

                Log::info(sprintf('Twofaccounts #%s assigned to group %s (ID #%s)', implode(',', $ids), var_export($group->name, true), $group->id));
            }, 5);
        } else {
            Log::info('Cannot find a group to assign the TwoFAccounts to');
        }
    }

    /**
     * Prepends the pseudo group named 'All' to a group collection
     *
     * @param  \Illuminate\Database\Eloquent\Collection<int, Group>  $groups
     * @return \Illuminate\Database\Eloquent\Collection<int, Group>
     */
    public static function prependTheAllGroup(Collection $groups, User $user)
    {
        $theAllGroup = new Group([
            'name' => __('label.all'),
        ]);

        $theAllGroup->id                 = 0;
        $theAllGroup->twofaccounts_count = $user->twofaccounts->count();

        return $groups->prepend($theAllGroup);
    }

    /**
     * Set owner of given groups
     *
     * @param  \Illuminate\Database\Eloquent\Collection<int, Group>  $groups
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

        return Group::where('id', $id)
            ->where('user_id', $user->id)
            ->first();
    }
}
