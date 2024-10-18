<?php

namespace App\Listeners;

use App\Events\TwoFAccountDeleted;
use App\Facades\IconStore;
use Illuminate\Support\Facades\Log;

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
        IconStore::delete($event->twofaccount->icon ?? []);
        
        Log::info(sprintf('Icon cleaned for deleted TwoFAccount #%d', $event->twofaccount->id));
    }
}
