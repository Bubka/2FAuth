<?php

namespace Database\Seeders;

use App\Facades\Groups;
use App\Models\Group;
use App\Models\TwoFAccount;
use App\Models\User;

trait SeederTrait
{
    /**
     * Ownership of single item condition
     */
    protected function CreateTwoFAccountWithGroupAssignment(User $user, array $attributes, Group $group) : void
    {
        $twofaccount = TwoFAccount::factory()
            ->for($user)
            ->create($attributes);

        Groups::assign($twofaccount->id, $user, $group);
    }
}
