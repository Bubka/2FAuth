<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class SetLanguage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // 3 possible cases here:
        // - The http client send an accept-language header
        // - There is an authenticated user with a possible language set
        // - No language is specified at all
        //
        // Priority to the user preference, then the header request, otherwise the fallback language.
        // Note that if a user is authenticated later by the auth guard, the app locale
        // will be overriden if the user has set a specific language in its preferences.

        $lang     = config('app.fallback_locale');
        $accepted = str_replace(' ', '', $request->header('Accept-Language'));

        if ($accepted && $accepted !== '*') {
            $prefLocales = array_reduce(
                array_diff(explode(',', $accepted), ['*']),
                function ($langs, $langItem) {
                    [$langLong, $weight] = array_merge(explode(';q=', $langItem), [1]);
                    $langShort           = substr($langLong, 0, 2);
                    if (array_key_exists($langShort, $langs)) {
                        if ($langs[$langShort] < $weight) {
                            $langs[$langShort] = (float) $weight;
                        }
                    } else {
                        $langs[$langShort] = (float) $weight;
                    }

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

        $user = $request->user();
        if (! is_null($user) && $request->user()->preferences['lang'] != 'browser') {
            $lang = $request->user()->preferences['lang'];
        }

        // If the language is not available (or partial), strings will be translated using the fallback language.
        App::setLocale($lang);

        return $next($request);
    }
}
