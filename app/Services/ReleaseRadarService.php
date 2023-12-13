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
     * @return string|null|false False if no new release, null if check failed, the new release number otherwise
     */
    public static function manualScan() : string|null|false
    {
        return self::newRelease();
    }

    /**
     * Run a release scan
     *
     * @return string|null|false False if no new release, null if check failed, the new release number otherwise
     */
    protected static function newRelease() : string|null|false
    {
        Log::info('Release scan started');
        $latestRelease = self::getLatestReleaseData();

        if ($latestRelease) {
            $latestReleaseData = json_decode($latestRelease);
            $githubVersion    = Helpers::cleanVersionNumber($latestReleaseData->tag_name);
            $installedVersion = Helpers::cleanVersionNumber(config('2fauth.version'));

            if ($githubVersion && $installedVersion) {
                if (version_compare($githubVersion, $installedVersion) > 0 && $latestReleaseData->prerelease == false && $latestReleaseData->draft == false) {
                    Settings::set('latestRelease', $latestReleaseData->tag_name);

                    Log::info(sprintf('New release found: %s', var_export($latestReleaseData->tag_name, true)));

                    return $latestReleaseData->tag_name;
                } else {
                    Settings::delete('latestRelease');
                }
            }
        }

        return $latestRelease ? false : null;
    }

    /**
     * Fetch releases on Github
     */
    protected static function getLatestReleaseData() : string|null
    {
        $url = config('2fauth.latestReleaseUrl');

        try {
            $response = Http::withOptions([
                'proxy' => config('2fauth.config.outgoingProxy'),
            ])->retry(3, 100)->get($url);

            if ($response->successful()) {
                Settings::set('lastRadarScan', time());

                return $response->body();
            }
        } catch (\Exception $exception) {
            Log::error(sprintf('cannot reach %s endpoint', var_export($url, true)));
        }

        return null;
    }
}
