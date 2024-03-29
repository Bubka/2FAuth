<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Support\Facades\Log;

class UserPolicy
{
    use SelfTrait;

    /**
     * Perform pre-authorization checks.
     */
    public function before(User $user, string $ability) : ?bool
    {
        if ($user->isAdministrator()) {
            return true;
        }

        return null;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user) : bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model) : bool
    {
        $can = $this->isHimself($user, $model);

        if (! $can) {
            Log::notice(sprintf('User ID #%s cannot view users other than himself)', $user->id));
        }

        return $can;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(?User $user) : bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model) : bool
    {
        $can = $this->isHimself($user, $model);

        if (! $can) {
            Log::notice(sprintf('User ID #%s cannot update users other than himself)', $user->id));
        }

        return $can;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model) : bool
    {
        $can = $this->isHimself($user, $model);

        if (! $can) {
            Log::notice(sprintf('User ID #%s cannot delete users other than himself)', $user->id));
        }

        return $can;
    }
}
