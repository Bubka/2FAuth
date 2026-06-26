<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\ClientRepository;
use Laravel\Passport\Http\Controllers\PersonalAccessTokenController as PassportPatController;
use Laravel\Passport\PersonalAccessTokenResult;
use Laravel\Passport\Token;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class PersonalAccessTokenController extends PassportPatController
{
    /**
     * Get all of the personal access tokens for the authenticated user.
     *
     * @return Collection<int, Token>|JsonResponse
     */
    public function forUser(Request $request)
    {
        if (Gate::denies('manage-pat')) {
            throw new AccessDeniedHttpException(__('error.unsupported_with_sso_only'));
        }

        $this->ensurePersonalAccessClientExists($request->user()->getProviderName());

        return parent::forUser($request);
    }

    /**
     * Create a new personal access token for the user.
     *
     * @return PersonalAccessTokenResult<mixed>
     */
    public function store(Request $request) : PersonalAccessTokenResult
    {
        if (Gate::denies('manage-pat')) {
            throw new AccessDeniedHttpException(__('error.unsupported_with_sso_only'));
        }

        $this->ensurePersonalAccessClientExists($request->user()->getProviderName());

        return parent::store($request);
    }

    /**
     * Delete the given token.
     */
    public function destroy(Request $request, string $tokenId) : Response
    {
        if (Gate::denies('manage-pat')) {
            throw new AccessDeniedHttpException(__('error.unsupported_with_sso_only'));
        }

        return parent::destroy($request, $tokenId);
    }

    /**
     * Ensure a personal access client exists for the given user provider.
     */
    protected function ensurePersonalAccessClientExists(string $userProvider) : void
    {
        $clients = app()->make(ClientRepository::class);

        try {
            $personalAccessClient = $clients->personalAccessClient($userProvider);
        } catch (\RuntimeException $e) {
            $clients->createPersonalAccessGrantClient(config('app.name'), $userProvider);
        }
    }
}
