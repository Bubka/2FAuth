<?php

namespace App\Listeners;

use App\Events\ScanForNewReleaseCalled;
use App\Services\ReleaseRadarService;
use Illuminate\Support\Facades\Log;

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
     * @param  \App\Events\ScanForNewReleaseCalled  $event
     * @return void
     */
    public function handle(ScanForNewReleaseCalled $event)
    {
        $releaseRadarService = app()->make(ReleaseRadarService::class);
        $releaseRadarService::scheduledScan();
        
        Log::info('Scheduled release scan complete');
    }
}
