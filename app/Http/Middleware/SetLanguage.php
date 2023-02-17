<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use Closure;
use Illuminate\Support\Facades\App;

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
        // 2 possible cases here:
        // - The http client send an accept-language header
        // - No language is specified
        //
        // We honor the language requested in the header or we use the fallback one.
        // Note that if a user is authenticated later by the auth guard, the app locale 
        // will be overriden if the user has set a specific language in its preferences.

        $lang     = config('app.fallback_locale');
        $accepted = str_replace(' ', '', $request->header('Accept-Language'));

        if ($accepted && $accepted !== '*') {
            $prefLocales = array_reduce(
                array_diff(explode(',', $accepted), ['*']),
                function ($langs, $langItem) {
                    [$langLong, $weight] = array_merge(explode(';q=', $langItem), [1]);
                    $langShort = substr($langLong, 0, 2);
                    if (array_key_exists($langShort, $langs)) {
                        if ($langs[$langShort] < $weight) {
                            $langs[$langShort] = (float) $weight;
                        }
                    }
                    else $langs[$langShort] = (float) $weight;

                    return $langs;
                },
                []
            );
            arsort($prefLocales);

            // We take the first accepted language available
            foreach ($prefLocales as $locale => $weight) {
                if (in_array($locale, config('2fauth.locales'))) {
                    $lang = $locale;
                    break;
                }
            }
        }

        // If the language is not available (or partial), strings will be translated using the fallback language.
        App::setLocale($lang);

        return $next($request);
    }
}
