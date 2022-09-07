<?php

namespace App\Http\Controllers\Auth;

use App\Facades\Settings;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\WebauthnRenameRequest;
use DarkGhostHunter\Larapass\Eloquent\WebAuthnCredential;

class WebAuthnManageController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | WebAuthn Manage Controller
    |--------------------------------------------------------------------------
    |
    |
    */

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
    }
    

    /**
     * List all WebAuthn registered credentials
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
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
        $webAuthnCredential->name = $validated['name']; // @phpstan-ignore-line
        $webAuthnCredential->save();

        return response()->json([
            'name' => $webAuthnCredential->name,
        ], 200);
    }

    /**
     * Remove the specified credential from storage.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  string|array  $credential
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request, $credential)
    {
        $user = $request->user();
        $user->removeCredential($credential);

        // Webauthn user options should be reset to prevent impossible login
        // See #110
        if (blank($user->allCredentialDescriptors())) {
            Settings::delete('useWebauthnAsDefault');
            Settings::delete('useWebauthnOnly');
        }

        return response()->json(null, 204);
    }
}