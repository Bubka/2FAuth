<?php

namespace App\Api\v1\Controllers;

use App\Models\User;
use App\Api\v1\Resources\UserResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Get detailed information about a user
     * 
     * @return \App\Api\v1\Resources\UserResource|\Illuminate\Http\JsonResponse
     */
    public function show(Request $request)
    {
        // 2 cases:
        // - The method is called from a protected route > we return the request's authenticated user
        // - The method is called from a guest route > we fetch a possible registered user
        $user = $request->user() ?: User::first();

        return $user
            ? new UserResource($user)
            : response()->json(['name' => null], 200);

    }
}