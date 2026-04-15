<?php

namespace App\Listeners;

use App\Events\TwoFAccountOwnershipTransferred;
use App\Facades\TwoFAccounts;

class PruneTwoFAccountUserOrdersAfterOwnershipTransfer
{
    /**
     * Handle the event.
     */
    public function handle(TwoFAccountOwnershipTransferred $event) : void
    {
        TwoFAccounts::pruneUsersWithoutAccessForAccount($event->twofaccount->refresh());
    }
}
