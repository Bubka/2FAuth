<?php

namespace App\Policies;

use App\Models\User;

trait OwnershipTrait
{
    /**
     * Ownership of single item condition
     *
     * @return bool
     */
    protected function isOwnerOf(User $user, mixed $item)
    {
        return $user->id === $item->user_id;
    }

    /**
     * Check if user can access an item (owns it or it's shared)
     *
     * @return bool
     */
    protected function canAccess(User $user, mixed $item)
    {
        return $user->id === $item->user_id || ($item->is_shared ?? false);
    }

    /**
     * Check if user is the creator of an item (for creator-only operations like update/delete)
     *
     * @return bool
     */
    protected function isCreatorOf(User $user, mixed $item)
    {
        return $this->isOwnerOf($user, $item);
    }

    /**
     * Ownership of collection condition
     *
     * @template TKey of array-key
     * @template TValue
     *
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

    /**
     * Check if user can access each item in collection (owns it or it's shared)
     *
     * @template TKey of array-key
     * @template TValue
     *
     * @param  \Illuminate\Contracts\Support\Arrayable<TKey, TValue>|iterable<TKey, TValue>  $items
     * @return bool
     */
    protected function canAccessEach(User $user, $items)
    {
        foreach ($items as $item) {
            if (! $this->canAccess($user, $item)) {
                return false;
            }
        }

        return true;
    }
}
