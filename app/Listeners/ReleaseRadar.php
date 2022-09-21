<?php

namespace App\Listeners;

use App\Events\ReleaseRadarActivated;
use App\Services\ReleaseRadarService;
use Illuminate\Support\Facades\App;
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
     * @param  \App\Events\ReleaseRadarActivated  $event
     * @return void
     */
    public function handle(ReleaseRadarActivated $event)
    {
        Log::info('Release radar activated');

        $releaseRadarService = App::make(ReleaseRadarService::class);
        $releaseRadarService->scanForNewRelease();
    }
}