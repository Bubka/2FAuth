<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Laragear\WebAuthn\Http\Requests\AssertedRequest;
use Laragear\WebAuthn\Http\Requests\AssertionRequest;
use Laragear\WebAuthn\WebAuthn;

class WebAuthnLoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | WebAuthn Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller allows the WebAuthn user device to request a login and
    | return the correctly signed challenge. Most of the hard work is done
    | by your Authentication Guard once the user is attempting to login.
    |
    */

    /**
     * Returns the challenge to assertion.
     *
     * @param  \Laragear\WebAuthn\Http\Requests\AssertionRequest  $request
     * @return \Illuminate\Contracts\Support\Responsable|\Illuminate\Http\JsonResponse
     */
    public function options(AssertionRequest $request) : Responsable|JsonResponse
    {
        switch (config('webauthn.user_verification')) {
            case WebAuthn::USER_VERIFICATION_DISCOURAGED:
                $request = $request->fastLogin();    // Makes the authenticator to only check for user presence on registration
                break;
            case WebAuthn::USER_VERIFICATION_REQUIRED:
                $request = $request->secureLogin();  // Makes the authenticator to always verify the user thoroughly on registration
                break;
        }

        return $request->toVerify($request->validate([
            'email' => [
                'required',
                'email',
                new \App\Rules\CaseInsensitiveEmailExists,
            ],
        ]));
    }

    /**
     * Log the user in.
     *
     * @param  \Laragear\WebAuthn\Http\Requests\AssertedRequest  $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function login(AssertedRequest $request)
    {
        Log::info('User login via webauthn requested');

        if ($request->has('response')) {
            $response = $request->response;

            // Some authenticators do not send a userHandle so we hack the response to be compliant
            // with Laragear\WebAuthn implementation that waits for a userHandle
            if (! Arr::exists($response, 'userHandle') || blank($response['userHandle'])) {
                $response['userHandle'] = User::getFromCredentialId($request->id)?->userHandle();
                $request->merge(['response' => $response]);
            }
        }

        $user = $request->login();

        if ($user) {
            $this->authenticated($user);

            return response()->noContent();
        }

        return response()->noContent(422);
    }

    /**
     * The user has been authenticated.
     *
     * @param  mixed  $user
     * @return void|\Illuminate\Http\JsonResponse
     */
    protected function authenticated($user)
    {
        $user->last_seen_at = Carbon::now()->format('Y-m-d H:i:s');
        $user->save();

        Log::info(sprintf('User ID #%s authenticated using webauthn', $user->id));
    }
}
