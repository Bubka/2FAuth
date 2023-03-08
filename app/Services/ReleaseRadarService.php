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
    public static function scheduledScan() : void
    {
        if ((Settings::get('lastRadarScan') + (60 * 60 * 24 * 7)) < time()) {
            self::newRelease();
        }
    }

    /**
     * Run a manual release scan
     *
     * @return false|string False if no new release, the new release number otherwise
     */
    public static function manualScan() : false|string
    {
        return self::newRelease();
    }

    /**
     * Run a release scan
     *
     * @return false|string False if no new release, the new release number otherwise
     */
    protected static function newRelease() : false|string
    {
        if ($latestReleaseData = json_decode(self::getLatestReleaseData())) {
            $githubVersion    = Helpers::cleanVersionNumber($latestReleaseData->tag_name);
            $installedVersion = Helpers::cleanVersionNumber(config('2fauth.version'));

            if ($githubVersion && $installedVersion) {
                if (version_compare($githubVersion, $installedVersion) > 0 && $latestReleaseData->prerelease == false && $latestReleaseData->draft == false) {
                    Settings::set('latestRelease', $latestReleaseData->tag_name);

                    return $latestReleaseData->tag_name;
                } else {
                    Settings::delete('latestRelease');
                }
            }
        }

        return false;
    }

    /**
     * Fetch releases on Github
     *
     * @return string|null
     */
    protected static function getLatestReleaseData() : string|null
    {
        try {
            $response = Http::retry(3, 100)
                ->get(config('2fauth.latestReleaseUrl'));

            if ($response->successful()) {
                Settings::set('lastRadarScan', time());

                return $response->body();
            }
        } catch (\Exception $exception) {
            Log::error('cannot reach latestReleaseUrl endpoint');
        }

        return null;
    }
}
