<?php

namespace App\Http\Controllers\Auth;

use App\Api\v1\Resources\UserResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserDeleteRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Update the user's profile information.
     *
     * @return \App\Api\v1\Resources\UserResource|\Illuminate\Http\JsonResponse
     */
    public function update(UserUpdateRequest $request)
    {
        $user      = $request->user();
        $validated = $request->validated();

        if (! Hash::check($request->password, Auth::user()->password)) {
            Log::notice('Account update failed: wrong password provided');

            return response()->json(['message' => __('errors.wrong_current_password')], 400);
        }

        if (! config('2fauth.config.isDemoApp')) {
            $user->update([
                'name'  => $validated['name'],
                'email' => $validated['email'],
            ]);
        }
        Log::info(sprintf('Account of user ID #%s updated', $user->id));

        return new UserResource($user);
    }

    /**
     * Delete the user's account.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(UserDeleteRequest $request)
    {
        $validated = $request->validated();
        $user      = Auth::user();

        Log::info(sprintf('Deletion of user ID #%s requested', $user->id));

        if ($user->is_admin && User::admins()->count() == 1) {
            return response()->json(['message' => __('errors.cannot_delete_the_only_admin')], 400);
        }

        if (! Hash::check($validated['password'], Auth::user()->password)) {
            return response()->json(['message' => __('errors.wrong_current_password')], 400);
        }

        try {
            DB::transaction(function () use ($user) {
                DB::table('twofaccounts')->where('user_id', $user->id)->delete();
                DB::table('groups')->where('user_id', $user->id)->delete();
                DB::table('webauthn_credentials')->where('authenticatable_id', $user->id)->delete();
                DB::table('webauthn_recoveries')->where('email', $user->email)->delete();
                DB::table('oauth_access_tokens')->where('user_id', $user->id)->delete();
                DB::table('password_resets')->where('email', $user->email)->delete();
                DB::table('users')->where('id', $user->id)->delete();
            });
        }
        // @codeCoverageIgnoreStart
        catch (\Throwable $e) {
            Log::error(sprintf('Deletion of user ID #%s failed, transaction has been rolled-back', $user->id));

            return response()->json(['message' => __('errors.user_deletion_failed')], 400);
        }
        // @codeCoverageIgnoreEnd

        Log::info(sprintf('User ID #%s deleted', $user->id));

        return response()->json(null, 204);
    }
}
