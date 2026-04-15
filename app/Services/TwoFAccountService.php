<?php

namespace App\Services;

use App\Events\TwoFAccountOwnershipTransferred;
use App\Factories\MigratorFactoryInterface;
use App\Helpers\Helpers;
use App\Models\TwoFAccount;
use App\Models\TwoFAccountGroupAssignment;
use App\Models\TwoFAccountShare;
use App\Models\TwoFAccountUserOrder;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TwoFAccountService
{
    /**
     * @var MigratorFactoryInterface The Migration service
     */
    protected $migratorFactory;

    /**
     * Constructor
     */
    public function __construct(MigratorFactoryInterface $migratorFactory)
    {
        $this->migratorFactory = $migratorFactory;
    }

    /**
     * Withdraw one or more twofaccounts from their group
     *
     * @param  int|array|string  $ids  twofaccount ids to free
     */
    public static function withdraw($ids, ?User $user = null) : void
    {
        // $ids as string could be a comma-separated list of ids
        // so in this case we explode the string to an array
        $ids = Helpers::commaSeparatedToArray($ids);
        $ids = is_array($ids) ? $ids : [$ids]; // whereIn() expects an array
        $user ??= Auth::user();

        if (! $user) {
            Log::warning(sprintf('Cannot withdraw TwoFAccounts from groups without an authenticated user (ids: %s)', implode(',', $ids)));

            return;
        }

        $affectedCount = TwoFAccountGroupAssignment::where('user_id', $user->id)
            ->whereIn('twofaccount_id', $ids)
            ->delete();

        if ($affectedCount) {
            Log::info(sprintf('TwoFAccounts with IDs #%s withdrawn', implode(',', $ids)));
        } else {
            Log::info(sprintf('Cannot find TwoFAccounts to withdraw using ids #%s', implode(',', $ids)));
        }
    }

    /**
     * Convert a migration payload to a set of TwoFAccount objects
     *
     * @param  string  $migrationPayload  Migration payload from 2FA apps export feature
     * @return \Illuminate\Support\Collection<int|string, TwoFAccount> The converted accounts
     */
    public function migrate(string $migrationPayload) : Collection
    {
        $migrator     = $this->migratorFactory->create($migrationPayload);
        $twofaccounts = $migrator->migrate($migrationPayload);

        return self::markAsDuplicate($twofaccounts);
    }

    /**
     * Export one or more twofaccounts
     *
     * @param  int|array|string  $ids  twofaccount ids to delete
     * @return \Illuminate\Support\Collection<int, TwoFAccount> The converted accounts
     */
    public static function export($ids) : Collection
    {
        $ids = Helpers::commaSeparatedToArray($ids);
        $ids = is_array($ids) ? $ids : func_get_args();

        $twofaccounts = TwoFAccount::whereIn('id', $ids)->get();

        return $twofaccounts;
    }

    /**
     * Delete one or more twofaccounts
     *
     * @param  int|array|string  $ids  twofaccount ids to delete
     * @return int The number of deleted
     */
    public static function delete($ids) : int
    {
        // $ids as string could be a comma-separated list of ids
        // so in this case we explode the string to an array
        $ids = Helpers::commaSeparatedToArray($ids);
        Log::info(sprintf('Deletion of TwoFAccounts #%s requested', is_array($ids) ? implode(',#', $ids) : $ids));
        $deleted = TwoFAccount::destroy($ids);

        return $deleted;
    }

    /**
     * Set owner of given twofaccounts
     *
     * @param  \Illuminate\Support\Collection<int, TwoFAccount>  $twofaccounts
     */
    public static function setUser(Collection $twofaccounts, User $user) : void
    {
        $twofaccounts->each(function ($twofaccount, $key) use ($user) {
            $twofaccount->user_id = $user->id;
            $twofaccount->save();
        });
    }

    /**
     * Persist a custom order for all accounts visible by the provided user.
     *
     * Any visible account omitted from the payload keeps appearing after provided ids,
     * preserving current relative order and ensuring a full ordered collection.
     *
     * @param  array<int|string, mixed>  $orderedIds
     * @return Collection<int, int>
     */
    public static function saveOrderForUser(User $user, array $orderedIds) : Collection
    {
        $currentVisibleOrder = self::orderedVisibleIdsForUser($user);

        $requestedIds      = collect($orderedIds)->map(static fn ($id) => (int) $id)->unique()->values();
        $orderedVisibleIds = $requestedIds->intersect($currentVisibleOrder)->values();

        $finalOrder = $orderedVisibleIds->merge($currentVisibleOrder->diff($orderedVisibleIds)->values())->values();

        if ($finalOrder->isEmpty()) {
            return $finalOrder;
        }

        $now     = now();
        $payload = [];

        foreach ($finalOrder as $index => $twofaccountId) {
            $payload[] = [
                'user_id'        => (int) $user->id,
                'twofaccount_id' => (int) $twofaccountId,
                'position'       => $index + 1,
                'created_at'     => $now,
                'updated_at'     => $now,
            ];
        }

        TwoFAccountUserOrder::query()->upsert(
            $payload,
            ['user_id', 'twofaccount_id'],
            ['position', 'updated_at'],
        );

        return $finalOrder;
    }

    /**
     * Return all visible TwoFAccount ids for the user, sorted according to stored custom order.
     *
     * @return Collection<int, int>
     */
    public static function orderedVisibleIdsForUser(User $user) : Collection
    {
        $visibleIds = self::visibleTwoFAccountIdsForUser($user);

        self::pruneInaccessibleOrdersForUser($user, $visibleIds);

        return self::sortIdsByUserOrder($visibleIds, $user);
    }

    /**
     * Sort a set of TwoFAccount models according to the custom order stored for a user.
     *
     * @param  Collection<int, TwoFAccount>  $twofaccounts
     * @return Collection<int, TwoFAccount>
     */
    public static function sortForUser(Collection $twofaccounts, User $user) : Collection
    {
        if ($twofaccounts->isEmpty()) {
            return $twofaccounts;
        }

        $accountIds = $twofaccounts->pluck('id')->map(static fn ($id) => (int) $id)->values();
        $orderedIds = self::sortIdsByUserOrder($accountIds, $user);
        $byId       = $twofaccounts->keyBy('id');

        return $orderedIds
            ->map(static fn (int $accountId) => $byId->get($accountId))
            ->filter(static fn ($twofaccount) => $twofaccount instanceof TwoFAccount)
            ->values();
    }

    /**
     * Remove all order rows for users that can no longer view the account.
     */
    public static function pruneUsersWithoutAccessForAccount(TwoFAccount $twofaccount) : void
    {
        $twofaccountId = (int) $twofaccount->id;

        if (! $twofaccountId) {
            return;
        }

        $isSharedWithAll = TwoFAccountShare::query()
            ->where('twofaccount_id', $twofaccountId)
            ->where('scope', TwoFAccountShare::SCOPE_ALL_USERS)
            ->exists();

        if ($isSharedWithAll) {
            return;
        }

        $explicitSharedUserIds = TwoFAccountShare::query()
            ->where('twofaccount_id', $twofaccountId)
            ->where('scope', TwoFAccountShare::SCOPE_USER)
            ->whereNotNull('shared_with_user_id')
            ->pluck('shared_with_user_id')
            ->map(static fn ($id) => (int) $id)
            ->values();

        $accessibleUserIds = collect([(int) $twofaccount->user_id])
            ->filter(static fn (int $id) => $id > 0)
            ->merge($explicitSharedUserIds)
            ->unique()
            ->values();

        $ordersQuery = TwoFAccountUserOrder::query()->where('twofaccount_id', $twofaccountId);

        if ($accessibleUserIds->isEmpty()) {
            $ordersQuery->delete();

            return;
        }

        $ordersQuery->whereNotIn('user_id', $accessibleUserIds->all())->delete();
    }

    /**
     * Transfer ownership of a twofaccount to another user.
     *
     * Keeps existing share audit trail untouched and only removes obsolete
     * explicit share to the new owner if it exists.
     */
    public static function transferOwnership(TwoFAccount $twofaccount, User $newOwner) : TwoFAccount
    {
        $previousOwner = $twofaccount->user;

        DB::transaction(function () use ($twofaccount, $newOwner) {
            $twofaccount->user_id = $newOwner->id;
            $twofaccount->save();

            TwoFAccountShare::query()
                ->where('twofaccount_id', $twofaccount->id)
                ->where('scope', TwoFAccountShare::SCOPE_USER)
                ->where('shared_with_user_id', $newOwner->id)
                ->delete();
        });

        $refreshedTwofaccount = $twofaccount->refresh();

        event(new TwoFAccountOwnershipTransferred($refreshedTwofaccount, $previousOwner));

        return $refreshedTwofaccount;
    }

    /**
     * Return the given collection with items marked as Duplicates (using id=-1) if similar records exist
     * in the authenticated user accounts
     *
     * @param  \Illuminate\Support\Collection<int|string, TwoFAccount>  $twofaccounts
     * @return \Illuminate\Support\Collection<int|string, TwoFAccount>
     */
    private static function markAsDuplicate(Collection $twofaccounts) : Collection
    {
        $userTwofaccounts = Auth::user()->twofaccounts;

        $twofaccounts = $twofaccounts->map(function ($twofaccount, $key) use ($userTwofaccounts) {
            if ($userTwofaccounts->contains(function ($value, $key) use ($twofaccount) {
                return $value->secret == $twofaccount->secret
                    && $value->service == $twofaccount->service
                    && $value->account == $twofaccount->account
                    && $value->otp_type == $twofaccount->otp_type
                    && $value->digits == $twofaccount->digits
                    && $value->algorithm == $twofaccount->algorithm;
            })) {
                $twofaccount->id = TwoFAccount::DUPLICATE_ID;
            }

            return $twofaccount;
        });

        return $twofaccounts;
    }

    /**
     * Return ids of accounts visible by the user.
     *
     * @return Collection<int, int>
     */
    private static function visibleTwoFAccountIdsForUser(User $user) : Collection
    {
        return TwoFAccount::visibleTo($user)
            ->pluck('id')
            ->map(static fn ($id) => (int) $id)
            ->values();
    }

    /**
     * Remove stale order rows that reference accounts no longer visible by the user.
     *
     * @param  Collection<int, int>|null  $visibleIds
     */
    private static function pruneInaccessibleOrdersForUser(User $user, ?Collection $visibleIds = null) : void
    {
        $visibleIds ??= self::visibleTwoFAccountIdsForUser($user);

        $ordersQuery = TwoFAccountUserOrder::query()->where('user_id', $user->id);

        if ($visibleIds->isEmpty()) {
            $ordersQuery->delete();

            return;
        }

        $ordersQuery->whereNotIn('twofaccount_id', $visibleIds->all())->delete();
    }

    /**
     * Sort a list of account ids according to persisted user order.
     *
     * @param  Collection<int, int>  $twofaccountIds
     * @return Collection<int, int>
     */
    private static function sortIdsByUserOrder(Collection $twofaccountIds, User $user) : Collection
    {
        if ($twofaccountIds->isEmpty()) {
            return collect();
        }

        $positions = TwoFAccountUserOrder::query()
            ->where('user_id', $user->id)
            ->whereIn('twofaccount_id', $twofaccountIds->all())
            ->pluck('position', 'twofaccount_id')
            ->mapWithKeys(static function ($position, $twofaccountId) {
                return [(int) $twofaccountId => (int) $position];
            });

        return $twofaccountIds
            ->sortBy(static function (int $twofaccountId) use ($positions) {
                $position = $positions->get($twofaccountId);

                return is_null($position)
                    ? 1000000000 + $twofaccountId
                    : $position;
            })
            ->values();
    }
}
