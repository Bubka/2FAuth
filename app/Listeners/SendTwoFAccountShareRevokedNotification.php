<?php

namespace App\Listeners;

use App\Events\TwoFAccountShareRevoked;
use App\Listeners\Traits\HasLocalizedNotification;
use App\Models\User;
use App\Notifications\TwoFAccountShareRevokedNotification;

class SendTwoFAccountShareRevokedNotification
{
    use HasLocalizedNotification;

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
     */
    public function handle(TwoFAccountShareRevoked $event) : void
    {
        $event->recipients->each(function (User $recipient) use ($event) {
            if ($recipient->preferences['notifyOnShare'] == true) {
                $recipient->notify(
                    (new TwoFAccountShareRevokedNotification($event->twofaccount, $event->actor->name, $event->isScopeAllUsers()))
                        ->locale($this->userLocale($recipient))
                );
            }
        });
    }
}
