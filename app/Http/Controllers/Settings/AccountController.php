<?php

namespace App\Http\Controllers\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{


    /**
     * get detailed information about a user
     * @return [type] [description]
     */
    public function show()
    {
        return response()->json(Auth::user()->only('name', 'email'), 200);
    }


    /**
     * Update the user's profile information.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = $request->user();

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.Auth::id(),
            'password' => 'required',
        ]);

        if (!Hash::check( $request->password, Auth::user()->password) ) {
            return response()->json(['message' => __('errors.wrong_current_password')], 400);
        }

        if (!config('app.options.isDemoApp') ) {
            tap($user)->update($request->only('name', 'email'));
        }        

        return response()->json([
                'message' => __('auth.forms.profile_saved'),
                'username' => $request->name
            ]);
    }
}