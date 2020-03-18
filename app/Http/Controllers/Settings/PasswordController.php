<?php

namespace App\Http\Controllers\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{

    /**
     * Update the user's password.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'currentPassword' => 'required',
            'password' => 'required|confirmed|min:8',
        ]);

        if (!Hash::check( $request->currentPassword, Auth::user()->password) ) {
            return response()->json(['message' => __('errors.wrong_current_password')], 400);
        }

        if (!config('app.options.isDemoApp') ) {
            $request->user()->update([
                'password' => bcrypt($request->password),
            ]);
        }

        return response()->json(['message' => __('auth.forms.password_successfully_changed')]);
    }
}