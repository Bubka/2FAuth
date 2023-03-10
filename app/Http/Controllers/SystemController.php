<?php

namespace App\Http\Controllers;

use App\Facades\Settings;
use App\Services\ReleaseRadarService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SystemController extends Controller
{
    /**
     * Get detailed information about the current installation
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function infos(Request $request)
    {
        $infos                        = [];
        $infos['common']['Date']      = date(DATE_RFC2822);
        $infos['common']['userAgent'] = $request->header('user-agent');
        // App info
        $infos['common']['Version']      = config('2fauth.version');
        $infos['common']['Environment']  = config('app.env');
        $infos['common']['Install path'] = '/' . config('2fauth.config.appSubdirectory');
        $infos['common']['Debug']        = var_export(config('app.debug'), true);
        $infos['common']['Cache driver'] = config('cache.default');
        $infos['common']['Log channel']  = config('logging.default');
        $infos['common']['Log level']    = env('LOG_LEVEL');
        $infos['common']['DB driver']    = DB::getDriverName();
        // PHP info
        $infos['common']['PHP version']      = PHP_VERSION;
        $infos['common']['Operating system'] = PHP_OS;
        $infos['common']['interface']        = PHP_SAPI;
        // Auth & Security infos
        if (! is_null($request->user())) {
            $infos['common']['Auth guard'] = config('auth.defaults.guard');
            if ($infos['common']['Auth guard'] === 'reverse-proxy-guard') {
                $infos['common']['Auth proxy logout url']       = config('2fauth.config.proxyLogoutUrl');
                $infos['common']['Auth proxy header for user']  = config('auth.auth_proxy_headers.user');
                $infos['common']['Auth proxy header for email'] = config('auth.auth_proxy_headers.email');
            }
            $infos['common']['webauthn user verification'] = config('webauthn.user_verification');
            $infos['common']['Trusted proxies']            = config('2fauth.config.trustedProxies') ?: 'none';

            // Admin settings
            if ($request->user()->is_admin == true) {
                $infos['admin_settings']['useEncryption']  = Settings::get('useEncryption');
                $infos['admin_settings']['lastRadarScan']  = Carbon::parse(Settings::get('lastRadarScan'))->format('Y-m-d H:i:s');
                $infos['admin_settings']['checkForUpdate'] = Settings::get('CheckForUpdate');
            }
        }
        // User info
        if ($request->user()) {
            $infos['user_preferences'] = $request->user()->preferences->toArray();
        }

        return response()->json($infos);
    }

    /**
     * Get latest release
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function latestRelease(Request $request, ReleaseRadarService $releaseRadar)
    {
        $release = $releaseRadar->manualScan();

        return response()->json(['newRelease' => $release]);
    }
}
