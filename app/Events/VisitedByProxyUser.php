<?php

namespace App\Events;

use App\Models\User;

class VisitedByProxyUser
{
    /**
     * The authenticated user.
     */
    public User $user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }
}
