<?php

namespace App\Http\Controllers;

use App\Facades\Settings;
use Illuminate\Support\Facades\App;

class SinglePageController extends Controller
{


    /**
     * return the main view
     * @return view
     */
    public function index()
    {
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
