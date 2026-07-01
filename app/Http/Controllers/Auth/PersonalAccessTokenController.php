<?php

namespace App\Http\Controllers\Auth;

use App\Services\Auth\PassportTokenRepository;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Laravel\Passport\ClientRepository;
use Laravel\Passport\Passport;
use Laravel\Passport\PersonalAccessTokenResult;
use Laravel\Passport\Token;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class PersonalAccessTokenController
{
    /**
     * Create a controller instance.
     */
    public function __construct(
        protected PassportTokenRepository $tokenRepository,
        protected ValidationFactory $validation,
    ) {}

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

        return $this->tokenRepository->forUser($request->user())
            ->filter(
                fn (Token $token) : bool => ! $token->client->revoked && $token->client->hasGrantType('personal_access')
            )
            ->values();
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

        $this->validation->make($request->all(), [
            'name'   => ['required', 'max:255'],
            'scopes' => ['array', Rule::in(Passport::scopeIds())],
        ])->validate();

        return $request->user()->createToken(
            $request->name, $request->scopes ?: []
        );
    }

    /**
     * Delete the given token.
     */
    public function destroy(Request $request, string $tokenId) : Response
    {
        if (Gate::denies('manage-pat')) {
            throw new AccessDeniedHttpException(__('error.unsupported_with_sso_only'));
        }

        $token = $this->tokenRepository->findForUser(
            $tokenId, $request->user()
        );

        if (is_null($token)) {
            return new Response('', 404);
        }

        $token->revoke();

        return new Response('', Response::HTTP_NO_CONTENT);
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
