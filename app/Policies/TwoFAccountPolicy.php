<?php

namespace App\Policies;

use App\Models\TwoFAccount;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Log;

class TwoFAccountPolicy
{
    use HandlesAuthorization, OwnershipTrait;

    /**
     * Determine whether the user can view any models.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    // public function viewAny(User $user)
    // {
    //     return false;
    // }

    /**
     * Determine whether the user can view the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, TwoFAccount $twofaccount)
    {
        $can = $this->isOwnerOf($user, $twofaccount);

        if (! $can) {
            Log::notice(sprintf('User ID #%s cannot view twofaccount ID #%s', $user->id, $twofaccount->id));
        }

        return $can;
    }

    /**
     * Determine whether the user can view all provided models.
     *
     * @param  \Illuminate\Support\Collection<int, \App\Models\TwoFAccount>  $twofaccounts
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewEach(User $user, TwoFAccount $twofaccount, $twofaccounts)
    {
        $can = $this->isOwnerOfEach($user, $twofaccounts);

        if (! $can) {
            $ids = $twofaccounts->map(function ($twofaccount, $key) {
                return $twofaccount->id;
            });
            Log::notice(sprintf('User ID #%s cannot view all twofaccounts in IDs #%s', $user->id, implode(',', $ids->toArray())));
        }

        return $can;
    }

    /**
     * Determine whether the user can create models.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        // Log::notice(sprintf('User ID #%s cannot create twofaccounts', $user->id));

        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, TwoFAccount $twofaccount)
    {
        $can = $this->isOwnerOf($user, $twofaccount);

        if (! $can) {
            Log::notice(sprintf('User ID #%s cannot update twofaccount ID #%s', $user->id, $twofaccount->id));
        }

        return $can;
    }

    /**
     * Determine whether the user can update all provided models.
     *
     * @param  \Illuminate\Support\Collection<int, \App\Models\TwoFAccount>  $twofaccounts
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function updateEach(User $user, TwoFAccount $twofaccount, $twofaccounts)
    {
        $can = $this->isOwnerOfEach($user, $twofaccounts);

        if (! $can) {
            $ids = $twofaccounts->map(function ($twofaccount, $key) {
                return $twofaccount->id;
            });
            Log::notice(sprintf('User ID #%s cannot update all twofaccounts in IDs #%s', $user->id, implode(',', $ids->toArray())));
        }

        return $can;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, TwoFAccount $twofaccount)
    {
        $can = $this->isOwnerOf($user, $twofaccount);

        if (! $can) {
            Log::notice(sprintf('User ID #%s cannot delete twofaccount ID #%s', $user->id, $twofaccount->id));
        }

        return $can;
    }

    /**
     * Determine whether the user can delete all provided models.
     *
     * @param  \Illuminate\Support\Collection<int, \App\Models\TwoFAccount>  $twofaccounts
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function deleteEach(User $user, TwoFAccount $twofaccount, $twofaccounts)
    {
        $can = $this->isOwnerOfEach($user, $twofaccounts);

        if (! $can) {
            $ids = $twofaccounts->map(function ($twofaccount, $key) {
                return $twofaccount->id;
            });
            Log::notice(sprintf('User ID #%s cannot delete all twofaccounts in IDs #%s', $user->id, implode(',', $ids->toArray())));
        }

        return $can;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TwoFAccount  $twofaccount
     * @return \Illuminate\Auth\Access\Response|bool
     */
    // public function restore(User $user, TwoFAccount $twofaccount)
    // {

    // }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TwoFAccount  $twofaccount
     * @return \Illuminate\Auth\Access\Response|bool
     */
    // public function forceDelete(User $user, TwoFAccount $twofaccount)
    // {

    // }
}
