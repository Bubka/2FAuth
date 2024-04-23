<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;

class VisitedByProxyUser
{
    /**
     * The authenticated user.
     *
     * @var User|Authenticatable
     */
    public $user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User|Authenticatable $user)
    {
        $this->user = $user;
    }
}
