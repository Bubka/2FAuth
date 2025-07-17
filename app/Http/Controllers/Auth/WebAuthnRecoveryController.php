<?php

namespace App\Http\Controllers\Auth;

use App\Extensions\WebauthnCredentialBroker;
use App\Http\Controllers\Controller;
use App\Http\Requests\WebauthnRecoveryRequest;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class WebAuthnRecoveryController extends Controller
{
    use ResetsPasswords;

    /**
     * Let the user regain access to his account using email+password by resetting
     * the "use webauthn only" setting.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function recover(WebauthnRecoveryRequest $request, WebauthnCredentialBroker $broker)
    {
        $credentials = $request->validated();

        $response = $broker->reset(
            $credentials,
            function ($user) use ($request) {
                // At this time, the WebAuthnUserProvider is already registered in the Laravel Service Container,
                // with a password_fallback value set using the useWebauthnOnly user setting (see AuthServiceProvider.php).
                // To ensure user login with email+pwd credentials, we replace the registered WebAuthnUserProvider instance
                // with a new instance configured with password_fallback On.
                $provider = new \Laragear\WebAuthn\Auth\WebAuthnUserProvider(
                    app()->make('hash'),
                    \App\Models\User::class,
                    app()->make(\Laragear\WebAuthn\Assertion\Validator\AssertionValidator::class),
                    true,
                );

                Auth::setProvider($provider);

                if (Auth::attempt($request->only('email', 'password'))) {
                    if ($this->shouldRevokeAllCredentials($request)) {
                        $user->flushCredentials();
                    }
                    $user['preferences->useWebauthnOnly'] = false;
                    $user->save();
                    Log::notice(sprintf('Legacy login restored for user ID #%s', $user->id));
                } else {
                    throw new AuthenticationException;
                }
            }
        );

        return $response === Password::PASSWORD_RESET
            ? $this->sendRecoveryResponse($request, $response)
            : $this->sendRecoveryFailedResponse($request, $response);
    }

    /**
     * Check if the user has set to revoke all credentials.
     *
     * @return bool|mixed
     */
    protected function shouldRevokeAllCredentials(WebauthnRecoveryRequest $request) : mixed
    {
        return filter_var($request->header('WebAuthn-Unique'), FILTER_VALIDATE_BOOLEAN)
            ?: $request->input('revokeAll', true);
    }

    /**
     * Get the response for a successful account recovery.
     */
    protected function sendRecoveryResponse(Request $request, string $response) : JsonResponse
    {
        return response()->json(['message' => __('message.webauthn_login_disabled')]);
    }

    /**
     * Get the response for a failed account recovery.
     *
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendRecoveryFailedResponse(Request $request, string $response) : JsonResponse
    {
        switch ($response) {
            case Password::INVALID_TOKEN:
                throw ValidationException::withMessages(['token' => [__('message.invalid_reset_token')]]);
            default:
                throw ValidationException::withMessages(['email' => [trans($response)]]);
        }
    }
}
