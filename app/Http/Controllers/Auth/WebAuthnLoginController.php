<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DarkGhostHunter\Larapass\Http\AuthenticatesWebAuthn;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class WebAuthnLoginController extends Controller
{
    // use AuthenticatesWebAuthn;
    use AuthenticatesWebAuthn {
		options as traitOptions;
		login as traitLogin;
	}

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
     * @return \Illuminate\Http\JsonResponse|\Webauthn\PublicKeyCredentialRequestOptions
     */
	public function options(Request $request)
	{
        // Since 2FAuth is single user designed we fetch the user instance
        // and merge its email address to the request. This let Larapass validate
        // the request against a user instance without the need to ask the visitor
        // for an email address.
        //
        // This approach override the Larapass 'userless' config value that seems buggy.
        $user = User::first();

        if (!$user) {
            return response()->json([
                'message' => 'no registered user'
            ], 400);
        }
        else $request->merge(['email' => $user->email]);

		return $this->traitOptions($request);
	}


    /**
     * Log the user in.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        Log::info('User login via webauthn requested');
        $request->validate($this->assertionRules());

        if ($request->has('response')) {
            $response = $request->response;

            // Some authenticators do not send a userHandle so we hack the response to be compliant
            // with Larapass/webauthn-lib implementation that wait for a userHandle
            if(!$response['userHandle']) {
                $user = User::getFromCredentialId($request->id);
                $response['userHandle'] = base64_encode($user->userHandle());
                $request->merge(['response' => $response]);
            }
        }

        return $this->traitLogin($request);
    }


    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     *
     * @return void|\Illuminate\Http\JsonResponse
     */
    protected function authenticated(Request $request, $user)
    {
        $user->last_seen_at = Carbon::now()->format('Y-m-d H:i:s');
        $user->save();

        Log::info('User authenticated via webauthn');
    }
}