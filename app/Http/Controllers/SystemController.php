<?php

namespace App\Http\Controllers;

use App\Services\ReleaseRadarService;
use App\Http\Controllers\Controller;
use App\Facades\Settings;
use Illuminate\Http\Request;
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
        $infos = array();
        $infos['Date']              = date(DATE_RFC2822);
        $infos['userAgent']         = $request->header('user-agent');
        // App info
        $infos['Version']           = config('2fauth.version');
        $infos['Environment']       = config('app.env');
        $infos['Debug']             = var_export(config('app.debug'), true);
        $infos['Cache driver']      = config('cache.default');
        $infos['Log channel']       = config('logging.default');
        $infos['Log level']         = env('LOG_LEVEL');
        $infos['DB driver']         = DB::getDriverName();
        // PHP info
        $infos['PHP version']       = PHP_VERSION;
        $infos['Operating system']  = PHP_OS;
        $infos['interface']         = PHP_SAPI;
        // Auth info
        if ($request->user()) {
            $infos['Auth guard']    = config('auth.defaults.guard');
            if ($infos['Auth guard'] === 'reverse-proxy-guard') {
                $infos['Auth proxy header for user'] = config('auth.auth_proxy_headers.user');
                $infos['Auth proxy header for email'] = config('auth.auth_proxy_headers.email');
            }
            $infos['webauthn user verification'] = config('larapass.login_verify');
            $infos['Trusted proxies']  = config('2fauth.trustedProxies') ?: 'none';
        }
        // User info
        if ($request->user()) {
            $infos['options'] = Settings::all()->toArray();
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
