<?php

namespace App\Http\Controllers\Auth;

use App\Api\v1\Resources\UserResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserDeleteRequest;
use App\Http\Requests\UserUpdateRequest;
use Illuminate\Support\Facades\Auth;
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

        $this->authorize('update', $user);

        if (config('auth.defaults.guard') === 'reverse-proxy-guard' || $user->oauth_provider) {
            Log::notice('Account update rejected: reverse-proxy-guard enabled or account from external sso provider');

            return response()->json(['message' => __('error.account_managed_by_external_provider')], 400);
        }

        if (! Hash::check($request->password, Auth::user()->password)) {
            Log::notice('Account update failed: wrong password provided');

            return response()->json(['message' => __('error.wrong_current_password')], 400);
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

        if (! Hash::check($validated['password'], Auth::user()->password)) {
            return response()->json(['message' => __('error.wrong_current_password')], 400);
        }

        // This will delete the user and all its 2FAs & Groups thanks to the onCascadeDelete constrains.
        // Deletion will not be done (and returns False) if the user is the only existing admin (see UserObserver clas)
        return $user->delete() === false
            ? response()->json([
                'message' => __('error.cannot_delete_the_only_admin'),
            ], 400)
            : response()->json(null, 204);
    }
}
