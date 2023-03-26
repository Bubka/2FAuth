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
        $validated = $request->validated();

        if (! Hash::check($validated['currentPassword'], Auth::user()->password)) {
            Log::notice('Password update failed: wrong password provided');

            return response()->json(['message' => __('errors.wrong_current_password')], 400);
        }

        if (! config('2fauth.config.isDemoApp')) {
            $request->user()->update([
                'password' => bcrypt($validated['password']),
            ]);
            Log::info(sprintf('Password of user ID #%s updated', $request->user()->id));
        }

        return response()->json(['message' => __('auth.forms.password_successfully_changed')]);
    }
}
