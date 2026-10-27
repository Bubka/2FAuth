<?php

namespace App\Listeners;

use App\Events\TwoFAccountShareRevoked;
use App\Models\TwoFAccountUserFavorite;

class DeleteRevokedTwoFAccountUserFavorites
{
    /**
     * Handle the event.
     */
    public function handle(TwoFAccountShareRevoked $event) : void
    {
        $userIds = $event->recipients
            ->pluck('id')
            ->map(static fn ($id) => (int) $id)
            ->values();

        if ($userIds->isEmpty()) {
            return;
        }

        TwoFAccountUserFavorite::query()
            ->where('twofaccount_id', $event->twofaccount->id)
            ->whereIn('user_id', $userIds->all())
            ->delete();
    }
}
