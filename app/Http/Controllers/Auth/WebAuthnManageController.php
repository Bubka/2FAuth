<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\WebauthnRenameRequest;
use DarkGhostHunter\Larapass\Eloquent\WebAuthnCredential;
use App\Exceptions\UnsupportedWithReverseProxyException;

class WebAuthnManageController extends Controller
{
    // use RecoversWebAuthn;

    /*
    |--------------------------------------------------------------------------
    | WebAuthn Manage Controller
    |--------------------------------------------------------------------------
    |
    |
    */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    /**
     * List all WebAuthn registered credentials
     */
    public function index(Request $request)
    {
        // WebAuthn is useless when authentication is handle by
        // a reverse proxy so we return a 202 response to tell the
        // client nothing more will happen
        if (config('auth.defaults.guard') === 'reverse-proxy-guard') {
            return response()->json([
                'message' => 'no webauthn with reverse proxy'], 202);
        }

        $user = $request->user();
        $allUserCredentials = $user->webAuthnCredentials()
                                    ->enabled()
                                    ->get()
                                    ->all();

        return response()->json($allUserCredentials, 200);
    }


    /**
     * Rename a WebAuthn device
     * 
     * @param \App\Http\Requests\WebauthnRenameRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function rename(WebauthnRenameRequest $request, string $credential)
    {
        $validated = $request->validated();

        $webAuthnCredential = WebAuthnCredential::where('id', $credential)->firstOrFail();
        $webAuthnCredential->name = $validated['name'];
        $webAuthnCredential->save();

        return response()->json([
            'name' => $webAuthnCredential->name,
        ], 200);
    }

    /**
     * Remove the specified credential from storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request, $credential)
    {
        $user = $request->user();
        $user->removeCredential($credential);

        return response()->json(null, 204);
    }
}