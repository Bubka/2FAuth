<?php

namespace App\Http\Controllers\Auth\Traits;

use App\Facades\Settings;
use App\Models\User;

trait HasAuthenticatedPayload
{
    /**
     * Returns the payload to send after a successful authentication
     */
    private function authenticatedPayload(string $message, User $user) : mixed
    {
        return [
            'message'        => $message,
            'id'             => $user->id,
            'name'           => $user->name,
            'email'          => $user->email,
            'oauth_provider' => $user->oauth_provider,
            'preferences'    => $user->preferences,
            'appSettings'    => [
                'enableSharing'              => Settings::get('enableSharing'),
                'enableAllUsersSharingScope' => Settings::get('enableAllUsersSharingScope'),
            ],
            'is_admin' => $user->isAdministrator(),
        ];
    }
}
