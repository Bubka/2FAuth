<?php

namespace App\Listeners;

use App\TwoFAccount;
use App\Events\GroupDeleting;
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
     * @param  GroupDeleting  $event
     * @return void
     */
    public function handle(GroupDeleting $event)
    {
        TwoFAccount::where('group_id', $event->group->id)
            ->update(
                ['group_id' => NULL]
            );
        
        Log::info(sprintf('TwoFAccounts dissociated from group #%d', $event->group->id));
    }
}
