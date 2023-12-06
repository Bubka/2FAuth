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
    public function redirect(Request $request, $driver)
    {
        return Socialite::driver($driver)->redirect();
    }

    public function callback(Request $request, $driver)
    {
        $socialiteUser = Socialite::driver($driver)->user();

        /** @var User $user */
        $user = User::firstOrNew([
            'email' => $socialiteUser->getEmail(),
        ], [
            'name' => $socialiteUser->getName(),
            'password' => bcrypt(Str::random()),
        ]);

        if (!$user->exists && Settings::get('disableRegistrationSso')) {
            return response(401);
        }

        $user->last_seen_at = Carbon::now()->format('Y-m-d H:i:s');
        $user->save();

        Auth::guard()->login($user, true);

        return redirect('/accounts?authenticated');
    }
}
