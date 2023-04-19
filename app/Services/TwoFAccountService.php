<?php

namespace App\Services;

use App\Factories\MigratorFactoryInterface;
use App\Helpers\Helpers;
use App\Models\TwoFAccount;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
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
     * @param  int|array|string  $ids twofaccount ids to free
     */
    public static function withdraw($ids) : void
    {
        // $ids as string could be a comma-separated list of ids
        // so in this case we explode the string to an array
        $ids = Helpers::commaSeparatedToArray($ids);

        // whereIn() expects an array
        $ids = is_array($ids) ? $ids : func_get_args();

        TwoFAccount::whereIn('id', $ids)
                    ->update(
                        ['group_id' => null]
                    );

        Log::info(sprintf('TwoFAccounts IDs #%s withdrawn', implode(',', $ids)));
    }

    /**
     * Convert a migration payload to a set of TwoFAccount objects
     *
     * @param  string  $migrationPayload Migration payload from 2FA apps export feature
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
     * @param  int|array|string  $ids twofaccount ids to delete
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
     * @param  int|array|string  $ids twofaccount ids to delete
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
     * @param  \App\Models\User  $user
     */
    public static function setUser(Collection $twofaccounts, User $user) : void
    {
        $twofaccounts->each(function ($twofaccount, $key) use ($user) {
            $twofaccount->user_id = $user->id;
            $twofaccount->save();
        });
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
}
