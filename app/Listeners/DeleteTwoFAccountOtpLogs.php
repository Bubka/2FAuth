<?php

namespace App\Listeners;

use App\Events\TwoFAccountDeleted;
use App\Models\OtpLog;

class DeleteTwoFAccountOtpLogs
{
    /**
     * Handle the event.
     */
    public function handle(TwoFAccountDeleted $event) : void
    {
        OtpLog::query()
            ->where('twofaccount_id', $event->twofaccount->id)
            ->delete();
    }
}
