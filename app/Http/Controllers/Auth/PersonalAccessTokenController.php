<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Http\Controllers\PersonalAccessTokenController as PassportPatController;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class PersonalAccessTokenController extends PassportPatController
{
    /**
     * Get all of the personal access tokens for the authenticated user.
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, \Laravel\Passport\Token>|\Illuminate\Http\JsonResponse
     */
    public function forUser(Request $request)
    {
        if (Gate::denies('manage-pat')) {
            throw new AccessDeniedHttpException(__('error.unsupported_with_sso_only'));
        }

        return parent::forUser($request);
    }

    /**
     * Create a new personal access token for the user.
     *
     * @return \Laravel\Passport\PersonalAccessTokenResult|\Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        if (Gate::denies('manage-pat')) {
            throw new AccessDeniedHttpException(__('error.unsupported_with_sso_only'));
        }

        return parent::store($request);
    }

    /**
     * Delete the given token.
     *
     * @param  string  $tokenId
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $tokenId)
    {
        if (Gate::denies('manage-pat')) {
            throw new AccessDeniedHttpException(__('error.unsupported_with_sso_only'));
        }

        return parent::destroy($request, $tokenId);
    }
}
