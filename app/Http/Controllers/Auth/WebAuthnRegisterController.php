<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Response;
use Laragear\WebAuthn\Http\Requests\AttestationRequest;
use Laragear\WebAuthn\Http\Requests\AttestedRequest;
use Laragear\WebAuthn\WebAuthn;

class WebAuthnRegisterController extends Controller
{
    /**
     * Returns a challenge to be verified by the user device.
     *
     * @param  \Laragear\WebAuthn\Http\Requests\AttestationRequest  $request
     * @return \Illuminate\Contracts\Support\Responsable
     */
    public function options(AttestationRequest $request) : Responsable
    {
        switch (env('WEBAUTHN_USER_VERIFICATION')) {
            case WebAuthn::USER_VERIFICATION_DISCOURAGED:
                $request = $request->fastRegistration();    // Makes the authenticator to only check for user presence on registration
                break;
            case WebAuthn::USER_VERIFICATION_REQUIRED:
                $request = $request->secureRegistration();  // Makes the authenticator to always verify the user thoroughly on registration
                break;
        }

        return $request
            // ->allowDuplicates() // Allows the device to create multiple credentials for the same user for this app
            // ->userless()        // Tells the authenticator use this credential to login instantly, instead of asking for one
            ->toCreate();
    }

    /**
     * Registers a device for further WebAuthn authentication.
     *
     * @param  \Laragear\WebAuthn\Http\Requests\AttestedRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function register(AttestedRequest $request) : Response
    {
        $request->save();

        return response()->noContent();
    }
}
