<?php

namespace App\Http\Controllers\Auth;

use App\Facades\Settings;
use App\Http\Controllers\Controller;
use App\Http\Requests\WebauthnRenameRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebAuthnManageController extends Controller
{
    /**
     * List all WebAuthn registered credentials
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $allUserCredentials = $request->user()->webAuthnCredentials()->WhereEnabled()->get();

        return response()->json($allUserCredentials, 200);
    }

    /**
     * Rename a WebAuthn credential
     *
     * @param  \App\Http\Requests\WebauthnRenameRequest  $request
     * @param  string  $credential
     * @return \Illuminate\Http\JsonResponse
     */
    public function rename(WebauthnRenameRequest $request, string $credential)
    {
        $validated = $request->validated();

        abort_if(! $request->user()->renameCredential($credential, $validated['name']), 404);

        return response()->json([
            'name' => $validated['name'],
        ], 200);
    }

    /**
     * Remove the specified credential from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|array  $credential
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request, $credential)
    {
        Log::info('Deletion of security device requested');

        $user = $request->user();
        $user->flushCredential($credential);

        // Webauthn user options need to be reset to prevent impossible login when
        // no more registered device exists.
        // See #110
        if (blank($user->webAuthnCredentials()->WhereEnabled()->get())) {
            Settings::delete('useWebauthnAsDefault');
            Settings::delete('useWebauthnOnly');
            Log::notice('No Webauthn credential enabled, Webauthn settings reset to default');
        }

        Log::info('Security device deleted');

        return response()->json(null, 204);
    }
}
