<?php

namespace App\Listeners;

use App\Events\TwoFAccountDeleted;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CleanIconStorage
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
    public function handle(TwoFAccountDeleted $event)
    {
        Storage::disk('icons')->delete($event->twofaccount->icon ?? []);
        Log::info(sprintf('Icon cleaned for deleted TwoFAccount #%d', $event->twofaccount->id));
    }
}
