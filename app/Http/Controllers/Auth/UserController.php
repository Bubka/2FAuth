<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\UserUpdateRequest;
use App\Http\Requests\UserDeleteRequest;
use App\Api\v1\Resources\UserResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{    
    /**
     * Update the user's profile information.
     *
     * @param  \App\Http\Requests\UserUpdateRequest $request
     * @return \App\Api\v1\Resources\UserResource|\Illuminate\Http\JsonResponse
     */
    public function update(UserUpdateRequest $request)
    {
        $user = $request->user();
        $validated = $request->validated();

        if (!Hash::check( $request->password, Auth::user()->password) ) {
            Log::notice('Account update failed: wrong password provided');
            return response()->json(['message' => __('errors.wrong_current_password')], 400);
        }

        if (!config('2fauth.config.isDemoApp') ) {
            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]);
        }
        Log::info('User account updated');

        return new UserResource($user);
    }

    
    /**
     * Delete the user's account.
     *
     * @param  \App\Http\Requests\UserDeleteRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(UserDeleteRequest $request)
    {
        Log::info('User deletion requested');
        $validated = $request->validated();

        if (!Hash::check( $validated['password'], Auth::user()->password) ) {
            return response()->json(['message' => __('errors.wrong_current_password')], 400);
        }

        try {
            DB::transaction(function () {
                DB::table('twofaccounts')->delete();
                DB::table('groups')->delete();
                DB::table('options')->delete();
                DB::table('web_authn_credentials')->delete();
                DB::table('web_authn_recoveries')->delete();
                DB::table('oauth_access_tokens')->delete();
                DB::table('oauth_auth_codes')->delete();
                DB::table('oauth_clients')->delete();
                DB::table('oauth_personal_access_clients')->delete();
                DB::table('oauth_refresh_tokens')->delete();
                DB::table('password_resets')->delete();
                DB::table('users')->delete();
            });

            Artisan::call('passport:install --force');
            Artisan::call('config:clear');
        }
        // @codeCoverageIgnoreStart
        catch (\Throwable $e) {
            Log::error('User deletion failed');
            return response()->json(['message' => __('errors.user_deletion_failed')], 400);
        }
        // @codeCoverageIgnoreEnd
        Log::info('User deleted');

        return response()->json(null, 204);
    }
}