<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use DarkGhostHunter\Larapass\Http\ConfirmsWebAuthn;
use App\Exceptions\UnsupportedWithReverseProxyException;

class WebAuthnConfirmController extends Controller
{
    use ConfirmsWebAuthn;

    /*
    |--------------------------------------------------------------------------
    | Confirm Device Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling WebAuthn confirmations and
    | uses a simple trait to include the behavior. You're free to explore
    | this trait and override any functions that require customization.
    |
    */

    /**
     * Where to redirect users when the intended url fails.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $authGuard = config('auth.defaults.guard');

        if ($authGuard === 'reverse-proxy-guard') {
            throw new UnsupportedWithReverseProxyException();
        }
    }
}