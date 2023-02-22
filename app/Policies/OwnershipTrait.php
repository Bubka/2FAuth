<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Contracts\Support\Arrayable;

trait OwnershipTrait
{

    /**
     * Ownership of single item condition
     * 
     * @param  \App\Models\User  $user
     * @param  mixed  $item
     * @return bool
     */
    protected function isOwnerOf(User $user, mixed $item)
    {
        return $user->id === $item->user_id;
    }

    /**
     * Ownership of collection condition
     * 
     * @template TKey of array-key
     * @template TValue
     * 
     * @param  \App\Models\User  $user
     * @param  \Illuminate\Contracts\Support\Arrayable<TKey, TValue>|iterable<TKey, TValue>  $items
     * @return bool
     */
    protected function isOwnerOfEach(User $user, $items)
    {
        foreach ($items as $item) {
            if (! $this->isOwnerOf($user, $item)) {
                return false;
            }
        }

        return true;
    }

}
