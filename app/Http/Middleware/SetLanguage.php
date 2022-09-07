<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use App\Facades\Settings;

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
        // FI: Settings::get() always returns a fallback value
        $lang = Settings::get('lang');

        if($lang === 'browser') {
            $lang = config('app.fallback_locale');
            $accepted = $request->header("Accept-Language");

            if ($accepted) {
                $accepted = is_array($accepted) ? implode(',', $accepted) : $accepted;
                $prefLocales = array_reduce(
                    explode(',', $accepted),
                    function ($res, $el) { 
                        list($l, $q) = array_merge(explode(';q=', $el), [1]); 
                        $res[$l] = (float) $q; 
                        return $res;
                    },
                    []
                );
                arsort($prefLocales);

                // We only keep the primary language passed via the header.
                $lang = array_key_first($prefLocales);
            }
        }

        // If the language is not available (or partial), strings will be translated using the fallback language.
        App::setLocale($lang);

        return $next($request);
    }
}
