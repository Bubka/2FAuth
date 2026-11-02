<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Auth\Traits\HasAuthenticatedPayload;
use App\Http\Controllers\Controller;
use App\Http\Requests\WebauthnAssertedRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Laragear\WebAuthn\Enums\UserVerification;
use Laragear\WebAuthn\Http\Requests\AssertionRequest;
use Laragear\WebAuthn\JsonTransport;

class WebAuthnLoginController extends Controller
{
    use AuthenticatesUsers, HasAuthenticatedPayload;

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
            case UserVerification::Discouraged:
                $request = $request->fastLogin();    // Makes the authenticator to only check for user presence on registration
                break;
            case UserVerification::Required:
                $request = $request->secureLogin();  // Makes the authenticator to always verify the user thoroughly on registration
                break;
        }

        $credentials = $request->validate(['email' => 'required|email']);
        $options     = $request->toVerify($credentials);

        // Without decoys, a missing allowCredentials list would tell the caller the account does not exist.
        if ($options instanceof JsonTransport && $options->get('allowCredentials') === null) {
            $ordered = [];

            // Insert before the challenge to keep the same key order as real responses.
            foreach ($options->json as $key => $value) {
                if ($key === 'challenge') {
                    $ordered['allowCredentials'] = $this->decoyCredentials($credentials['email']);
                }
                $ordered[$key] = $value;
            }

            $options->json = $ordered;
        }

        return $options;
    }

    /**
     * Build stable fake credentials, derived from the email, so unknown accounts look like known ones.
     *
     * @return array<int, array{id: string, type: string}>
     */
    protected function decoyCredentials(string $email) : array
    {
        $seed   = hash_hmac('sha256', strtolower($email), (string) config('app.key'), true);
        $count  = 1 + (ord($seed[0]) % 3);
        $decoys = [];

        for ($i = 0; $i < $count; $i++) {
            $id       = hash_hmac('sha256', (string) $i, $seed, true);
            $decoys[] = [
                'id'   => rtrim(strtr(base64_encode($id), '+/', '-_'), '='),
                'type' => 'public-key',
            ];
        }

        return $decoys;
    }

    /**
     * Log the user in.
     *
     * @return Response|JsonResponse
     */
    public function login(WebauthnAssertedRequest $request)
    {
        Log::info(sprintf('User login via webauthn requested by %s from %s', var_export($request['email'], true), $request->ip()));

        $this->maxAttempts = config('auth.throttle.login');

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            Log::notice(sprintf(
                '%s from %s locked-out, too many failed login attempts (using webauthn)',
                var_export($request['email'], true),
                $request->ip()
            ));

            return $this->sendLockoutResponse($request);
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
     * @return JsonResponse
     */
    protected function sendLoginResponse(WebauthnAssertedRequest $request)
    {
        $this->clearLoginAttempts($request);

        /**
         * @var User|null
         */
        $user = $this->guard()->user();

        $this->authenticated($user);

        return response()->json($this->authenticatedPayload('authenticated', $user), Response::HTTP_OK);
    }

    /**
     * Get the failed login response instance.
     *
     * @return JsonResponse
     */
    protected function sendFailedLoginResponse(WebauthnAssertedRequest $request)
    {
        return response()->json(['message' => 'unauthorized'], Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Redirect the user after determining they are locked out.
     *
     * @return JsonResponse
     */
    protected function sendLockoutResponse(WebauthnAssertedRequest $request)
    {
        $seconds = $this->limiter()->availableIn(
            $this->throttleKey($request)
        );

        return response()->json(['message' => Lang::get('message.throttle', ['seconds' => $seconds])], Response::HTTP_TOO_MANY_REQUESTS);
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
     * @return void|JsonResponse
     */
    protected function authenticated($user)
    {
        $user->last_seen_at = Carbon::now()->format('Y-m-d H:i:s');
        $user->save();

        Log::info(sprintf('User ID #%s authenticated (using webauthn)', $user->id));
    }
}
