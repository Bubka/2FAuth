<?php

namespace App\Listeners;

use App\Events\GroupDeleting;
use App\Models\TwoFAccount;
use Illuminate\Support\Facades\Log;

class DissociateTwofaccountFromGroup
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
    public function handle(GroupDeleting $event)
    {
        TwoFAccount::where('group_id', $event->group->id)
            ->update(
                ['group_id' => null]
            );

        Log::info(sprintf('TwoFAccounts dissociated from group %s (id #%d)', var_export($event->group->name, true), $event->group->id));
    }
}
