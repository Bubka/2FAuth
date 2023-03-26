<?php

namespace App\Listeners;

use App\Events\ScanForNewReleaseCalled;
use App\Services\ReleaseRadarService;

class ReleaseRadar
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
    public function handle(ScanForNewReleaseCalled $event)
    {
        $releaseRadarService = app()->make(ReleaseRadarService::class);
        $releaseRadarService::scheduledScan();
    }
}
