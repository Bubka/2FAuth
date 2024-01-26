<?php

namespace App\Policies;

use App\Models\User;

trait SelfTrait
{
    /**
     * Ownership of single item condition
     *
     * @return bool
     */
    protected function isHimself(User $user, mixed $item)
    {
        return $user->id === $item->id;
    }
}
