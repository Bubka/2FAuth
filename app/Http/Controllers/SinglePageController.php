<?php

namespace App\Http\Controllers;

use App\Events\ScanForNewReleaseCalled;
use App\Facades\Settings;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Vite;

class SinglePageController extends Controller
{
    /**
     * return the main view
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index()
    {
        event(new ScanForNewReleaseCalled);

        $settings           = Settings::all()->toJson();
        $proxyAuth          = config('auth.defaults.guard') === 'reverse-proxy-guard' ? true : false;
        $proxyLogoutUrl     = config('2fauth.config.proxyLogoutUrl') ? config('2fauth.config.proxyLogoutUrl') : false;
        $subdir             = config('2fauth.config.appSubdirectory') ? '/' . config('2fauth.config.appSubdirectory') : '';
        $defaultPreferences = collect(config('2fauth.preferences')); /** @phpstan-ignore-line */
        $isDemoApp          = config('2fauth.config.isDemoApp') ? 'true' : 'false';
        $isTestingApp       = config('2fauth.config.isTestingApp') ? 'true' : 'false';
        $lang               = App::getLocale();
        $locales            = collect(config('2fauth.locales'))->toJson(); /** @phpstan-ignore-line */
        $openidAuth         = config('services.openid.client_secret') ? true : false;
        $githubAuth         = config('services.github.client_secret') ? true : false;
        $installDocUrl      = config('2fauth.installDocUrl');
        $ssoDocUrl          = config('2fauth.ssoDocUrl');
        $exportSchemaUrl    = config('2fauth.exportSchemaUrl');
        $cspNonce           = Vite::cspNonce();
        $isSecure           = str_starts_with(config('app.url'), 'https');

        // if (Auth::user()->preferences)

        return view('landing')->with([
            'appSettings' => $settings,
            'appConfig'   => collect([
                'proxyAuth'      => $proxyAuth,
                'proxyLogoutUrl' => $proxyLogoutUrl,
                'sso'            => [
                    'openid' => $openidAuth,
                    'github' => $githubAuth,
                ],
                'subdirectory' => $subdir,
            ])->toJson(),
            'urls' => collect([
                'installDocUrl'   => $installDocUrl,
                'ssoDocUrl'       => $ssoDocUrl,
                'exportSchemaUrl' => $exportSchemaUrl,
            ]),
            'defaultPreferences' => $defaultPreferences,
            'subdirectory'       => $subdir,
            'isDemoApp'          => $isDemoApp,
            'isTestingApp'       => $isTestingApp,
            'lang'               => $lang,
            'locales'            => $locales,
            'cspNonce'           => $cspNonce,
            'isSecure'           => $isSecure,
        ]);
    }
}
