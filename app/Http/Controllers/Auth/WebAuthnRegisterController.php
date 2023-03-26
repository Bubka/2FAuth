<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Laragear\WebAuthn\Http\Requests\AttestationRequest;
use Laragear\WebAuthn\Http\Requests\AttestedRequest;
use Laragear\WebAuthn\WebAuthn;

class WebAuthnRegisterController extends Controller
{
    /**
     * Returns a challenge to be verified by the user device.
     */
    public function options(AttestationRequest $request) : Responsable
    {
        switch (config('webauthn.user_verification')) {
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
     */
    public function register(AttestedRequest $request) : Response
    {
        $request->save();

        Log::info(sprintf('User ID #%s registered a new security device', $request->user()->id));

        return response()->noContent();
    }
}
