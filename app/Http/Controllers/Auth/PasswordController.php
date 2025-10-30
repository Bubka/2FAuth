<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserPatchPwdRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UserPatchPwdRequest $request)
    {
        $user      = $request->user();
        $validated = $request->validated();

        if ($user->oauth_provider) {
            Log::notice('Password update rejected: external account from a sso provider');

            return response()->json(['message' => __('error.account_managed_by_external_provider')], 400);
        }

        if (! Hash::check($validated['currentPassword'], Auth::user()->password)) {
            Log::notice('Password update failed: wrong password provided');

            return response()->json(['message' => __('error.wrong_current_password')], 400);
        }

        if (! config('2fauth.config.isDemoApp')) {
            $user->update([
                'password' => Hash::make($validated['password']),
            ]);
            Log::info(sprintf('Password of user ID #%s updated', $user->id));
        }

        return response()->json(['message' => __('notification.password_successfully_changed')]);
    }
}
