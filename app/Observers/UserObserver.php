<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Storage;

class UserObserver
{
    /**
     * Handle the User "created" event.
     *
     * @codeCoverageIgnore
     */
    public function created(User $user) : void
    {
        //
    }

    /**
     * Handle the User "updated" event.
     *
     * @codeCoverageIgnore
     */
    public function updated(User $user) : void
    {
        //
    }

    /**
     * Handle the User "demoting" event.
     */
    public function demoting(User $user) : bool
    {
        // Prevent demotion of the only administrator
        if ($user->isLastAdministrator()) {
            Log::notice(sprintf('Demotion of user ID #%s denied, cannot demote the only administrator', $user->id));

            return false;
        }

        return true;
    }

    /**
     * Handle the User "deleting" event.
     */
    public function deleting(User $user) : bool
    {
        Log::info(sprintf('Deletion of User ID #%s requested by User ID #%s', $user->id, Auth::user()->id ?? 'unknown'));

        // Prevent deletion of the only administrator
        if ($user->isLastAdministrator()) {
            Log::notice(sprintf('Deletion of user ID #%s denied, cannot delete the only administrator', $user->id));

            return false;
        }

        // Deleting user's twofaccounts icon
        $iconPathes = $user->twofaccounts->filter(function ($twofaccount, $key) {
            return $twofaccount->icon;
        })->map(function ($twofaccount, $key) {
            return $twofaccount->icon;
        });
        Storage::disk('icons')->delete($iconPathes->toArray());

        return true;
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user) : void
    {
        // DB has cascade delete enabled to flush 2FA and Groups but,
        // for an unknown reason, SQLite refuses to delete these related.
        // (despite DB_FOREIGN_KEYS=true which is supposed to enable it)
        // So it ends with a direct db delete for SQLite...
        if (DB::getDriverName() === 'sqlite') {
            DB::table('twofaccounts')->where('user_id', $user->id)->delete();
            DB::table('groups')->where('user_id', $user->id)->delete();
        }

        // We flush webauthn credentials & tokens
        Password::broker('webauthn')->deleteToken($user);
        $user->flushCredentials();

        // And Passport tokens
        Password::broker()->deleteToken($user);
        DB::table('oauth_access_tokens')->where('user_id', $user->id)->delete();

        Log::info(sprintf('User ID #%s and all user traces deleted', $user->id));
    }

    /**
     * Handle the User "restored" event.
     *
     * @codeCoverageIgnore
     */
    public function restored(User $user) : void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     *
     * @codeCoverageIgnore
     */
    public function forceDeleted(User $user) : void
    {
        //
    }
}
