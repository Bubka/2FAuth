<?php

namespace App\Policies;

use App\Models\TwoFAccount;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class TwoFAccountPolicy
{
    use HandlesAuthorization, OwnershipTrait;

    /**
     * Determine whether the user can view any models.
     *
     * @return Response|bool
     */
    // public function viewAny(User $user)
    // {
    //     return false;
    // }

    /**
     * Determine whether the user can view the model.
     *
     * @return Response|bool
     */
    public function view(User $user, TwoFAccount $twofaccount)
    {
        $can = $twofaccount->isOwnedBy($user) || $twofaccount->isSharedWith($user);

        if (! $can) {
            Log::notice(sprintf('User ID #%s cannot view twofaccount ID #%s', $user->id, $twofaccount->id));
        }

        return $can;
    }

    /**
     * Determine whether the user can read the account secret.
     *
     * @return Response|bool
     */
    public function viewSecret(User $user, TwoFAccount $twofaccount)
    {
        $can = $twofaccount->canReadSecret($user);

        if (! $can) {
            Log::notice(sprintf('User ID #%s cannot view secret of twofaccount ID #%s', $user->id, $twofaccount->id));
        }

        return $can;
    }

    /**
     * Determine whether the user can generate OTP for the account.
     *
     * @return Response|bool
     */
    public function generateOtp(User $user, TwoFAccount $twofaccount)
    {
        $can = $twofaccount->canGenerateOtp($user);

        if (! $can) {
            Log::notice(sprintf('User ID #%s cannot generate otp for twofaccount ID #%s', $user->id, $twofaccount->id));
        }

        return $can;
    }

    /**
     * Determine whether the user can view all provided models.
     *
     * @param  Collection<int, TwoFAccount>  $twofaccounts
     * @return Response|bool
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
     * @return Response|bool
     */
    public function create(User $user)
    {
        // Log::notice(sprintf('User ID #%s cannot create twofaccounts', $user->id));

        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @return Response|bool
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
     * @param  Collection<int, TwoFAccount>  $twofaccounts
     * @return Response|bool
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
     * Determine whether the user can reorder all provided models.
     *
     * @param  Collection<int, TwoFAccount>  $twofaccounts
     * @return Response|bool
     */
    public function reorderEach(User $user, TwoFAccount $twofaccount, $twofaccounts)
    {
        $can = true;

        foreach ($twofaccounts as $candidate) {
            if (! $candidate->isOwnedBy($user) && ! $candidate->isSharedWith($user)) {
                $can = false;
                break;
            }
        }

        if (! $can) {
            $ids = $twofaccounts->map(function ($candidate, $key) {
                return $candidate->id;
            });

            Log::notice(sprintf('User ID #%s cannot reorder all twofaccounts in IDs #%s', $user->id, implode(',', $ids->toArray())));
        }

        return $can;
    }

    /**
     * Determine whether the user can assign all provided models to one of their groups.
     *
     * @param  Collection<int, TwoFAccount>  $twofaccounts
     * @return Response|bool
     */
    public function assignToGroupEach(User $user, TwoFAccount $twofaccount, $twofaccounts)
    {
        $can = true;

        foreach ($twofaccounts as $candidate) {
            if (! $candidate->isOwnedBy($user) && ! $candidate->isSharedWith($user)) {
                $can = false;
                break;
            }
        }

        if (! $can) {
            $ids = $twofaccounts->map(function ($candidate, $key) {
                return $candidate->id;
            });

            Log::notice(sprintf('User ID #%s cannot assign all twofaccounts in IDs #%s to a group', $user->id, implode(',', $ids->toArray())));
        }

        return $can;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @return Response|bool
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
     * @param  Collection<int, TwoFAccount>  $twofaccounts
     * @return Response|bool
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
     * Determine whether the user can transfer account ownership.
     *
     * @return Response|bool
     */
    public function transferOwnership(User $user, TwoFAccount $twofaccount)
    {
        $can = $twofaccount->isOwnedBy($user);

        if (! $can) {
            Log::notice(sprintf('User ID #%s cannot transfer twofaccount ID #%s', $user->id, $twofaccount->id));
        }

        return $can;
    }

    /**
     * Determine whether the user can manage account shares.
     *
     * @return Response|bool
     */
    public function manageShares(User $user, TwoFAccount $twofaccount)
    {
        $can = $twofaccount->isOwnedBy($user);

        if (! $can) {
            Log::notice(sprintf('User ID #%s cannot manage shares for twofaccount ID #%s', $user->id, $twofaccount->id));
        }

        return $can;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  User  $user
     * @param  TwoFAccount  $twofaccount
     * @return Response|bool
     */
    // public function restore(User $user, TwoFAccount $twofaccount)
    // {

    // }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  User  $user
     * @param  TwoFAccount  $twofaccount
     * @return Response|bool
     */
    // public function forceDelete(User $user, TwoFAccount $twofaccount)
    // {

    // }
}
