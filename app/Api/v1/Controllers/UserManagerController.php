<?php

namespace App\Api\v1\Controllers;

use App\Api\v1\Requests\UserManagerPromoteRequest;
use App\Api\v1\Requests\UserManagerStoreRequest;
use App\Api\v1\Resources\UserAuthenticationResource;
use App\Api\v1\Resources\UserManagerResource;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Laravel\Passport\TokenRepository;

class UserManagerController extends Controller
{
    /**
     * Display all users.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        return UserManagerResource::collection(User::withCount('twofaccounts')->get());
    }

    /**
     * Get a user
     *
     * @return \App\Api\v1\Resources\UserManagerResource
     */
    public function show(User $user)
    {
        $this->authorize('view', $user);

        return new UserManagerResource($user);
    }

    /**
     * Reset user's password
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPassword(Request $request, User $user)
    {
        Log::info(sprintf('Password reset for User ID #%s requested by User ID #%s', $user->id, $request->user()->id));

        $this->authorize('update', $user);

        $credentials = [
            'token'    => $this->broker()->createToken($user),
            'email'    => $user->email,
            'password' => $user->password,
        ];

        $response = $this->broker()->reset(
            $credentials, function ($user) {
                $user->resetPassword();
                $user->save();
            }
        );

        if ($response == Password::PASSWORD_RESET) {
            Log::info(sprintf('Temporary password set for User ID #%s', $user->id));

            $response = $this->broker()->sendResetLink(
                ['email' => $credentials['email']]
            );
        } else {
            return response()->json([
                'message' => 'bad request',
                'reason'  => is_string($response) ? __($response) : __('error.no_pwd_reset_for_this_user_type'),
            ], 400);
        }

        return $response == Password::RESET_LINK_SENT
                    ? new UserManagerResource($user)
                    : response()->json([
                        'message' => 'bad request',
                        'reason'  => __($response),
                    ], 400);
    }

    /**
     * Store a newly created user in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(UserManagerStoreRequest $request)
    {
        $this->authorize('create', User::class);

        $validated = $request->validated();

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        Log::info(sprintf('User ID #%s created by user ID #%s', $user->id, $request->user()->id));

        if ($validated['is_admin']) {
            if ($user->promoteToAdministrator()) {
                $user->save();
                Log::notice(sprintf('User ID #%s set as administrator at creation by user ID #%s', $user->id, $request->user()->id));
            }
        }

        $user->refresh();

        return (new UserManagerResource($user))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Purge user's PATs.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function revokePATs(Request $request, User $user, TokenRepository $tokenRepository)
    {
        Log::info(sprintf('Deletion of all personal access tokens for User ID #%s requested by User ID #%s', $user->id, $request->user()->id));

        $this->authorize('update', $user);

        $tokens = $tokenRepository->forUser($user->getAuthIdentifier());

        $tokens->load('client')->filter(function ($token) {
            return $token->client->personal_access_client && ! $token->revoked; /** @phpstan-ignore-line */
        })->each(function ($token) {
            $token->revoke(); /** @phpstan-ignore-line */
        });

        Log::info(sprintf('All personal access tokens for User ID #%s have been revoked', $user->id));

        return response()->json(null, 204);
    }

    /**
     * Purge user's webauthn credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function revokeWebauthnCredentials(Request $request, User $user)
    {
        Log::info(sprintf('Deletion of all security devices for User ID #%s requested by User ID #%s', $user->id, $request->user()->id));

        $this->authorize('update', $user);

        $user->flushCredentials();

        // WebauthnOnly user options need to be reset to prevent impossible login when
        // no more registered device exists.
        // See #110
        if (blank($user->webAuthnCredentials()->WhereEnabled()->get())) {
            $user['preferences->useWebauthnOnly'] = false;
            $user->save();
            Log::notice(sprintf('No more Webauthn credential for user ID #%s, useWebauthnOnly user preference reset to false', $user->id));
        }

        Log::info(sprintf('All security devices for User ID #%s have been revoked', $user->id));

        return response()->json(null, 204);
    }

    /**
     * Remove the specified user from storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, User $user)
    {
        $this->authorize('delete', $user);

        // This will delete the user and all its 2FAs & Groups thanks to the onCascadeDelete constrains.
        // Deletion will not be done (and returns False) if the user is the only existing admin (see UserObserver clas)
        return $user->delete() === false
            ? response()->json([
                'message' => __('error.cannot_delete_the_only_admin'),
            ], 403)
            : response()->json(null, 204);
    }

    /**
     * Promote (or demote) a user
     *
     * @return \App\Api\v1\Resources\UserManagerResource|\Illuminate\Http\JsonResponse
     */
    public function promote(UserManagerPromoteRequest $request, User $user)
    {
        $this->authorize('promote', $user);

        if ($user->promoteToAdministrator($request->validated('is_admin'))) {
            $user->save();
            Log::info(sprintf('User ID #%s set is_admin=%s for User ID #%s', $request->user()->id, $user->isAdministrator(), $user->id));

            return new UserManagerResource($user);
        }

        return response()->json([
            'message' => __('error.cannot_demote_the_only_admin'),
        ], 403);
    }

    /**
     * Get the user's authentication logs
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function authentications(Request $request, User $user)
    {
        $this->authorize('view', $user);

        // Here we purge the authentication log.
        // Running the purge command when someone fetchs the auth log
        // is not very elegant but it's straitforward compared
        // to a scheduled task, and the delete query is light.
        // => To enhance.
        Artisan::call('2fauth:purge-log');

        $validated = $this->validate($request, [
            'period' => 'sometimes|numeric',
            'limit'  => 'sometimes|numeric',
        ]);

        $authentications = $request->has('period') ? $user->authenticationsByPeriod($validated['period']) : $user->authentications;
        $authentications = $request->has('limit') ? $authentications->take($validated['limit']) : $authentications;

        return UserAuthenticationResource::collection($authentications);
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker|\Illuminate\Auth\Passwords\PasswordBroker
     */
    protected function broker()
    {
        return Password::broker();
    }
}
