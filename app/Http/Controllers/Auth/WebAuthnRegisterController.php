<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use DarkGhostHunter\Larapass\Http\RegistersWebAuthn;
use App\Exceptions\UnsupportedWithReverseProxyException;

class WebAuthnRegisterController extends Controller
{
    use RegistersWebAuthn;

    /*
    |--------------------------------------------------------------------------
    | WebAuthn Registration Controller
    |--------------------------------------------------------------------------
    |
    | This controller receives an user request to register a device and also
    | verifies the registration. If everything goes ok, the credential is
    | persisted into the application, otherwise it will signal failure.
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
}