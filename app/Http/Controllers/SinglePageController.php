<?php

namespace App\Http\Controllers;

use App\Facades\Settings;
use Illuminate\Support\Facades\App;
use App\Events\ScanForNewReleaseCalled;

class SinglePageController extends Controller
{


    /**
     * return the main view
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index()
    {
        event(new ScanForNewReleaseCalled());

        return view('landing')->with([
            'appSettings' => Settings::all()->toJson(),
            'appConfig' => collect([
                'proxyAuth' => config("auth.defaults.guard") === 'reverse-proxy-guard' ? true : false,
                'proxyLogoutUrl' => config("2fauth.config.proxyLogoutUrl") ? config("2fauth.config.proxyLogoutUrl") : false,
            ])->toJson(),
            'lang' => App::currentLocale(),
            'isDemoApp' => config("2fauth.config.isDemoApp") ? 'true' : 'false',
            'isTestingApp' => config("2fauth.config.isTestingApp") ? 'true' : 'false',
            'locales' => collect(config("2fauth.locales"))->toJson()
        ]);
    }
}
