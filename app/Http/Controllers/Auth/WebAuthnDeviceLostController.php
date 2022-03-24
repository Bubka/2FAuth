<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use DarkGhostHunter\Larapass\Http\SendsWebAuthnRecoveryEmail;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Exceptions\UnsupportedWithReverseProxyException;

class WebAuthnDeviceLostController extends Controller
{
    use SendsWebAuthnRecoveryEmail;

    /*
    |--------------------------------------------------------------------------
    | WebAuthn Device Lost Controller
    |--------------------------------------------------------------------------
    |
    | This is a convenience controller that will allow your users who have lost
    | their WebAuthn device to register another without using passwords. This
    | will send him a link to his email to create new WebAuthn credentials.
    |
    */

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $authGuard = config('auth.defaults.guard');

        if ($authGuard === 'reverse-proxy-guard') {
            throw new UnsupportedWithReverseProxyException();
        }
    }


    /**
     * The recovery credentials to retrieve through validation rules.
     *
     * @return array|string[]
     */
    protected function recoveryRules(): array
    {
        return [
            'email' => 'required|exists:users,email',
        ];
    }


    /**
     * Get the response for a successful account recovery link.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendRecoveryLinkResponse(Request $request, string $response)
    {
        return response()->json(['message' => __('auth.webauthn.account_recovery_email_sent')]);
    }


    /**
     * Get the response for a failed account recovery link.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendRecoveryLinkFailedResponse(Request $request, string $response)
    {
        throw ValidationException::withMessages(['email' => [trans($response)]]);
    }
}