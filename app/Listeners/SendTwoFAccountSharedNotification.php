<?php

namespace App\Listeners;

use App\Events\TwoFAccountShared;
use App\Listeners\Traits\HasLocalizedNotification;
use App\Models\User;
use App\Notifications\TwoFAccountSharedNotification;

class SendTwoFAccountSharedNotification
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
    public function handle(TwoFAccountShared $event) : void
    {
        $event->recipients->each(function (User $recipient) use ($event) {
            if ($recipient->preferences['notifyOnShare'] == true) {
                $recipient->notify(
                    (new TwoFAccountSharedNotification($event->twofaccount, $event->actor->name, $event->isScopeAllUsers()))
                        ->locale($this->userLocale($recipient))
                );
            }
        });
    }
}
