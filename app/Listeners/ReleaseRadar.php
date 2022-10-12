<?php

namespace App\Listeners;

use App\Events\ScanForNewReleaseCalled;
use App\Services\ReleaseRadarService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class ReleaseRadar
{
    /**
     * @var ReleaseRadarService $releaseRadar
     */
    protected $releaseRadar;


    /**
     * Create the event listener.
     * 
     * @param  \App\Services\ReleaseRadarService  $releaseRadar
     *
     * @return void
     */
    public function __construct(ReleaseRadarService $releaseRadar)
    {
        $this->releaseRadar = $releaseRadar;
    }


    /**
     * Handle the event.
     *
     * @param  \App\Events\ScanForNewReleaseCalled  $event
     * @return void
     */
    public function handle(ScanForNewReleaseCalled $event)
    {
        $this->releaseRadar->scheduledScan();
        Log::info('Scheduled release scan complete');
    }
}