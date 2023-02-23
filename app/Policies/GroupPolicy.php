<?php

namespace App\Policies;

use App\Models\Group;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class GroupPolicy
{
    use HandlesAuthorization, OwnershipTrait;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Group $group)
    {
        return $this->isOwnerOf($user, $group);
    }

    /**
     * Determine whether the user can view all provided models.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Group  $group
     * @param  \Illuminate\Support\Collection<int, \App\Models\Group>  $groups
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewEach(User $user, Group $group, $groups)
    {
        return $this->isOwnerOfEach($user, $groups);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Group $group)
    {
        return $this->isOwnerOf($user, $group);
    }

    /**
     * Determine whether the user can update all provided models.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Group  $group
     * @param  \Illuminate\Support\Collection<int, \App\Models\Group>  $groups
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function updateEach(User $user, Group $group, $groups)
    {
        return $this->isOwnerOfEach($user, $groups);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Group $group)
    {
        return $this->isOwnerOf($user, $group);
    }

    /**
     * Determine whether the user can delete all provided models.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Group  $group
     * @param  \Illuminate\Support\Collection<int, \App\Models\Group>  $groups
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function deleteEach(User $user, Group $group, $groups)
    {
        return $this->isOwnerOfEach($user, $groups);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Group $group)
    {
        return $this->isOwnerOf($user, $group);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Group $group)
    {
        return $this->isOwnerOf($user, $group);
    }
}
