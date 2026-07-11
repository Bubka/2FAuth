<?php

namespace App\Facades;

use App\Models\TwoFAccount;
use App\Models\User;
use App\Services\TwoFAccountService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;

/**
 * @see TwoFAccountService
 *
 * @method static void withdraw(int|array|string $ids, User $owner)
 * @method static Collection<int|string, TwoFAccount> migrate(string $migrationPayload)
 * @method static Collection<int, TwoFAccount> export(int|array|string $ids)
 * @method static int delete(int|array|string $ids)
 * @method static void setUser(Collection<int, TwoFAccount> $twofaccounts, User $user)
 * @method static Collection<int, int> saveOrderForUser(User $user, array<int|string, mixed> $orderedIds)
 * @method static Collection<int, int> orderedVisibleIdsForUser(User $user)
 * @method static Builder<TwoFAccount> applyOrderToQueryForUser(Builder<TwoFAccount> $query, User $user)
 * @method static void pruneUsersWithoutAccessForAccount(TwoFAccount $twofaccount)
 * @method static TwoFAccount transferOwnership(TwoFAccount $twofaccount, User $newOwner)
 */
class TwoFAccounts extends Facade
{
    protected static function getFacadeAccessor()
    {
        return TwoFAccountService::class;
    }
}
