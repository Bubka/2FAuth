<?php

namespace App\Api\v1\Controllers\Auth;

use App\Api\v1\Requests\UserPatchPwdRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{

    /**
     * Update the user's password.
     *
     * @param  \App\Api\v1\Requests\UserPatchPwdRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UserPatchPwdRequest $request)
    {
        $validated = $request->validated();

        if (!Hash::check( $validated['currentPassword'], Auth::user()->password) ) {
            return response()->json(['message' => __('errors.wrong_current_password')], 400);
        }

        if (!config('2fauth.config.isDemoApp') ) {
            $request->user()->update([
                'password' => bcrypt($validated['password']),
            ]);
        }

        return response()->json(['message' => __('auth.forms.password_successfully_changed')]);
    }
}