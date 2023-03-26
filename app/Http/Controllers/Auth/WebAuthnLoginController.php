<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\WebauthnAssertedRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Laragear\WebAuthn\Http\Requests\AssertionRequest;
use Laragear\WebAuthn\WebAuthn;

class WebAuthnLoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * The login throttle.
     *
     * @var int
     */
    protected $maxAttempts;

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
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function login(WebauthnAssertedRequest $request)
    {
        Log::info(sprintf('User login via webauthn requested by %s from %s', var_export($request['email'], true), $request->ip()));

        $this->maxAttempts = config('auth.throttle.login');

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            Log::notice(sprintf(
                '%s from %s locked-out, too many failed login attempts (using webauthn)',
                var_export($request['email'], true),
                $request->ip()
            ));

            return $this->sendLockoutResponse($request);
        }

        if ($request->has('response')) {
            $response = $request->response;

            // Some authenticators do not send a userHandle so we hack the response to be compliant
            // with Laragear\WebAuthn implementation that waits for a userHandle
            if (! Arr::exists($response, 'userHandle') || blank($response['userHandle'])) {
                $response['userHandle'] = User::getFromCredentialId($request->id)?->userHandle();
                $request->merge(['response' => $response]);
            }
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        Log::notice(sprintf(
            'Failed login for %s from %s - Attemp %d/%d (using webauthn)',
            var_export($request['email'], true),
            $request->ip(),
            $this->limiter()->attempts($this->throttleKey($request)),
            $this->maxAttempts()
        ));

        return response()->json(['message' => 'unauthorized'], Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Attempt to log the user into the application.
     *
     * @return bool
     */
    protected function attemptLogin(WebauthnAssertedRequest $request)
    {
        return ! is_null($request->login());
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function sendLoginResponse(WebauthnAssertedRequest $request)
    {
        $this->clearLoginAttempts($request);

        /**
         * @var \App\Models\User|null
         */
        $user = $this->guard()->user();

        $this->authenticated($user);

        return response()->json([
            'message'     => 'authenticated',
            'name'        => $user->name,
            'preferences' => $user->preferences,
        ], Response::HTTP_OK);
    }

    /**
     * Get the failed login response instance.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function sendFailedLoginResponse(WebauthnAssertedRequest $request)
    {
        return response()->json(['message' => 'unauthorized'], Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Redirect the user after determining they are locked out.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function sendLockoutResponse(WebauthnAssertedRequest $request)
    {
        $seconds = $this->limiter()->availableIn(
            $this->throttleKey($request)
        );

        return response()->json(['message' => Lang::get('auth.throttle', ['seconds' => $seconds])], Response::HTTP_TOO_MANY_REQUESTS);
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'email';
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @return array
     */
    protected function credentials(WebauthnAssertedRequest $request)
    {
        $credentials = [
            $this->username() => strtolower($request->input($this->username())),
        ];

        return $credentials;
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

        Log::info(sprintf('User ID #%s authenticated (using webauthn)', $user->id));
    }
}
