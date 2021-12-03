<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Facades\App\Services\SettingService;

class SetLanguage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // 3 possible cases here:
        // - The user has choosen a specific language among those available in the Setting view of 2FAuth
        // - The client send an accept-language header
        // - No language is passed from the client
        //
        // We prioritize the user defined one, then the request header one, and finally the fallback one.
        // FI: SettingService::get() always returns a fallback value
        $lang = SettingService::get('lang');

        if($lang === 'browser') {
            if ($request->hasHeader("Accept-Language")) {
                // We only keep the primary language passed via the header.
                $lang = head(explode(',', $request->header("Accept-Language")));
            }
            else $lang = config('app.fallback_locale');
        }

        // If the language is not available (or partial), strings will be translated using the fallback language.
        App::setLocale($lang);

        return $next($request);
    }
}
