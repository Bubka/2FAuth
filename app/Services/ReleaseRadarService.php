<?php

namespace App\Services;

use App\Facades\Settings;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ReleaseRadarService
{
    /**
     * 
     */
    public function scanForNewRelease() : void
    {
        // Only if the last check is old enough
        // if ((Settings::get('lastRadarScan') + 604800) < time()) {
            if ($latestReleaseData = json_decode($this->GetLatestReleaseData()))
            {
                if ($latestReleaseData->prerelease == false && $latestReleaseData->draft == false) {

                    Settings::set('lastRadarScan', time());
                    Settings::set('lastRadarScan', time());
                }
            }

            return $latestReleaseData;
        // }

        // tag_name
        // prerelease
        // draft
    }


    /**
     * Fetch releases on Github
     * 
     * @return string|null
     */
    protected function GetLatestReleaseData() : string|null
    {
        return null;
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