<?php

namespace App\Http\Controllers\Auth;

use App\Extensions\WebauthnCredentialBroker;
use App\Http\Controllers\Controller;
use App\Http\Requests\WebauthnDeviceLostRequest;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class WebAuthnDeviceLostController extends Controller
{
    use ResetsPasswords;

    /**
     * Send a recovery email to the user.
     *
     * @return JsonResponse|RedirectResponse
     *
     * @throws ValidationException
     */
    public function sendRecoveryEmail(WebauthnDeviceLostRequest $request, WebauthnCredentialBroker $broker)
    {
        $credentials = $request->validated();

        $response = $broker->sendResetLink($credentials);

        return $response === Password::RESET_LINK_SENT
            ? $this->sendRecoveryLinkResponse($request, $response)
            : $this->sendRecoveryLinkFailedResponse($request, $response);
    }

    /**
     * Get the response for a failed account recovery link.
     *
     * @return JsonResponse
     *
     * @throws ValidationException
     */
    protected function sendRecoveryLinkFailedResponse(Request $request, string $response)
    {
        throw ValidationException::withMessages(['email' => [trans($response)]]);
    }

    /**
     * Get the response for a successful account recovery link.
     *
     * @return JsonResponse
     */
    protected function sendRecoveryLinkResponse(Request $request, string $response)
    {
        return response()->json(['message' => __('message.account_recovery_email_sent')]);
    }
}
