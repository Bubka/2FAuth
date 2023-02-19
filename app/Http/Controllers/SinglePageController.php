<?php

namespace App\Http\Controllers;

use App\Events\ScanForNewReleaseCalled;
use App\Facades\Settings;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class SinglePageController extends Controller
{
    /**
     * return the main view
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index()
    {
        event(new ScanForNewReleaseCalled());

        $subdir = config('2fauth.config.appSubdirectory') ? '/' . config('2fauth.config.appSubdirectory') : '';

        return view('landing')->with([
            'appSettings' => Settings::all()->toJson(),
            'appConfig'   => collect([
                'proxyAuth'      => config('auth.defaults.guard') === 'reverse-proxy-guard' ? true : false,
                'proxyLogoutUrl' => config('2fauth.config.proxyLogoutUrl') ? config('2fauth.config.proxyLogoutUrl') : false,
                'subdirectory'   => $subdir,
            ])->toJson(),
            'userPreferences' => Auth::user()->preferences ?? collect(config('2fauth.preferences')),
            'subdirectory'    => $subdir,
            'isDemoApp'       => config('2fauth.config.isDemoApp') ? 'true' : 'false',
            'isTestingApp'    => config('2fauth.config.isTestingApp') ? 'true' : 'false',
            'lang'            => App::getLocale(),
            'locales'         => collect(config('2fauth.locales'))->toJson(), /** @phpstan-ignore-line */
        ]);
    }
}
