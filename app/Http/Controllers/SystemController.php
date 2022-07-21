<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\SettingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SystemController extends Controller
{
    /**
     * The Settings Service instance.
     */
    protected SettingService $settingService;


    /**
     * Create a new controller instance.
     */
    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }


    /**
     * Get detailed information about the current installation
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function infos(Request $request)
    {
        $infos['Date']               = date(DATE_RFC2822);
        $infos['userAgent']        = $request->header('user-agent');
        // App info
        $infos['Version']          = config('2fauth.version');
        $infos['Environment']          = config('app.env');
        $infos['Debug']        = var_export(config('app.debug'), true);
        $infos['Cache driver']     = config('cache.default');
        $infos['Log channel']      = config('logging.default');
        $infos['Log level']     = env('LOG_LEVEL');
        $infos['DB driver']    = DB::getDriverName();
        // PHP info
        $infos['PHP version'] = PHP_VERSION;
        $infos['Operating system']      = PHP_OS;
        $infos['interface']       = PHP_SAPI;
        // Auth info
        $infos['Auth guard']        = config('auth.defaults.guard');
        if ($infos['Auth guard'] === 'reverse-proxy-guard') {
            $infos['Auth proxy header for user'] = config('auth.auth_proxy_headers.user');
            $infos['Auth proxy header for email'] = config('auth.auth_proxy_headers.email');
        }
        $infos['webauthn user verification'] = config('larapass.login_verify');
        $infos['Trusted proxies']  = config('2fauth.trustedProxies') ?: 'none';
        // User info
        if ($request->user()) {
            $infos['options']     = $this->settingService->all()->toArray();
        }

        return response()->json($infos);
    }
}