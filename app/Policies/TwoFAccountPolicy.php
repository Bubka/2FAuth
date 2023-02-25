<?php

namespace App\Policies;

use App\Models\TwoFAccount;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TwoFAccountPolicy
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
     * @param  \App\Models\TwoFAccount  $twofaccount
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, TwoFAccount $twofaccount)
    {
        return $this->isOwnerOf($user, $twofaccount);
    }

    /**
     * Determine whether the user can view all provided models.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TwoFAccount  $twofaccount
     * @param  \Illuminate\Support\Collection<int, \App\Models\TwoFAccount>  $twofaccounts
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewEach(User $user, TwoFAccount $twofaccount, $twofaccounts)
    {
        return $this->isOwnerOfEach($user, $twofaccounts);
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
     * @param  \App\Models\TwoFAccount  $twofaccount
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, TwoFAccount $twofaccount)
    {
        return $this->isOwnerOf($user, $twofaccount);
    }

    /**
     * Determine whether the user can update all provided models.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TwoFAccount  $twofaccount
     * @param  \Illuminate\Support\Collection<int, \App\Models\TwoFAccount>  $twofaccounts
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function updateEach(User $user, TwoFAccount $twofaccount, $twofaccounts)
    {
        return $this->isOwnerOfEach($user, $twofaccounts);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TwoFAccount  $twofaccount
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, TwoFAccount $twofaccount)
    {
        return $this->isOwnerOf($user, $twofaccount);
    }

    /**
     * Determine whether the user can delete all provided models.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TwoFAccount  $twofaccount
     * @param  \Illuminate\Support\Collection<int, \App\Models\TwoFAccount>  $twofaccounts
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function deleteEach(User $user, TwoFAccount $twofaccount, $twofaccounts)
    {
        return $this->isOwnerOfEach($user, $twofaccounts);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TwoFAccount  $twofaccount
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, TwoFAccount $twofaccount)
    {
        return $this->isOwnerOf($user, $twofaccount);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TwoFAccount  $twofaccount
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, TwoFAccount $twofaccount)
    {
        return $this->isOwnerOf($user, $twofaccount);
    }
}
