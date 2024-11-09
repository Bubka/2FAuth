<?php

namespace App\Listeners;

use App\Events\StoreIconsInDatabaseSettingChanged;
use App\Facades\IconStore;

class ToggleIconReplicationToDatabase
{
    /**
     * Create the event listener.
     */
    public function __construct() {}

    /**
     * Handle the event.
     */
    public function handle(StoreIconsInDatabaseSettingChanged $event) : void
    {
        IconStore::setDatabaseReplication($event->newValue);
    }
}
