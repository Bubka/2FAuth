<?php

namespace App\Http\Controllers\Auth;

use App\Facades\Settings;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    /**
     * Redirect to the provider's authentication url
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Illuminate\Http\RedirectResponse
     */
    public function redirect(Request $request, string $driver)
    {
        if (! config('services.' . $driver . '.client_id') || ! config('services.' . $driver . '.client_secret')) {
            return redirect('/error?err=sso_bad_provider_setup');
        }

        return Settings::get('enableSso')
            ? Socialite::driver($driver)->redirect()
            : redirect('/error?err=sso_disabled');
    }

    /**
     * Register (if needed) the user and authenticate him
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function callback(Request $request, string $driver)
    {
        try {
            $socialiteUser = Socialite::driver($driver)->user();
        } catch (\Exception $e) {
            return redirect('/error?err=sso_failed');
        }

        $uniqueName     = $socialiteUser->getId() . '@' . $driver;
        $socialiteEmail = $socialiteUser->getEmail() ?? $uniqueName;
        $socialiteName  = ($socialiteUser->getNickname() ?? $socialiteUser->getName()) . ' (' . $uniqueName . ')';

        /** @var User|null $user */
        $user = User::firstOrNew([
            'oauth_id'       => $socialiteUser->getId(),
            'oauth_provider' => $driver,
        ]);

        if (! $user->exists) {
            if (User::where('email', $socialiteEmail)->exists()) {
                return redirect('/error?err=sso_email_already_used');
            } elseif (User::count() === 0) {
                $user->promoteToAdministrator();
            } elseif (Settings::get('disableRegistration')) {
                return redirect('/error?err=sso_no_register');
            }
            $user->password = bcrypt(Str::random());
        }

        $user->email        = $socialiteEmail;
        $user->name         = $socialiteName;
        $user->last_seen_at = Carbon::now()->format('Y-m-d H:i:s');
        $user->save();

        Auth::guard()->login($user);

        return redirect('/accounts');
    }
}
