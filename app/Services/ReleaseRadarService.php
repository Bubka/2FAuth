<?php

namespace App\Services;

use App\Facades\Settings;
use App\Helpers\Helpers;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ReleaseRadarService
{
    /**
     * Run a scheduled release scan
     * 
     * @return void
     */
    public function scheduledScan() : void
    {
        if ((Settings::get('lastRadarScan') + 604800) < time()) {
            $this->newRelease();
        }
    }


    /**
     * Run a manual release scan
     * 
     * @return false|string False if no new release, the new release number otherwise
     */
    public function manualScan() : false|string
    {
        return $this->newRelease();
    }

    /**
     * Run a release scan
     * 
     * @return false|string False if no new release, the new release number otherwise
     */
    protected function newRelease() : false|string
    {
        if ($latestReleaseData = json_decode($this->getLatestReleaseData()))
        {
            $githubVersion = Helpers::cleanVersionNumber($latestReleaseData->tag_name);
            $installedVersion = Helpers::cleanVersionNumber(config('2fauth.version'));

            if ($githubVersion > $installedVersion && $latestReleaseData->prerelease == false && $latestReleaseData->draft == false) {
                Settings::set('latestRelease', $latestReleaseData->tag_name);
                
                return $latestReleaseData->tag_name;
            }
            else {
                Settings::delete('latestRelease');
            }

            Settings::set('lastRadarScan', time());
        }

        return false;
    }


    /**
     * Fetch releases on Github
     * 
     * @return string|null
     */
    protected function getLatestReleaseData() : string|null
    {
        try {
            $response = Http::retry(3, 100)
                ->get(config('2fauth.latestReleaseUrl'));
            
            if ($response->successful()) {
                return $response->body();
            }
        }
        catch (\Exception $exception) {
            Log::error('cannot reach latestReleaseUrl endpoint');
        }

        return null;
    }
}