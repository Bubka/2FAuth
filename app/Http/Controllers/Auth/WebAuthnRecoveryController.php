<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use DarkGhostHunter\Larapass\Http\RecoversWebAuthn;
use DarkGhostHunter\Larapass\Facades\WebAuthn;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class WebAuthnRecoveryController extends Controller
{
    use RecoversWebAuthn;

    /*
    |--------------------------------------------------------------------------
    | WebAuthn Recovery Controller
    |--------------------------------------------------------------------------
    |
    | When an user loses his device he will reach this controller to attach a
    | new device. The user will attach a new device, and optionally, disable
    | all others. Then he will be authenticated and redirected to your app.
    |
    */

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;
    

    /**
     * Returns the credential creation options to the user.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function options(Request $request): JsonResponse
    {
        $user = WebAuthn::getUser($request->validate($this->rules()));

        // We will proceed only if the broker can find the user and the token is valid.
        // If the user doesn't exists or the token is invalid, we will bail out with a
        // HTTP 401 code because the user doing the request is not authorized for it.
        abort_unless(WebAuthn::tokenExists($user, $request->input('token')), 401, __('auth.webauthn.invalid_recovery_token'));

        return response()->json(WebAuthn::generateAttestation($user));
    }

    /**
     * Get the response for a successful account recovery.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     *
     * @return \Illuminate\Http\JsonResponse
     * 
     * @codeCoverageIgnore - already covered by larapass test
     */
    protected function sendRecoveryResponse(Request $request, string $response): JsonResponse
    {
        return response()->json(['message' => __('auth.webauthn.device_successfully_registered')]);
    }

    /**
     * Get the response for a failed account recovery.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     *
     * @return \Illuminate\Http\JsonResponse|void
     * @throws \Illuminate\Validation\ValidationException
     * 
     * @codeCoverageIgnore - already covered by larapass test
     */
    protected function sendRecoveryFailedResponse(Request $request, string $response): JsonResponse
    {
        throw ValidationException::withMessages(['email' => [trans($response)]]);
    }
}