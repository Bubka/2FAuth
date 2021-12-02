<?php

namespace App\Api\v1\Controllers\Auth;

use App\Models\User;
use App\Api\v1\Requests\UserUpdateRequest;
use App\Api\v1\Resources\UserResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Get detailed information about a user
     * 
     * @return \App\Api\v1\Resources\UserResource
     */
    public function show()
    {
        $user = User::first();

        return $user
            ? new UserResource($user)
            : response()->json(['name' => null], 200);

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