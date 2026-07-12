<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Auth\Traits\HasAuthenticatedPayload;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application.
    | The controller uses a trait to conveniently provide its functionality
    | to your applications.
    |
    */

    use AuthenticatesUsers, HasAuthenticatedPayload;

    /**
     * The login throttle.
     *
     * @var int
     */
    protected $maxAttempts;

    /**
     * Handle a login request to the application.
     *
     * @return JsonResponse
     *
     * @throws ValidationException
     */
    public function login(LoginRequest $request)
    {
        Log::info(sprintf('User login requested by %s from %s', var_export($request['email'], true), $request->ip()));

        $this->maxAttempts = config('auth.throttle.login');

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            Log::notice(sprintf(
                '%s from %s locked-out, too many failed login attempts (using email+password)',
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
            'Failed login for %s from %s - Attemp %d/%d (using email+password)',
            var_export($request['email'], true),
            $request->ip(),
            $this->limiter()->attempts($this->throttleKey($request)),
            $this->maxAttempts()
        ));

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * log out current user
     *
     * @return JsonResponse
     */
    public function logout(Request $request)
    {
        $userId = $request->user()->id;

        $this->guard()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Log::info(sprintf('User ID #%s logged out', $userId));

        return response()->json(['message' => 'signed out'], Response::HTTP_OK);
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @return JsonResponse
     */
    protected function sendLoginResponse(Request $request)
    {
        $this->clearLoginAttempts($request);

        /**
         * @var User|null
         */
        $user = $this->guard()->user()->loadCount('twofaccounts');
        $this->authenticated($request, $this->guard()->user());

        return response()->json($this->authenticatedPayload('authenticated', $user), Response::HTTP_OK);
    }

    /**
     * Get the failed login response instance.
     *
     * @return JsonResponse
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        return response()->json(['message' => 'unauthorized'], Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Redirect the user after determining they are locked out.
     *
     * @return JsonResponse
     */
    protected function sendLockoutResponse(Request $request)
    {
        $seconds = $this->limiter()->availableIn(
            $this->throttleKey($request)
        );

        return response()->json(['message' => Lang::get('message.throttle', ['seconds' => $seconds])], Response::HTTP_TOO_MANY_REQUESTS);
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @return array
     */
    protected function credentials(Request $request)
    {
        $credentials = [
            $this->username() => strtolower($request->input($this->username())),
            'password'        => $request->get('password'),
        ];

        return $credentials;
    }

    /**
     * The user has been authenticated.
     *
     * @param  mixed  $user
     * @return void
     */
    protected function authenticated(Request $request, $user)
    {
        $user->last_seen_at = Carbon::now()->format('Y-m-d H:i:s');
        $user->save();

        Log::info(sprintf('User ID #%s authenticated (using email+password)', $user->id));
    }
}
