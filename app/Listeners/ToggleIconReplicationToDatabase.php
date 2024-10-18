<?php

namespace App\Listeners;

use App\Events\storeIconsInDatabaseSettingChanged;
use App\Facades\IconStore;

class ToggleIconReplicationToDatabase
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     */
    public function handle(storeIconsInDatabaseSettingChanged $event): void
    {
        IconStore::setDatabaseReplication($event->newValue);
    }
}
