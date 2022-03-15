<?php

namespace App\Api\v1\Controllers;

use App\Models\User;
use App\Api\v1\Requests\UserUpdateRequest;
use App\Api\v1\Resources\UserResource;
use App\Http\Controllers\Controller;

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
}