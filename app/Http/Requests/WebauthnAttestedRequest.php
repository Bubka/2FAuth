<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Laragear\WebAuthn\Contracts\WebAuthnAuthenticatable;
use Laragear\WebAuthn\Http\Requests\AttestedRequest;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class WebauthnAttestedRequest extends AttestedRequest
{
    /**
     * Handle a failed authorization attempt.
     *
     * @return void
     *
     * @throws \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
     */
    protected function failedAuthorization()
    {
        throw new AccessDeniedHttpException(__('error.unsupported_with_sso_only'));
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(?WebAuthnAuthenticatable $user) : bool
    {
        return (bool) $user && Gate::allows('manage-webauthn-credentials');
    }
}
