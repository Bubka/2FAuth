<?php

namespace App\Http\Middleware;

use Closure;
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
        \App::setLocale(SettingService::get('lang', 'en'));

        return $next($request);
    }
}
