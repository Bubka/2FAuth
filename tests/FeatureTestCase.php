<?php

namespace Tests;

use App\Facades\Groups;
use App\Models\Group;
use App\Models\TwoFAccount;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class FeatureTestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Refresh db only when test is using database transaction.
     */
    use LazilyRefreshDatabase;

    /**
     * Perform any work that should take place once the database has finished refreshing.
     *
     * @return void
     */
    protected function afterRefreshingDatabase()
    {
        //
    }

    /**
     * Create a TwoFAccount and assign it to the provided group for the owner.
     *
     * @param  array<string, mixed>  $attributes
     */
    protected function createTwofaccountInGroup(User $owner, Group $group, array $attributes = []) : TwoFAccount
    {
        unset($attributes['group_id']);

        $twofaccount = TwoFAccount::factory()
            ->for($owner)
            ->create($attributes);

        Groups::assign($twofaccount->id, $owner, $group);

        return $twofaccount->refresh();
    }
}
