<?php

namespace App\Listeners;

use App\Events\TwoFAccountDeleted;
use App\Models\TwoFAccountUserOrder;

class DeleteTwoFAccountUserOrders
{
    /**
     * Handle the event.
     */
    public function handle(TwoFAccountDeleted $event) : void
    {
        TwoFAccountUserOrder::query()
            ->where('twofaccount_id', $event->twofaccount->id)
            ->delete();
    }
}
