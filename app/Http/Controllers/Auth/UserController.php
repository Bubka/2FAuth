<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\UserUpdateRequest;
use App\Api\v1\Resources\UserResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Exceptions\UnsupportedWithReverseProxyException;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $authGuard = config('auth.defaults.guard');

        if ($authGuard === 'reverse-proxy-guard') {
            throw new UnsupportedWithReverseProxyException();
        }
    }

    
    /**
     * Update the user's profile information.
     *
     * @param  \App\Api\v1\Requests\UserUpdateRequest $request
     * @return \App\Api\v1\Resources\UserResource
     */
    public function update(UserUpdateRequest $request)
    {
        $user = $request->user();
        $validated = $request->validated();

        if (!Hash::check( $request->password, Auth::user()->password) ) {
            return response()->json(['message' => __('errors.wrong_current_password')], 400);
        }

        if (!config('2fauth.config.isDemoApp') ) {
            tap($user)->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]);
        }        

        return new UserResource($user);
    }
}