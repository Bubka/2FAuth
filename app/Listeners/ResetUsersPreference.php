<?php

namespace App\Listeners;

use App\Events\GroupDeleted;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class ResetUsersPreference
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle(GroupDeleted $event)
    {
        // a group is possibly set as the default group or the active group for some users.
        // In this case, after the group has been deleted, we must reset:
        //      - the 'defaultGroup' preference to "No group" (groupId = 0)
        //      - the 'activeGroup' preference to the pseudo "All" group (groupId = 0)
        foreach (User::all() as $user) {
            if ($user->preferences['defaultGroup'] == $event->group->id) {
                $user['preferences->defaultGroup'] = 0;
            }

            if ($user->preferences['activeGroup'] == $event->group->id) {
                $user['preferences->activeGroup'] = 0;
            }

            if ($user->isDirty()) {
                $user->save();
                Log::info(sprintf('Group %s (id #%d) removed from user %s (id #%d) preferences', var_export($event->group->name, true), $event->group->id, var_export($user->name, true), $user->id));
            }
        }
    }
}
