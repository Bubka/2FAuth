<?php

namespace App\Http\Controllers\Auth;

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
            $request->user()->preferences['useWebauthnOnly'] = false;
            $request->user()->save();
            Log::notice(sprintf('No more Webauthn credential for user ID #%s, user Webauthn options reset to default', $user->id));
        }

        Log::info(sprintf('User ID #%s revoked a security device', $user->id));

        return response()->json(null, 204);
    }
}
