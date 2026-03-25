<?php

namespace App\Events\Traits;

use App\Models\TwoFAccountShare;

trait HasSharingScopes
{
    /**
     * Determine if the scope is set to all users.
     */
    public function isScopeAllUsers() : bool
    {
        return $this->scope === TwoFAccountShare::SCOPE_ALL_USERS;
    }
}
