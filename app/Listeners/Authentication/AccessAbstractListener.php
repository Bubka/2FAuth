<?php

namespace App\Listeners\Authentication;

use Illuminate\Http\Request;

abstract class AccessAbstractListener
{
    /**
     * The current request
     */
    public Request $request;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return void
     */
    abstract public function handle(mixed $event);

    /**
     * Get the login method based on the request input parameters
    */
    public function loginMethod() : ?string
    {
        if ($this->request->has('response.authenticatorData')) {
            return 'webauthn';
        } elseif ($this->request->has('password')) {
            return 'password';
        } else {
            return null;
        }
    }
}
