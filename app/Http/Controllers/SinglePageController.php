<?php

namespace App\Http\Controllers;

use App\Events\ScanForNewReleaseCalled;
use App\Facades\Settings;
use Illuminate\Support\Facades\App;
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
        $appSettings = Settings::all();

        if ($appSettings['checkForUpdate'] == true) {
            event(new ScanForNewReleaseCalled);
        }

        // We only share necessary and acceptable values with the HTML front-end.
        // But all the properties have to be pushed to init the appSetting store state correctly,
        // so we set them to null, they will be fed later by the front-end
        $publicSettings = $appSettings->only([
            'disableRegistration',
            'enableSso',
            'useSsoOnly',
        ]);
        $settings = $appSettings->map(function (mixed $item, string $key) {
            return null;
        })->merge($publicSettings)->toJson();

        $proxyAuth          = config('auth.defaults.guard') === 'reverse-proxy-guard' ? true : false;
        $proxyLogoutUrl     = config('2fauth.config.proxyLogoutUrl') ? config('2fauth.config.proxyLogoutUrl') : false;
        $subdir             = config('2fauth.config.appSubdirectory') ? '/' . config('2fauth.config.appSubdirectory') : '';
        $defaultPreferences = collect(config('2fauth.preferences')); /** @phpstan-ignore-line */
        $lockedPreferences  = collect(config('2fauth.lockedPreferences')); /** @phpstan-ignore-line */
        $isDemoApp          = config('2fauth.config.isDemoApp') ? 'true' : 'false';
        $isTestingApp       = config('2fauth.config.isTestingApp') ? 'true' : 'false';
        $lang               = App::getLocale();
        $locales            = collect(config('2fauth.locales'))->toJson(); /** @phpstan-ignore-line */
        $openidAuth         = config('services.openid.client_secret') ? true : false;
        $githubAuth         = config('services.github.client_secret') ? true : false;
        $installDocUrl      = config('2fauth.installDocUrl');
        $ssoDocUrl          = config('2fauth.ssoDocUrl');
        $exportSchemaUrl    = config('2fauth.exportSchemaUrl');
        $isSecure           = str_starts_with(config('app.url'), 'https');

        $viewData = [
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
            'lockedPreferences'  => $lockedPreferences,
            'subdirectory'       => $subdir,
            'isDemoApp'          => $isDemoApp,
            'isTestingApp'       => $isTestingApp,
            'lang'               => $lang,
            'locales'            => $locales,
            'isSecure'           => $isSecure,
        ];

        if (config('2fauth.config.contentSecurityPolicy')) {
            $viewData['cspNonce'] = Vite::cspNonce();
        }

        return view('landing')->with($viewData);
    }
}
