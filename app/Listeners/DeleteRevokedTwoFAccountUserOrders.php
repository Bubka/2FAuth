<?php

namespace App\Listeners;

use App\Events\TwoFAccountShareRevoked;
use App\Models\TwoFAccountUserOrder;

class DeleteRevokedTwoFAccountUserOrders
{
    /**
     * Handle the event.
     */
    public function handle(TwoFAccountShareRevoked $event) : void
    {
        $recipientIds = $event->recipients
            ->pluck('id')
            ->map(static fn ($id) => (int) $id)
            ->values();

        if ($recipientIds->isEmpty()) {
            return;
        }

        TwoFAccountUserOrder::query()
            ->where('twofaccount_id', $event->twofaccount->id)
            ->whereIn('user_id', $recipientIds->all())
            ->delete();
    }
}
